@extends('layouts.app')

@section('title', 'Kelola Acara - Panel Admin')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="flex items-center gap-1 text-xs text-slate-500 font-semibold mb-2">
                    <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Admin</a>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                    <span class="text-slate-400">Acara</span>
                </div>
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Manajemen Acara</h1>
                <p class="text-sm text-slate-400">Kelola daftar konser, seminar, pameran seni, atau buat baru.</p>
            </div>
            
            <a href="{{ route('admin.events.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/20">
                <i data-lucide="plus" class="w-4 h-4"></i> Tambah Acara Baru
            </a>
        </div>

        <!-- Success/Error Alerts -->
        @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm rounded-xl flex items-center gap-2">
            <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        <!-- Events List Table -->
        <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-wider bg-slate-950/20">
                            <th class="py-4 px-6">Informasi Acara</th>
                            <th class="py-4 px-6">Tanggal & Tempat</th>
                            <th class="py-4 px-6">Kategori & Kuota Tiket</th>
                            <th class="py-4 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50 text-slate-300 text-xs font-medium">
                        @forelse($events as $event)
                            <tr class="hover:bg-slate-900/20 transition-colors">
                                <!-- Image & Title -->
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-14 h-14 rounded-lg overflow-hidden border border-slate-800 bg-slate-950 flex-shrink-0">
                                            <img src="{{ $event->image_path }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="space-y-0.5">
                                            <h4 class="text-sm font-bold text-white leading-tight line-clamp-1">{{ $event->title }}</h4>
                                            <div class="flex items-center gap-1.5 mt-1">
                                                @if($event->is_featured)
                                                    <span class="inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[9px] font-bold text-indigo-300 bg-indigo-500/10 border border-indigo-500/20 rounded">Featured</span>
                                                @endif
                                                <form action="{{ route('admin.events.toggle-status', $event->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="inline-flex items-center gap-0.5 px-1.5 py-0.5 text-[9px] font-bold border rounded uppercase transition-colors {{ $event->status === 'published' ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400 hover:bg-emerald-500/20' : 'bg-slate-800 border-slate-700 text-slate-400 hover:bg-slate-700' }}" title="Klik untuk mengubah status acara (Publish/Draft)">
                                                        {{ $event->status }}
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Date & Venue -->
                                <td class="py-4 px-6 space-y-1">
                                    <div class="flex items-center gap-1 text-slate-200">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-indigo-400"></i>
                                        <span>{{ $event->date->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </div>
                                    <div class="flex items-center gap-1 text-slate-400">
                                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500 flex-shrink-0"></i>
                                        <span class="line-clamp-1 max-w-[200px]">{{ $event->location }}</span>
                                    </div>
                                </td>

                                <!-- Categories & Quota remaining -->
                                <td class="py-4 px-6">
                                    <div class="flex flex-wrap gap-1.5 max-w-sm">
                                        @foreach($event->ticketCategories as $cat)
                                            <div class="px-2 py-1 bg-slate-950/60 border border-slate-800 rounded-lg text-[10px] space-y-0.5 flex-shrink-0">
                                                <span class="font-bold text-white">{{ $cat->name }}</span>
                                                <div class="flex items-center gap-1.5 text-slate-400">
                                                    <span>Rp {{ number_format($cat->price, 0, ',', '.') }}</span>
                                                    <span class="text-slate-600">|</span>
                                                    <span class="font-semibold {{ $cat->available_quota <= 5 ? 'text-rose-400 font-bold' : 'text-indigo-400' }}">{{ $cat->available_quota }}/{{ $cat->total_quota }} Sisa</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>

                                <!-- CRUD Actions -->
                                <td class="py-4 px-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.events.edit', $event->id) }}" class="p-2 bg-slate-900 border border-slate-800 hover:border-indigo-500/30 hover:text-indigo-400 rounded-xl transition-all" title="Edit">
                                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus acara ini? Kategori tiket yang terhubung juga akan dihapus!')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 bg-slate-900 border border-slate-800 hover:border-rose-500/30 hover:text-rose-400 rounded-xl transition-all" title="Hapus">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-12 text-slate-500 font-normal">
                                    Belum ada acara ditambahkan. Klik tombol "Tambah Acara Baru" untuk membuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
