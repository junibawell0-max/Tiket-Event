@extends('layouts.app')

@section('title', 'Daftar Pelanggan - Panel Admin')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-1 text-xs text-slate-500 font-semibold mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Admin</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-slate-400">Pelanggan</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Manajemen Pelanggan</h1>
                <p class="text-sm text-slate-400">Tinjau daftar pelanggan terdaftar, jumlah transaksi, dan loyalitas mereka.</p>
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

        <!-- Filter Widget & Search Form -->
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between mb-6">
            <form action="{{ route('admin.customers.index') }}" method="GET" class="w-full md:max-w-md relative group">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari pelanggan berdasarkan nama atau email..." 
                    class="w-full pl-11 pr-4 py-3 bg-slate-900/50 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm transition-all focus:bg-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 group-focus-within:text-indigo-400 transition-colors"></i>
                </div>
            </form>
        </div>

        <!-- Customers Table Card -->
        <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-wider bg-slate-950/20">
                            <th class="py-4 px-6">Pelanggan</th>
                            <th class="py-4 px-6">Terdaftar Sejak</th>
                            <th class="py-4 px-6 text-center">Jumlah Pembelian Tiket (Lunas)</th>
                            <th class="py-4 px-6 text-right">Total Transaksi Lunas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-300 text-xs font-medium">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <!-- Profile Photo Initials & Name -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-300 font-bold select-none flex-shrink-0">
                                            {{ strtoupper(substr($customer->name, 0, 1)) }}{{ strtoupper(substr(strrchr($customer->name, ' ') ?: $customer->name, 1, 1)) }}
                                        </div>
                                        <div class="space-y-0.5">
                                            <h4 class="text-sm font-bold text-white leading-tight">{{ $customer->name }}</h4>
                                            <p class="text-[10px] text-slate-400 font-normal select-all">{{ $customer->email }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Registered Since -->
                                <td class="py-4 px-6 text-slate-400">
                                    {{ $customer->created_at->translatedFormat('d F Y, H:i') }} WIB
                                </td>

                                <!-- Tickets count -->
                                <td class="py-4 px-6 text-center text-slate-200">
                                    <span class="px-2.5 py-1 bg-slate-950/60 border border-slate-800 rounded-lg text-xs font-bold font-mono">
                                        {{ $customer->orders_count }} Pemesanan
                                    </span>
                                </td>

                                <!-- Total money spent -->
                                <td class="py-4 px-6 text-right text-indigo-400 font-extrabold text-sm">
                                    Rp {{ number_format($customer->total_spent ?? 0, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-500 font-normal">
                                    Tidak ada pelanggan terdaftar ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $customers->appends(request()->query())->links() }}
        </div>

    </div>
</div>
@endsection
