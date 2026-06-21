@extends('layouts.app')

@section('title', 'Tambah Acara Baru - Panel Admin')

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
                <span class="text-slate-400">Tambah Baru</span>
            </div>
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Buat Acara Baru</h1>
            <p class="text-sm text-slate-400">Buat konser, seminar, eksibisi baru dan tambahkan opsi tiket.</p>
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

            <form action="{{ route('admin.events.store') }}" method="POST" class="space-y-8">
                @csrf
                
                <!-- Section 1: Event Details -->
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-5">
                    <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                        <i data-lucide="info" class="w-5 h-5 text-indigo-400"></i> Informasi Utama Acara
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label for="title" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Acara</label>
                            <input type="text" name="title" id="title" required value="{{ old('title') }}" placeholder="Contoh: Jakarta Indie Rock Fest 2026"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div>
                            <label for="date" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Tanggal & Waktu</label>
                            <input type="datetime-local" name="date" id="date" required value="{{ old('date') }}"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div>
                            <label for="location" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Lokasi / Tempat Acara</label>
                            <input type="text" name="location" id="location" required value="{{ old('location') }}" placeholder="Contoh: Balai Sarbini, Jakarta"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="image_path" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">URL Cover/Poster Gambar</label>
                            <input type="url" name="image_path" id="image_path" value="{{ old('image_path') }}" placeholder="Contoh: https://images.unsplash.com/... (Biarkan kosong untuk default)"
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label for="description" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Deskripsi Acara (HTML/Teks)</label>
                            <textarea name="description" id="description" rows="5" required placeholder="Jelaskan deskripsi acara, syarat masuk, penampil, dll..."
                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">{{ old('description') }}</textarea>
                        </div>
                        
                        <div class="sm:col-span-2">
                            <label class="inline-flex items-center cursor-pointer select-none">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-800 rounded bg-slate-950">
                                <span class="ml-2 text-xs font-semibold text-slate-300">Tampilkan sebagai Acara Unggulan (Slide Banner Utama)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Ticket Categories (Dynamic) -->
                <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2">
                            <i data-lucide="ticket" class="w-5 h-5 text-indigo-400"></i> Kategori & Tarif Tiket
                        </h3>
                        <button type="button" onclick="addCategoryRow()" 
                            class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-950 border border-slate-800 hover:border-slate-700 text-indigo-400 hover:text-indigo-300 rounded-lg text-xs font-semibold transition-colors">
                            <i data-lucide="plus" class="w-3.5 h-3.5"></i> Tambah Kategori
                        </button>
                    </div>

                    <!-- Category Rows Container -->
                    <div id="categoryContainer" class="space-y-4">
                        <!-- Default Row #1 -->
                        <div class="category-row grid grid-cols-1 sm:grid-cols-12 gap-3 p-4 bg-slate-950/40 border border-slate-800 rounded-xl items-end relative">
                            <div class="sm:col-span-5">
                                <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-1">Nama Tiket (e.g. VIP, General)</label>
                                <input type="text" name="categories[0][name]" required placeholder="Masukkan nama"
                                    class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-white text-xs">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-1">Harga (Rupiah)</label>
                                <input type="number" name="categories[0][price]" required min="0" placeholder="e.g. 500000"
                                    class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-white text-xs">
                            </div>
                            <div class="sm:col-span-3">
                                <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-1">Kapasitas (Kuota)</label>
                                <input type="number" name="categories[0][quota]" required min="1" placeholder="e.g. 100"
                                    class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-white text-xs">
                            </div>
                            <div class="sm:col-span-1 flex justify-center pb-1">
                                <button type="button" onclick="removeRow(this)" disabled
                                    class="p-2 text-slate-600 cursor-not-allowed hover:text-rose-400 rounded transition-colors remove-btn">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end gap-3 max-w-4xl mx-auto">
                    <a href="{{ route('admin.events.index') }}" class="px-6 py-3 bg-slate-900 border border-slate-800 text-slate-400 hover:text-white rounded-xl text-xs font-bold transition-all">
                        Batalkan
                    </a>
                    <button type="submit" 
                        class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-bold transition-all shadow-lg shadow-indigo-600/20">
                        Simpan Acara
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    let categoryCounter = 1;

    function addCategoryRow() {
        const container = document.getElementById('categoryContainer');
        const newRow = document.createElement('div');
        newRow.className = "category-row grid grid-cols-1 sm:grid-cols-12 gap-3 p-4 bg-slate-950/40 border border-slate-800 rounded-xl items-end relative";
        
        newRow.innerHTML = `
            <div class="sm:col-span-5">
                <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-1">Nama Tiket (e.g. VIP, General)</label>
                <input type="text" name="categories[${categoryCounter}][name]" required placeholder="Masukkan nama"
                    class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-white text-xs">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-1">Harga (Rupiah)</label>
                <input type="number" name="categories[${categoryCounter}][price]" required min="0" placeholder="e.g. 500000"
                    class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-white text-xs">
            </div>
            <div class="sm:col-span-3">
                <label class="block text-[10px] font-semibold text-slate-500 uppercase mb-1">Kapasitas (Kuota)</label>
                <input type="number" name="categories[${categoryCounter}][quota]" required min="1" placeholder="e.g. 100"
                    class="w-full px-3 py-2 bg-slate-950 border border-slate-800 rounded-lg text-white text-xs">
            </div>
            <div class="sm:col-span-1 flex justify-center pb-1">
                <button type="button" onclick="removeRow(this)"
                    class="p-2 text-slate-500 hover:text-rose-400 rounded transition-colors remove-btn">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        `;

        container.appendChild(newRow);
        categoryCounter++;
        
        // Reinitialize Lucide Icons on the newly created DOM nodes
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
        updateRemoveButtons();
    }

    function removeRow(buttonElement) {
        const row = buttonElement.closest('.category-row');
        const container = document.getElementById('categoryContainer');
        if (container.getElementsByClassName('category-row').length > 1) {
            row.remove();
            updateRemoveButtons();
        }
    }

    function updateRemoveButtons() {
        const container = document.getElementById('categoryContainer');
        const buttons = container.getElementsByClassName('remove-btn');
        const rowsCount = container.getElementsByClassName('category-row').length;

        for (let btn of buttons) {
            if (rowsCount <= 1) {
                btn.setAttribute('disabled', 'true');
                btn.classList.add('text-slate-600', 'cursor-not-allowed');
                btn.classList.remove('text-slate-500', 'hover:text-rose-400');
            } else {
                btn.removeAttribute('disabled');
                btn.classList.remove('text-slate-600', 'cursor-not-allowed');
                btn.classList.add('text-slate-500', 'hover:text-rose-400');
            }
        }
    }
</script>
@endsection
