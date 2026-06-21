<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function index()
    {
        // 1. Core Summary Metrics
        $totalRevenue = Order::where('status', 'paid')->sum('total_amount');
        $ticketsSold = Ticket::whereHas('order', function ($query) {
            $query->where('status', 'paid');
        })->count();
        $totalEvents = Event::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $ticketsCheckedIn = Ticket::where('status', 'checked_in')->count();

        // 2. Chart Sales Trend (last 7 days)
        // Group paid orders by date in SQLite/MySQL compatible format
        $salesTrend = Order::where('status', 'paid')
            ->select(
                DB::raw("date(created_at) as date"),
                DB::raw("sum(total_amount) as total_amount"),
                DB::raw("count(*) as total_orders")
            )
            ->groupBy(DB::raw("date(created_at)"))
            ->orderBy('date', 'asc')
            ->limit(10)
            ->get();

        $chartLabels = $salesTrend->pluck('date')->toArray();
        $chartData = $salesTrend->pluck('total_amount')->toArray();

        // If empty, supply mock baseline data to make chart look gorgeous
        if (empty($chartLabels)) {
            $chartLabels = [date('Y-m-d', strtotime('-4 days')), date('Y-m-d', strtotime('-3 days')), date('Y-m-d', strtotime('-2 days')), date('Y-m-d', strtotime('-1 day')), date('Y-m-d')];
            $chartData = [0, 0, 0, 0, 0];
        }

        // 3. Recent Orders
        $recentOrders = Order::with('event')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'ticketsSold', 
            'totalEvents', 
            'totalCustomers',
            'ticketsCheckedIn',
            'chartLabels',
            'chartData',
            'recentOrders'
        ));
    }

    public function events()
    {
        $events = Event::with('ticketCategories')->orderBy('date', 'desc')->get();
        return view('admin.events.index', compact('events'));
    }

    public function createEvent()
    {
        return view('admin.events.create');
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string',
            'image_path' => 'nullable|url',
            'is_featured' => 'nullable|boolean',
            'categories' => 'required|array|min:1',
            'categories.*.name' => 'required|string|max:255',
            'categories.*.price' => 'required|integer|min:0',
            'categories.*.quota' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $event = Event::create([
                'title' => $request->title,
                'slug' => Str::slug($request->title) . '-' . Str::lower(Str::random(4)),
                'description' => $request->description,
                'date' => $request->date,
                'location' => $request->location,
                'image_path' => $request->image_path ?: 'https://images.unsplash.com/photo-1470225620780-dba8ba36b745?auto=format&fit=crop&w=1200&q=80',
                'is_featured' => $request->boolean('is_featured'),
                'status' => 'published'
            ]);

            foreach ($request->categories as $cat) {
                TicketCategory::create([
                    'event_id' => $event->id,
                    'name' => $cat['name'],
                    'price' => $cat['price'],
                    'total_quota' => $cat['quota'],
                    'available_quota' => $cat['quota']
                ]);
            }
        });

        return redirect()->route('admin.events.index')->with('success', 'Acara baru berhasil ditambahkan!');
    }

    public function editEvent(Event $event)
    {
        $event->load('ticketCategories');
        return view('admin.events.edit', compact('event'));
    }

    public function updateEvent(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'location' => 'required|string',
            'image_path' => 'nullable|url',
            'is_featured' => 'nullable|boolean',
        ]);

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'location' => $request->location,
            'image_path' => $request->image_path ?: $event->image_path,
            'is_featured' => $request->boolean('is_featured')
        ]);

        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil diperbarui!');
    }

    public function deleteEvent(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'Acara berhasil dihapus.');
    }

    public function checkIn(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = Ticket::where('ticket_code', $request->ticket_code)
            ->with(['order', 'ticketCategory', 'order.event'])
            ->first();

        if (!$ticket) {
            return back()->withErrors(['checkin_error' => 'Tiket dengan kode tersebut tidak ditemukan.']);
        }

        if ($ticket->order->status !== 'paid') {
            return back()->withErrors(['checkin_error' => "Pemesanan tiket ini belum dibayar (Status: {$ticket->order->status})."]);
        }

        if ($ticket->status === 'checked_in') {
            return back()->withErrors(['checkin_error' => "Tiket ini SUDAH digunakan untuk Check-In peserta pada tanggal: " . $ticket->updated_at->translatedFormat('d M Y, H:i') . " WIB."]);
        }

        $ticket->status = 'checked_in';
        $ticket->save();

        return back()->with('checkin_success', "Check-In BERHASIL! Nama Peserta: {$ticket->attendee_name} ({$ticket->ticketCategory->name}) untuk acara: {$ticket->order->event->title}.");
    }

    public function orders(Request $request)
    {
        $query = Order::with(['event', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $order->load(['event', 'tickets.ticketCategory', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled',
        ]);

        $oldStatus = $order->status;
        $newStatus = $request->status;

        if ($oldStatus === $newStatus) {
            return back()->with('success', 'Status pesanan tidak berubah.');
        }

        try {
            DB::transaction(function () use ($order, $oldStatus, $newStatus) {
                // If transitioning to cancelled (and wasn't already cancelled)
                if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                    // Restore ticket quotas
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
                }
                
                // If transitioning FROM cancelled to paid/pending, we re-deduct quota
                if ($oldStatus === 'cancelled' && ($newStatus === 'paid' || $newStatus === 'pending')) {
                    $ticketsGrouped = Ticket::where('order_id', $order->id)
                        ->select('ticket_category_id', DB::raw('count(*) as total'))
                        ->groupBy('ticket_category_id')
                        ->get();

                    foreach ($ticketsGrouped as $group) {
                        $category = TicketCategory::lockForUpdate()->find($group->ticket_category_id);
                        if ($category) {
                            if ($category->available_quota < $group->total) {
                                throw new \Exception("Kuota tiket '{$category->name}' tidak mencukupi untuk memulihkan pesanan ini.");
                            }
                            $category->available_quota -= $group->total;
                            $category->save();
                        }
                    }
                }

                $order->status = $newStatus;
                $order->save();
            });

            return back()->with('success', "Status pesanan {$order->order_number} berhasil diubah menjadi: " . strtoupper($newStatus));

        } catch (\Exception $e) {
            return back()->withErrors(['status_error' => $e->getMessage()]);
        }
    }

    public function customers(Request $request)
    {
        $query = User::where('role', 'customer');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->withCount(['orders' => function($q) {
            $q->where('status', 'paid');
        }])
        ->withSum(['orders as total_spent' => function($q) {
            $q->where('status', 'paid');
        }], 'total_amount')
        ->orderBy('total_spent', 'desc')
        ->paginate(10);

        return view('admin.customers.index', compact('customers'));
    }

    public function toggleEventStatus(Event $event)
    {
        $event->status = $event->status === 'published' ? 'draft' : 'published';
        $event->save();

        return back()->with('success', "Status acara '{$event->title}' berhasil diubah menjadi: " . strtoupper($event->status));
    }

    public function deleteOrder(Order $order)
    {
        DB::transaction(function() use ($order) {
            // Restore ticket quotas if the order was not already cancelled or pending (actually, let's restore quota if it wasn't cancelled)
            if ($order->status !== 'cancelled') {
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
            }

            // Delete associated tickets and the order
            $order->tickets()->delete();
            $order->delete();
        });

        return redirect()->route('admin.orders.index')->with('success', "Pesanan {$order->order_number} berhasil dihapus dari sistem.");
    }

    public function storeCategory(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'quota' => 'required|integer|min:1',
        ]);

        TicketCategory::create([
            'event_id' => $event->id,
            'name' => $request->name,
            'price' => $request->price,
            'total_quota' => $request->quota,
            'available_quota' => $request->quota,
        ]);

        return back()->with('success', 'Kategori tiket baru berhasil ditambahkan.');
    }

    public function updateCategory(Request $request, TicketCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'total_quota' => 'required|integer|min:1',
        ]);

        $quotaDiff = $request->total_quota - $category->total_quota;
        $newAvailableQuota = $category->available_quota + $quotaDiff;

        if ($newAvailableQuota < 0) {
            return back()->withErrors(['quota_error' => 'Kapasitas tiket baru tidak boleh lebih kecil dari jumlah tiket yang sudah dipesan.']);
        }

        $category->update([
            'name' => $request->name,
            'price' => $request->price,
            'total_quota' => $request->total_quota,
            'available_quota' => $newAvailableQuota,
        ]);

        return back()->with('success', 'Kategori tiket berhasil diperbarui.');
    }

    public function destroyCategory(TicketCategory $category)
    {
        $ticketsCount = Ticket::where('ticket_category_id', $category->id)->count();
        if ($ticketsCount > 0) {
            return back()->withErrors(['category_error' => 'Kategori tiket ini tidak bisa dihapus karena sudah memiliki transaksi pembelian.']);
        }

        $category->delete();
        return back()->with('success', 'Kategori tiket berhasil dihapus.');
    }
}
