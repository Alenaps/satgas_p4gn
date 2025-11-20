<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans antialiased">

<div class="flex flex-col md:flex-row min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-full md:w-64 bg-gradient-to-b from-green-600 to-green-700 text-white flex flex-col">

        <!-- Logo Section -->
        <div class="flex flex-col items-center px-4 py-6">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-md mb-3">
                <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo" class="w-12 h-12">
            </div>
            <div class="text-center">
                <h1 class="text-lg font-bold">SATGAS P4GN UNILA</h1>
                <p class="text-xs opacity-90">SISTEM INFORMASI <br>KONSELING DAN PELAPORAN<br>UNIVERSITAS LAMPUNG</p>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2">
            <a href="#" class="flex items-center gap-2 p-2 hover:bg-green-500 rounded">
                🏠 Beranda
            </a>
            <a href="#" class="flex items-center gap-2 p-2 hover:bg-green-500 rounded">
                📄 Publikasi
            </a>
            <a href="#" class="flex items-center gap-2 p-2 hover:bg-green-500 rounded">
                👥 Pengguna
            </a>
            <!-- <a href="#" class="flex items-center gap-2 p-2 hover:bg-green-500 rounded">
                📬 Inbox
            </a> -->
            <a href="#" class="flex items-center gap-2 p-2 hover:bg-green-500 rounded">
                📊 Laporan
            </a>
        </nav>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}" class="px-4 py-4">
            @csrf
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded">🔓 Logout</button>
        </form>

        <footer class="text-center text-xs p-4 border-t border-green-800">
            © {{ date('Y') }} SATGAS P4GN UNILA
        </footer>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-6 md:p-10">
        @yield('content')
    </main>

</div>

</body>
</html>
