@extends('layouts.app')

@section('title', 'Selesaikan Pembayaran - TiketAcara')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Step Indicator -->
        <div class="max-w-4xl mx-auto mb-10">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                <div class="flex items-center gap-1.5 text-indigo-400">
                    <span class="w-5 h-5 rounded-full bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-[10px] font-bold">1</span>
                    <span>Pilih Tiket</span>
                </div>
                <div class="w-16 h-px bg-indigo-500/20"></div>
                <div class="flex items-center gap-1.5 text-indigo-400">
                    <span class="w-5 h-5 rounded-full bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-[10px] font-bold">2</span>
                    <span>Isi Data & Checkout</span>
                </div>
                <div class="w-16 h-px bg-indigo-500/20"></div>
                <div class="flex items-center gap-1.5 text-indigo-400">
                    <span class="w-5 h-5 rounded-full bg-indigo-500 border border-indigo-500 flex items-center justify-center text-[10px] font-bold text-white shadow-lg shadow-indigo-600/30">3</span>
                    <span class="text-white font-bold">Pembayaran</span>
                </div>
                <div class="w-16 h-px bg-slate-800"></div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-[10px] font-bold">4</span>
                    <span>E-Tiket Terbit</span>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto grid grid-cols-1 gap-8">
            
            <!-- 1. Countdown Widget -->
            <div class="bg-indigo-950/20 border border-indigo-500/30 rounded-2xl p-6 text-center space-y-2">
                <p class="text-xs font-semibold text-indigo-400 uppercase tracking-widest">Selesaikan Pembayaran Dalam Waktu</p>
                <div class="text-3xl font-black text-white tracking-widest font-mono" id="countdown">10:00</div>
                <p class="text-xs text-slate-400">Segera selesaikan pembayaran Anda sebelum tiket dirilis kembali ke kuota umum.</p>
            </div>

            <!-- 2. Invoice Card -->
            <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-6">
                <div class="flex justify-between items-center border-b border-slate-800 pb-4">
                    <div>
                        <p class="text-xs text-slate-500 font-semibold uppercase">Nomor Pesanan</p>
                        <h3 class="text-md font-bold text-white tracking-tight">{{ $order->order_number }}</h3>
                    </div>
                    <span class="px-2.5 py-1 bg-amber-500/10 border border-amber-500/25 text-amber-400 text-xs font-bold rounded-lg flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span> Menunggu Pembayaran
                    </span>
                </div>

                <!-- Payment Details Instructions -->
                <div class="space-y-4">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Detail Pembayaran</p>
                    
                    <div class="p-4 bg-slate-950/40 border border-slate-800 rounded-xl space-y-3">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-slate-400">Metode Pembayaran:</span>
                            <span class="text-white font-bold">{{ $order->payment_method }}</span>
                        </div>
                        
                        <div class="border-t border-slate-900 my-2"></div>
                        
                        <!-- Dynamic Bank VA details based on selection -->
                        <div class="space-y-1">
                            @if(Str::contains($order->payment_method, 'BCA'))
                                <p class="text-[10px] text-slate-500 font-semibold">NOMOR VIRTUAL ACCOUNT BCA</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-mono font-bold text-indigo-400 select-all">80931 {{ date('Ymd') }} 74</span>
                                    <span class="text-xs text-slate-500 font-mono">BCA VA</span>
                                </div>
                            @elseif(Str::contains($order->payment_method, 'Mandiri'))
                                <p class="text-[10px] text-slate-500 font-semibold">NOMOR VIRTUAL ACCOUNT MANDIRI</p>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-mono font-bold text-indigo-400 select-all">88908 {{ date('Ymd') }} 12</span>
                                    <span class="text-xs text-slate-500 font-mono">MANDIRI VA</span>
                                </div>
                            @else
                                <p class="text-[10px] text-slate-500 font-semibold">PINDAI KODE QRIS DI BAWAH</p>
                                <div class="flex justify-center p-4 bg-white rounded-xl w-32 h-32 mx-auto">
                                    <!-- A mock QR code image using public API or simple pattern -->
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=TIKETACARA-INVOICE-{{ $order->order_number }}" alt="QRIS QR Code" class="w-full h-full">
                                </div>
                            @endif
                        </div>

                        <div class="border-t border-slate-900 my-2"></div>

                        <div class="flex justify-between items-center">
                            <span class="text-xs text-slate-400 font-semibold">Total Transfer:</span>
                            <span class="text-md font-extrabold text-indigo-400">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Event Details Brief -->
                <div class="p-4 bg-slate-900/30 border border-slate-900 rounded-xl flex items-center justify-between">
                    <div>
                        <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tiket Acara</h4>
                        <p class="text-sm font-bold text-white mt-1">{{ $order->event->title }}</p>
                        <p class="text-[11px] text-slate-400">{{ $order->tickets->count() }} Tiket ({{ $order->tickets->pluck('ticketCategory.name')->unique()->join(', ') }})</p>
                    </div>
                </div>

                <!-- Action Button Form (Simulasi) -->
                <div class="border-t border-slate-800 pt-6 space-y-3">
                    <p class="text-xs text-slate-500 text-center font-light uppercase tracking-wider mb-2">Simulasi Gateway Pembayaran</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Success Button -->
                        <form action="{{ route('checkout.pay', $order->order_number) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="success">
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 transition-colors shadow-indigo-600/10 flex items-center justify-center gap-2">
                                <i data-lucide="check-circle" class="w-4 h-4"></i> Simulasikan Bayar Sukses
                            </button>
                        </form>

                        <!-- Cancel/Expire Button -->
                        <form action="{{ route('checkout.pay', $order->order_number) }}" method="POST">
                            @csrf
                            <input type="hidden" name="action" value="cancel">
                            <button type="submit" 
                                class="w-full flex justify-center py-3 px-4 border border-rose-500/20 rounded-xl shadow-lg text-xs font-bold text-rose-400 hover:bg-rose-500/10 transition-colors flex items-center justify-center gap-2">
                                <i data-lucide="x-circle" class="w-4 h-4"></i> Batalkan Pemesanan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Countdown Timer logic (10 minutes)
    document.addEventListener('DOMContentLoaded', () => {
        let totalSeconds = 600; // 10 minutes
        const display = document.getElementById('countdown');

        const interval = setInterval(() => {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;

            const formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
            const formattedSeconds = seconds < 10 ? '0' + seconds : seconds;

            display.innerText = `${formattedMinutes}:${formattedSeconds}`;

            if (totalSeconds <= 0) {
                clearInterval(interval);
                // Trigger auto cancel
                alert('Waktu pembayaran habis. Anda akan dialihkan ke halaman utama.');
                window.location.href = "{{ route('home') }}";
            }

            totalSeconds--;
        }, 1000);
    });
</script>
@endsection
