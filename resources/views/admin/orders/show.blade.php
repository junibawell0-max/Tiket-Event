@extends('layouts.app')

@section('title', 'Detail Pemesanan #' . $order->order_number . ' - Panel Admin')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Back button -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-1 text-xs text-slate-500 font-semibold mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Admin</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <a href="{{ route('admin.orders.index') }}" class="hover:text-white transition-colors">Pemesanan</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-slate-400">Detail #{{ $order->order_number }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.orders.index') }}" class="p-2 bg-slate-900 border border-slate-800 hover:border-slate-700 text-slate-300 hover:text-white rounded-xl transition-all duration-300">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    </a>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight">Detail Pemesanan</h1>
                </div>
            </div>
            
            <!-- Quick Status Badge -->
            <div>
                @if($order->status === 'paid')
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Lunas / Terbayar
                    </span>
                @elseif($order->status === 'pending')
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-amber-500/10 border border-amber-500/20 text-amber-400">
                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Pembayaran
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-wider bg-rose-500/10 border border-rose-500/20 text-rose-400">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Dibatalkan
                    </span>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl text-emerald-400 text-sm flex items-center gap-3">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($errors->has('status_error'))
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl text-rose-400 text-sm flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 flex-shrink-0"></i>
                <span>{{ $errors->first('status_error') }}</span>
            </div>
        @endif

        <!-- Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left 2 Columns: Transaction Info & Tickets -->
            <div class="lg:col-span-2 space-y-8">
                
                <!-- Order Summary Card -->
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                        <i data-lucide="shopping-bag" class="w-32 h-32 text-indigo-500"></i>
                    </div>

                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="info" class="w-5 h-5 text-indigo-400"></i> Rincian Pesanan
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <span class="text-xs text-slate-500 block font-semibold mb-1">Nomor Pesanan</span>
                                <span class="text-sm font-bold text-slate-200 font-mono select-all">{{ $order->order_number }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block font-semibold mb-1">Nama Acara</span>
                                <span class="text-sm font-bold text-indigo-300 hover:text-indigo-200 transition-colors">
                                    {{ $order->event->title }}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block font-semibold mb-1">Jadwal Acara</span>
                                <span class="text-sm font-bold text-slate-200">
                                    {{ $order->event->date->translatedFormat('d F Y, H:i') }} WIB
                                </span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <span class="text-xs text-slate-500 block font-semibold mb-1">Tanggal Transaksi</span>
                                <span class="text-sm font-bold text-slate-200">
                                    {{ $order->created_at->translatedFormat('d F Y, H:i') }} WIB
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block font-semibold mb-1">Metode Pembayaran</span>
                                <span class="text-sm font-bold text-slate-200 uppercase">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                            </div>
                            <div>
                                <span class="text-xs text-slate-500 block font-semibold mb-1">Total Pembayaran</span>
                                <span class="text-base font-extrabold text-indigo-400">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tickets List Card -->
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="ticket" class="w-5 h-5 text-indigo-400"></i> Tiket dan Nama Peserta
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-wider bg-slate-950/20">
                                    <th class="py-3 px-4">Kode Tiket</th>
                                    <th class="py-3 px-4">Kategori</th>
                                    <th class="py-3 px-4">Nama Peserta</th>
                                    <th class="py-3 px-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800/50 text-slate-300 text-xs font-medium">
                                @foreach($order->tickets as $ticket)
                                    <tr class="hover:bg-slate-900/10 transition-colors">
                                        <td class="py-3.5 px-4 font-mono font-bold text-slate-200 select-all">
                                            {{ $ticket->ticket_code }}
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-400">
                                            {{ $ticket->ticketCategory->name }}
                                        </td>
                                        <td class="py-3.5 px-4 text-slate-200 font-bold">
                                            {{ $ticket->attendee_name }}
                                        </td>
                                        <td class="py-3.5 px-4 text-center">
                                            @if($ticket->status === 'checked_in')
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-purple-500/10 border border-purple-500/20 text-purple-400" title="Check-In Terdaftar">
                                                    Checked In
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-500/10 border border-emerald-500/20 text-emerald-400">
                                                    Aktif
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right Column: Customer Details & Admin Actions -->
            <div class="space-y-8">
                
                <!-- Customer info card -->
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="user" class="w-5 h-5 text-indigo-400"></i> Detail Pemesan
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-300 font-bold select-none flex-shrink-0">
                                {{ strtoupper(substr($order->customer_name, 0, 1)) }}{{ strtoupper(substr(strrchr($order->customer_name, ' ') ?: $order->customer_name, 1, 1)) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-white leading-tight">{{ $order->customer_name }}</h4>
                                <span class="text-[10px] text-indigo-400 uppercase font-semibold">User Pelanggan</span>
                            </div>
                        </div>

                        <hr class="border-slate-800">

                        <div>
                            <span class="text-xs text-slate-500 block font-semibold mb-1">Email Kontak</span>
                            <span class="text-xs font-bold text-slate-200 select-all">{{ $order->customer_email }}</span>
                        </div>

                        <div>
                            <span class="text-xs text-slate-500 block font-semibold mb-1">Nomor Telepon</span>
                            <span class="text-xs font-bold text-slate-200 select-all">{{ $order->customer_phone }}</span>
                        </div>

                        <div>
                            <span class="text-xs text-slate-500 block font-semibold mb-1">Akun Terdaftar</span>
                            @if($order->user)
                                <span class="text-xs font-bold text-slate-200">{{ $order->user->name }} ({{ $order->user->email }})</span>
                            @else
                                <span class="text-xs font-semibold text-slate-500">Tanpa Akun (Guest Checkout)</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Admin Action Control card -->
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 shadow-xl">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <i data-lucide="shield-alert" class="w-5 h-5 text-indigo-400"></i> Kontrol Pembayaran
                    </h3>

                    <div class="space-y-4">
                        @if($order->status === 'pending')
                            <!-- Status is pending: Confirm paid or Cancel order -->
                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="paid">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin mengonfirmasi pembayaran lunas untuk pesanan ini?')"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm rounded-xl transition-all duration-300 shadow-lg shadow-emerald-600/20 hover:shadow-emerald-600/35">
                                    <i data-lucide="check-circle" class="w-4 h-4"></i> Konfirmasi Lunas
                                </button>
                            </form>

                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Semua kuota tiket yang dipesan akan otomatis dikembalikan ke sistem.')"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-slate-900 hover:bg-rose-950/20 border border-slate-800 hover:border-rose-900/30 text-slate-400 hover:text-rose-400 font-bold text-sm rounded-xl transition-all duration-300">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan Transaksi
                                </button>
                            </form>
                        @elseif($order->status === 'paid')
                            <!-- Status is paid: Cancel order option -->
                            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" onclick="return confirm('PERINGATAN: Anda akan membatalkan pesanan yang sudah lunas. Kuota tiket akan dikembalikan ke sistem. Lanjutkan?')"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-rose-600/10 hover:bg-rose-600 border border-rose-500/20 hover:border-rose-500 text-rose-400 hover:text-white font-bold text-sm rounded-xl transition-all duration-300">
                                    <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan & Kembalikan Kuota
                                </button>
                            </form>
                        @else
                            <!-- Status is cancelled: Restore option -->
                            <div class="p-4 bg-slate-950/40 border border-slate-800 rounded-xl text-center">
                                <p class="text-xs text-slate-500 mb-3 font-semibold">Pesanan ini telah dibatalkan dan kuota tiket telah dikembalikan.</p>
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin memulihkan status pesanan ini menjadi LUNAS? Sistem akan memvalidasi sisa kuota tiket terlebih dahulu.')"
                                            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-lg transition-all duration-300 shadow-md">
                                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i> Pulihkan ke Lunas
                                    </button>
                                </form>
                            </div>
                        @endif

                        <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" class="mt-4 border-t border-slate-800 pt-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('TINDAKAN SANGAT SENSITIF: Apakah Anda yakin ingin MENGHAPUS PERMANEN pesanan ini dari database beserta tiketnya?')"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-transparent hover:bg-rose-600/10 border border-rose-500/35 hover:border-rose-500 text-rose-500 font-bold text-xs rounded-xl transition-all duration-300">
                                <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus Pesanan Permanen
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@endsection
