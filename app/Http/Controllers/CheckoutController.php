<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function showCheckout(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'tickets' => 'required|array',
        ]);

        $event = Event::findOrFail($request->event_id);
        $selectedTickets = [];
        $totalAmount = 0;

        foreach ($request->tickets as $catId => $qty) {
            $qty = intval($qty);
            if ($qty > 0) {
                $category = TicketCategory::where('event_id', $event->id)->findOrFail($catId);
                
                // Check quota
                if ($qty > $category->available_quota) {
                    return back()->withErrors([
                        'error' => "Kuota untuk tiket '{$category->name}' tidak mencukupi. Tersisa {$category->available_quota} tiket."
                    ]);
                }

                $selectedTickets[] = [
                    'category' => $category,
                    'quantity' => $qty,
                    'subtotal' => $category->price * $qty
                ];
                $totalAmount += $category->price * $qty;
            }
        }

        if (empty($selectedTickets)) {
            return back()->withErrors(['error' => 'Silakan pilih minimal 1 tiket sebelum melanjutkan.']);
        }

        return view('checkout.index', compact('event', 'selectedTickets', 'totalAmount'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'payment_method' => 'required|string',
            'items' => 'required|array', // category_id => qty
            'attendees' => 'required|array', // category_id => [index => name]
        ]);

        $event = Event::findOrFail($request->event_id);
        $orderNumber = 'TKT-' . date('Ymd') . '-' . strtoupper(Str::random(6));

        try {
            $order = DB::transaction(function () use ($request, $event, $orderNumber) {
                $totalAmount = 0;
                $orderItems = [];

                // 1. Process categories, lock for update to prevent race conditions
                foreach ($request->items as $catId => $qty) {
                    $qty = intval($qty);
                    if ($qty > 0) {
                        $category = TicketCategory::where('event_id', $event->id)
                            ->lockForUpdate()
                            ->findOrFail($catId);

                        if ($category->available_quota < $qty) {
                            throw new \Exception("Kuota tiket '{$category->name}' tidak mencukupi.");
                        }

                        // Deduct quota
                        $category->available_quota -= $qty;
                        $category->save();

                        $totalAmount += $category->price * $qty;
                        $orderItems[$catId] = [
                            'category' => $category,
                            'quantity' => $qty
                        ];
                    }
                }

                // 2. Create the order
                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                    'event_id' => $event->id,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                ]);

                // 3. Create the tickets
                foreach ($orderItems as $catId => $item) {
                    $category = $item['category'];
                    $qty = $item['quantity'];

                    for ($i = 0; $i < $qty; $i++) {
                        $attendeeName = $request->attendees[$catId][$i] ?? $request->customer_name;
                        
                        Ticket::create([
                            'ticket_code' => 'TCK-' . strtoupper(Str::random(10)),
                            'order_id' => $order->id,
                            'ticket_category_id' => $category->id,
                            'attendee_name' => $attendeeName,
                            'status' => 'active'
                        ]);
                    }
                }

                return $order;
            });

            return redirect()->route('checkout.payment', $order->order_number);

        } catch (\Exception $e) {
            return redirect()->route('event.show', $event->slug)->withErrors([
                'error' => 'Pemesanan gagal: ' . $e->getMessage()
            ]);
        }
    }

    public function showPayment($order_number)
    {
        $order = Order::where('order_number', $order_number)
            ->with(['event', 'tickets.ticketCategory'])
            ->firstOrFail();

        if ($order->status === 'paid') {
            // If already paid, redirect to the first ticket page
            $firstTicket = $order->tickets->first();
            return redirect()->route('checkout.ticket', $firstTicket->ticket_code);
        }

        if ($order->status === 'cancelled') {
            return redirect()->route('home')->withErrors(['error' => 'Transaksi ini telah dibatalkan.']);
        }

        return view('checkout.payment', compact('order'));
    }

    public function pay(Request $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();
        $action = $request->input('action', 'success'); // success or cancel

        if ($order->status !== 'pending') {
            return redirect()->route('home');
        }

        if ($action === 'success') {
            $order->status = 'paid';
            $order->save();

            $firstTicket = Ticket::where('order_id', $order->id)->first();
            return redirect()->route('checkout.ticket', $firstTicket->ticket_code)->with('success', 'Pembayaran berhasil dikonfirmasi!');
        } else {
            // Restore ticket quotas
            DB::transaction(function () use ($order) {
                $order->status = 'cancelled';
                $order->save();

                // Get tickets count grouped by category
                $ticketsGrouped = Ticket::where('order_id', $order->id)
                    ->select('ticket_category_id', DB::raw('count(*) as total'))
                    ->groupBy('ticket_category_id')
                    ->get();

                foreach ($ticketsGrouped as $group) {
                    $category = TicketCategory::find($group->ticket_category_id);
                    if ($category) {
                        $category->available_quota += $group->total;
                        $category->save();
                    }
                }
            });

            return redirect()->route('home')->withErrors(['error' => 'Pemesanan tiket Anda telah dibatalkan.']);
        }
    }

    public function showTicket($ticket_code)
    {
        $ticket = Ticket::where('ticket_code', $ticket_code)
            ->with(['order.event', 'ticketCategory'])
            ->firstOrFail();

        if ($ticket->order->status !== 'paid') {
            abort(403, 'Tiket belum dibayar.');
        }

        // Get other tickets in the same order
        $otherTickets = Ticket::where('order_id', $ticket->order_id)
            ->where('id', '!=', $ticket->id)
            ->with('ticketCategory')
            ->get();

        return view('checkout.ticket', compact('ticket', 'otherTickets'));
    }

    public function myTickets()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['event', 'tickets.ticketCategory'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('checkout.my-tickets', compact('orders'));
    }
}
