<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950 text-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'TiketAcara - Platform Pemesanan Tiket Premium')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        /* Custom Glassmorphism Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.6);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(99, 102, 241, 0.3);
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(99, 102, 241, 0.6);
        }
    </style>
    @yield('styles')
</head>
<body class="flex flex-col min-h-screen bg-slate-950 selection:bg-indigo-500 selection:text-white overflow-x-hidden relative">
    
    <!-- Ambient Background Glows -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>
    <div class="absolute top-1/3 right-10 w-[400px] h-[400px] bg-purple-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-10 left-10 w-80 h-80 bg-emerald-500/5 rounded-full blur-[90px] pointer-events-none"></div>

    <!-- Header Navbar -->
    <header class="sticky top-0 z-50 w-full border-b border-slate-800/60 bg-slate-950/80 backdrop-blur-md">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <div class="p-2 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-xl shadow-lg shadow-indigo-500/20 group-hover:scale-105 transition-all duration-300">
                    <i data-lucide="ticket" class="w-5 h-5 text-white"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tight bg-gradient-to-r from-white via-indigo-200 to-indigo-400 bg-clip-text text-transparent">
                    Tiket<span class="text-indigo-400 group-hover:text-indigo-300 transition-colors">Acara</span>
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium text-slate-300">
                <a href="{{ route('home') }}" class="hover:text-white transition-colors">Jelajahi Acara</a>
                @auth
                    <a href="{{ route('customer.tickets') }}" class="hover:text-white transition-colors">Tiket Saya</a>
                    <a href="{{ route('profile.show') }}" class="hover:text-white transition-colors">Profil Saya</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-amber-400 hover:text-amber-300 transition-colors flex items-center gap-1">
                            <i data-lucide="layout-dashboard" class="w-4 h-4"></i> Panel Admin
                        </a>
                    @endif
                @endauth
            </nav>

            <!-- Auth Buttons -->
            <div class="flex items-center gap-4">
                @auth
                    <div class="flex items-center gap-3">
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 group">
                            <div class="hidden sm:block text-right">
                                <p class="text-sm font-semibold text-slate-200 group-hover:text-indigo-400 transition-colors">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-indigo-400">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'Pelanggan' }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-300 font-bold select-none group-hover:border-indigo-400 transition-colors">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(strrchr(Auth::user()->name, ' ') ?: Auth::user()->name, 1, 1)) }}
                            </div>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="p-2 rounded-xl text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-transparent hover:border-rose-500/20 transition-all duration-300" title="Keluar">
                                <i data-lucide="log-out" class="w-5 h-5"></i>
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all duration-300 shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/35">
                        Daftar Sekarang
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-900 bg-slate-950/60 mt-12 py-8">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 bg-indigo-500/20 border border-indigo-500/30 rounded-lg">
                        <i data-lucide="ticket" class="w-4 h-4 text-indigo-400"></i>
                    </div>
                    <span class="text-md font-bold text-white">TiketAcara &copy; {{ date('Y') }}</span>
                </div>
                <p class="text-sm text-slate-500">Dibuat untuk Pengalaman Tiket Konser & Acara Kelas Premium.</p>
                <div class="flex gap-4 text-xs text-slate-400">
                    <a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Initialize Lucide Icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });
    </script>
    @yield('scripts')
</body>
</html>
