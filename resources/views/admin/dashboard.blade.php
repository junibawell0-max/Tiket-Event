@extends('layouts.app')

@section('title', 'Dashboard Panel Admin - TiketAcara')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Panel Administrasi</h1>
                <p class="text-sm text-slate-400">Ringkasan bisnis, penjualan tiket, dan manajemen check-in acara.</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-900 border border-slate-800 text-slate-300 hover:text-white rounded-xl text-xs font-bold transition-all">
                    <i data-lucide="calendar" class="w-4 h-4"></i> Kelola Acara
                </a>
                <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/20">
                    <i data-lucide="plus" class="w-4 h-4"></i> Tambah Acara Baru
                </a>
            </div>
        </div>

        <!-- Checkin & Alerts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
            <!-- Checkin Widget -->
            <div class="lg:col-span-2 bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 flex flex-col justify-between">
                <div class="space-y-2">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2">
                        <i data-lucide="scan-line" class="w-5 h-5 text-indigo-400"></i> Verifikasi & Check-In Tiket
                    </h3>
                    <p class="text-xs text-slate-400">Masukkan kode unik tiket peserta (format TCK-XXXXXXXXXX) di bawah ini untuk menandai kehadiran di lokasi acara.</p>
                </div>
                
                <form action="{{ route('admin.tickets.checkin') }}" method="POST" class="mt-4 flex gap-2">
                    @csrf
                    <div class="relative flex-grow">
                        <input type="text" name="ticket_code" required placeholder="Contoh: TCK-XYZ123ABC9"
                            class="w-full pl-4 pr-10 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white font-mono text-sm uppercase">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <i data-lucide="key" class="w-4 h-4 text-slate-500"></i>
                        </div>
                    </div>
                    <button type="submit" class="px-5 bg-indigo-600 hover:bg-indigo-500 rounded-xl text-xs font-bold text-white transition-colors flex items-center gap-1.5 flex-shrink-0">
                        Check-In
                    </button>
                </form>

                <!-- Checkin Notifications -->
                <div class="mt-4">
                    @if(session('checkin_success'))
                    <div class="p-3 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs rounded-xl flex items-start gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                        <span>{{ session('checkin_success') }}</span>
                    </div>
                    @endif

                    @if($errors->has('checkin_error'))
                    <div class="p-3 bg-rose-500/10 border border-rose-500/30 text-rose-400 text-xs rounded-xl flex items-start gap-2">
                        <i data-lucide="x-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                        <span>{{ $errors->first('checkin_error') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stats Mini Column (Quick Quick Overview) -->
            <div class="bg-indigo-950/10 border border-indigo-950 rounded-2xl p-6 flex flex-col justify-center text-center space-y-2">
                <i data-lucide="wallet" class="w-10 h-10 text-indigo-400 mx-auto bg-indigo-500/10 p-2 rounded-xl border border-indigo-500/20"></i>
                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-2">Total Pendapatan Terkumpul</h4>
                <p class="text-2xl font-black text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                <p class="text-[10px] text-emerald-400 font-semibold flex items-center justify-center gap-1">
                    <i data-lucide="trending-up" class="w-3.5 h-3.5"></i> Transaksi Berhasil
                </p>
            </div>
        </div>

        <!-- 4 Stats Cards Row -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Stat 1 -->
            <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Tiket Terjual</p>
                    <h3 class="text-xl font-bold text-white mt-1">{{ $ticketsSold }}</h3>
                </div>
                <div class="p-2.5 bg-slate-950/60 rounded-xl border border-slate-800 text-indigo-400">
                    <i data-lucide="ticket" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Jumlah Acara</p>
                    <h3 class="text-xl font-bold text-white mt-1">{{ $totalEvents }}</h3>
                </div>
                <div class="p-2.5 bg-slate-950/60 rounded-xl border border-slate-800 text-emerald-400">
                    <i data-lucide="calendar" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Total Pelanggan</p>
                    <h3 class="text-xl font-bold text-white mt-1">{{ $totalCustomers }}</h3>
                </div>
                <div class="p-2.5 bg-slate-950/60 rounded-xl border border-slate-800 text-purple-400">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
            </div>

            <!-- Stat 4 -->
            <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Check-In Terverifikasi</p>
                    <h3 class="text-xl font-bold text-white mt-1">
                        {{ $ticketsCheckedIn }}
                    </h3>
                </div>
                <div class="p-2.5 bg-slate-950/60 rounded-xl border border-slate-800 text-rose-400">
                    <i data-lucide="check-square" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <!-- Sales Chart & Recent Orders Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Chart Area -->
            <div class="lg:col-span-8 bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 shadow-2xl space-y-4">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="trending-up" class="w-5 h-5 text-indigo-400"></i> Grafik Tren Omset Penjualan
                </h3>
                <div class="w-full overflow-hidden">
                    <div id="salesChart"></div>
                </div>
            </div>

            <!-- Recent Orders Area -->
            <div class="lg:col-span-4 bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 shadow-2xl space-y-4">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="shopping-bag" class="w-5 h-5 text-indigo-400"></i> Transaksi Terbaru
                </h3>
                
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        <div class="p-3 bg-slate-950/40 border border-slate-800 rounded-xl space-y-1.5 text-xs">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-white">{{ $order->customer_name }}</span>
                                <span class="font-mono text-slate-500 font-semibold">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between items-center text-slate-400">
                                <span class="line-clamp-1 max-w-[150px]">{{ $order->event->title }}</span>
                                <span class="font-bold text-indigo-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center pt-1 border-t border-slate-900">
                                <span class="text-[10px] text-slate-500">{{ $order->created_at->diffForHumans() }}</span>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase {{ $order->status === 'paid' ? 'bg-emerald-500/10 border border-emerald-500/25 text-emerald-400' : 'bg-amber-500/10 border border-amber-500/25 text-amber-400' }}">
                                    {{ $order->status }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-6">Belum ada transaksi pemesanan masuk.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Prepare data from Blade
        const chartLabels = {!! json_encode($chartLabels) !!};
        const chartData = {!! json_encode($chartData) !!};

        const options = {
            chart: {
                type: 'area',
                height: 300,
                toolbar: { show: false },
                background: 'transparent',
                foreColor: '#64748b'
            },
            series: [{
                name: 'Omset Penjualan (IDR)',
                data: chartData
            }],
            xaxis: {
                categories: chartLabels,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                labels: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString('id-ID');
                    }
                }
            },
            colors: ['#6366f1'],
            stroke: {
                curve: 'smooth',
                width: 3
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [0, 90, 100]
                }
            },
            grid: {
                borderColor: '#1e293b',
                strokeDashArray: 4
            },
            theme: {
                mode: 'dark'
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return "Rp " + value.toLocaleString('id-ID');
                    }
                }
            }
        };

        const chart = new ApexCharts(document.querySelector("#salesChart"), options);
        chart.render();
    });
</script>
@endsection
