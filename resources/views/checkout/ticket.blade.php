@extends('layouts.app')

@section('title', 'E-Tiket Resmi - TiketAcara')

@section('styles')
<style>
    /* Styling khusus cetak (print CSS) untuk hanya mencetak card tiket */
    @media print {
        header, footer, .no-print, .ambient-bg {
            display: none !important;
        }
        body {
            background: white !important;
            color: black !important;
        }
        .print-container {
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            background: white !important;
            width: 100% !important;
        }
        .ticket-card {
            border: 2px solid #000 !important;
            background: white !important;
            color: black !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        .ticket-header {
            background: #f1f5f9 !important;
            color: black !important;
            border-bottom: 2px solid #000 !important;
        }
        .text-indigo-400, .text-indigo-300 {
            color: #4f46e5 !important;
        }
        .text-slate-400, .text-slate-500 {
            color: #475569 !important;
        }
        #qrcode img {
            filter: grayscale(1) !important;
        }
    }
</style>
@endsection

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Success Banner -->
        @if(session('success'))
        <div class="max-w-3xl mx-auto mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm rounded-xl flex items-center gap-2 no-print">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span>{{ session('success') }} Tiket Anda telah resmi diterbitkan.</span>
        </div>
        @endif

        <div class="max-w-3xl mx-auto space-y-6 print-container">
            
            <!-- E-Ticket Main Card -->
            <div class="ticket-card bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl overflow-hidden shadow-2xl relative">
                <!-- Tear strip circles decorators -->
                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-6 h-12 bg-slate-950 border-r border-slate-800/80 rounded-r-full hidden md:block no-print"></div>
                <div class="absolute right-0 top-1/2 -translate-y-1/2 w-6 h-12 bg-slate-950 border-l border-slate-800/80 rounded-l-full hidden md:block no-print"></div>
                
                <!-- Ticket Header -->
                <div class="ticket-header p-6 bg-slate-900/60 border-b border-slate-800/80 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="p-2 bg-indigo-600 rounded-lg text-white">
                            <i data-lucide="ticket" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <span class="text-xs text-indigo-400 font-bold uppercase tracking-widest">E-Tiket Resmi</span>
                            <h2 class="text-md font-extrabold text-white">TiketAcara Pass</h2>
                        </div>
                    </div>
                    
                    <div class="text-left sm:text-right">
                        <p class="text-[10px] text-slate-500 font-semibold uppercase">Kode Booking</p>
                        <p class="text-sm font-mono font-bold text-white tracking-wide">{{ $ticket->order->order_number }}</p>
                    </div>
                </div>

                <!-- Ticket Content -->
                <div class="p-6 md:p-8 grid grid-cols-1 md:grid-cols-12 gap-8 items-center">
                    
                    <!-- Left: Details -->
                    <div class="md:col-span-8 space-y-6">
                        <div>
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-extrabold bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 uppercase tracking-wider">
                                {{ $ticket->ticketCategory->name }}
                            </span>
                            <h3 class="text-xl md:text-2xl font-black text-white tracking-tight mt-2 leading-tight">
                                {{ $ticket->order->event->title }}
                            </h3>
                        </div>

                        <div class="grid grid-cols-2 gap-y-4 gap-x-2 text-xs">
                            <div>
                                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Nama Peserta</p>
                                <p class="text-sm font-bold text-slate-200 mt-0.5">{{ $ticket->attendee_name }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Tanggal & Waktu</p>
                                <p class="text-sm font-bold text-slate-200 mt-0.5">{{ $ticket->order->event->date->translatedFormat('d F Y, H:i') }} WIB</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Lokasi / Venue</p>
                                <p class="text-sm font-bold text-slate-200 mt-0.5">{{ $ticket->order->event->location }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right: QR Code -->
                    <div class="md:col-span-4 flex flex-col items-center justify-center p-4 bg-slate-950/40 border border-slate-800 rounded-2xl text-center space-y-3">
                        <div id="qrcode" class="p-3 bg-white rounded-xl flex items-center justify-center w-36 h-36">
                            <!-- QR Code generated by JS -->
                        </div>
                        <div class="space-y-0.5">
                            <p class="text-[10px] text-slate-500 font-semibold uppercase">Kode Tiket Unik</p>
                            <p class="text-xs font-mono font-bold text-indigo-400 select-all">{{ $ticket->ticket_code }}</p>
                        </div>
                    </div>

                </div>

                <!-- Print Note/Divider -->
                <div class="px-6 py-4 bg-slate-900/20 border-t border-slate-800/40 flex justify-between items-center text-xs text-slate-500 no-print">
                    <div class="flex items-center gap-1">
                        <i data-lucide="info" class="w-4 h-4 text-slate-500"></i>
                        <span>Tunjukkan kode QR di atas kepada panitia untuk Check-In</span>
                    </div>
                </div>
            </div>

            <!-- Print Actions and Print Info -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4 no-print">
                <button onclick="window.print()" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 hover:bg-slate-800 border border-slate-800 hover:border-slate-700 text-white font-bold rounded-xl transition-all">
                    <i data-lucide="printer" class="w-4 h-4"></i> Cetak Tiket (PDF)
                </button>
                
                <a href="{{ route('home') }}" 
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all">
                    Jelajahi Acara Lain <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

            <!-- Other tickets from the same order -->
            @if($otherTickets->count() > 0)
            <div class="bg-slate-900/20 border border-slate-900 rounded-2xl p-6 space-y-4 no-print">
                <h3 class="text-sm font-bold text-white flex items-center gap-1.5">
                    <i data-lucide="copy" class="w-4 h-4 text-indigo-400"></i> Tiket Lain dalam Pesanan Ini
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($otherTickets as $other)
                    <a href="{{ route('checkout.ticket', $other->ticket_code) }}" 
                        class="p-4 bg-slate-950/40 hover:bg-slate-900/60 border border-slate-800 rounded-xl flex items-center justify-between transition-colors group">
                        <div class="space-y-0.5">
                            <p class="text-xs font-bold text-white group-hover:text-indigo-400 transition-colors">{{ $other->attendee_name }}</p>
                            <p class="text-[10px] text-slate-500">{{ $other->ticketCategory->name }}</p>
                        </div>
                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-500 group-hover:text-indigo-400 transition-colors"></i>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- QR Code Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Generate QR code for the ticket code
        const ticketCode = "{{ $ticket->ticket_code }}";
        const qrcodeDiv = document.getElementById("qrcode");
        
        new QRCode(qrcodeDiv, {
            text: ticketCode,
            width: 120,
            height: 120,
            colorDark : "#020617",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    });
</script>
@endsection
