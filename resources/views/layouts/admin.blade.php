<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans antialiased">

<div class="flex flex-col md:flex-row min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-full md:w-64 bg-gradient-to-b from-green-600 to-green-700 text-white flex flex-col shadow-xl">

        <!-- Logo Section -->
        <div class="flex flex-col items-center px-4 py-6 border-b border-green-500">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-lg mb-3">
                <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo" class="w-12 h-12">
            </div>
            <div class="text-center">
                <h1 class="text-lg font-bold">SATGAS P4GN UNILA</h1>
                <p class="text-xs opacity-90 mt-1">SISTEM INFORMASI<br>KONSELING DAN PELAPORAN<br>UNIVERSITAS LAMPUNG</p>
            </div>
        </div>

        <!-- User Profile Section -->
        <div class="px-4 py-4 border-b border-green-500">
            <a href="{{ route('profile.index') }}" class="flex items-center gap-3 p-3 hover:bg-green-500 rounded-lg transition-colors group">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-md overflow-hidden flex-shrink-0">
                    @if(auth()->user()->foto)
                        <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Profile" class="w-full h-full object-cover">
                    @else
                        <i class="fas fa-user text-green-600 text-xl"></i>
                    @endif
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-sm truncate group-hover:text-green-50">{{ auth()->user()->nama }}</p>
                    <p class="text-xs opacity-75 truncate">{{ auth()->user()->npm_nip }}</p>
                    <p class="text-xs opacity-90 bg-green-800 px-2 py-0.5 rounded-full inline-block mt-1">
                        {{ ucfirst(auth()->user()->role) }}
                    </p>
                </div>
            </a>
        </div>

        <!-- Menu Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">

            <!-- Beranda -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-green-500' : '' }}">
                <i class="fas fa-home w-5"></i>
                <span>Beranda</span>
            </a>

            <!-- Publikasi -->
            <a href="{{ route('admin.publikasi.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('admin.publikasi.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-newspaper w-5"></i>
                <span>Publikasi</span>
            </a>

            <!-- Data Laporan -->
            <a href="{{ route('admin.laporan.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('admin.laporan.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-file-alt w-5"></i>
                <span>Data Laporan</span>
            </a>

            <!-- Kelola Pengguna -->
            <a href="{{ route('admin.kelola_pengguna.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('admin.kelola_pengguna.*') ? 'bg-green-500' : '' }}">
                <i class="fas fa-users w-5"></i>
                <span>Kelola Pengguna</span>
            </a>

            <!-- Master Data (Accordion) -->
            @php
                $isMasterActive = request()->routeIs('admin.unit.*') ||
                                  request()->routeIs('admin.instansi.*') ||
                                  request()->routeIs('admin.jabatan.*');
            @endphp

            <div x-data="{ open: {{ $isMasterActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center justify-between gap-3 px-3 py-2.5 hover:bg-green-500 rounded-lg transition-colors {{ $isMasterActive ? 'bg-green-500' : '' }}">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-database w-5"></i>
                        <span>Master Data</span>
                    </div>
                    <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition class="mt-1 ml-4 space-y-1 border-l-2 border-green-400 pl-3">
                    <a href="{{ route('admin.unit.index') }}"
                       class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('admin.unit.*') ? 'bg-green-500' : '' }}">
                        <i class="fas fa-sitemap w-4 text-xs"></i>
                        <span>Unit</span>
                    </a>
                    <a href="{{ route('admin.instansi.index') }}"
                       class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('admin.instansi.*') ? 'bg-green-500' : '' }}">
                        <i class="fas fa-building w-4 text-xs"></i>
                        <span>Instansi</span>
                    </a>
                    <a href="{{ route('admin.jabatan.index') }}"
                       class="flex items-center gap-2 px-3 py-2 text-sm hover:bg-green-500 rounded-lg transition-colors {{ request()->routeIs('admin.jabatan.*') ? 'bg-green-500' : '' }}">
                        <i class="fas fa-id-badge w-4 text-xs"></i>
                        <span>Jabatan</span>
                    </a>
                </div>
            </div>

        </nav>

        <!-- Logout Button -->
        <div class="px-4 py-4 border-t border-green-500">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2.5 rounded-lg transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

        <footer class="text-center text-xs px-4 py-3 border-t border-green-500 opacity-75">
            © {{ date('Y') }} SATGAS P4GN UNILA
        </footer>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 md:p-10 overflow-y-auto">
        @yield('content')
    </main>

</div>

{{-- Alpine.js for accordion --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

@yield('scripts')
@stack('scripts')

</body>
</html>