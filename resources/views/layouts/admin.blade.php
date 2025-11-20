<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Dashboard Admin')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#16a34a',   /* hijau terang */
            secondary: '#047857', /* hijau gelap */
            hovergreen: '#15803d' /* hijau hover */
          }
        }
      }
    }
  </script>
  @stack('styles')
</head>
<body class="bg-gray-100 font-sans antialiased">

<div class="flex flex-col md:flex-row min-h-screen">

  <!-- SIDEBAR -->
  <aside class="w-full md:w-64 bg-gradient-to-b from-primary to-secondary text-white flex flex-col">
    <nav class="flex-1 px-4 py-6 space-y-2">
      <nav class="flex-1 space-y-4">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 hover:bg-hovergreen p-2 rounded-md transition">
            <img src="https://img.icons8.com/fluency/48/home.png" alt="Home" class="w-6 h-6">
            <span class="text-base">Beranda</span>
        </a>
        <a href="{{ route('admin.publikasi.index') }}" class="flex items-center gap-3 hover:bg-hovergreen p-2 rounded-md transition">
            <img src="https://img.icons8.com/3d-fluency/94/group--v1.png" alt="Data Warga" class="w-6 h-6">
            <span class="text-base">KELOLA PUBLIKASI</span>
        </a>
        <a href="{{ route('admin.pengguna.index') }}" class="flex items-center gap-3 hover:bg-hovergreen p-2 rounded-md transition">
            <img src="https://img.icons8.com/3d-fluency/94/group--v1.png" alt="Data Warga" class="w-6 h-6">
            <span class="text-base">KELOLA PENGGUNA</span>
        </a>
        <a href="{{ route('admin.kartukeluarga.index') }}" class="flex items-center gap-3 hover:bg-hovergreen p-2 rounded-md transition">
            <img src="https://img.icons8.com/stickers/100/overview-pages-1.png" alt="Kartu Keluarga" class="w-6 h-6">
            <span class="text-base">INBOX</span>
        </a>
        <a href="{{ route('admin.laporan.index') }}" class="flex items-center gap-3 hover:bg-hovergreen p-2 rounded-md transition">
            <img src="https://img.icons8.com/stickers/100/overview-pages-1.png" alt="Kartu Keluarga" class="w-6 h-6">
            <span class="text-base">DATA LAPORAN</span>
        </a>
      </nav>
    </nav>

    <!-- Tombol Logout -->
    <form method="POST" action="{{ route('logout') }}" class="px-4 pb-4">
      @csrf
      <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 rounded shadow transition">
        🔓 Logout
      </button>
    </form>

    <footer class="text-center text-xs p-4 border-t border-secondary">
       © {{ date('Y') }} SATGAS P4GN UNILA — Sistem Informasi Konseling & Pelaporan.
    </footer>
  </aside>

  <!-- MAIN CONTENT -->
  <main class="flex-1 p-4 md:p-10 bg-white">
    @yield('content')
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil',
        text: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 2000
    })
</script>
@endif
@stack('scripts')
</body>
</html>
