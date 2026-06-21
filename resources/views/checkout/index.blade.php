@extends('layouts.app')

@section('title', 'Formulir Pemesanan Tiket - TiketAcara')

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
                    <span class="w-5 h-5 rounded-full bg-indigo-500 border border-indigo-500 flex items-center justify-center text-[10px] font-bold text-white shadow-lg shadow-indigo-600/30">2</span>
                    <span class="text-white">Isi Data & Checkout</span>
                </div>
                <div class="w-16 h-px bg-slate-800"></div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-[10px] font-bold">3</span>
                    <span>Pembayaran</span>
                </div>
                <div class="w-16 h-px bg-slate-800"></div>
                <div class="flex items-center gap-1.5">
                    <span class="w-5 h-5 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center text-[10px] font-bold">4</span>
                    <span>E-Tiket Terbit</span>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" class="max-w-6xl mx-auto">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                <!-- Left Column: Forms -->
                <div class="lg:col-span-8 space-y-8">
                    
                    <!-- 1. Customer Info -->
                    <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-4">
                        <h3 class="text-md font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                            <i data-lucide="user" class="w-5 h-5 text-indigo-400"></i> Informasi Kontak Pembeli
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="customer_name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                                <input type="text" name="customer_name" id="customer_name" required value="{{ old('customer_name', Auth::user()->name) }}"
                                    class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                            </div>
                            <div>
                                <label for="customer_email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat Email</label>
                                <input type="email" name="customer_email" id="customer_email" required value="{{ old('customer_email', Auth::user()->email) }}"
                                    class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                            </div>
                            <div class="sm:col-span-2">
                                <label for="customer_phone" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nomor Telepon (WhatsApp)</label>
                                <input type="text" name="customer_phone" id="customer_phone" required value="{{ old('customer_phone') }}" placeholder="Contoh: 08123456789"
                                    class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                            </div>
                        </div>
                    </div>

                    <!-- 2. Attendee Names -->
                    <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-6">
                        <h3 class="text-md font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                            <i data-lucide="users" class="w-5 h-5 text-indigo-400"></i> Detail Peserta Acara
                        </h3>
                        <p class="text-xs text-slate-400 font-light -mt-2">Nama-nama ini akan tertera pada masing-masing E-Tiket Anda secara individu.</p>
                        
                        <div class="space-y-6">
                            @foreach($selectedTickets as $item)
                                @php $cat = $item['category']; $qty = $item['quantity']; @endphp
                                <input type="hidden" name="items[{{ $cat->id }}]" value="{{ $qty }}">
                                
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold bg-indigo-500/10 border border-indigo-500/20 text-indigo-300">
                                            Kategori: {{ $cat->name }} ({{ $qty }} Tiket)
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @for($i = 0; $i < $qty; $i++)
                                        <div>
                                            <label class="block text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama Peserta #{{ $i + 1 }}</label>
                                            <input type="text" name="attendees[{{ $cat->id }}][{{ $i }}]" required 
                                                value="{{ $i === 0 ? Auth::user()->name : '' }}" placeholder="Masukkan nama lengkap"
                                                class="w-full px-4 py-2.5 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-xs">
                                        </div>
                                        @endfor
                                    </div>
                                </div>
                                @if(!$loop->last)
                                    <div class="border-t border-slate-800/40 my-4"></div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- 3. Payment Methods -->
                    <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 space-y-4">
                        <h3 class="text-md font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                            <i data-lucide="credit-card" class="w-5 h-5 text-indigo-400"></i> Metode Pembayaran Uji Coba
                        </h3>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- BCA -->
                            <label class="relative flex flex-col p-4 rounded-xl border border-slate-800 bg-slate-950/30 cursor-pointer select-none hover:border-indigo-500/40 transition-all">
                                <input type="radio" name="payment_method" value="BCA Virtual Account" checked class="sr-only peer">
                                <div class="peer-checked:text-indigo-400 text-slate-400 flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold">BCA VA</span>
                                    <div class="w-4 h-4 rounded-full border border-slate-700 flex items-center justify-center peer-checked:border-indigo-500">
                                        <div class="w-2 h-2 rounded-full bg-transparent peer-checked:bg-indigo-500 hidden" id="dot-bca"></div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-white">BCA Virtual Account</span>
                                <span class="text-[10px] text-slate-500 mt-1">Simulasi transfer bank instan</span>
                            </label>

                            <!-- Mandiri -->
                            <label class="relative flex flex-col p-4 rounded-xl border border-slate-800 bg-slate-950/30 cursor-pointer select-none hover:border-indigo-500/40 transition-all">
                                <input type="radio" name="payment_method" value="Mandiri Virtual Account" class="sr-only peer">
                                <div class="peer-checked:text-indigo-400 text-slate-400 flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold">Mandiri VA</span>
                                    <div class="w-4 h-4 rounded-full border border-slate-700 flex items-center justify-center peer-checked:border-indigo-500">
                                        <div class="w-2 h-2 rounded-full bg-transparent peer-checked:bg-indigo-500 hidden" id="dot-mandiri"></div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-white">Mandiri Virtual Account</span>
                                <span class="text-[10px] text-slate-500 mt-1">Simulasi transfer Bank Mandiri</span>
                            </label>

                            <!-- QRIS / ShopeePay -->
                            <label class="relative flex flex-col p-4 rounded-xl border border-slate-800 bg-slate-950/30 cursor-pointer select-none hover:border-indigo-500/40 transition-all">
                                <input type="radio" name="payment_method" value="QRIS / ShopeePay" class="sr-only peer">
                                <div class="peer-checked:text-indigo-400 text-slate-400 flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold">QRIS e-Wallet</span>
                                    <div class="w-4 h-4 rounded-full border border-slate-700 flex items-center justify-center peer-checked:border-indigo-500">
                                        <div class="w-2 h-2 rounded-full bg-transparent peer-checked:bg-indigo-500 hidden" id="dot-qris"></div>
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-white">ShopeePay / GoPay</span>
                                <span class="text-[10px] text-slate-500 mt-1">Scan kode QRIS instan</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Order Summary -->
                <div class="lg:col-span-4">
                    <div class="bg-slate-900/50 backdrop-blur-md border border-slate-800/80 rounded-2xl p-6 shadow-2xl space-y-6">
                        <h3 class="text-md font-bold text-white border-b border-slate-800 pb-3 flex items-center gap-2">
                            <i data-lucide="receipt" class="w-5 h-5 text-indigo-400"></i> Ringkasan Pesanan
                        </h3>

                        <!-- Mini Event Card -->
                        <div class="flex gap-3 items-center">
                            <div class="w-16 h-16 rounded-lg overflow-hidden border border-slate-800 flex-shrink-0 bg-slate-950">
                                <img src="{{ $event->image_path }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
                            </div>
                            <div class="space-y-0.5">
                                <h4 class="text-sm font-bold text-white line-clamp-1">{{ $event->title }}</h4>
                                <p class="text-[11px] text-indigo-400 font-semibold">{{ $event->date->translatedFormat('d M Y') }}</p>
                                <p class="text-[10px] text-slate-400 line-clamp-1">{{ $event->location }}</p>
                            </div>
                        </div>

                        <!-- Ticket items listing -->
                        <div class="border-t border-slate-800/60 pt-4 space-y-2">
                            @foreach($selectedTickets as $item)
                                @php $cat = $item['category']; $qty = $item['quantity']; @endphp
                                <div class="flex justify-between items-center text-xs">
                                    <span class="text-slate-400 font-medium">{{ $cat->name }} <strong class="text-indigo-400 font-bold">x{{ $qty }}</strong></span>
                                    <span class="text-slate-200 font-bold">Rp {{ number_format($cat->price * $qty, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total Pricing -->
                        <div class="border-t border-slate-800 pt-4 space-y-3">
                            <div class="flex justify-between items-center text-xs font-semibold text-slate-500">
                                <span>Biaya Layanan (Pajak)</span>
                                <span>Gratis (Simulasi)</span>
                            </div>
                            <div class="flex justify-between items-center border-t border-slate-800/40 pt-2">
                                <span class="text-sm font-bold text-slate-300">Total Tagihan:</span>
                                <span class="text-lg font-black text-indigo-400">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="pt-2">
                            <button type="submit" 
                                class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg text-sm font-bold text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-indigo-600/20 hover:shadow-indigo-600/35">
                                Bayar Sekarang
                            </button>
                            <p class="text-[10px] text-slate-500 text-center mt-3 font-light leading-relaxed">Dengan mengklik "Bayar Sekarang", Anda menyetujui Syarat dan Ketentuan pembelian tiket di TiketAcara.</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal overlay -->
<div id="confirmModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden">
    <!-- Dark backdrop with blur -->
    <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="closeModal()"></div>
    
    <!-- Modal content card -->
    <div class="relative bg-slate-900 border border-slate-800/80 rounded-2xl w-full max-w-lg p-6 shadow-2xl space-y-6 mx-4">
        <!-- Header -->
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <h3 class="text-md font-bold text-white flex items-center gap-2">
                <i data-lucide="help-circle" class="w-5 h-5 text-indigo-400"></i> Konfirmasi Pemesanan Anda
            </h3>
            <button type="button" onclick="closeModal()" class="text-slate-400 hover:text-white transition-colors">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Body Summary -->
        <div class="space-y-4 text-xs">
            <p class="text-slate-300">Mohon periksa kembali detail pesanan Anda sebelum melanjutkan ke pembayaran:</p>

            <div class="p-4 bg-slate-950/60 border border-slate-800/80 rounded-xl space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px] flex-shrink-0">Acara</span>
                    <span class="text-white font-bold text-right max-w-[250px] line-clamp-2" id="modalEventTitle">{{ $event->title }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Metode</span>
                    <span class="text-white font-semibold text-right" id="modalPaymentMethod">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">Nama Kontak</span>
                    <span class="text-white font-semibold text-right" id="modalContactName">-</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500 font-semibold uppercase tracking-wider text-[10px]">No. Telepon</span>
                    <span class="text-white font-mono text-right" id="modalContactPhone">-</span>
                </div>
                <div class="border-t border-slate-800/60 pt-2"></div>
                <div class="flex justify-between items-center">
                    <span class="text-slate-300 font-bold uppercase tracking-wider text-[10px]">Total Tagihan</span>
                    <span class="text-sm font-extrabold text-indigo-400">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                </div>
            </div>
            
            <p class="text-[11px] text-amber-400/90 font-light leading-relaxed flex items-start gap-1.5 bg-amber-500/5 p-3 border border-amber-500/20 rounded-xl">
                <i data-lucide="alert-circle" class="w-4 h-4 mt-0.5 flex-shrink-0"></i>
                <span>Pastikan data nama peserta sudah benar. E-Tiket akan diterbitkan dengan nama-nama yang telah Anda isi.</span>
            </p>
        </div>

        <!-- Action buttons -->
        <div class="flex gap-3 justify-end border-t border-slate-800 pt-4">
            <button type="button" onclick="closeModal()" 
                class="px-4 py-2.5 rounded-xl border border-slate-800 text-slate-400 hover:text-white bg-slate-900/60 hover:bg-slate-800 transition-colors text-xs font-bold">
                Periksa Kembali
            </button>
            <button type="button" onclick="submitForm()" 
                class="px-5 py-2.5 rounded-xl text-white bg-indigo-600 hover:bg-indigo-500 transition-colors text-xs font-bold shadow-lg shadow-indigo-600/20 flex items-center gap-1">
                Ya, Bayar Sekarang <i data-lucide="chevron-right" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const checkoutForm = document.querySelector('form[action="{{ route('checkout.store') }}"]');
        const confirmModal = document.getElementById('confirmModal');
        
        // Intercept form submit
        checkoutForm.addEventListener('submit', function(e) {
            // Only trigger modal if not already confirmed
            if (checkoutForm.dataset.confirmed !== 'true') {
                e.preventDefault();
                
                // Gather input values to display in the modal
                const contactName = document.getElementById('customer_name').value;
                const contactPhone = document.getElementById('customer_phone').value;
                
                // Get selected payment method
                const paymentMethodInput = document.querySelector('input[name="payment_method"]:checked');
                const paymentMethod = paymentMethodInput ? paymentMethodInput.value : '-';
                
                // Set modal texts
                document.getElementById('modalContactName').innerText = contactName;
                document.getElementById('modalContactPhone').innerText = contactPhone;
                document.getElementById('modalPaymentMethod').innerText = paymentMethod;
                
                // Open modal
                confirmModal.classList.remove('hidden');
                
                // Refresh Lucide icons inside modal just in case
                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }
            }
        });
        
        window.closeModal = function() {
            confirmModal.classList.add('hidden');
        };
        
        window.submitForm = function() {
            checkoutForm.dataset.confirmed = 'true';
            checkoutForm.submit();
        };
    });
</script>
@endsection
