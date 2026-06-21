@extends('layouts.app')

@section('title', 'Profil Saya - TiketAcara')

@section('content')
<div class="py-8 bg-grid-pattern">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="max-w-4xl mx-auto mb-8">
            <h1 class="text-3xl font-extrabold text-white tracking-tight">Pengaturan Profil</h1>
            <p class="text-sm text-slate-400">Kelola detail akun Anda, perbarui alamat email, dan ubah kata sandi.</p>
        </div>

        <div class="max-w-4xl mx-auto">
            
            <!-- Success/Error Alerts -->
            @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-sm rounded-xl flex items-center gap-2">
                <i data-lucide="check-circle" class="w-5 h-5 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm rounded-xl flex items-start gap-2">
                <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                <div>
                    <p class="font-semibold text-rose-400">Terjadi Kesalahan:</p>
                    <ul class="list-disc list-inside mt-1 text-rose-300 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <!-- Left Sidebar: Profile Summary -->
                <div class="md:col-span-4 space-y-6">
                    <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6 text-center space-y-4">
                        <div class="w-20 h-20 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-300 text-2xl font-bold select-none mx-auto">
                            {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($user->name, ' ') ?: $user->name, 1, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-white leading-tight">{{ $user->name }}</h3>
                            <p class="text-xs text-indigo-400 mt-1 uppercase tracking-widest font-semibold">{{ $user->role === 'admin' ? 'Administrator' : 'Pelanggan' }}</p>
                        </div>
                        <div class="border-t border-slate-800/60 pt-4 text-left space-y-2 text-xs text-slate-400">
                            <div class="flex justify-between">
                                <span>Terdaftar sejak:</span>
                                <span class="text-slate-200 font-semibold">{{ $user->created_at->translatedFormat('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Total Tiket:</span>
                                <span class="text-indigo-400 font-bold font-mono">
                                    {{ \App\Models\Ticket::whereHas('order', function($q) use ($user) { $q->where('user_id', $user->id)->where('status', 'paid'); })->count() }} Pcs
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Content: Profile Form & Password Form -->
                <div class="md:col-span-8 space-y-8">
                    <!-- Form Profile -->
                    <div class="bg-slate-900/40 backdrop-blur border border-slate-800/80 rounded-2xl p-6">
                        <h3 class="text-sm font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3 mb-6">
                            <i data-lucide="user" class="w-4 h-4 text-indigo-400"></i> Informasi Pribadi
                        </h3>
                        
                        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}"
                                        class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                                </div>

                                <div>
                                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Alamat Email</label>
                                    <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}"
                                        class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                                </div>
                            </div>

                            <div class="border-t border-slate-800/60 pt-6 mt-6">
                                <h3 class="text-sm font-bold text-white flex items-center gap-2 mb-4">
                                    <i data-lucide="key" class="w-4 h-4 text-indigo-400"></i> Keamanan & Kata Sandi (Opsional)
                                </h3>
                                <p class="text-xs text-slate-400 mb-4 font-light leading-relaxed">Biarkan kolom di bawah ini kosong jika Anda tidak ingin mengubah kata sandi Anda.</p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="old_password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kata Sandi Lama</label>
                                        <input type="password" name="old_password" id="old_password" placeholder="••••••••"
                                            class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Kata Sandi Baru</label>
                                            <input type="password" name="password" id="password" placeholder="Minimal 8 karakter"
                                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                                        </div>

                                        <div>
                                            <label for="password_confirmation" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Konfirmasi Kata Sandi Baru</label>
                                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Ulangi kata sandi baru"
                                                class="w-full px-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white text-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-6 flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/35 transition-all">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
