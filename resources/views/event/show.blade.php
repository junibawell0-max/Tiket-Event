@extends('layouts.app')

@section('title', $event->title . ' - TiketAcara')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Back Link -->
        <div class="mb-6">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-400 hover:text-white transition-colors">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
            </a>
        </div>

        <!-- Success/Error Alerts -->
        @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-400 text-sm rounded-xl flex items-start gap-2">
            <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
            <div>
                <p class="font-bold">Terjadi Kesalahan:</p>
                <ul class="list-disc list-inside mt-1 text-rose-300 text-xs">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Poster, Title, Description -->
            <div class="lg:col-span-8 space-y-8">
                <!-- Poster Banner -->
                <div class="relative rounded-2xl overflow-hidden border border-slate-800 bg-slate-900 aspect-video shadow-2xl">
                    <img src="{{ $event->image_path }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent"></div>
                </div>

                <!-- Event Details Header -->
                <div class="space-y-4">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight">
                        {{ $event->title }}
                    </h1>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 p-4 bg-slate-900/40 rounded-2xl border border-slate-800/40">
                            <div class="p-2.5 bg-indigo-500/10 rounded-xl text-indigo-400">
                                <i data-lucide="calendar" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Tanggal & Waktu</p>
                                <p class="text-sm font-bold text-slate-200">{{ $event->date->translatedFormat('d F Y') }}</p>
                                <p class="text-xs text-slate-400">{{ $event->date->translatedFormat('H:i') }} WIB</p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 p-4 bg-slate-900/40 rounded-2xl border border-slate-800/40">
                            <div class="p-2.5 bg-indigo-500/10 rounded-xl text-indigo-400">
                                <i data-lucide="map-pin" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-500 font-semibold uppercase">Lokasi Acara</p>
                                <p class="text-sm font-bold text-slate-200 line-clamp-1">{{ $event->location }}</p>
                                <p class="text-xs text-slate-400">Fisik / On-site</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description Block -->
                <div class="bg-slate-900/20 rounded-2xl border border-slate-900 p-6 space-y-4">
                    <h3 class="text-lg font-bold text-white flex items-center gap-2">
                        <i data-lucide="align-left" class="w-5 h-5 text-indigo-400"></i> Deskripsi Acara
                    </h3>
                    <div class="text-slate-300 text-sm leading-relaxed space-y-4 font-light">
                        {!! $event->description !!}
                    </div>
                </div>
            </div>

            <!-- Right Column: Ticket Purchase Panel -->
            <div class="lg:col-span-4">
                <div class="sticky top-24 bg-slate-900/50 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-2xl space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                            <i data-lucide="ticket" class="w-5 h-5 text-indigo-400"></i> Pilih Tiket
                        </h3>
                    </div>

                    <!-- Checkout Form -->
                    <form action="{{ route('checkout.show') }}" method="POST" id="ticketForm" class="space-y-6">
                        @csrf
                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div class="space-y-4">
                            @foreach($event->ticketCategories as $category)
                            <div class="p-4 rounded-xl border transition-all duration-300 {{ $category->available_quota > 0 ? 'bg-slate-950/40 border-slate-800/80' : 'bg-slate-950/10 border-slate-900/60 opacity-60' }}">
                                <div class="flex items-start justify-between gap-2">
                                    <div class="space-y-1">
                                        <h4 class="font-bold text-white text-sm">{{ $category->name }}</h4>
                                        <p class="text-indigo-400 font-bold text-sm">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                                        <p class="text-[10px] text-slate-500 font-medium">Sisa Kuota: {{ $category->available_quota }} / {{ $category->total_quota }}</p>
                                    </div>

                                    @if($category->available_quota > 0)
                                    <!-- Counter Widget -->
                                    <div class="flex items-center gap-2 border border-slate-800 bg-slate-950 rounded-lg p-1">
                                        <button type="button" onclick="decrement({{ $category->id }}, {{ $category->price }})"
                                            class="w-6 h-6 rounded flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-slate-300 font-bold text-sm transition-colors select-none">-</button>
                                        
                                        <input type="number" name="tickets[{{ $category->id }}]" id="qty_{{ $category->id }}" value="0" min="0" max="{{ min(5, $category->available_quota) }}"
                                            class="w-8 text-center text-xs font-bold text-white bg-transparent border-0 p-0 focus:ring-0 select-none pointer-events-none" readonly>
                                        
                                        <button type="button" onclick="increment({{ $category->id }}, {{ $category->price }}, {{ min(5, $category->available_quota) }})"
                                            class="w-6 h-6 rounded flex items-center justify-center bg-slate-900 hover:bg-slate-800 text-slate-300 font-bold text-sm transition-colors select-none">+</button>
                                    </div>
                                    @else
                                    <span class="text-xs font-semibold px-2 py-1 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-lg">Habis</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Summary pricing info -->
                        <div class="border-t border-slate-800 pt-4 space-y-3">
                            <div class="flex justify-between items-center text-sm font-medium text-slate-400">
                                <span>Total Tiket:</span>
                                <span id="totalTickets">0</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-slate-800/40 pt-2">
                                <span class="text-sm font-semibold text-slate-300">Total Harga:</span>
                                <span class="text-lg font-black text-indigo-400" id="totalPriceDisplay">Rp 0</span>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div>
                            @auth
                                <button type="submit" id="checkoutBtn" disabled
                                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-indigo-600/20 hover:shadow-indigo-600/35 disabled:opacity-50 disabled:cursor-not-allowed">
                                    Lanjut ke Pemesanan
                                </button>
                            @else
                                <a href="{{ route('login') }}" 
                                    class="w-full flex justify-center py-3.5 px-4 rounded-xl text-center text-sm font-bold text-white bg-slate-800 hover:bg-slate-700 transition-colors border border-slate-700">
                                    Masuk untuk Memesan
                                </a>
                            @endauth
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Object tracking quantities
    const ticketQuantities = {};
    const ticketPrices = {};

    function increment(catId, price, maxQuota) {
        const input = document.getElementById(`qty_${catId}`);
        let currentVal = parseInt(input.value);
        if (currentVal < maxQuota) {
            input.value = currentVal + 1;
            ticketQuantities[catId] = currentVal + 1;
            ticketPrices[catId] = price;
            updateSummary();
        }
    }

    function decrement(catId, price) {
        const input = document.getElementById(`qty_${catId}`);
        let currentVal = parseInt(input.value);
        if (currentVal > 0) {
            input.value = currentVal - 1;
            ticketQuantities[catId] = currentVal - 1;
            ticketPrices[catId] = price;
            updateSummary();
        }
    }

    function updateSummary() {
        let totalQty = 0;
        let totalPrice = 0;

        for (const [id, qty] of Object.entries(ticketQuantities)) {
            totalQty += qty;
            totalPrice += qty * (ticketPrices[id] || 0);
        }

        document.getElementById('totalTickets').innerText = totalQty;
        document.getElementById('totalPriceDisplay').innerText = 'Rp ' + totalPrice.toLocaleString('id-ID');

        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            if (totalQty > 0) {
                checkoutBtn.removeAttribute('disabled');
            } else {
                checkoutBtn.setAttribute('disabled', 'true');
            }
        }
    }
</script>
@endsection
