@extends('layouts.app')

@section('title', 'TiketAcara - Jelajahi Acara Premium Terpopuler')

@section('content')
<div class="bg-grid-pattern relative">
    
    <!-- Hero Section (Featured Event) -->
    @if($featuredEvent)
    <div class="relative py-12 md:py-20 overflow-hidden border-b border-slate-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Text Content -->
                <div class="lg:col-span-7 text-left space-y-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-semibold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 rounded-full">
                        <i data-lucide="sparkles" class="w-3.5 h-3.5"></i> ACARA UNGGULAN
                    </span>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white tracking-tight leading-tight">
                        {{ $featuredEvent->title }}
                    </h1>
                    <p class="text-slate-300 text-lg leading-relaxed max-w-2xl font-light">
                        {!! strip_tags(Str::limit($featuredEvent->description, 180)) !!}
                    </p>
                    
                    <div class="flex flex-wrap gap-4 text-sm text-slate-400 font-medium">
                        <div class="flex items-center gap-2 px-3 py-2 bg-slate-900/40 rounded-xl border border-slate-800/50">
                            <i data-lucide="calendar" class="w-4 h-4 text-indigo-400"></i>
                            <span>{{ $featuredEvent->date->translatedFormat('d F Y, H:i') }} WIB</span>
                        </div>
                        <div class="flex items-center gap-2 px-3 py-2 bg-slate-900/40 rounded-xl border border-slate-800/50">
                            <i data-lucide="map-pin" class="w-4 h-4 text-indigo-400"></i>
                            <span>{{ Str::limit($featuredEvent->location, 30) }}</span>
                        </div>
                    </div>
                    
                    <div class="pt-2">
                        <a href="{{ route('event.show', $featuredEvent->slug) }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold rounded-xl transition-all duration-300 shadow-lg shadow-indigo-600/30 hover:scale-[1.02]">
                            Pesan Tiket Sekarang <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </a>
                    </div>
                </div>

                <!-- Visual Card -->
                <div class="lg:col-span-5 relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-2xl blur-lg opacity-40 group-hover:opacity-60 transition duration-1000"></div>
                    <div class="relative rounded-2xl overflow-hidden border border-slate-800 bg-slate-950 aspect-video lg:aspect-square">
                        <img src="{{ $featuredEvent->image_path }}" alt="{{ $featuredEvent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Discovery Controls -->
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between mb-10">
            <!-- Search Widget -->
            <form action="{{ route('home') }}" method="GET" class="w-full md:max-w-md relative group">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari acara musik, seminar, turnamen..." 
                    class="w-full pl-11 pr-4 py-3 bg-slate-900/50 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm transition-all focus:bg-slate-900">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-slate-500 group-focus-within:text-indigo-400 transition-colors"></i>
                </div>
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>

            <!-- Category Filter Pills -->
            <div class="flex flex-wrap gap-2 w-full md:w-auto overflow-x-auto pb-2 md:pb-0">
                @php
                    $activeCat = request('category');
                    $searchVal = request('search');
                @endphp
                <a href="{{ route('home', array_filter(['search' => $searchVal])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold border transition-all duration-300 {{ !$activeCat ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                    Semua
                </a>
                <a href="{{ route('home', array_filter(['category' => 'music', 'search' => $searchVal])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold border transition-all duration-300 {{ $activeCat === 'music' ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                    Musik
                </a>
                <a href="{{ route('home', array_filter(['category' => 'tech', 'search' => $searchVal])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold border transition-all duration-300 {{ $activeCat === 'tech' ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                    Teknologi
                </a>
                <a href="{{ route('home', array_filter(['category' => 'art', 'search' => $searchVal])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold border transition-all duration-300 {{ $activeCat === 'art' ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                    Seni & Budaya
                </a>
                <a href="{{ route('home', array_filter(['category' => 'esports', 'search' => $searchVal])) }}" 
                   class="px-4 py-2 rounded-xl text-xs font-bold border transition-all duration-300 {{ $activeCat === 'esports' ? 'bg-indigo-600 border-indigo-600 text-white shadow-lg shadow-indigo-600/15' : 'bg-slate-900/60 border-slate-800 text-slate-400 hover:text-white hover:border-slate-700' }}">
                    Esports
                </a>
            </div>
        </div>

        <!-- Event Cards Grid -->
        @if($events->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($events as $event)
                @php
                    $minPrice = $event->ticketCategories->min('price');
                    // Simple category check
                    $catLabel = 'Event';
                    $catColor = 'bg-slate-500/15 text-slate-300 border-slate-500/25';
                    if (Str::contains(strtolower($event->title), ['symphony', 'concert', 'musik'])) {
                        $catLabel = 'Musik';
                        $catColor = 'bg-indigo-500/10 text-indigo-300 border-indigo-500/20';
                    } elseif (Str::contains(strtolower($event->title), ['tech', 'summit', 'teknologi'])) {
                        $catLabel = 'Teknologi';
                        $catColor = 'bg-emerald-500/10 text-emerald-300 border-emerald-500/20';
                    } elseif (Str::contains(strtolower($event->title), ['art', 'exhibition', 'pameran'])) {
                        $catLabel = 'Seni & Budaya';
                        $catColor = 'bg-purple-500/10 text-purple-300 border-purple-500/20';
                    } elseif (Str::contains(strtolower($event->title), ['esports', 'gaming', 'turnamen'])) {
                        $catLabel = 'Esports';
                        $catColor = 'bg-rose-500/10 text-rose-300 border-rose-500/20';
                    }
                @endphp
                <div class="glass-card glass-card-hover rounded-2xl overflow-hidden border border-slate-800/80 flex flex-col h-full group">
                    <!-- Poster Image -->
                    <div class="relative overflow-hidden aspect-video bg-slate-900">
                        <img src="{{ $event->image_path }}" alt="{{ $event->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $catColor }}">
                            {{ $catLabel }}
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div class="space-y-2">
                            <div class="flex items-center gap-1.5 text-xs text-indigo-400 font-semibold">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i>
                                <span>{{ $event->date->translatedFormat('d M Y') }}</span>
                            </div>
                            <h3 class="text-md font-bold text-white group-hover:text-indigo-400 transition-colors line-clamp-2 leading-snug">
                                <a href="{{ route('event.show', $event->slug) }}">{{ $event->title }}</a>
                            </h3>
                            <div class="flex items-center gap-1 text-xs text-slate-400">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500 flex-shrink-0"></i>
                                <span class="line-clamp-1">{{ $event->location }}</span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="border-t border-slate-900 mt-5 pt-4 flex items-center justify-between gap-2">
                            <div>
                                <p class="text-[10px] text-slate-500 font-semibold uppercase tracking-wider">Mulai Dari</p>
                                <p class="text-sm font-bold text-white">
                                    {{ $minPrice ? 'Rp ' . number_format($minPrice, 0, ',', '.') : 'Gratis' }}
                                </p>
                            </div>
                            <a href="{{ route('event.show', $event->slug) }}" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-indigo-500/10 group-hover:bg-indigo-600 text-indigo-300 group-hover:text-white transition-all duration-300">
                                <i data-lucide="chevron-right" class="w-4 h-4"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16 bg-slate-900/20 border border-slate-950 rounded-2xl">
            <div class="w-16 h-16 bg-slate-900 border border-slate-800 rounded-2xl flex items-center justify-center mx-auto mb-4 text-slate-500">
                <i data-lucide="calendar-off" class="w-8 h-8"></i>
            </div>
            <h3 class="text-lg font-bold text-white">Tidak Ada Acara Ditemukan</h3>
            <p class="text-slate-400 text-sm mt-1">Coba gunakan kata kunci pencarian atau kategori lain.</p>
        </div>
        @endif
    </div>
</div>
@endsection
