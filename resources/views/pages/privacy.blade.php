@extends('layouts.informasi')

@section('title','Kebijakan Privasi')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<style>
    .kp-scope { font-family: 'Inter', sans-serif; }
    .kp-scope .font-serif-doc { font-family: 'Source Serif 4', serif; }

    .kp-toc a { display: block; padding: .5rem .75rem; border-radius: .5rem; font-size: .84rem; color: #475569; }
    .kp-toc a:hover { background: #f1f5f9; color: #1e3a8a; }
    .kp-toc a.is-active { background: #eff6ff; color: #1e40af; font-weight: 600; }

    .kp-section { scroll-margin-top: 6.5rem; }

    .kp-badge {
        width: 2.25rem; height: 2.25rem; flex-shrink: 0;
        border-radius: .65rem;
        background: #dbeafe; color: #1e40af;
        display: flex; align-items: center; justify-content: center;
        font-family: 'Source Serif 4', serif; font-weight: 600; font-size: .95rem;
    }

    /* layout grid: sidebar TOC + content, without relying on Tailwind arbitrary values */
    .kp-layout { display: block; }
    @media (min-width: 1024px) {
        .kp-layout { display: grid; grid-template-columns: 220px 1fr; gap: 2rem; }
    }
    .kp-aside { display: none; }
    @media (min-width: 1024px) { .kp-aside { display: block; } }

    /* body text indent aligned under the number badge (2.25rem badge + 1rem gap) */
    .kp-body { padding-left: 3.25rem; }
    @media (max-width: 639px) { .kp-body { padding-left: 0; margin-top: .5rem; } }

    /* explicit bullet list styling, independent of Tailwind's list-disc utility */
    .kp-ul { list-style: none; margin: 0; padding: 0; }
    .kp-ul li {
        position: relative;
        padding-left: 1.15rem;
        margin-bottom: .5rem;
    }
    .kp-ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: .55em;
        width: 6px; height: 6px;
        border-radius: 999px;
        background: #1e40af;
    }

    /* back button — explicit CSS so it never depends on an untested Tailwind color combo */
    .kp-btn-back {
        display: inline-flex; align-items: center; gap: .5rem;
        background: #1e40af; color: #ffffff;
        padding: .75rem 2rem; border-radius: .75rem;
        font-size: .875rem; font-weight: 500;
        box-shadow: 0 1px 2px rgba(0,0,0,.06);
        transition: background .2s ease;
    }
    .kp-btn-back:hover { background: #1e3a8a; }
</style>
@endpush

@section('content')

<div class="kp-scope">
<section class="bg-slate-50 py-12">

    <div class="max-w-6xl mx-auto px-4 lg:px-8">

        {{-- Breadcrumb --}}
        <div class="mb-6 flex items-center justify-between">
            <div class="text-sm text-slate-500 flex items-center gap-2">
                <a href="{{ url()->previous() }}" class="hover:text-blue-700 inline-flex items-center gap-1">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700 font-medium">Kebijakan Privasi</span>
            </div>
        </div>

        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="bg-blue-800 h-1.5"></div>

            <div class="p-8 lg:p-10">
                <div class="flex items-start gap-6">

                    <div class="w-16 h-16 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                d="M12 2L5 5v6c0 5.25 3.44 10.02 7 11 3.56-.98 7-5.75 7-11V5l-7-3z"/>
                        </svg>
                    </div>

                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-blue-700 mb-2">Kebijakan Resmi &middot; SATGAS P4GN UNILA</p>
                        <h1 class="font-serif-doc text-3xl lg:text-4xl font-semibold text-slate-900">
                            Kebijakan Privasi
                        </h1>
                        <p class="text-slate-500 mt-2">
                            Sistem Informasi Konseling dan Pelaporan Penyalahgunaan Narkoba
                        </p>
                        <p class="text-xs text-slate-400 mt-3">
                            Terakhir diperbarui {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        @php
            $sections = [
                [
                    'id' => 'data-dikumpulkan',
                    'title' => 'Data yang Dikumpulkan',
                    'body' => '
                    <ul class="kp-ul">
                        <li>Data identitas pengguna.</li>
                        <li>Data laporan beserta kronologi kejadian.</li>
                        <li>Data terlapor apabila diketahui.</li>
                        <li>Dokumen atau bukti pendukung.</li>
                        <li>Riwayat konsultasi.</li>
                        <li>Aktivitas penggunaan sistem.</li>
                    </ul>',
                ],
                [
                    'id' => 'tujuan-penggunaan',
                    'title' => 'Tujuan Penggunaan Data',
                    'body' => '
                    <ul class="kp-ul">
                        <li>Melakukan verifikasi laporan.</li>
                        <li>Memberikan layanan konseling.</li>
                        <li>Melaksanakan tindak lanjut oleh petugas.</li>
                        <li>Mempermudah komunikasi dengan pengguna.</li>
                        <li>Meningkatkan kualitas pelayanan sistem.</li>
                    </ul>',
                ],
                [
                    'id' => 'akses-data',
                    'title' => 'Siapa yang Dapat Mengakses Data',
                    'body' => '
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="border border-slate-200 rounded-xl p-4 bg-white">
                            <p class="font-semibold text-slate-800 mb-1">Administrator</p>
                            <p class="text-sm text-slate-600">Mengelola sistem dan data pengguna.</p>
                        </div>
                        <div class="border border-slate-200 rounded-xl p-4 bg-white">
                            <p class="font-semibold text-slate-800 mb-1">Petugas</p>
                            <p class="text-sm text-slate-600">Mengakses data laporan untuk proses verifikasi dan tindak lanjut.</p>
                        </div>
                        <div class="border border-slate-200 rounded-xl p-4 bg-white">
                            <p class="font-semibold text-slate-800 mb-1">Konselor</p>
                            <p class="text-sm text-slate-600">Hanya mengakses data konseling yang menjadi tanggung jawabnya.</p>
                        </div>
                        <div class="border border-slate-200 rounded-xl p-4 bg-white">
                            <p class="font-semibold text-slate-800 mb-1">Pelapor</p>
                            <p class="text-sm text-slate-600">Hanya dapat melihat data miliknya sendiri.</p>
                        </div>
                    </div>',
                ],
                [
                    'id' => 'kerahasiaan',
                    'title' => 'Kerahasiaan Data',
                    'body' => '
                    <p>
                    Identitas pelapor serta seluruh informasi yang diberikan
                    dijaga kerahasiaannya. Informasi tidak akan dipublikasikan
                    kepada pihak yang tidak memiliki kewenangan dan hanya
                    digunakan untuk kepentingan pelayanan, verifikasi, serta
                    penanganan laporan.
                    </p>',
                ],
                [
                    'id' => 'penyimpanan-keamanan',
                    'title' => 'Penyimpanan dan Keamanan Data',
                    'body' => '
                    <p>
                    Data disimpan pada server sistem dan dilindungi melalui
                    autentikasi pengguna, pembatasan hak akses, serta mekanisme
                    keamanan untuk mencegah akses tanpa izin.
                    </p>',
                ],
                [
                    'id' => 'hak-pengguna',
                    'title' => 'Hak Pengguna',
                    'body' => '
                    <ul class="kp-ul">
                        <li>Melihat data miliknya.</li>
                        <li>Memperbarui profil.</li>
                        <li>Menggunakan layanan konseling.</li>
                        <li>Menyampaikan laporan.</li>
                        <li>Menghubungi pengelola apabila terdapat kesalahan data.</li>
                    </ul>',
                ],
            ];
        @endphp

        <div class="mt-8 kp-layout">

            {{-- Table of contents --}}
            <aside class="kp-aside">
                <nav class="kp-toc sticky top-24 border border-slate-200 rounded-xl bg-white p-3">
                    <p class="px-3 pt-1 pb-2 text-xs font-semibold uppercase tracking-wider text-slate-400">Daftar Isi</p>
                    @foreach($sections as $i => $section)
                        <a href="#{{ $section['id'] }}">{{ $i + 1 }}. {{ $section['title'] }}</a>
                    @endforeach
                    <a href="#persetujuan">{{ count($sections) + 1 }}. Persetujuan Pengguna</a>
                </nav>
            </aside>

            {{-- Main content --}}
            <div class="space-y-5">

                {{-- Highlight --}}
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-6 mb-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-700 mt-0.5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 2L5 5v6c0 5.25 3.44 10.02 7 11 3.56-.98 7-5.75 7-11V5l-7-3z" />
                        </svg>
                        <div>
                            <h3 class="font-semibold text-blue-900 mb-2">Komitmen Perlindungan Data</h3>
                            <p class="text-slate-700 leading-relaxed text-sm">
                                SATGAS P4GN Universitas Lampung berkomitmen menjaga keamanan,
                                kerahasiaan, dan integritas seluruh data yang diberikan oleh
                                pengguna. Informasi yang dikumpulkan hanya digunakan untuk
                                kepentingan pelayanan konseling, proses pelaporan, verifikasi,
                                serta tindak lanjut oleh pihak yang berwenang sesuai ketentuan
                                yang berlaku.
                            </p>
                        </div>
                    </div>
                </div>

                @foreach($sections as $i => $section)
                <div id="{{ $section['id'] }}" class="kp-section bg-blue-50 border border-blue-100 rounded-xl p-6 mb-5">
                    <div class="flex items-start gap-4 mb-4">
                        <span class="kp-badge">{{ $i + 1 }}</span>
                        <h2 class="font-serif-doc text-xl font-semibold text-slate-900 pt-1">
                            {{ $section['title'] }}
                        </h2>
                    </div>
                    <div class="text-slate-600 leading-relaxed text-sm kp-body">
                        {!! $section['body'] !!}
                    </div>
                </div>
                @endforeach

                {{-- Persetujuan --}}
                <div id="persetujuan" class="kp-section bg-blue-50 border border-blue-100 rounded-xl p-6">
                    <div class="flex items-start gap-4 mb-3">
                        <span class="kp-badge">{{ count($sections) + 1 }}</span>
                        <h2 class="font-serif-doc text-xl font-semibold text-slate-900 pt-1">Persetujuan Pengguna</h2>
                    </div>
                    <p class="text-slate-600 leading-relaxed text-sm kp-body">
                        Dengan menggunakan Sistem Informasi Konseling dan Pelaporan
                        Penyalahgunaan Narkoba, pengguna dianggap telah membaca,
                        memahami, dan menyetujui kebijakan privasi ini. Pengguna
                        bertanggung jawab atas kebenaran data yang diberikan dan
                        bersedia menggunakan layanan sesuai ketentuan yang berlaku.
                    </p>
                </div>

            </div>
        </div>

        <div class="text-center mt-10">
            <a href="{{ url()->previous() }}" class="kp-btn-back">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
        </div>

    </div>

</section>
</div>

@push('scripts')
<script>
    // Highlight active TOC item on scroll
    (function () {
        var links = document.querySelectorAll('.kp-toc a');
        var sections = document.querySelectorAll('.kp-section');
        if (!links.length || !sections.length) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    links.forEach(function (l) { l.classList.remove('is-active'); });
                    var active = document.querySelector('a[href="#' + entry.target.id + '"]');
                    if (active) active.classList.add('is-active');
                }
            });
        }, { rootMargin: '-40% 0px -50% 0px' });

        sections.forEach(function (s) { observer.observe(s); });
    })();
</script>
@endpush

@endsection