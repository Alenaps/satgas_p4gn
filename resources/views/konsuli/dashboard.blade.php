@extends('layouts.konsuli')

@section('title', 'Dashboard Konsuli')
@section('body_class', 'bg-gray-950 antialiased')

@section('content')

{{-- ============================================================ --}}
{{-- SECTION 1: HERO - Image Slider + Left-Aligned Content + CTAs --}}
{{-- ============================================================ --}}
<section id="hero" class="relative min-h-screen flex items-center overflow-hidden">

    {{-- Background Slider Images (3 stacked, CSS fade) --}}
    <div class="absolute inset-0">
        <div class="hero-slide absolute inset-0">
            <img src="{{ asset('assets/bg_team.webp') }}" alt="Tim Satgas P4GN UNILA" class="w-full h-full object-cover">
        </div>
        <div class="hero-slide absolute inset-0">
            <img src="{{ asset('assets/bg_team1.webp') }}" alt="Kegiatan Satgas P4GN" class="w-full h-full object-cover">
        </div>
        <div class="hero-slide absolute inset-0">
            <img src="{{ asset('assets/bg_team2.webp') }}" alt="Sosialisasi Anti Narkoba" class="w-full h-full object-cover">
        </div>
    </div>

    {{-- Dark Gradient Overlay --}}
    <div class="absolute inset-0 bg-gradient-to-r from-gray-950/90 via-gray-900/70 to-gray-900/40"></div>

    {{-- Hero Content (Left-Aligned) --}}
    <div class="relative z-10 max-w-7xl mx-auto px-6 md:px-12 w-full py-20">
        <div class="max-w-2xl">
            <p class="text-emerald-400 text-sm font-semibold tracking-wider uppercase mb-4 fade-in-up">
                Satgas P4GN Universitas Lampung
            </p>

            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-6 fade-in-up fade-in-up-d1">
                Selamat Datang, Konsuli!
            </h1>

            <p class="text-gray-300 text-base md:text-lg leading-relaxed max-w-xl mb-10 fade-in-up fade-in-up-d2">
                Layanan konseling dan pelaporan anti-narkoba untuk seluruh civitas akademika Universitas Lampung.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 fade-in-up fade-in-up-d3">
                <a href="{{ route('konsuli.konseling.index') }}"
                   id="cta-konseling"
                   class="inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-full font-semibold transition-all duration-300 hover:scale-[0.98] active:scale-95 shadow-lg shadow-emerald-600/20">
                    <i class="fas fa-comments text-sm" aria-hidden="true"></i>
                    Mulai Konseling
                </a>
                <a href="{{ route('konsuli.laporan.create') }}"
                   id="cta-laporan"
                   class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-full font-semibold transition-all duration-300 hover:scale-[0.98] active:scale-95 shadow-lg shadow-blue-600/20">
                    <i class="fas fa-bullhorn text-sm" aria-hidden="true"></i>
                    Lapor Sekarang!
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 2: DASAR HUKUM - Quote Card (Center-Aligned)         --}}
{{-- ============================================================ --}}
<section id="dasar-hukum" class="bg-gray-900 py-24">
    <div class="max-w-3xl mx-auto px-6">
        <div class="relative bg-white/5 border border-white/10 rounded-2xl p-10 md:p-14 text-center">

            <h2 class="sr-only">Dasar Hukum</h2>

            {{-- Decorative Icon --}}
            <div class="flex justify-center mb-6">
                <div class="w-16 h-16 rounded-full bg-emerald-500/10 flex items-center justify-center">
                    <i class="fas fa-balance-scale text-emerald-400 text-2xl" aria-hidden="true"></i>
                </div>
            </div>

            <blockquote class="text-gray-200 text-lg md:text-xl leading-relaxed italic">
                "Masyarakat mempunyai kesempatan yang seluas-luasnya untuk berperan serta membantu pencegahan dan pemberantasan penyalahgunaan dan peredaran gelap Narkotika dan Prekursor Narkotika."
            </blockquote>

            <div class="mt-8 pt-6 border-t border-white/10">
                <p class="text-emerald-400 font-semibold">UU RI No. 35 Tahun 2009</p>
                <p class="text-gray-400 text-sm mt-1">Pasal 104 - Tentang Narkotika</p>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 3: KONSELING & PELAPORAN (Zig-Zag Layout)            --}}
{{-- ============================================================ --}}
<section id="layanan-utama" class="bg-gray-950 py-24">
    <div class="max-w-7xl mx-auto px-6 space-y-20">

        {{-- Row 1: KONSELING (Text Left, Image Right) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Text --}}
            <div class="order-2 md:order-1 text-left">
                <span class="inline-block bg-emerald-500/10 text-emerald-400 text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
                    Layanan Konseling
                </span>

                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">
                    Ruang Aman untuk Bercerita
                </h2>

                <p class="text-gray-300 leading-relaxed mb-8">
                    Kami menyediakan layanan konseling profesional bagi mahasiswa dan civitas akademika yang membutuhkan tempat berbagi cerita, mendapatkan informasi, atau pendampingan terkait permasalahan narkoba.
                </p>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-shield-alt text-emerald-400 text-[10px]" aria-hidden="true"></i>
                        </div>
                        <span class="text-gray-300 text-sm leading-relaxed">Kerahasiaan 100% terjamin oleh sistem dan kode etik profesional</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-user-md text-emerald-400 text-[10px]" aria-hidden="true"></i>
                        </div>
                        <span class="text-gray-300 text-sm leading-relaxed">Pendampingan langsung oleh Psikolog dan Konselor bersertifikat</span>
                    </li>
                    <li class="flex items-start gap-3">
                        <div class="w-6 h-6 rounded-full bg-emerald-500/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-laptop text-emerald-400 text-[10px]" aria-hidden="true"></i>
                        </div>
                        <span class="text-gray-300 text-sm leading-relaxed">Konseling online yang mudah diakses kapan saja</span>
                    </li>
                </ul>

                <a href="{{ route('konsuli.konseling.index') }}"
                   class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-full font-semibold text-sm transition-all duration-300 hover:scale-[0.98] active:scale-95">
                    <i class="fas fa-comments text-xs" aria-hidden="true"></i>
                    Mulai Konseling
                </a>
            </div>

            {{-- Image --}}
            <div class="order-1 md:order-2">
                <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-emerald-500/5">
                    <img src="{{ asset('assets/konseling.webp') }}"
                         alt="Layanan Konseling Satgas P4GN"
                         class="w-full h-72 md:h-[420px] object-cover"
                         loading="lazy">
                </div>
            </div>
        </div>

        {{-- Subtle Divider --}}
        <div class="border-t border-white/5"></div>

        {{-- Row 2: PELAPORAN (Image Left, Text Right) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 lg:gap-16 items-center">
            {{-- Image --}}
            <div class="order-1">
                <div class="rounded-2xl overflow-hidden border border-white/10 shadow-2xl shadow-blue-500/5">
                    <img src="{{ asset('assets/pelaporan.webp') }}"
                         alt="Layanan Pelaporan Satgas P4GN"
                         class="w-full h-72 md:h-[420px] object-cover"
                         loading="lazy">
                </div>
            </div>

            {{-- Text --}}
            <div class="order-2 md:text-right">
                <span class="inline-block bg-blue-500/10 text-blue-400 text-xs font-semibold px-3 py-1.5 rounded-full mb-5">
                    Layanan Pelaporan
                </span>

                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 leading-tight">
                    Sampaikan Informasi Tanpa Ragu
                </h2>

                <p class="text-gray-300 leading-relaxed mb-8">
                    Laporkan informasi terkait penyalahgunaan atau peredaran gelap narkoba di lingkungan kampus dengan aman. Setiap laporan akan diterima dan ditangani secara serius oleh Satgas P4GN UNILA.
                </p>

                <ul class="space-y-4 mb-8">
                    <li class="flex items-start gap-3 md:flex-row-reverse md:text-right">
                        <div class="w-6 h-6 rounded-full bg-blue-500/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-user-secret text-blue-400 text-[10px]" aria-hidden="true"></i>
                        </div>
                        <span class="text-gray-300 text-sm leading-relaxed">Sampaikan informasi berdasarkan apa yang diketahui tanpa perlu khawatir</span>
                    </li>
                    <li class="flex items-start gap-3 md:flex-row-reverse md:text-right">
                        <div class="w-6 h-6 rounded-full bg-blue-500/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-bolt text-blue-400 text-[10px]" aria-hidden="true"></i>
                        </div>
                        <span class="text-gray-300 text-sm leading-relaxed">Data dan informasi laporan dijaga kerahasiaannya sesuai kebijakan privasi</span>
                    </li>
                    <li class="flex items-start gap-3 md:flex-row-reverse md:text-right">
                        <div class="w-6 h-6 rounded-full bg-blue-500/10 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="fas fa-lock text-blue-400 text-[10px]" aria-hidden="true"></i>
                        </div>
                        <span class="text-gray-300 text-sm leading-relaxed">Laporan dapat dipantau dan ditindaklanjuti oleh tim Satgas P4GN UNILA</span>
                    </li>
                </ul>

                <a href="{{ route('konsuli.laporan.create') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-semibold text-sm transition-all duration-300 hover:scale-[0.98] active:scale-95">
                    <i class="fas fa-bullhorn text-xs" aria-hidden="true"></i>
                    Lapor Sekarang!
                </a>
            </div>
        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 4: PROGRAM LAYANAN (Vertical Large Cards)            --}}
{{-- ============================================================ --}}
<section id="program" class="bg-gray-900 py-24">
    <div class="max-w-5xl mx-auto px-6">

        {{-- Section Header (Centered) --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Program Satgas P4GN</h2>
            <p class="text-gray-400 max-w-2xl mx-auto leading-relaxed">
                Tiga pilar operasional yang menjadi fondasi kerja Satgas dalam upaya pencegahan dan pemberantasan narkoba di Universitas Lampung.
            </p>
        </div>

        {{-- Vertical Card Stack --}}
        <div class="space-y-8">

            @php
            $programs = [
                [
                    'title' => 'Program Screening',
                    'icon'  => 'fa-vial',
                    'color' => 'blue',
                    'desc'  => 'Deteksi dini melalui pemeriksaan urine secara berkala dan terstruktur untuk menjaga lingkungan kampus tetap bersih dari narkoba.',
                    'items' => [
                        'Tes urine berkala untuk mahasiswa, dosen, dan karyawan melalui stratified random sampling',
                        'Tes urine wajib bagi calon mahasiswa baru (S1 - S3)',
                        'Survei literasi dan potensi penggunaan narkoba di lingkungan kampus',
                    ],
                ],
                [
                    'title' => 'Program Edukasi',
                    'icon'  => 'fa-graduation-cap',
                    'color' => 'emerald',
                    'desc'  => 'Sosialisasi dan pelatihan komprehensif untuk meningkatkan kesadaran civitas akademika tentang bahaya narkoba.',
                    'items' => [
                        'Roadshow pelatihan dan sosialisasi ke seluruh fakultas',
                        'Sosialisasi pada kegiatan PKKMB (Pengenalan Kehidupan Kampus)',
                        'Pembentukan relawan anti narkoba dan edukasi lembaga kemahasiswaan',
                        'Edukasi melalui media sosial dan platform digital',
                    ],
                ],
                [
                    'title' => 'Kerja Sama dengan BNN',
                    'icon'  => 'fa-handshake',
                    'color' => 'blue',
                    'desc'  => 'Kolaborasi strategis dengan Badan Narkotika Nasional untuk memperkuat upaya pencegahan dan penanganan di lingkungan kampus.',
                    'items' => [
                        'Penyediaan narasumber untuk sosialisasi dan pelatihan fakultas',
                        'Tenaga ahli untuk layanan e-konseling',
                        'Penelitian potensi penyalahgunaan narkoba di lingkungan kampus',
                        'Pendampingan civitas akademika yang terdeteksi terlibat narkoba',
                    ],
                ],
            ];
            @endphp

            @foreach($programs as $program)
                <div class="bg-white/5 border border-white/10 rounded-2xl p-8 md:p-10 hover:border-{{ $program['color'] }}-500/20 transition-colors duration-300">
                    <div class="grid grid-cols-1 md:grid-cols-[80px_1fr] gap-6 items-start">
                        {{-- Icon --}}
                        <div class="flex items-start">
                            <div class="w-16 h-16 rounded-2xl bg-{{ $program['color'] }}-500/10 flex items-center justify-center">
                                <i class="fas {{ $program['icon'] }} text-{{ $program['color'] }}-400 text-2xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        {{-- Content --}}
                        <div>
                            <h3 class="text-xl font-bold text-white mb-3">{{ $program['title'] }}</h3>
                            <p class="text-gray-400 text-sm mb-5 leading-relaxed max-w-2xl">
                                {{ $program['desc'] }}
                            </p>
                            <ul class="space-y-3">
                                @foreach($program['items'] as $item)
                                    <li class="flex items-start gap-3">
                                        <i class="fas fa-check text-{{ $program['color'] }}-400 mt-1 text-xs" aria-hidden="true"></i>
                                        <span class="text-gray-300 text-sm leading-relaxed">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>


{{-- ============================================================ --}}
{{-- SECTION 5: PUBLIKASI & ARTIKEL TERKINI                       --}}
{{-- ============================================================ --}}
<section id="publikasi" class="bg-gray-950 py-24">
    <div class="max-w-7xl mx-auto px-6">

        {{-- Section Header (Centered) --}}
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Publikasi Terkini</h2>
            <p class="text-gray-400 max-w-xl mx-auto leading-relaxed">
                Berita, artikel edukasi, dan informasi terbaru seputar pencegahan narkoba dari Satgas P4GN UNILA.
            </p>
        </div>

        @if($publikasis->isNotEmpty())
        {{-- Publication Grid (4 columns on desktop) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($publikasis as $publikasi)
            <a href="{{ route('konsuli.publikasi.show', $publikasi->slug) }}"
               class="group bg-white/5 border border-white/10 rounded-2xl overflow-hidden hover:border-emerald-500/20 transition-all duration-300 hover:-translate-y-1"
               aria-label="Baca artikel: {{ $publikasi->judul }}">

                {{-- Thumbnail --}}
                <div class="aspect-video overflow-hidden bg-gray-800">
                    @if($publikasi->thumbnail)
                        <img src="{{ asset('storage/' . $publikasi->thumbnail) }}"
                             alt="{{ $publikasi->judul }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="lazy">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-emerald-900/40 to-blue-900/40 flex items-center justify-center">
                            <i class="fas fa-newspaper text-white/20 text-3xl" aria-hidden="true"></i>
                        </div>
                    @endif
                </div>

                {{-- Card Content --}}
                <div class="p-5">
                    <p class="text-gray-500 text-xs mb-2">
                        <i class="far fa-calendar-alt mr-1" aria-hidden="true"></i>
                        {{ $publikasi->created_at->format('d M Y') }}
                    </p>

                    <h3 class="text-white font-semibold text-sm leading-snug mb-2 group-hover:text-emerald-400 transition-colors duration-200 line-clamp-2">
                        {{ $publikasi->judul }}
                    </h3>

                    <p class="text-gray-400 text-xs leading-relaxed line-clamp-3 mb-3">
                        {{ Str::limit($publikasi->ringkasan ?? strip_tags($publikasi->isi), 100) }}
                    </p>

                    <span class="inline-flex items-center gap-1.5 text-emerald-400 text-xs font-medium group-hover:gap-2.5 transition-all duration-300">
                        Baca <i class="fas fa-arrow-right text-[10px]" aria-hidden="true"></i>
                    </span>
                </div>
            </a>
            @endforeach
        </div>
        @else
        {{-- Empty State --}}
        <div class="text-center py-16">
            <div class="w-20 h-20 rounded-2xl bg-white/5 flex items-center justify-center mx-auto mb-6">
                <i class="fas fa-newspaper text-gray-600 text-3xl" aria-hidden="true"></i>
            </div>
            <p class="text-gray-400 text-lg font-medium">Belum ada data publikasi saat ini</p>
            <p class="text-gray-500 text-sm mt-2">Artikel dan berita terbaru akan ditampilkan di sini.</p>
        </div>
        @endif

    </div>
</section>

@endsection