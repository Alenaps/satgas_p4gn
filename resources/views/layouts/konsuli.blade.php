<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SATGAS P4GN UNILA')</title>

    @vite('resources/css/app.css')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @stack('styles')
</head>

<body class="bg-white">

    <!-- NAVBAR -->
    <nav class="bg-gradient-to-r from-green-500 to-green-600 shadow-lg" x-data="{ open: false }">

        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- LOGO -->
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-white rounded-full flex items-center justify-center shadow-md">
                   <img src="{{ asset('assets/logo_unila.png') }}" class="w-10 h-10">
                </div>
                <div class="text-white leading-tight">
                    <h1 class="text-lg font-bold">SATGAS P4GN UNILA</h1>
                    <p class="text-xs opacity-90">
                        SISTEM INFORMASI KONSELING DAN PELAPORAN<br>
                        UNIVERSITAS LAMPUNG
                    </p>
                </div>
            </div>

            <!-- TOMBOL MOBILE -->
            <button @click="open = !open" class="lg:hidden text-white focus:outline-none">
                <svg x-show="!open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- MENU DESKTOP -->
            <ul class="hidden lg:flex items-center gap-6 text-sm font-medium text-white">

                <li>
                    <a href="{{ route('konsuli.dashboard') }}"
                       class="hover:bg-white/15 px-4 py-2 rounded {{ Request::routeIs('konsuli.dashboard') ? 'bg-white/20' : '' }}">
                        BERANDA
                    </a>
                </li>

                <li><a href="{{ route('konsuli.tentang') }}" class="hover:bg-white/15 px-4 py-2 rounded {{ Request::routeIs('tentang') ? 'bg-white/20' : '' }}">TENTANG</a></li>
                <li><a href="{{ route('konsuli.publikasi.index') }}" class="hover:bg-white/15 px-4 py-2 rounded {{ Request::routeIs('konsuli.publikasi.index') ? 'bg-white/20' : '' }}">PUBLIKASI</a></li>
                <li><a href="{{ route('konsuli.konselor.index') }}" class="hover:bg-white/15 px-4 py-2 rounded {{ Request::routeIs('konsuli.konselor.index') ? 'bg-white/20' : '' }}">KONSELOR</a></li>
                <li><a href="{{ route('konsuli.konseling.index') }}" class="hover:bg-white/15 px-4 py-2 rounded {{ Request::routeIs('konsuli.konseling.index') ? 'bg-white/20' : '' }}">KONSELING</a></li>
                <li><a href="{{ route('profile.index') }}" class="hover:bg-white/15 px-4 py-2 rounded {{ Request::routeIs('profile.index') ? 'bg-white/20' : '' }}">PROFILE</a></li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="hover:bg-white/15 px-4 py-2 rounded">
                            LOGOUT
                        </button>
                    </form>
                </li>

            </ul>
        </div>

        <!-- MOBILE MENU -->
        <div x-show="open" x-transition class="lg:hidden bg-green-600/90 backdrop-blur-md">
            <ul class="flex flex-col items-center py-4 space-y-2 text-white">

                <li><a href="{{ route('konsuli.dashboard') }}" class="px-4 py-2 hover:bg-white/15 rounded block">BERANDA</a></li>
                <li><a href="{{ route('tentang') }}" class="px-4 py-2 hover:bg-white/15 rounded block">TENTANG</a></li>
                <li><a href="{{ route('konsuli.publikasi.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">PUBLIKASI</a></li>
                <li><a href="{{ route('konsuli.konselor.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">KONSELOR</a></li>
                <li><a href="{{ route('konsuli.konseling.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">KONSELING</a></li>
                <li><a href="{{ route('profile.index') }}" class="px-4 py-2 hover:bg-white/15 rounded block">PROFILE</a></li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 hover:bg-white/15 rounded block">LOGOUT</button>
                    </form>
                </li>

            </ul>
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <main>
        @yield('content')
    </main>

    <!-- =========================== -->
    <!--           FOOTER            -->
    <!-- =========================== -->

    <footer class="bg-green-800 text-green-300 border-t border-gray-700">
        <div class="max-w-7xl mx-auto px-6 py-10 grid grid-cols-1 md:grid-cols-3 gap-8">

            <!-- Logo -->
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ asset('assets/logo_unila.png') }}" class="w-12 h-12 bg-white rounded-full p-1 shadow">
                    <div>
                        <h3 class="font-semibold text-white text-lg">SATGAS P4GN UNILA</h3>
                        <p class="text-sm opacity-80 leading-tight">
                            Pencegahan & Pemberantasan Penyalahgunaan Narkoba
                        </p>
                    </div>
                </div>
                <p class="text-sm opacity-75">
                    Sistem Informasi Konseling dan Pelaporan  
                    <br>Universitas Lampung.
                </p>
            </div>

            <!-- Navigasi -->
            <div>
                <h3 class="text-white font-semibold mb-3">Navigasi</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('konsuli.dashboard') }}" class="hover:text-white">Beranda</a></li>
                    <li><a href="{{ route('tentang') }}" class="hover:text-white">Tentang</a></li>
                    <li><a href="{{ route('konsuli.publikasi.index') }}" class="hover:text-white">Publikasi</a></li>
                    <li><a href="{{ route('konsuli.konselor.index') }}" class="hover:text-white">Konselor</a></li>
                    <li><a href="{{ route('konsuli.konseling.index') }}" class="hover:text-white">Konseling</a></li>
                    <li><a href="{{ route('konsuli.laporan.create') }}" class="hover:text-white">Lapor</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h3 class="text-white font-semibold mb-3">Kontak</h3>
                <p class="text-sm opacity-80">UPA Bimbingan Konseling Universitas Lampung</p>
                <p class="text-sm mt-2 opacity-70">Email: bk@unila.ac.id</p>
                <p class="text-sm opacity-70">Alamat: Gedung Rektorat Lt. 2, Universitas Lampung</p>

                <div class="flex gap-4 mt-4">
                    <a href="#" class="text-gray-300 hover:text-white">FB</a>
                    <a href="#" class="text-gray-300 hover:text-white">IG</a>
                </div>
            </div>

        </div>

        <div class="text-center text-green-400 text-xs py-4 border-t border-gray-700">
            © {{ date('Y') }} SATGAS P4GN UNILA — Sistem Informasi Konseling & Pelaporan.
        </div>
    </footer>

    @stack('scripts')

</body>
</html>