@extends('layouts.konselor')

@section('title', 'Statistik Konseling Saya')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ── BASE ── */
[data-stat-root] { font-family: 'Sora', sans-serif; }
.mono { font-family: 'DM Mono', monospace; }

/* ── ANIMATIONS ── */
@keyframes riseIn {
    from { opacity: 0; transform: translateY(20px) scale(.98); }
    to   { opacity: 1; transform: translateY(0) scale(1); }
}
.rise { animation: riseIn .45s cubic-bezier(.22,.61,.36,1) both; }
.rise:nth-child(1){animation-delay:.04s}
.rise:nth-child(2){animation-delay:.09s}
.rise:nth-child(3){animation-delay:.14s}
.rise:nth-child(4){animation-delay:.19s}
.rise:nth-child(5){animation-delay:.24s}

/* ── CARD ACCENT BAR ── */
.card-accent { position:relative; overflow:hidden; }
.card-accent::before {
    content:'';
    position:absolute;
    inset-x:0; top:0;
    height:3px;
    border-radius:2px 2px 0 0;
}
.accent-yellow::before { background:#f59e0b; }
.accent-blue::before   { background:#3b82f6; }
.accent-red::before    { background:#ef4444; }
.accent-emerald::before{ background:#10b981; }
.accent-indigo::before { background:#6366f1; }
.accent-teal::before   { background:#14b8a6; }
.accent-purple::before { background:#8b5cf6; }

/* ── PERIOD TABS (dark, inside header card) ── */
a.period-tab-dark,
a.period-tab-dark:link,
a.period-tab-dark:visited,
.period-tab-dark {
    padding: .4rem .95rem;
    border-radius: .55rem;
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .04em;
    cursor: pointer;
    border: 1.5px solid rgba(255,255,255,.15);
    transition: all .18s ease;
    text-decoration: none !important;
    color: #ffffff !important; 
    background: rgba(255,255,255,.1);
    display: inline-flex;
    align-items: center;
    gap: .35rem;
}

a.period-tab-dark:hover {
    background: rgba(255,255,255,.2); /* Background lebih terang saat kursor diarahkan (hover) */
    color: #ffffff !important;
    border-color: rgba(167,243,208,.4);
}

a.period-tab-dark.period-tab-dark--active,
a.period-tab-dark.period-tab-dark--active:link,
a.period-tab-dark.period-tab-dark--active:visited {
    background: rgba(255,255,255,.25); /* Background paling terang untuk tab yang sedang aktif */
    color: #ffffff !important;
    border-color: rgba(52,211,153,.5);
    box-shadow: 0 4px 10px rgba(0,0,0,0.15), 0 0 0 1px rgba(52,211,153,.3);
}

/* ── PERIOD TABS (legacy, keep for ref) ── */
.period-tab {
    padding: .45rem 1rem;
    border-radius: .625rem;
    font-size: .75rem;
    font-weight: 600;
    letter-spacing: .04em;
    cursor: pointer;
    border: 1.5px solid transparent;
    transition: all .18s;
    text-decoration: none;
    color: #64748b;
}
.period-tab:hover { border-color: #cbd5e1; background: #f8fafc; color:#1e293b; }
.period-tab.active {
    background: #1e293b;
    color: #fff;
    border-color: #1e293b;
    box-shadow: 0 2px 8px rgba(30,41,59,.18);
}

/* ── PROGRESS BAR ── */
.progress-bar { transition: width .7s cubic-bezier(.4,0,.2,1); }

/* ══════════════════════════════════════════
   PRINT STYLES
   ══════════════════════════════════════════ */
@media print {
    /* Reset */
    *, *::before, *::after { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    /* Sembunyikan seluruh halaman, lalu tampilkan hanya #print-wrapper.
       Pakai visibility (bukan display) agar aman walau #print-wrapper
       tidak berada langsung di bawah <body> (masih dibungkus layout: #app, <main>, dsb). */
    body * { visibility: hidden !important; }
    #print-wrapper, #print-wrapper * { visibility: visible !important; }
    #print-wrapper {
        display: block !important;
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    header, nav, aside, footer,
    [data-no-print],
    .no-print { display: none !important; }

    @page {
        size: A4 portrait;
        margin: 16mm 14mm 14mm 14mm;
    }

    /* Typography resets */
    [data-stat-root] { font-family: 'Sora', sans-serif; font-size: 10pt; color: #0f172a; }

    /* Print header */
    .print-header { display: flex !important; }

    /* Grid adjustments */
    .print-grid-2 { display: grid !important; grid-template-columns: 1fr 1fr; gap: .75rem; }
    .print-grid-4 { display: grid !important; grid-template-columns: repeat(4, 1fr); gap: .6rem; }

    /* Cards */
    .print-card {
        border: 1px solid #e2e8f0 !important;
        border-radius: .75rem !important;
        padding: .85rem 1rem !important;
        page-break-inside: avoid;
        break-inside: avoid;
    }

    /* Shrink canvas */
    canvas { max-height: 160px !important; }

    /* Page breaks */
    .print-break-before { page-break-before: always; break-before: always; }

    /* Hide period tabs + buttons on print */
    [data-no-print] { display: none !important; }

    /* Smaller scorecard numbers */
    .scorecard-num { font-size: 22pt !important; }

    /* Remove shadows */
    * { box-shadow: none !important; }

    /* Remove animations */
    .rise { animation: none !important; opacity: 1 !important; transform: none !important; }
}
</style>
@endpush

@section('content')
@php
    $totalG  = $distribusiGender['L'] + $distribusiGender['P'];
    $pctL    = $totalG > 0 ? round($distribusiGender['L'] / $totalG * 100) : 0;
    $pctP    = $totalG > 0 ? round($distribusiGender['P'] / $totalG * 100) : 0;

    $totalRasio  = $totalDiterima + $totalDitolak;
    $pctDiterima = $totalRasio > 0 ? round($totalDiterima / $totalRasio * 100) : 0;
    $pctDitolak  = $totalRasio > 0 ? round($totalDitolak  / $totalRasio * 100) : 0;

    $maxUnit  = $distribusiUnit->first()->jumlah ?? 1;

    $periodOptions = [
        'harian'   => ['label' => 'Harian',   'icon' => 'fa-sun'],
        'mingguan' => ['label' => 'Mingguan',  'icon' => 'fa-calendar-week'],
        'bulanan'  => ['label' => 'Bulanan',   'icon' => 'fa-calendar'],
        'tahunan'  => ['label' => 'Tahunan',   'icon' => 'fa-calendar-days'],
    ];

    $durHari  = $rataRataDurasi ? floor($rataRataDurasi / 60 / 24) : 0;
    $durJam   = $rataRataDurasi ? floor(($rataRataDurasi % (60*24)) / 60) : 0;
    $durMenit = $rataRataDurasi ? round($rataRataDurasi % 60) : 0;
@endphp

{{-- ═══════════════════════════════════════════════════════
     MAIN WRAPPER  (shown on screen AND print)
     ═══════════════════════════════════════════════════════ --}}
<div id="print-wrapper" data-stat-root class="max-w-6xl mx-auto px-2 sm:px-4 pb-12">

    {{-- ── PRINT-ONLY HEADER ── --}}
    <div class="print-header hidden items-center justify-between mb-6 pb-4 border-b-2 border-slate-200">
        <div>
            <p class="text-[9pt] font-bold uppercase tracking-widest text-slate-400 mb-1">Laporan Statistik Konseling</p>
            <h1 class="text-[16pt] font-extrabold text-slate-900">{{ auth()->user()->nama }}</h1>
            <p class="text-[9pt] text-slate-500 mt-0.5">
                Periode:
                <span class="font-bold text-slate-700">{{ strtoupper($periodOptions[$periode]['label']) }}</span>
                &nbsp;·&nbsp; {{ $labelPeriode }}
            </p>
        </div>
        <div class="text-right">
            <p class="text-[8pt] text-slate-400">Diunduh pada</p>
            <p class="text-[10pt] font-bold text-slate-700 mono">{{ $downloadedAt }}</p>
        </div>
    </div>

    {{-- ── SCREEN HEADER CARD ── --}}
    <div data-no-print class="bg-gradient-to-br from-slate-900 via-green-950 to-green-900 p-8 md:p-10 rounded-3xl mb-8 relative overflow-hidden">

        {{-- decorative blobs --}}
        <div class="absolute -top-10 -right-10 w-56 h-56 rounded-full opacity-10 pointer-events-none"
             style="background:radial-gradient(circle,#34d399 0%,transparent 70%)"></div>
        <div class="absolute -bottom-8 -left-8 w-40 h-40 rounded-full opacity-[.07] pointer-events-none"
             style="background:radial-gradient(circle,#6ee7b7 0%,transparent 70%)"></div>
        {{-- grid texture --}}
        <div class="absolute inset-0 opacity-[.04] pointer-events-none"
             style="background-image:repeating-linear-gradient(0deg,#fff 0,#fff 1px,transparent 1px,transparent 32px),repeating-linear-gradient(90deg,#fff 0,#fff 1px,transparent 1px,transparent 32px)">
        </div>

        {{-- Top row --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
            <div>
                <h1 class="text-white text-2xl md:text-3xl font-extrabold flex items-center gap-3 leading-tight">
                    <i class="fas fa-chart-line opacity-75"></i>
                    Statistik Saya
                </h1>
                <p class="text-white/60 mt-2 text-sm">
                    Selamat datang,
                    <span class="text-white/80 font-semibold">{{ auth()->user()->nama }}</span>
                    — data periode
                    <span class="text-emerald-300 font-semibold">{{ $labelPeriode }}</span>
                </p>
            </div>
            <div class="flex flex-col items-end gap-2 shrink-0">
                <div class="text-white/40 text-xs text-right">
                    <i class="fas fa-calendar mr-1"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
                <button onclick="window.print()"
                    class="flex items-center gap-2 bg-white/10 hover:bg-white/20 active:bg-white/5
                           text-white border border-white/15 text-xs font-semibold
                           px-4 py-2 rounded-xl transition-all duration-150 whitespace-nowrap">
                    <i class="fas fa-print text-white/60"></i>
                    Cetak / Unduh
                </button>
            </div>
        </div>

        {{-- Divider --}}
        <div class="relative z-10 mt-6 mb-0 h-px bg-white/10"></div>

        {{-- Period tabs --}}
        <div class="relative z-10 flex items-center gap-1 pt-4">
            @foreach($periodOptions as $key => $opt)
            <a href="{{ request()->fullUrlWithQuery(['periode' => $key]) }}"
            class="period-tab-dark text-white hover:text-white {{ $periode === $key ? 'period-tab-dark--active' : '' }}">
                <i class="fas {{ $opt['icon'] }} text-[.6rem] opacity-75"></i>
                {{ $opt['label'] }}
            </a>
            @endforeach
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         SECTION 1 · SCORECARD
         ══════════════════════════════════════════ --}}
    <p class="text-[.65rem] font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
        <span class="w-4 h-px bg-slate-300 inline-block"></span>
        Ringkasan Sesi
        <span class="flex-1 h-px bg-slate-100 inline-block"></span>
    </p>

    <div class="print-grid-4 grid grid-cols-2 xl:grid-cols-4 gap-3.5 mb-7">

        {{-- Pending --}}
        <div class="print-card card-accent accent-yellow bg-white border border-slate-200 rounded-2xl p-5
                    flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 rise">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center text-base shrink-0 border border-amber-100">
                <i class="fas fa-clock"></i>
            </div>
            <div class="min-w-0">
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest leading-none">Permintaan Baru</p>
                <p class="scorecard-num text-3xl font-extrabold text-slate-900 mt-1.5 mono leading-none">{{ $totalPending }}</p>
                <p class="text-[.68rem] text-slate-400 mt-1">menunggu respons</p>
            </div>
            @if($totalPending > 0)
            <span class="absolute top-3.5 right-3.5 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-amber-400"></span>
            </span>
            @endif
        </div>

        {{-- Active --}}
        <div class="print-card card-accent accent-blue bg-white border border-slate-200 rounded-2xl p-5
                    flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 rise">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center text-base shrink-0 border border-blue-100">
                <i class="fas fa-circle-dot"></i>
            </div>
            <div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest leading-none">Sedang Berjalan</p>
                <p class="scorecard-num text-3xl font-extrabold text-slate-900 mt-1.5 mono leading-none">{{ $totalActive }}</p>
                <p class="text-[.68rem] text-slate-400 mt-1">sesi aktif</p>
            </div>
        </div>

        {{-- Unread --}}
        <div class="print-card card-accent accent-red bg-white border border-slate-200 rounded-2xl p-5
                    flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 rise">
            <div class="w-10 h-10 rounded-xl bg-red-50 text-red-500 flex items-center justify-center text-base shrink-0 border border-red-100">
                <i class="fas fa-envelope"></i>
            </div>
            <div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest leading-none">Pesan Belum Dibaca</p>
                <p class="scorecard-num text-3xl font-extrabold text-slate-900 mt-1.5 mono leading-none">{{ $unreadMessages }}</p>
                <p class="text-[.68rem] text-slate-400 mt-1">dari klien aktif</p>
            </div>
            @if($unreadMessages > 0)
            <span class="absolute top-3.5 right-3.5 flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-400"></span>
            </span>
            @endif
        </div>

        {{-- Completed --}}
        <div class="print-card card-accent accent-emerald bg-white border border-slate-200 rounded-2xl p-5
                    flex items-start gap-4 hover:-translate-y-0.5 hover:shadow-lg transition-all duration-200 rise">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center text-base shrink-0 border border-emerald-100">
                <i class="fas fa-circle-check"></i>
            </div>
            <div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest leading-none">Total Selesai</p>
                <p class="scorecard-num text-3xl font-extrabold text-slate-900 mt-1.5 mono leading-none">{{ $totalCompleted }}</p>
                <p class="text-[.68rem] text-slate-400 mt-1">sesi ditutup</p>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         SECTION 2 · PERFORMA PRIBADI
         ══════════════════════════════════════════ --}}
    <p class="text-[.65rem] font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
        <span class="w-4 h-px bg-slate-300 inline-block"></span>
        Performa Pribadi
        <span class="flex-1 h-px bg-slate-100 inline-block"></span>
    </p>

    <div class="print-grid-3 grid grid-cols-1 md:grid-cols-3 gap-4 mb-7">

        {{-- Rata-rata Durasi --}}
        <div class="print-card card-accent accent-purple bg-white border border-slate-200 rounded-2xl p-6 rise">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 rounded-lg bg-purple-50 text-purple-500 flex items-center justify-center text-xs border border-purple-100">
                    <i class="fas fa-stopwatch"></i>
                </div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest">Rata-rata Durasi Kasus</p>
            </div>
            @if($rataRataDurasi)
            <div class="text-center pb-2">
                <p class="text-[2.6rem] font-extrabold text-slate-900 leading-none mono">
                    {{ $durHari }}<span class="text-base font-semibold text-slate-400 ml-1">hari</span>
                </p>
                <p class="text-sm text-slate-500 mt-2">
                    {{ $durJam }} jam {{ $durMenit }} menit
                </p>
                <p class="text-[.65rem] text-slate-300 mt-1.5">dari sesi yang selesai</p>
            </div>
            @else
            <div class="text-center py-6 text-slate-300">
                <i class="fas fa-hourglass text-3xl mb-2 block opacity-50"></i>
                <p class="text-xs">Belum ada sesi selesai</p>
            </div>
            @endif
        </div>

        {{-- Rasio Diterima vs Ditolak --}}
        <div class="print-card card-accent accent-teal bg-white border border-slate-200 rounded-2xl p-6 rise">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 rounded-lg bg-teal-50 text-teal-500 flex items-center justify-center text-xs border border-teal-100">
                    <i class="fas fa-scale-balanced"></i>
                </div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest">Rasio Diterima / Ditolak</p>
            </div>
            <div class="flex gap-2.5 mb-4">
                <div class="flex-1 bg-emerald-50 border border-emerald-100 rounded-xl p-3 text-center">
                    <p class="text-2xl font-extrabold text-emerald-700 mono leading-none">{{ $totalDiterima }}</p>
                    <p class="text-[.6rem] font-bold uppercase text-emerald-500 mt-1">Diterima</p>
                    <p class="text-[.65rem] text-slate-400 mt-0.5">{{ $pctDiterima }}%</p>
                </div>
                <div class="flex-1 bg-red-50 border border-red-100 rounded-xl p-3 text-center">
                    <p class="text-2xl font-extrabold text-red-700 mono leading-none">{{ $totalDitolak }}</p>
                    <p class="text-[.6rem] font-bold uppercase text-red-400 mt-1">Ditolak</p>
                    <p class="text-[.65rem] text-slate-400 mt-0.5">{{ $pctDitolak }}%</p>
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                <div class="h-full bg-emerald-400 rounded-full progress-bar" style="width:{{ $pctDiterima }}%"></div>
            </div>
            <p class="text-[.65rem] text-slate-400 mt-2 text-center">{{ $totalRasio }} sesi diproses</p>
        </div>

        {{-- Aktivitas Pesan --}}
        <div class="print-card card-accent accent-indigo bg-white border border-slate-200 rounded-2xl p-6 rise">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-500 flex items-center justify-center text-xs border border-indigo-100">
                    <i class="fas fa-comments"></i>
                </div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest">Aktivitas Pesan</p>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                    <span class="text-sm text-slate-500">Total Pesan</span>
                    <span class="font-bold text-slate-900 mono">{{ number_format($totalPesanKonselor) }}</span>
                </div>
                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                    <span class="text-sm text-slate-500">Total Sesi</span>
                    <span class="font-bold text-slate-900 mono">{{ $totalSesiKonselor }}</span>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-sm font-semibold text-slate-600">Rata-rata / Sesi</span>
                    <span class="text-xl font-extrabold text-indigo-600 mono">{{ $rataRataPesan }}</span>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         SECTION 3 · DEMOGRAFI
         ══════════════════════════════════════════ --}}
    <p class="text-[.65rem] font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
        <span class="w-4 h-px bg-slate-300 inline-block"></span>
        Demografi Klien
        <span class="flex-1 h-px bg-slate-100 inline-block"></span>
    </p>

    <div class="print-grid-2 grid grid-cols-1 md:grid-cols-2 gap-4 mb-7">

        {{-- Sivitas + Gender --}}
        <div class="print-card bg-white border border-slate-200 rounded-2xl p-6 rise">
            {{-- Sivitas --}}
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-violet-50 text-violet-500 flex items-center justify-center text-xs border border-violet-100">
                    <i class="fas fa-id-card"></i>
                </div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest">Sivitas Akademika</p>
            </div>

            @if($distribusiSivitas->isEmpty())
            <div class="text-center py-6 text-slate-300">
                <i class="fas fa-users text-3xl mb-2 block opacity-50"></i>
                <p class="text-xs">Belum ada data</p>
            </div>
            @else
            <div class="mb-5">
                <canvas id="chartSivitas" height="130"></canvas>
            </div>
            @endif

            {{-- Gender --}}
            <div class="pt-3 border-t border-slate-100">
                <div class="flex items-center gap-2 mb-3">
                    <div class="w-5 h-5 rounded-md bg-pink-50 text-pink-400 flex items-center justify-center text-[.6rem] border border-pink-100">
                        <i class="fas fa-venus-mars"></i>
                    </div>
                    <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest">Gender Klien</p>
                </div>
                <div class="grid grid-cols-2 gap-2.5">
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-center">
                        <i class="fas fa-mars text-blue-400 text-sm mb-1.5 block"></i>
                        <p class="text-2xl font-extrabold text-blue-700 mono leading-none">{{ $distribusiGender['L'] }}</p>
                        <p class="text-[.6rem] font-bold uppercase text-blue-400 mt-1">Laki-laki</p>
                        <p class="text-[.65rem] text-slate-400">{{ $pctL }}%</p>
                    </div>
                    <div class="bg-fuchsia-50 border border-fuchsia-100 rounded-xl p-3 text-center">
                        <i class="fas fa-venus text-fuchsia-400 text-sm mb-1.5 block"></i>
                        <p class="text-2xl font-extrabold text-fuchsia-700 mono leading-none">{{ $distribusiGender['P'] }}</p>
                        <p class="text-[.6rem] font-bold uppercase text-fuchsia-400 mt-1">Perempuan</p>
                        <p class="text-[.65rem] text-slate-400">{{ $pctP }}%</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Unit / Fakultas --}}
        <div class="print-card bg-white border border-slate-200 rounded-2xl p-6 rise">
            <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-500 flex items-center justify-center text-xs border border-emerald-100">
                    <i class="fas fa-building"></i>
                </div>
                <p class="text-[.62rem] font-bold text-slate-400 uppercase tracking-widest">Asal Unit / Fakultas</p>
            </div>

            @forelse($distribusiUnit as $i => $unit)
            @php
                $pct   = $maxUnit > 0 ? round($unit->jumlah / $maxUnit * 100) : 0;
                $barColors = ['bg-emerald-400','bg-teal-400','bg-cyan-400','bg-indigo-400','bg-violet-400','bg-fuchsia-400'];
                $bc    = $barColors[$i % count($barColors)];
            @endphp
            <div class="mb-3">
                <div class="flex justify-between items-center text-xs mb-1.5 gap-2">
                    <span class="text-slate-600 font-medium truncate">{{ $unit->nama_unit }}</span>
                    <span class="font-bold text-slate-800 mono shrink-0">{{ $unit->jumlah }}</span>
                </div>
                <div class="bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="h-full {{ $bc }} rounded-full progress-bar" style="width:{{ $pct }}%"></div>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-slate-300">
                <i class="fas fa-building text-3xl mb-2 block opacity-50"></i>
                <p class="text-xs">Belum ada data</p>
            </div>
            @endforelse
        </div>

    </div>

    {{-- ══════════════════════════════════════════
         SECTION 4 · TREN BEBAN KERJA
         ══════════════════════════════════════════ --}}
    <p class="text-[.65rem] font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
        <span class="w-4 h-px bg-slate-300 inline-block"></span>
        Tren Beban Kerja
        <span class="flex-1 h-px bg-slate-100 inline-block"></span>
    </p>

    <div class="print-card bg-white border border-slate-200 rounded-2xl p-6 mb-4 rise">
        <div class="flex items-center justify-between mb-5 pb-3 border-b border-slate-100">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-orange-50 text-orange-500 flex items-center justify-center text-xs border border-orange-100">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <span class="text-[.62rem] font-bold text-slate-500 uppercase tracking-widest">Jumlah Sesi</span>
                <span class="text-slate-300 text-xs leading-none">—</span>
                @php
                    $trendPills = [
                        'harian'   => ['label' => 'per Jam',  'bg' => '#7c3aed', 'ring' => '#c4b5fd'],
                        'mingguan' => ['label' => 'per Hari', 'bg' => '#2563eb', 'ring' => '#93c5fd'],
                        'bulanan'  => ['label' => 'per Bulan','bg' => '#059669', 'ring' => '#6ee7b7'],
                        'tahunan'  => ['label' => 'per Bulan','bg' => '#ea580c', 'ring' => '#fdba74'],
                    ];
                    $tp = $trendPills[$periode] ?? $trendPills['bulanan'];
                @endphp
                <span style="display:inline-flex;align-items:center;background:{{ $tp['bg'] }};color:#fff;
                             font-size:.58rem;font-weight:700;letter-spacing:.07em;text-transform:uppercase;
                             padding:.25rem .55rem;border-radius:.375rem;
                             box-shadow:0 0 0 2px {{ $tp['ring'] }},0 1px 4px rgba(0,0,0,.12);">
                    {{ $tp['label'] }}
                </span>
            </div>
            <span class="text-[.65rem] text-slate-400 bg-slate-50 border border-slate-100 rounded-lg px-2.5 py-1 mono">
                {{ $labelPeriode }}
            </span>
        </div>

        @if($trenBebanKerja->isEmpty())
        <div class="text-center py-12 text-slate-300">
            <i class="fas fa-chart-bar text-4xl mb-3 block opacity-30"></i>
            <p class="text-sm">Belum ada data tren untuk periode ini</p>
        </div>
        @else
        <canvas id="chartTren" height="90"></canvas>
        @endif
    </div>

    {{-- ── PRINT FOOTER ── --}}
    <div class="print-header hidden items-center justify-between pt-4 border-t border-slate-200 text-[8pt] text-slate-400">
        <span>Sistem Konseling — Dokumen rahasia, hanya untuk penggunaan internal</span>
        <span class="mono">{{ $downloadedAt }}</span>
    </div>

</div>{{-- /print-wrapper --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Sora', sans-serif";
Chart.defaults.color       = '#94a3b8';

const CHART_COLORS = ['#818cf8','#34d399','#f87171','#fbbf24','#c084fc','#38bdf8','#fb7185','#a3e635'];

// ── Chart Sivitas ──────────────────────────────
const elSivitas = document.getElementById('chartSivitas');
if (elSivitas) {
    new Chart(elSivitas, {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribusiSivitas->pluck('nama')->values()) !!},
            datasets: [{
                data: {!! json_encode($distribusiSivitas->pluck('jumlah')->values()) !!},
                backgroundColor: CHART_COLORS,
                borderRadius: 6,
                borderSkipped: false,
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 11 } },
                    grid: { color: '#f1f5f9' }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                },
            }
        }
    });
}

// ── Chart Tren Beban Kerja ─────────────────────
const elTren = document.getElementById('chartTren');
if (elTren) {
    const trenRaw    = {!! json_encode($trenBebanKerja) !!};
    const trenLabels = trenRaw.map(r => r.bulan);
    const trenValues = trenRaw.map(r => r.jumlah);
    const maxVal     = Math.max(...trenValues, 1);

    new Chart(elTren, {
        type: 'bar',
        data: {
            labels: trenLabels,
            datasets: [{
                label: 'Jumlah Sesi',
                data: trenValues,
                backgroundColor: trenValues.map(v =>
                    v === maxVal ? '#6366f1' : '#c7d2fe'
                ),
                borderRadius: 6,
                hoverBackgroundColor: '#4f46e5',
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} sesi`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, font: { size: 11 } },
                    grid: { color: '#f8fafc' }
                },
            }
        }
    });
}
</script>
@endpush