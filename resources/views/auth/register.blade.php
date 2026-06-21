@extends('layouts.app')

@section('title', 'Daftar Akun TiketAcara - Akses Konser & Acara Premium')

@section('content')
<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 relative">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold text-white tracking-tight">Buat Akun Baru</h2>
            <p class="mt-2 text-sm text-slate-400">
                Bergabung untuk mendapatkan tiket acara terpopuler dengan mudah
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-slate-900/40 backdrop-blur-md border border-slate-800/80 py-8 px-4 shadow-2xl rounded-2xl sm:px-10">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 text-sm rounded-xl flex items-start gap-2">
                    <i data-lucide="alert-circle" class="w-5 h-5 flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="font-semibold">Terjadi Kesalahan:</p>
                        <ul class="list-disc list-inside mt-1 text-rose-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300">Nama Lengkap</label>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="user" class="h-5 w-5 text-slate-500"></i>
                        </div>
                        <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm transition-all focus:bg-slate-950" 
                            placeholder="Yusuf Maulana">
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300">Alamat Email</label>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="h-5 w-5 text-slate-500"></i>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm transition-all focus:bg-slate-950" 
                            placeholder="nama@email.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300">Kata Sandi</label>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="h-5 w-5 text-slate-500"></i>
                        </div>
                        <input id="password" name="password" type="password" required 
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm transition-all focus:bg-slate-950" 
                            placeholder="Minimal 8 karakter">
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300">Konfirmasi Kata Sandi</label>
                    <div class="mt-1 relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="lock-keyhole" class="h-5 w-5 text-slate-500"></i>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required 
                            class="block w-full pl-10 pr-4 py-3 bg-slate-950/80 border border-slate-800 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 text-white placeholder-slate-500 text-sm transition-all focus:bg-slate-950" 
                            placeholder="Ulangi kata sandi">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 shadow-indigo-600/20 hover:shadow-indigo-600/35">
                        Buat Akun
                    </button>
                </div>
            </form>

            <div class="mt-6 text-center text-sm border-t border-slate-800 pt-6">
                <span class="text-slate-400">Sudah memiliki akun?</span>
                <a href="{{ route('login') }}" class="font-semibold text-indigo-400 hover:text-indigo-300 transition-colors ml-1">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
