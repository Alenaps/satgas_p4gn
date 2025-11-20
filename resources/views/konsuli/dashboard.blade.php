@extends('layouts.konsuli')

@section('title', 'SATGAS P4GN UNILA - KONSULI')

@push('styles')
<style>
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }
    .animate-fadeInUp-delay {
        animation: fadeInUp 0.8s ease-out 0.2s backwards;
    }

    @media (max-width: 640px) {
        h2 { font-size: 2.25rem !important; }
        .btn-custom { width: 100%; padding: 0.75rem 1rem; font-size: 0.9rem; }
    }
    @media (min-width: 641px) and (max-width: 1023px) {
        h2 { font-size: 3.5rem !important; }
        .btn-custom { padding: 1rem 2rem; }
    }
    @media (min-width: 1024px) {
        h2 { font-size: 5rem !important; }
    }
</style>
@endpush

@section('content')

<!-- HERO SECTION -->
<section class="bg-gradient-to-br from-gray-600 to-gray-800 min-h-screen flex items-center justify-center relative overflow-hidden">

    <div class="absolute inset-0 opacity-40">
        <img src="{{ asset('assets/bg_team.jpeg') }}" alt="background" class="w-full h-full object-cover">
    </div>

    <div class="relative z-10 text-center px-4 sm:px-8 md:px-16 max-w-4xl">
        <h2 class="font-bold text-white mb-10 drop-shadow-2xl animate-fadeInUp leading-tight">
            Selamat Datang!
        </h2>

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center animate-fadeInUp-delay">
            <a href="{{ route('konseling.create') }}" 
               class="btn-custom bg-blue-500 text-white px-10 py-4 rounded-full font-semibold shadow-xl hover:shadow-cyan-500/50 hover:-translate-y-1 transition-all duration-300 text-center">
                Mulai Konseling
            </a>
            <a href="{{ route('laporan.create') }}" 
               class="btn-custom bg-blue-500 text-white px-10 py-4 rounded-full font-semibold shadow-xl hover:shadow-blue-500/50 hover:-translate-y-1 transition-all duration-300 text-center">
                Lapor Sekarang!
            </a>
        </div>
    </div>
</section>


<!-- LAYANAN SECTION -->
<section class="py-20 bg-gradient-to-b from-gray-900 to-gray-800 relative overflow-hidden">

    <img src="{{ asset('assets/bg_soft.png') }}" 
         class="absolute inset-0 w-full h-full object-cover opacity-10 pointer-events-none">

    <div class="relative z-10 max-w-7xl mx-auto px-6">

        <h2 class="text-3xl md:text-5xl font-bold text-center text-white mb-10 animate-fadeInUp">
            Layanan SATGAS P4GN UNILA
        </h2>

        <div class="w-24 h-1 bg-blue-500 mx-auto mb-16 rounded-full animate-fadeInUp"></div>

        <!-- GRID LAYANAN -->
        <div class="grid md:grid-cols-4 gap-10">

            <!-- === PROGRAM SCREENING === -->
            <div class="p-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl border border-white/10 
                        hover:shadow-blue-500/20 hover:-translate-y-2 transition duration-300 animate-fadeInUp">

                <div class="flex justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400" 
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                        <path d="M9 12h6" />
                        <path d="M12 9v6" />
                        <circle cx="12" cy="12" r="9" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-white text-center mb-4">Program Screening</h3>

                <ul class="text-gray-300 text-sm space-y-2">
                    <li>• Tes urine berkala untuk mahasiswa, dosen, dan karyawan (Sampel diambil secara Stratified Random Sampling)</li>
                    <li>• Tes urine wajib bagi calon mahasiswa baru (S1–S3)</li>
                    <li>• (Usulan) Tes urine calon wisudawan (S1–S3)</li>
                    <li>• Survei literasi & potensi penggunaan narkoba</li>
                </ul>
            </div>

            <!-- === PROGRAM EDUKASI === -->
            <div class="p-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl border border-white/10 
                        hover:shadow-blue-500/20 hover:-translate-y-2 transition duration-300 animate-fadeInUp">

                <div class="flex justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                        <path d="M12 3l8 4-8 4-8-4 8-4z" />
                        <path d="M4 10l8 4 8-4" />
                        <path d="M4 14l8 4 8-4" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-white text-center mb-4">Program Edukasi</h3>

                <ul class="text-gray-300 text-sm space-y-2">
                    <li>• Roadshow pelatihan & sosialisasi ke fakultas</li>
                    <li>• Sosialisasi pada kegiatan PKKMB</li>
                    <li>• Edukasi untuk lembaga kemahasiswaan</li>
                    <li>• Pembentukan relawan anti narkoba</li>
                    <li>• Edukasi melalui media sosial/digital</li>
                </ul>
            </div>

            <!-- === PROGRAM PENDAMPINGAN === -->
            <div class="p-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl border border-white/10 
                        hover:shadow-blue-500/20 hover:-translate-y-2 transition duration-300 animate-fadeInUp">

                <div class="flex justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                        <path d="M12 11a4 4 0 110-8 4 4 0 010 8z" />
                        <path d="M6 21v-2a6 6 0 1112 0v2" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-white text-center mb-4">Pelayanan & Pendampingan</h3>

                <ul class="text-gray-300 text-sm space-y-2">
                    <li>• Konsultasi psikologis (e-konseling BK UNILA)</li>
                    <li>• Layanan rawat jalan di Klinik UNILA</li>
                    <li>• Pendampingan korban untuk pemulihan & hak hukum</li>
                    <li>• Kerja sama dengan institusi anti narkoba</li>
                </ul>
            </div>

            <!-- === USULAN KERJA SAMA BNN === -->
            <div class="p-8 bg-white/10 backdrop-blur-lg rounded-2xl shadow-xl border border-white/10 
                        hover:shadow-blue-500/20 hover:-translate-y-2 transition duration-300 animate-fadeInUp">

                <div class="flex justify-center mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-14 h-14 text-blue-400"
                         viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" fill="none">
                        <path d="M16 12h2a2 2 0 012 2v5H4v-5a2 2 0 012-2h2" />
                        <path d="M12 3v12m0-12l-3 3m3-3l3 3" />
                    </svg>
                </div>

                <h3 class="text-xl font-semibold text-white text-center mb-4">
                    Usulan Kerja Sama dengan BNN
                </h3>

                <ul class="text-gray-300 text-sm space-y-2">
                    <li>• Narasumber sosialisasi & pelatihan fakultas</li>
                    <li>• Tenaga ahli untuk e-konseling</li>
                    <li>• Dukungan kegiatan edukasi & layanan rawat jalan</li>
                    <li>• Penelitian potensi penyalahgunaan narkoba</li>
                    <li>• Pendampingan civitas akademika yang terditeksi narkoba di luar kampus</li>
                </ul>
            </div>

        </div>
    </div>
</section>

@endsection
