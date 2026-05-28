@extends('layouts.admin')

@section('title', 'Statistik E-Konseling')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════════
   SCREEN STYLES
═══════════════════════════════════════════════ */
:root {
    --clr-ink:       #0f172a;
    --clr-ink-2:     #475569;
    --clr-ink-3:     #94a3b8;
    --clr-surface:   #ffffff;
    --clr-border:    #e2e8f0;
    --clr-bg:        #f8fafc;
    --clr-accent:    #4f46e5;
    --clr-accent-lt: #eef2ff;
    --clr-green:     #059669;
    --clr-green-lt:  #ecfdf5;
    --clr-amber:     #d97706;
    --clr-amber-lt:  #fffbeb;
    --clr-rose:      #e11d48;
    --clr-rose-lt:   #fff1f2;
    --ff-base:       'DM Sans', sans-serif;
    --ff-mono:       'DM Mono', monospace;
    --radius:        1rem;
    --shadow-card:   0 1px 3px rgba(15,23,42,.06), 0 4px 16px rgba(15,23,42,.04);
    --shadow-hover:  0 8px 32px rgba(15,23,42,.10);
}
* { box-sizing: border-box; }
#stat-page { font-family: var(--ff-base); background: var(--clr-bg); min-height: 100vh; padding: 1.5rem; }

/* ── Stagger animation ── */
@keyframes slideUp {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0); }
}
.anim-card { animation: slideUp .45s cubic-bezier(.22,1,.36,1) both; }
.anim-card:nth-child(1){animation-delay:.04s} .anim-card:nth-child(2){animation-delay:.09s}
.anim-card:nth-child(3){animation-delay:.14s} .anim-card:nth-child(4){animation-delay:.19s}
.anim-card:nth-child(5){animation-delay:.24s} .anim-card:nth-child(6){animation-delay:.29s}

/* ── Page header ── */
.page-header {
    background: var(--clr-ink); border-radius: var(--radius);
    padding: 1.75rem 2rem; margin-bottom: 1.25rem;
    position: relative; overflow: hidden;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: radial-gradient(ellipse 80% 60% at 110% -10%, #4338ca55, transparent),
                radial-gradient(ellipse 50% 80% at -10% 120%, #7c3aed33, transparent);
    pointer-events: none;
}
.page-header-grid {
    display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start;
    gap: 1rem; position: relative; z-index: 1;
}
.page-title {
    font-size: 1.5rem; font-weight: 700; color: #fff; letter-spacing: -.02em; margin: 0;
    display: flex; align-items: center; gap: .625rem;
}
.page-title-icon {
    width: 2.25rem; height: 2.25rem; background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.18); border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; color: rgba(255,255,255,.85);
}
.page-subtitle { color: rgba(255,255,255,.45); font-size: .8rem; margin-top: .25rem; }
.page-meta {
    margin-top: .5rem; display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
    border-radius: 999px; padding: .3rem .85rem; font-size: .72rem; color: rgba(255,255,255,.55);
}
.page-meta strong { color: rgba(255,255,255,.85); font-weight: 600; }
.page-meta .dot { width: 3px; height: 3px; border-radius: 50%; background: rgba(255,255,255,.3); }

/* ── Filter pills ── */
.filter-wrap {
    position: relative; z-index: 1; display: flex; align-items: center; flex-wrap: wrap; gap: .5rem;
    margin-top: 1.25rem; padding-top: 1.25rem; border-top: 1px solid rgba(255,255,255,.08);
}
.filter-label { font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: rgba(255,255,255,.35); margin-right: .25rem; }
.pill {
    padding: .4rem 1rem; border-radius: 999px; font-size: .78rem; font-weight: 600;
    cursor: pointer; border: 1.5px solid transparent; transition: all .2s ease;
    background: transparent; color: rgba(255,255,255,.5); font-family: var(--ff-base);
}
.pill:hover { background: rgba(255,255,255,.08); color: rgba(255,255,255,.8); }
.pill.active { background: #fff; color: var(--clr-accent); box-shadow: 0 2px 8px rgba(0,0,0,.2); }

/* ── Print button ── */
.btn-print {
    display: inline-flex; align-items: center; gap: .5rem;
    padding: .55rem 1.25rem; border-radius: .625rem;
    font-size: .8rem; font-weight: 600; cursor: pointer;
    background: rgba(255,255,255,.1); border: 1.5px solid rgba(255,255,255,.18);
    color: #fff; transition: all .2s; font-family: var(--ff-base);
}
.btn-print:hover { background: rgba(255,255,255,.18); border-color: rgba(255,255,255,.3); }

/* ── Cards ── */
.card {
    background: var(--clr-surface); border: 1px solid var(--clr-border);
    border-radius: var(--radius); box-shadow: var(--shadow-card);
    transition: box-shadow .2s, transform .2s;
}
.card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }

/* ── KPI ── */
.kpi-grid { display: grid; grid-template-columns: repeat(2,1fr); gap: 1rem; margin-bottom: 1.25rem; }
@media(min-width:1024px){ .kpi-grid { grid-template-columns: repeat(4,1fr); } }
.kpi-card {
    padding: 1.25rem 1.375rem; border-radius: var(--radius);
    background: var(--clr-surface); border: 1px solid var(--clr-border);
    box-shadow: var(--shadow-card); position: relative; overflow: hidden;
    transition: box-shadow .2s, transform .2s;
}
.kpi-card:hover { box-shadow: var(--shadow-hover); transform: translateY(-2px); }
.kpi-card-bar { position: absolute; top: 0; left: 0; right: 0; height: 3px; border-radius: var(--radius) var(--radius) 0 0; }
.kpi-icon { width: 2.25rem; height: 2.25rem; border-radius: .6rem; display: flex; align-items: center; justify-content: center; font-size: .9rem; margin-bottom: .875rem; }
.kpi-label { font-size: .67rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--clr-ink-3); margin-bottom: .25rem; }
.kpi-value { font-family: var(--ff-mono); font-size: 2rem; font-weight: 500; color: var(--clr-ink); line-height: 1; }
.kpi-value .unit { font-size: 1rem; opacity: .45; }
.kpi-sub { font-size: .72rem; color: var(--clr-ink-3); margin-top: .35rem; }
.kpi-sub strong { color: var(--clr-ink-2); }

/* ── Section header ── */
.sec-header {
    display: flex; align-items: center; gap: .5rem;
    font-size: .68rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase;
    color: var(--clr-ink-3); padding-bottom: .875rem; margin-bottom: 1.125rem;
    border-bottom: 1px solid var(--clr-border);
}
.sec-header i { color: var(--clr-accent); font-size: .8rem; }
.sec-badge {
    margin-left: auto; background: var(--clr-bg); border: 1px solid var(--clr-border);
    border-radius: 999px; padding: .2rem .7rem; font-size: .63rem; font-weight: 700; color: var(--clr-ink-2);
}

/* ── Chart rows ── */
.chart-row { display: grid; grid-template-columns: 1fr; gap: 1.25rem; margin-bottom: 1.25rem; }
@media(min-width:768px){ .chart-row-2 { grid-template-columns: 1fr 1fr; } }
@media(min-width:1024px){ .chart-row-3 { grid-template-columns: 1fr 2fr; } }

/* ── Kategori legend ── */
.kat-legend-item { display: flex; align-items: center; gap: .625rem; font-size: .8rem; padding: .3rem 0; }
.kat-dot { width: .5rem; height: .5rem; border-radius: 50%; flex-shrink: 0; }
.kat-name { flex: 1; color: var(--clr-ink-2); }
.kat-count { font-family: var(--ff-mono); font-weight: 500; color: var(--clr-ink); font-size: .78rem; }

/* ── Gender cards ── */
.gender-card { border-radius: .75rem; padding: 1rem; text-align: center; border: 1px solid; }
.gender-num { font-family: var(--ff-mono); font-size: 1.75rem; font-weight: 500; line-height: 1; margin: .375rem 0; }
.gender-label { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em; }

/* ── Unit bar list ── */
.unit-group-title {
    display: flex; align-items: center; gap: .5rem;
    font-size: .67rem; font-weight: 700; text-transform: uppercase; letter-spacing: .07em;
    color: var(--clr-ink-2); padding: .45rem .75rem;
    background: var(--clr-bg); border: 1px solid var(--clr-border); border-radius: .5rem; margin-bottom: .75rem;
}
.unit-bar-item { margin-bottom: .875rem; }
.unit-bar-item:last-child { margin-bottom: 0; }
.unit-bar-label { display: flex; justify-content: space-between; font-size: .78rem; margin-bottom: .35rem; }
.unit-bar-name { color: var(--clr-ink-2); }
.unit-bar-count { font-family: var(--ff-mono); font-weight: 500; color: var(--clr-ink); }
.unit-bar-track { background: var(--clr-bg); border-radius: 999px; height: 5px; overflow: hidden; }
.unit-bar-fill  { height: 100%; border-radius: 999px; transition: width .6s ease; }

/* ── Peak badge ── */
.peak-badge {
    margin-top: .875rem; padding: .625rem .875rem;
    background: var(--clr-amber-lt); border: 1px solid #fde68a;
    border-radius: .625rem; display: flex; align-items: center; gap: .5rem;
    font-size: .75rem; color: var(--clr-ink-2);
}
.peak-badge strong { font-family: var(--ff-mono); color: var(--clr-amber); font-size: .85rem; }

/* ── Konselor table ── */
.konselor-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.konselor-table thead tr { background: var(--clr-bg); }
.konselor-table th {
    padding: .6rem 1rem; text-align: left; font-size: .67rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase; color: var(--clr-ink-3);
}
.konselor-table th:first-child { border-radius: .5rem 0 0 .5rem; }
.konselor-table th:last-child  { border-radius: 0 .5rem .5rem 0; }
.konselor-table tbody tr { border-bottom: 1px solid var(--clr-border); }
.konselor-table tbody tr:last-child { border-bottom: none; }
.konselor-table tbody tr:hover { background: var(--clr-bg); }
.konselor-table td { padding: .75rem 1rem; }
.rank-badge {
    width: 1.5rem; height: 1.5rem; border-radius: .375rem;
    display: inline-flex; align-items: center; justify-content: center; font-size: .7rem; font-weight: 700;
}
.rate-bar { height: 5px; background: var(--clr-bg); border-radius: 999px; overflow: hidden; flex: 1; min-width: 48px; }
.rate-fill { height: 100%; border-radius: 999px; }

/* ── Toast ── */
.toast {
    position: fixed; bottom: 1.5rem; right: 1.5rem; z-index: 9999;
    display: flex; align-items: center; gap: .75rem;
    background: var(--clr-ink); color: #fff;
    padding: .75rem 1.25rem; border-radius: .75rem; font-size: .82rem; font-weight: 600;
    box-shadow: 0 8px 32px rgba(15,23,42,.25);
    transform: translateY(80px); opacity: 0; transition: all .3s cubic-bezier(.22,1,.36,1);
    pointer-events: none;
}
.toast.show { transform: translateY(0); opacity: 1; }

/* ── Screen-only / print-only helpers ── */
.print-only   { display: none !important; }
.chart-img-pr { display: none; }

/* ═══════════════════════════════════════════════
   PRINT — A4, semua seksi masuk, 2-3 halaman
═══════════════════════════════════════════════ */
@media print {

    /* ── @page ── */
    @page          { size: A4 portrait; margin: 10mm 11mm 12mm; }
    @page :first   { margin-top: 8mm; }

    /* ── Force color print ── */
    *, *::before, *::after {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* ── Kill layout shell ── */
    #sidebar, aside, header, nav, footer,
    .no-print, .btn-print, .filter-wrap,
    .toast { display: none !important; }

    /* ── Reset page wrapper ── */
    html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    #stat-page  { background: #fff !important; padding: 0 !important; font-size: 9pt !important; font-family: 'DM Sans', sans-serif !important; }

    /* ── Kill motion ── */
    * { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }

    /* ── Helpers ── */
    .print-only  { display: block !important; }
    .screen-only { display: none !important; }

    /* ── Page header: compact ── */
    .page-header {
        background: #0f172a !important;
        border-radius: 5px !important;
        padding: .55rem .9rem !important;
        margin-bottom: .55rem !important;
        page-break-after: avoid; break-after: avoid;
    }
    .page-title         { font-size: 12pt !important; }
    .page-subtitle      { font-size: 7pt !important; }
    .page-meta          { font-size: 6.5pt !important; padding: .18rem .55rem !important; }
    .page-title-icon    { width: 1.5rem !important; height: 1.5rem !important; font-size: .7rem !important; }
    .print-only-ts      { display: flex !important; margin-top: .35rem !important; font-size: 6.5pt !important; color: rgba(255,255,255,.5) !important; align-items: center; gap: .4rem; }

    /* ── KPI grid: 4 col, compact ── */
    .kpi-grid {
        display: grid !important;
        grid-template-columns: repeat(4, 1fr) !important;
        gap: .4rem !important;
        margin-bottom: .5rem !important;
    }
    .kpi-card   { padding: .5rem .65rem !important; border-radius: 5px !important; box-shadow: none !important; break-inside: avoid !important; }
    .kpi-card-bar { height: 2px !important; }
    .kpi-icon   { width: 1.4rem !important; height: 1.4rem !important; font-size: .65rem !important; margin-bottom: .25rem !important; }
    .kpi-label  { font-size: 6pt !important; }
    .kpi-value  { font-size: 15pt !important; }
    .kpi-sub    { font-size: 6pt !important; }

    /* ── Section headers ── */
    .sec-header   { font-size: 6.5pt !important; padding-bottom: .35rem !important; margin-bottom: .45rem !important; }
    .sec-header i { font-size: .65rem !important; }
    .sec-badge    { font-size: 5.5pt !important; padding: .1rem .35rem !important; }

    /* ── Chart rows: 2 col ── */
    .chart-row   { gap: .45rem !important; margin-bottom: .5rem !important; }
    .chart-row-2 { display: grid !important; grid-template-columns: 1fr 1fr !important; }
    .chart-row-3 { display: grid !important; grid-template-columns: 36% 64% !important; }

    /* ── Cards: compact ── */
    .card {
        padding: .6rem .75rem !important;
        border: 1px solid #dde3ea !important;
        border-radius: 5px !important;
        box-shadow: none !important;
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    /* ── Chart: hide canvas, show img ── */
    canvas { display: none !important; }
    .chart-img-pr {
        display: block !important;
        width: 100% !important; height: auto !important;
        object-fit: contain !important;
    }

    /* ── Kategori legend ── */
    .kat-legend-item { font-size: 7pt !important; padding: .1rem 0 !important; }
    .kat-dot         { width: .4rem !important; height: .4rem !important; }

    /* ── Gender cards ── */
    .gender-card  { padding: .45rem .5rem !important; border-radius: 4px !important; }
    .gender-num   { font-size: 13pt !important; margin: .2rem 0 !important; }
    .gender-label { font-size: 5.5pt !important; }

    /* ── Unit bars ── */
    .unit-group-title { font-size: 6pt !important; padding: .2rem .45rem !important; margin-bottom: .3rem !important; }
    .unit-bar-item    { margin-bottom: .4rem !important; }
    .unit-bar-label   { font-size: 6.5pt !important; margin-bottom: .15rem !important; }
    .unit-bar-track   { height: 3.5px !important; }
    .unit-bar-fill    { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    /* ── Peak badge ── */
    .peak-badge { padding: .3rem .55rem !important; font-size: 7pt !important; margin-top: .4rem !important; }

    /* ── Konselor table ── */
    .konselor-table th { font-size: 6pt !important; padding: .3rem .55rem !important; }
    .konselor-table td { font-size: 7pt !important; padding: .4rem .55rem !important; }
    .rank-badge        { width: 1.1rem !important; height: 1.1rem !important; font-size: 6pt !important; }
    .rate-bar          { height: 4px !important; }
    .rate-fill         { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }

    /* ── Force colored elements ── */
    .kpi-card-bar,
    .unit-bar-fill,
    .rate-fill,
    .gender-card {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }

    /* ── Page break sebelum section Konselor ── */
    .section-break-before {
        break-before: page !important;
        page-break-before: always !important;
        margin-top: 0 !important;
    }

    /* ── Print footer ── */
    .print-footer {
        display: flex !important;
        font-size: 6.5pt !important;
        margin-top: .65rem !important;
        padding-top: .45rem !important;
        border-top: 1px solid #e2e8f0 !important;
    }
}
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;

    $completionRate = $totalSesi > 0
        ? round(($distribusiStatus['completed'] ?? 0) / $totalSesi * 100, 1) : 0;
    $topUnit        = $distribusiUnit->first();
    $konselor_aktif = $bebanKonselor->count();

    $genderL = $distribusiGender['L'] ?? 0;
    $genderP = $distribusiGender['P'] ?? 0;
    $totalG  = $genderL + $genderP;
    $pctL    = $totalG > 0 ? round($genderL / $totalG * 100) : 0;
    $pctP    = $totalG > 0 ? round($genderP / $totalG * 100) : 0;

    $katColors   = ['#6366f1','#10b981','#f59e0b','#3b82f6','#ef4444','#8b5cf6','#14b8a6','#f97316'];
    $katColorMap = [];
    foreach ($distribusiKategori as $idx => $kat) {
        $katColorMap[$kat->kategori_unit] = $katColors[$idx % count($katColors)];
    }
    $maxUnit     = $distribusiUnit->first()->jumlah ?? 1;
    $unitGrouped = $distribusiUnit->groupBy('kategori_unit');
    $chunkSize   = max((int) ceil($unitGrouped->count() / 2), 1);
    $chunks      = $unitGrouped->chunk($chunkSize);
    $peakMax     = $peakHours->max('jumlah');
    $peakJam     = $peakMax ? $peakHours->firstWhere('jumlah', $peakMax) : null;

    $periodeMap = [
        'harian'   => 'Harian',
        'mingguan' => 'Mingguan',
        'bulanan'  => 'Bulanan',
        'tahunan'  => 'Tahunan',
    ];
    $periodeLabel = $periodeMap[$periode] ?? $periode;
    $now          = Carbon::now();
@endphp

<div id="stat-page">

{{-- ═══════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════ --}}
<div class="page-header">
    <div class="page-header-grid">

        {{-- Left --}}
        <div>
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-chart-line"></i></span>
                Statistik E-Konseling
            </h1>
            <p class="page-subtitle">SatgasP4GN · Analitik Layanan Konseling Online</p>
            <div class="page-meta">
                <span class="dot"></span>
                <strong>{{ $periodeLabel }}</strong>
                <span class="dot"></span>
                <span>{{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}</span>
            </div>
            {{-- print-only timestamp --}}
            <div class="print-only-ts" style="display:none;">
                <i class="fas fa-download" style="opacity:.5;"></i>
                <span>Dicetak: <strong style="color:rgba(255,255,255,.8);">{{ $now->format('d M Y, H:i') }} WIB</strong></span>
            </div>
        </div>

        {{-- Right: print btn (screen only) --}}
        <div class="no-print" style="display:flex;align-items:flex-start;">
            <button class="btn-print" onclick="cetakPDF()">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>

    </div>

    {{-- Periode filter (screen only) --}}
    <div class="filter-wrap no-print">
        <span class="filter-label">Periode:</span>
        @foreach($periodeMap as $key => $lbl)
        <button onclick="gantiPeriode('{{ $key }}')" data-p="{{ $key }}"
                class="pill {{ $periode === $key ? 'active' : '' }}">{{ $lbl }}</button>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════════
     KPI SCORECARD
═══════════════════════════════════════════ --}}
<div class="kpi-grid">

    <div class="kpi-card anim-card">
        <div class="kpi-card-bar" style="background:#3b82f6;"></div>
        <div class="kpi-icon" style="background:#eff6ff;color:#3b82f6;"><i class="fas fa-user-check"></i></div>
        <div class="kpi-label">Total Klien</div>
        <div class="kpi-value">{{ number_format($totalKlien) }}</div>
        <div class="kpi-sub">dari <strong>{{ number_format($totalSesi) }}</strong> sesi</div>
    </div>

    <div class="kpi-card anim-card">
        <div class="kpi-card-bar" style="background:#059669;"></div>
        <div class="kpi-icon" style="background:#ecfdf5;color:#059669;"><i class="fas fa-circle-check"></i></div>
        <div class="kpi-label">Penyelesaian</div>
        <div class="kpi-value">{{ $completionRate }}<span class="unit">%</span></div>
        <div class="kpi-sub"><strong>{{ $distribusiStatus['completed'] ?? 0 }}</strong> sesi selesai</div>
    </div>

    <div class="kpi-card anim-card">
        <div class="kpi-card-bar" style="background:#d97706;"></div>
        <div class="kpi-icon" style="background:#fffbeb;color:#d97706;"><i class="fas fa-building-columns"></i></div>
        <div class="kpi-label">Unit Paling Aktif</div>
        <div class="kpi-value" style="font-size:1.05rem;font-family:var(--ff-base);font-weight:700;line-height:1.3;word-break:break-word;">
            {{ $topUnit->nama_unit ?? '—' }}
        </div>
        <div class="kpi-sub"><strong>{{ $topUnit->jumlah ?? 0 }}</strong> sesi · {{ $topUnit->kategori_unit ?? '' }}</div>
    </div>

    <div class="kpi-card anim-card">
        <div class="kpi-card-bar" style="background:#4f46e5;"></div>
        <div class="kpi-icon" style="background:#eef2ff;color:#4f46e5;"><i class="fas fa-user-tie"></i></div>
        <div class="kpi-label">Konselor Aktif</div>
        <div class="kpi-value">{{ $konselor_aktif }}</div>
        <div class="kpi-sub">konselor menangani sesi</div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ROW 2 — Kategori + Demografi
═══════════════════════════════════════════ --}}
<div class="chart-row chart-row-2">

    {{-- Distribusi Kategori --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-chart-pie"></i> Distribusi Kategori Unit
            <span class="sec-badge">{{ $distribusiKategori->count() }} kategori</span>
        </div>
        @if($distribusiKategori->isEmpty())
        <div style="text-align:center;padding:3rem 0;color:var(--clr-ink-3);">
            <i class="fas fa-chart-pie" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.35;"></i>
            <p style="font-size:.85rem;">Belum ada data</p>
        </div>
        @else
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1.25rem;">
            {{-- canvas (screen) --}}
            <canvas id="chartKategori" width="160" height="160" style="flex-shrink:0;margin:0 auto;"></canvas>
            {{-- img placeholder (print) --}}
            <img id="imgKategori" class="chart-img-pr" alt="Grafik Kategori" style="width:140px;height:140px;flex-shrink:0;margin:0 auto;" />
            <div style="width:100%;">
                @foreach($distribusiKategori as $idx => $kat)
                <div class="kat-legend-item">
                    <span class="kat-dot" style="background:{{ $katColors[$idx % count($katColors)] }};"></span>
                    <span class="kat-name">{{ $kat->kategori_unit }}</span>
                    <span class="kat-count">{{ number_format($kat->jumlah) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Demografi --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-users"></i> Demografi Konseli
        </div>
        <p style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--clr-ink-3);margin-bottom:.625rem;">Gender</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;margin-bottom:1.25rem;">
            <div class="gender-card" style="background:#eff6ff;border-color:#bfdbfe;">
                <i class="fas fa-mars" style="color:#60a5fa;font-size:1.25rem;"></i>
                <div class="gender-num" style="color:#1d4ed8;">{{ number_format($genderL) }}</div>
                <div class="gender-label" style="color:#3b82f6;">Laki-laki · {{ $pctL }}%</div>
            </div>
            <div class="gender-card" style="background:#fdf4ff;border-color:#e9d5ff;">
                <i class="fas fa-venus" style="color:#c084fc;font-size:1.25rem;"></i>
                <div class="gender-num" style="color:#7e22ce;">{{ number_format($genderP) }}</div>
                <div class="gender-label" style="color:#a855f7;">Perempuan · {{ $pctP }}%</div>
            </div>
        </div>
        <p style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--clr-ink-3);margin-bottom:.75rem;">Sivitas Akademika</p>
        @if($distribusiSivitas->isEmpty())
        <div style="text-align:center;padding:1.5rem 0;color:var(--clr-ink-3);">
            <i class="fas fa-users" style="font-size:1.75rem;display:block;margin-bottom:.5rem;opacity:.35;"></i>
            <p style="font-size:.78rem;">Belum ada data</p>
        </div>
        @else
        <canvas id="chartSivitas" height="120"></canvas>
        <img id="imgSivitas" class="chart-img-pr" alt="Grafik Sivitas" />
        @endif
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ROW 3 — Top Unit per Kategori
═══════════════════════════════════════════ --}}
@if($chunks->isNotEmpty())
<div class="chart-row chart-row-2" style="margin-bottom:1.25rem;">
    @forelse($chunks as $chunkIndex => $unitsChunk)
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-building"></i> Top Unit / Fakultas
            @if($loop->first)
            <span class="sec-badge" style="background:#ecfdf5;border-color:#a7f3d0;color:#065f46;">TOP 10</span>
            @endif
        </div>
        @foreach($unitsChunk as $kategori => $units)
        <div style="margin-bottom:1.25rem;">
            <div class="unit-group-title">
                <span style="width:.5rem;height:.5rem;border-radius:50%;background:{{ $katColorMap[$kategori] ?? '#94a3b8' }};flex-shrink:0;"></span>
                {{ $kategori ?: 'Tidak Berkategori' }}
                <span style="margin-left:auto;color:var(--clr-ink-3);font-weight:600;">{{ $units->count() }} unit</span>
            </div>
            @foreach($units as $unit)
            @php $pct = $maxUnit > 0 ? round($unit->jumlah / $maxUnit * 100) : 0; @endphp
            <div class="unit-bar-item">
                <div class="unit-bar-label">
                    <span class="unit-bar-name">{{ $unit->nama_unit }}</span>
                    <span class="unit-bar-count">{{ number_format($unit->jumlah) }}</span>
                </div>
                <div class="unit-bar-track">
                    <div class="unit-bar-fill" style="width:{{ $pct }}%;background:{{ $katColorMap[$kategori] ?? '#6366f1' }};"></div>
                </div>
            </div>
            @endforeach
        </div>
        @endforeach
    </div>
    @empty
    <div class="card" style="padding:1.375rem;grid-column:span 2;">
        <div style="text-align:center;padding:3rem 0;color:var(--clr-ink-3);">
            <i class="fas fa-building" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.35;"></i>
            <p style="font-size:.85rem;">Belum ada data unit</p>
        </div>
    </div>
    @endforelse
</div>
@endif

{{-- ═══════════════════════════════════════════
     ROW 4 — Peak Hours + Beban Konselor
     class="section-break-before" → halaman baru saat print
═══════════════════════════════════════════ --}}
<div class="chart-row chart-row-3 section-break-before" style="margin-bottom:2rem;">

    {{-- Peak Hours --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-clock"></i> Waktu Tersibuk
        </div>
        <canvas id="chartPeakHours" height="200"></canvas>
        <img id="imgPeakHours" class="chart-img-pr" alt="Grafik Peak Hours" />
        @if($peakJam)
        <div class="peak-badge">
            <i class="fas fa-fire" style="color:var(--clr-amber);"></i>
            <span>Paling ramai pukul</span>
            <strong>{{ str_pad($peakJam->jam, 2, '0', STR_PAD_LEFT) }}:00</strong>
            <span style="margin-left:auto;color:var(--clr-ink-3);">{{ number_format($peakJam->jumlah) }} pesan</span>
        </div>
        @endif
    </div>

    {{-- Beban Konselor --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-user-tie"></i> Beban Kerja Konselor
            <span class="sec-badge">{{ count($bebanKonselor) }} konselor</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="konselor-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Konselor</th>
                        <th style="text-align:center;">Total Sesi</th>
                        <th style="text-align:center;">Selesai</th>
                        <th>Completion Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bebanKonselor as $i => $k)
                    <tr>
                        <td>
                            <span class="rank-badge" style="{{ $i===0?'background:#fef9c3;color:#a16207;':($i===1?'background:#f1f5f9;color:#475569;':($i===2?'background:#ffedd5;color:#c2410c;':'background:#f8fafc;color:#94a3b8;')) }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td style="font-weight:600;color:var(--clr-ink-2);">{{ $k->nama }}</td>
                        <td style="text-align:center;font-family:var(--ff-mono);font-weight:500;color:var(--clr-ink);">{{ number_format($k->total_sesi) }}</td>
                        <td style="text-align:center;font-family:var(--ff-mono);font-weight:500;color:var(--clr-green);">{{ number_format($k->sesi_selesai) }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.625rem;">
                                <div class="rate-bar">
                                    <div class="rate-fill"
                                         style="width:{{ $k->completion_rate }}%;
                                                background:{{ $k->completion_rate >= 70 ? '#059669' : ($k->completion_rate >= 40 ? '#d97706' : '#e11d48') }};
                                                -webkit-print-color-adjust:exact;print-color-adjust:exact;">
                                    </div>
                                </div>
                                <span style="font-family:var(--ff-mono);font-size:.75rem;font-weight:500;color:var(--clr-ink-2);width:2.75rem;text-align:right;">
                                    {{ $k->completion_rate }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:2.5rem;color:var(--clr-ink-3);">
                            <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            Belum ada data konselor
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     PRINT FOOTER
═══════════════════════════════════════════ --}}
<div class="print-footer" style="display:none;justify-content:space-between;align-items:center;font-size:.7rem;color:#94a3b8;">
    <div>
        <strong style="color:#475569;">SatgasP4GN</strong> · Statistik E-Konseling
        · Periode: <strong style="color:#475569;">{{ $periodeLabel }}</strong>
        ({{ $from->format('d M Y') }} – {{ $to->format('d M Y') }})
    </div>
    <div>Dicetak: {{ $now->format('d M Y, H:i') }} WIB</div>
</div>

</div>{{-- /stat-page --}}

{{-- Toast --}}
<div id="rt-toast" class="toast" role="status" aria-live="polite">
    <i class="fas fa-circle-check" style="color:#34d399;font-size:1rem;"></i>
    <span id="rt-msg">Data diperbarui</span>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ── Chart defaults ── */
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#94a3b8';

const KAT_COLORS = ['#6366f1','#10b981','#f59e0b','#3b82f6','#ef4444','#8b5cf6','#14b8a6','#f97316'];

/* ── Map: canvas id → paired <img> id + max-height (px, A4-scaled) ── */
const CHART_IMG_MAP = {
    chartKategori  : { imgId: 'imgKategori',   maxH: 140 },
    chartSivitas   : { imgId: 'imgSivitas',     maxH: 110 },
    chartPeakHours : { imgId: 'imgPeakHours',   maxH: 195 },
};

/* ─────────────────────────────────────────
   Build charts — store refs for later export
───────────────────────────────────────── */
const chartInstances = {};

/* Kategori Doughnut */
const elKat = document.getElementById('chartKategori');
if (elKat) {
    chartInstances.chartKategori = new Chart(elKat, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($distribusiKategori->pluck('kategori_unit')->values()) !!},
            datasets: [{
                data: {!! json_encode($distribusiKategori->pluck('jumlah')->values()) !!},
                backgroundColor: KAT_COLORS,
                borderWidth: 3, borderColor: '#fff', hoverOffset: 8
            }]
        },
        options: {
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID') + ' sesi' } }
            }
        }
    });
}

/* Sivitas Horizontal Bar */
const elSiv = document.getElementById('chartSivitas');
if (elSiv) {
    chartInstances.chartSivitas = new Chart(elSiv, {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribusiSivitas->pluck('nama')->values()) !!},
            datasets: [{
                data: {!! json_encode($distribusiSivitas->pluck('jumlah')->values()) !!},
                backgroundColor: ['#818cf8','#34d399','#fb923c','#f472b6','#facc15','#60a5fa'],
                borderRadius: 6, borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                y: { grid: { display: false } }
            }
        }
    });
}

/* Peak Hours Bar */
const peakRaw  = {!! json_encode($peakHours) !!};
const peakVals = Array(24).fill(0);
peakRaw.forEach(r => { peakVals[r.jam] = r.jumlah; });
const peakLbls = Array.from({ length: 24 }, (_, i) => i.toString().padStart(2, '0'));
const maxPeak  = Math.max(...peakVals);

const elPeak = document.getElementById('chartPeakHours');
if (elPeak) {
    chartInstances.chartPeakHours = new Chart(elPeak, {
        type: 'bar',
        data: {
            labels: peakLbls,
            datasets: [{
                label: 'Pesan',
                data: peakVals,
                backgroundColor: peakVals.map(v => (maxPeak > 0 && v === maxPeak) ? '#6366f1' : '#e0e7ff'),
                borderRadius: 4, borderSkipped: false
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { maxRotation: 90, font: { size: 9 } }, grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } }
            }
        }
    });
}

/* ─────────────────────────────────────────
   CETAK PDF
   Cara kerja:
   1. Render setiap canvas ke <img> yang sudah ada di DOM
      (berpasangan dengan canvas-nya, tersembunyi di screen)
   2. window.print()
   3. Setelah dialog tutup, bersihkan src img
───────────────────────────────────────── */
function cetakPDF() {
    // Snapshot semua canvas ke img pasangannya
    Object.entries(CHART_IMG_MAP).forEach(([canvasId, cfg]) => {
        const canvas = document.getElementById(canvasId);
        const img    = document.getElementById(cfg.imgId);
        if (!canvas || !img) return;

        // Ukuran cetak A4 (pt 595px wide approx; kita set proporsional)
        img.src              = canvas.toDataURL('image/png', 1.0);
        img.style.maxHeight  = cfg.maxH + 'px';
        img.style.width      = '100%';
        img.style.height     = 'auto';
        img.style.objectFit  = 'contain';
    });

    window.print();

    // Bersihkan setelah print dialog tutup
    setTimeout(() => {
        Object.values(CHART_IMG_MAP).forEach(cfg => {
            const img = document.getElementById(cfg.imgId);
            if (img) { img.src = ''; }
        });
    }, 2500);
}

/* ── Filter periode ── */
function gantiPeriode(periode) {
    document.querySelectorAll('.pill').forEach(el => {
        el.classList.toggle('active', el.dataset.p === periode);
    });
    showToast('Memuat data ' + periode + '…');
    window.location.href = '{{ route("admin.statistik.konseling") }}?periode=' + periode;
}

/* ── Toast ── */
function showToast(msg) {
    const t = document.getElementById('rt-toast');
    document.getElementById('rt-msg').textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 3000);
}
</script>
@endpush