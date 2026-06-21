@extends('layouts.app')

@section('title', 'Daftar Pemesanan - Panel Admin')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-1 text-xs text-slate-500 font-semibold mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Admin</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-slate-400">Pemesanan</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Manajemen Pemesanan</h1>
                <p class="text-sm text-slate-400">Kelola pesanan tiket acara, verifikasi pembayaran, dan pantau status transaksi.</p>
            </div>
        </div>

        <!-- Admin Navigation Tabs -->
        <div class="flex flex-wrap items-center gap-2 border-b border-slate-800 pb-4 mb-8 text-xs font-bold uppercase tracking-wider">
            <a href="{{ route('admin.dashboard') }}" 
               class="px-4 py-2.5 rounded-xl border transition-all duration-300 {{ Route::is('admin.dashboard') ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                <i data-lucide="layout-dashboard" class="w-3.5 h-3.5 inline mr-1.5 -mt-0.5"></i> Dashboard
            </a>
            <a href="{{ route('admin.events.index') }}" 
               class="px-4 py-2.5 rounded-xl border transition-all duration-300 {{ Route::is('admin.events.*') ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                <i data-lucide="calendar" class="w-3.5 h-3.5 inline mr-1.5 -mt-0.5"></i> Acara
            </a>
            <a href="{{ route('admin.orders.index') }}" 
               class="px-4 py-2.5 rounded-xl border transition-all duration-300 {{ Route::is('admin.orders.*') ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                <i data-lucide="shopping-bag" class="w-3.5 h-3.5 inline mr-1.5 -mt-0.5"></i> Pemesanan
            </a>
            <a href="{{ route('admin.customers.index') }}" 
               class="px-4 py-2.5 rounded-xl border transition-all duration-300 {{ Route::is('admin.customers.*') ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                <i data-lucide="users" class="w-3.5 h-3.5 inline mr-1.5 -mt-0.5"></i> Pelanggan
            </a>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <!-- Filter Widget & Search Form -->
        <div class="flex flex-col lg:flex-row gap-4 items-center justify-between mb-6">
            <!-- Status Tabs -->
            <div class="flex items-center gap-1.5 bg-slate-900/40 p-1 border border-slate-800/80 rounded-xl w-full lg:w-auto">
                <a href="{{ route('admin.orders.index', request()->only(['search'])) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 {{ !request('status') ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    Semua
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->only(['search']), ['status' => 'pending'])) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 {{ request('status') === 'pending' ? 'bg-amber-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    Pending
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->only(['search']), ['status' => 'paid'])) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 {{ request('status') === 'paid' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    Lunas
                </a>
                <a href="{{ route('admin.orders.index', array_merge(request()->only(['search']), ['status' => 'cancelled'])) }}" 
                   class="px-4 py-2 rounded-lg text-xs font-bold transition-all duration-200 {{ request('status') === 'cancelled' ? 'bg-rose-600 text-white shadow-md' : 'text-slate-400 hover:text-white' }}">
                    Dibatalkan
                </a>
            </div>

            <!-- Search Form -->
            <form action="{{ route('admin.orders.index') }}" method="GET" class="w-full lg:max-w-md relative group">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari kode pesanan, nama pelanggan, atau email..." 
                    class="w-full pl-11 pr-4 py-3 bg-slate-900/50 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm transition-all focus:bg-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 group-focus-within:text-indigo-400 transition-colors"></i>
                </div>
            </form>
        </div>

        <!-- Orders Table Card -->
        <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-wider bg-slate-950/20">
                            <th class="py-4 px-6">No. Pesanan</th>
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Acara</th>
                            <th class="py-4 px-6 text-right">Total Bayar</th>
                            <th class="py-4 px-6 text-center">Status</th>
                            <th class="py-4 px-6">Tanggal</th>
                            <th class="py-4 px-6 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-300 text-xs font-medium">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <!-- Order Number -->
                                <td class="py-4 px-6 select-all font-mono font-bold text-slate-200">
                                    {{ $order->order_number }}
                                </td>

                                <!-- Customer Details -->
                                <td class="py-4 px-6">
                                    <div class="space-y-0.5">
                                        <h4 class="text-sm font-bold text-white leading-tight">{{ $order->customer_name }}</h4>
                                        <p class="text-[10px] text-slate-400 font-normal select-all">{{ $order->customer_email }}</p>
                                    </div>
                                </td>

                                <!-- Event title -->
                                <td class="py-4 px-6 text-slate-300 max-w-[200px] truncate" title="{{ $order->event->title }}">
                                    {{ $order->event->title }}
                                </td>

                                <!-- Total amount -->
                                <td class="py-4 px-6 text-right text-indigo-400 font-bold">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </td>

                                <!-- Status Badge -->
                                <td class="py-4 px-6 text-center">
                                    @if($order->status === 'paid')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Lunas
                                        </span>
                                    @elseif($order->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-amber-500/10 border border-amber-500/20 text-amber-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500/10 border border-rose-500/20 text-rose-400">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Dibatalkan
                                        </span>
                                    @endif
                                </td>

                                <!-- Date -->
                                <td class="py-4 px-6 text-slate-400">
                                    {{ $order->created_at->translatedFormat('d M Y, H:i') }}
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-6 text-center">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                           class="inline-flex items-center justify-center p-2 rounded-xl text-indigo-400 hover:text-white hover:bg-indigo-600 border border-indigo-500/20 hover:border-indigo-600 transition-all duration-300"
                                           title="Lihat Detail & Kelola Status">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini secara permanen dari sistem?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center p-2 rounded-xl text-rose-400 hover:text-white hover:bg-rose-600 border border-rose-500/20 hover:border-rose-600 transition-all duration-300" title="Hapus Permanen">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-500 font-normal">
                                    Tidak ada transaksi pemesanan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $orders->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection
