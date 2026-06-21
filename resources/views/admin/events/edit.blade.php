@extends('layouts.app')

@section('title', 'Edit Acara - Panel Admin')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Title -->
        <div class="max-w-4xl mx-auto mb-8">
            <div class="flex items-center gap-1 text-xs text-slate-500 font-semibold mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-white transition-colors">Admin</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <a href="{{ route('admin.events.index') }}" class="hover:text-white transition-colors">Acara</a>
                <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                <span class="text-slate-400">Edit Acara</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Edit Acara</h1>
            <p class="text-sm text-slate-400">Perbarui rincian detail acara yang sudah terdaftar.</p>
        </div>

        <!-- Form Container -->
        <div class="max-w-4xl mx-auto">
            
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm rounded-xl flex items-start gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-semibold">Terjadi Kesalahan Validasi:</p>
                        <ul class="list-disc list-inside mt-1 text-rose-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.events.update', $event->id) }}" method="POST" class="space-y-8">
                @csrf
                @method('PUT')
                
                <!-- Section 1: Event Details -->
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-5">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                        <i data-lucide="info" class="w-5 h-5 text-indigo-400"></i> Informasi Utama Acara
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Acara</label>
                            <input type="text" name="title" id="title" required value="{{ old('title', $event->title) }}"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div>
                            <label for="date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tanggal & Waktu</label>
                            <input type="datetime-local" name="date" id="date" required value="{{ old('date', $event->date->format('Y-m-d\TH:i')) }}"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div>
                            <label for="location" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Lokasi / Tempat Acara</label>
                            <input type="text" name="location" id="location" required value="{{ old('location', $event->location) }}"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="image_path" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">URL Cover/Poster Gambar</label>
                            <input type="url" name="image_path" id="image_path" value="{{ old('image_path', $event->image_path) }}"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Acara (HTML/Teks)</label>
                            <textarea name="description" id="description" rows="5" required
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">{{ old('description', $event->description) }}</textarea>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-800 rounded bg-slate-950">
                                <span class="ml-2 text-xs font-semibold text-slate-300">Tampilkan sebagai Acara Unggulan (Slide Banner Utama)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end gap-3 max-w-4xl mx-auto mt-6">
                    <a href="{{ route('admin.events.index') }}" class="px-6 py-3 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white rounded-xl text-xs font-bold transition-all">
                        Kembali
                    </a>
                    <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/20">
                        Perbarui Informasi Acara
                    </button>
                </div>
            </form>

            <!-- Section 2: Ticket Categories (CRUD) -->
            <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-6 mt-8">
                <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                    <i data-lucide="ticket" class="w-5 h-5 text-indigo-400"></i> Kelola Kategori Tiket (CRUD)
                </h3>

                <!-- Alert Success / Quota Error -->
                @if($errors->has('quota_error') || $errors->has('category_error'))
                    <div class="p-4 bg-rose-500/10 border border-rose-500/20 rounded-xl text-rose-400 text-xs flex items-center gap-2">
                        <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                        <span>{{ $errors->first('quota_error') ?: $errors->first('category_error') }}</span>
                    </div>
                @endif

                <!-- List of current categories -->
                <div class="space-y-4">
                    @forelse($event->ticketCategories as $cat)
                        <div class="p-4 bg-slate-950/60 border border-slate-850 rounded-xl flex flex-col md:flex-row md:items-center justify-between gap-4 hover:border-slate-800 transition">
                            <div class="space-y-1.5">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-bold text-white">{{ $cat->name }}</span>
                                    <span class="px-2.5 py-0.5 bg-indigo-500/10 border border-indigo-500/20 rounded-lg text-xs font-extrabold text-indigo-400">
                                        Rp {{ number_format($cat->price, 0, ',', '.') }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-400 font-semibold flex items-center gap-3">
                                    <span>Kapasitas: <strong class="text-slate-200">{{ $cat->total_quota }}</strong></span>
                                    <span>Tersisa: <strong class="text-indigo-450">{{ $cat->available_quota }}</strong></span>
                                    <span>Terjual: <strong class="text-emerald-450">{{ $cat->total_quota - $cat->available_quota }}</strong></span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button" onclick="document.getElementById('edit-form-{{ $cat->id }}').classList.toggle('hidden')" 
                                        class="px-3 py-1.5 bg-slate-900 border border-slate-800 hover:border-slate-700 text-xs font-bold text-slate-300 hover:text-white rounded-lg transition flex items-center gap-1">
                                    <i data-lucide="edit-2" class="w-3.5 h-3.5"></i> Edit
                                </button>

                                <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori tiket ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 bg-rose-600/10 hover:bg-rose-650 border border-rose-500/20 hover:border-rose-650 text-xs font-bold text-rose-450 hover:text-white rounded-lg transition flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Inline Edit form (hidden by default) -->
                        <div id="edit-form-{{ $cat->id }}" class="hidden p-4 bg-slate-900/60 border border-slate-800 rounded-xl mt-2 space-y-4">
                            <h4 class="text-xs font-bold text-slate-200">Ubah Kategori Tiket: {{ $cat->name }}</h4>
                            <form action="{{ route('admin.categories.update', $cat->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                @csrf
                                @method('PUT')
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Kategori</label>
                                    <input type="text" name="name" value="{{ $cat->name }}" required class="w-full px-3 py-2 bg-slate-950/80 border border-slate-800 rounded-lg text-xs text-white focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Harga (Rp)</label>
                                    <input type="number" name="price" value="{{ $cat->price }}" required class="w-full px-3 py-2 bg-slate-950/80 border border-slate-800 rounded-lg text-xs text-white focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Kapasitas Baru</label>
                                    <input type="number" name="total_quota" value="{{ $cat->total_quota }}" required class="w-full px-3 py-2 bg-slate-950/80 border border-slate-800 rounded-lg text-xs text-white focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="sm:col-span-3 flex justify-end gap-2 mt-2">
                                    <button type="button" onclick="document.getElementById('edit-form-{{ $cat->id }}').classList.add('hidden')" class="px-3 py-1.5 text-xs text-slate-500 font-bold hover:text-slate-300">
                                        Batal
                                    </button>
                                    <button type="submit" class="px-4 py-1.5 bg-indigo-600 hover:bg-indigo-500 text-xs text-white font-bold rounded-lg transition shadow-md">
                                        Simpan Kategori
                                    </button>
                                </div>
                            </form>
                        </div>
                    @empty
                        <p class="text-xs text-slate-500 text-center py-4">Belum ada kategori tiket untuk acara ini.</p>
                    @endforelse
                </div>

                <hr class="border-slate-800/60 my-6">

                <!-- Add new category form -->
                <form action="{{ route('admin.categories.store', $event->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <h4 class="text-xs font-bold uppercase tracking-wider text-indigo-400 flex items-center gap-1.5">
                        <i data-lucide="plus-circle" class="w-4 h-4"></i> Tambah Kategori Baru
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-450 uppercase tracking-wider mb-1">Nama Kategori</label>
                            <input type="text" name="name" required placeholder="Contoh: VIP Standing" class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-xs text-white focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-455 uppercase tracking-wider mb-1">Harga (Rp)</label>
                            <input type="number" name="price" required placeholder="Contoh: 750000" class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-xs text-white focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-460 uppercase tracking-wider mb-1">Kapasitas (Kuota)</label>
                            <input type="number" name="quota" required placeholder="Contoh: 100" class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl text-xs text-white focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="px-5 py-2.5 bg-indigo-650 hover:bg-indigo-600 text-xs font-bold text-white rounded-xl transition shadow-lg shadow-indigo-600/20">
                            Simpan Kategori Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
