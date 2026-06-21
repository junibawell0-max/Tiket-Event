@extends('layouts.app')

@section('title', 'Tiket Saya - Histori Pembelian TiketAcara')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="max-w-4xl mx-auto mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Daftar Tiket Saya</h1>
            <p class="text-sm text-slate-400">Temukan seluruh e-tiket acara dan riwayat transaksi pemesanan Anda.</p>
        </div>

        <div class="max-w-4xl mx-auto space-y-6">
            @forelse($orders as $order)
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-4 shadow-xl">
                    <!-- Order Header -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 border-b border-slate-800 pb-3">
                        <div class="space-y-0.5">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Nomor Booking</p>
                            <h3 class="text-sm font-mono font-bold text-white tracking-wide">{{ $order->order_number }}</h3>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-[10px] text-slate-400">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $order->status === 'paid' ? 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-400' : ($order->status === 'cancelled' ? 'bg-rose-500/10 border border-rose-500/25 text-rose-400' : 'bg-amber-500/10 border border-amber-500/25 text-amber-400') }}">
                                {{ $order->status }}
                            </span>
                        </div>
                    </div>

                    <!-- Event Info -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-800 bg-slate-950 flex-shrink-0">
                                <img src="{{ $order->event->image_path }}" alt="{{ $order->event->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-md font-bold text-white leading-tight line-clamp-1">
                                    <a href="{{ route('event.show', $order->event->slug) }}" class="hover:text-indigo-400 transition-colors">{{ $order->event->title }}</a>
                                </h4>
                                <p class="text-xs text-indigo-400 font-semibold">{{ $order->event->date->translatedFormat('d F Y') }}</p>
                                <p class="text-[10px] text-slate-400 line-clamp-1">{{ $order->event->location }}</p>
                            </div>
                        </div>

                        <div class="text-left sm:text-right">
                            <p class="text-[10px] text-slate-500 font-bold uppercase">Total Tagihan</p>
                            <p class="text-md font-bold text-indigo-400 mt-0.5">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-slate-400">{{ $order->tickets->count() }} Tiket ({{ $order->payment_method }})</p>
                        </div>
                    </div>

                    <!-- List of Issued Tickets -->
                    @if($order->status === 'paid')
                        <div class="border-t border-slate-800/40 pt-4 space-y-2">
                            <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider mb-2">Tiket yang Diterbitkan:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($order->tickets as $ticket)
                                    <div class="p-3 bg-slate-950/40 border border-slate-800 rounded-xl flex items-center justify-between">
                                        <div class="space-y-0.5">
                                            <p class="text-xs font-bold text-slate-200">{{ $ticket->attendee_name }}</p>
                                            <p class="text-[10px] text-slate-500 flex items-center gap-1.5 flex-wrap">
                                                <span>{{ $ticket->ticketCategory->name }}</span>
                                                <span class="text-slate-700">&bull;</span>
                                                <span class="font-mono text-indigo-400/80">{{ $ticket->ticket_code }}</span>
                                                @if($ticket->status === 'checked_in')
                                                    <span class="text-slate-700">&bull;</span>
                                                    <span class="text-emerald-400 font-bold uppercase text-[9px]">Checked-In</span>
                                                @elseif($ticket->status === 'cancelled')
                                                    <span class="text-slate-700">&bull;</span>
                                                    <span class="text-rose-400 font-bold uppercase text-[9px]">Batal</span>
                                                @endif
                                            </p>
                                        </div>
                                        
                                        @if($ticket->status !== 'cancelled')
                                            <a href="{{ route('checkout.ticket', $ticket->ticket_code) }}" 
                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-500/10 hover:bg-indigo-600 text-indigo-300 hover:text-white rounded-lg text-[10px] font-bold transition-all">
                                                <i data-lucide="eye" class="w-3.5 h-3.5"></i> Lihat Tiket
                                            </a>
                                        @else
                                            <span class="text-[10px] text-slate-600 font-bold uppercase py-1.5 px-3">Batal</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @elseif($order->status === 'pending')
                        <div class="border-t border-slate-800/40 pt-3 flex justify-between items-center text-xs text-amber-400 font-semibold bg-amber-500/5 -mx-6 -mb-6 p-4 rounded-b-2xl">
                            <span class="flex items-center gap-1"><i data-lucide="alert-circle" class="w-4 h-4"></i> Pemesanan Anda masih menunggu pembayaran.</span>
                            <a href="{{ route('checkout.payment', $order->order_number) }}" class="px-3 py-1.5 bg-amber-500 text-slate-950 font-bold rounded-lg text-[10px] uppercase hover:bg-amber-400 transition-colors">Bayar Sekarang</a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-center py-16 bg-slate-900/10 border border-slate-900 rounded-2xl">
                    <div class="w-16 h-16 bg-slate-900 border border-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-500">
                        <i data-lucide="ticket" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Belum Ada Tiket yang Dipesan</h3>
                    <p class="text-slate-400 text-sm mt-1">Anda belum melakukan pemesanan tiket acara apa pun.</p>
                    <div class="mt-6">
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-colors">
                            Jelajahi Acara Sekarang <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
