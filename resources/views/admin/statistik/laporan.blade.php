@extends('layouts.admin')

@section('title', 'Statistik Pelaporan Narkoba')

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
    --clr-accent:    #dc2626;
    --clr-accent-lt: #fff1f2;
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
    background: #1a0000;
    background-image: radial-gradient(ellipse 80% 60% at 110% -10%, #7f1d1d88, transparent),
                      radial-gradient(ellipse 50% 80% at -10% 120%, #450a0a55, transparent);
    border-radius: var(--radius);
    padding: 1.75rem 2rem; margin-bottom: 1.25rem;
    position: relative; overflow: hidden;
}
.page-header::before {
    content: ''; position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='%23ffffff' fill-opacity='0.015'%3E%3Cpath d='M0 0h20v20H0zm20 20h20v20H20z'/%3E%3C/g%3E%3C/svg%3E");
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
    width: 2.25rem; height: 2.25rem; background: rgba(239,68,68,.2);
    border: 1px solid rgba(239,68,68,.35); border-radius: .625rem;
    display: flex; align-items: center; justify-content: center;
    font-size: .9rem; color: #fca5a5;
}
.page-subtitle { color: rgba(255,255,255,.45); font-size: .8rem; margin-top: .25rem; }
.page-meta {
    margin-top: .5rem; display: inline-flex; align-items: center; gap: .5rem;
    background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12);
    border-radius: 999px; padding: .3rem .85rem; font-size: .72rem; color: rgba(255,255,255,.55);
}
.page-meta strong { color: rgba(255,255,255,.85); font-weight: 600; }
.page-meta .dot { width: 3px; height: 3px; border-radius: 50%; background: rgba(255,255,255,.3); }

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

/* ── Status badge chips ── */
.status-chips { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .75rem; }
.status-chip {
    display: inline-flex; align-items: center; gap: .375rem;
    padding: .3rem .75rem; border-radius: 999px; font-size: .72rem; font-weight: 600;
}

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

/* ── Legend items ── */
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

/* ── Admin table ── */
.admin-table { width: 100%; border-collapse: collapse; font-size: .82rem; }
.admin-table thead tr { background: var(--clr-bg); }
.admin-table th {
    padding: .6rem 1rem; text-align: left; font-size: .67rem; font-weight: 700;
    letter-spacing: .07em; text-transform: uppercase; color: var(--clr-ink-3);
}
.admin-table th:first-child { border-radius: .5rem 0 0 .5rem; }
.admin-table th:last-child  { border-radius: 0 .5rem .5rem 0; }
.admin-table tbody tr { border-bottom: 1px solid var(--clr-border); }
.admin-table tbody tr:last-child { border-bottom: none; }
.admin-table tbody tr:hover { background: var(--clr-bg); }
.admin-table td { padding: .75rem 1rem; }
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

.print-only   { display: none !important; }
.chart-img-pr { display: none; }

/* ═══════════════════════════════════════════════
   PRINT
═══════════════════════════════════════════════ */
@media print {
    @page          { size: A4 portrait; margin: 10mm 11mm 12mm; }
    @page :first   { margin-top: 8mm; }
    *, *::before, *::after { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    #sidebar, aside, header, nav, footer, .no-print, .btn-print, .filter-wrap, .toast { display: none !important; }
    html, body { background: #fff !important; margin: 0 !important; padding: 0 !important; }
    #stat-page  { background: #fff !important; padding: 0 !important; font-size: 9pt !important; font-family: 'DM Sans', sans-serif !important; }
    * { animation: none !important; transition: none !important; opacity: 1 !important; transform: none !important; }
    .print-only  { display: block !important; }
    .screen-only { display: none !important; }
    .page-header {
        background: #1a0000 !important; border-radius: 5px !important;
        padding: .55rem .9rem !important; margin-bottom: .55rem !important;
        page-break-after: avoid; break-after: avoid;
    }
    .page-title { font-size: 12pt !important; }
    .page-subtitle { font-size: 7pt !important; }
    .page-meta { font-size: 6.5pt !important; padding: .18rem .55rem !important; }
    .page-title-icon { width: 1.5rem !important; height: 1.5rem !important; font-size: .7rem !important; }
    .print-only-ts { display: flex !important; margin-top: .35rem !important; font-size: 6.5pt !important; color: rgba(255,255,255,.5) !important; align-items: center; gap: .4rem; }
    .kpi-grid { display: grid !important; grid-template-columns: repeat(4, 1fr) !important; gap: .4rem !important; margin-bottom: .5rem !important; }
    .kpi-card   { padding: .5rem .65rem !important; border-radius: 5px !important; box-shadow: none !important; break-inside: avoid !important; }
    .kpi-card-bar { height: 2px !important; }
    .kpi-icon   { width: 1.4rem !important; height: 1.4rem !important; font-size: .65rem !important; margin-bottom: .25rem !important; }
    .kpi-label  { font-size: 6pt !important; }
    .kpi-value  { font-size: 15pt !important; }
    .kpi-sub    { font-size: 6pt !important; }
    .sec-header   { font-size: 6.5pt !important; padding-bottom: .35rem !important; margin-bottom: .45rem !important; }
    .sec-header i { font-size: .65rem !important; }
    .sec-badge    { font-size: 5.5pt !important; padding: .1rem .35rem !important; }
    .chart-row   { gap: .45rem !important; margin-bottom: .5rem !important; }
    .chart-row-2 { display: grid !important; grid-template-columns: 1fr 1fr !important; }
    .chart-row-3 { display: grid !important; grid-template-columns: 36% 64% !important; }
    .card { padding: .6rem .75rem !important; border: 1px solid #dde3ea !important; border-radius: 5px !important; box-shadow: none !important; break-inside: avoid !important; page-break-inside: avoid !important; }
    canvas { display: none !important; }
    .chart-img-pr { display: block !important; width: 100% !important; height: auto !important; object-fit: contain !important; }
    .kat-legend-item { font-size: 7pt !important; padding: .1rem 0 !important; }
    .kat-dot { width: .4rem !important; height: .4rem !important; }
    .gender-card  { padding: .45rem .5rem !important; border-radius: 4px !important; }
    .gender-num   { font-size: 13pt !important; margin: .2rem 0 !important; }
    .gender-label { font-size: 5.5pt !important; }
    .unit-group-title { font-size: 6pt !important; padding: .2rem .45rem !important; margin-bottom: .3rem !important; }
    .unit-bar-item { margin-bottom: .4rem !important; }
    .unit-bar-label { font-size: 6.5pt !important; margin-bottom: .15rem !important; }
    .unit-bar-track { height: 3.5px !important; }
    .unit-bar-fill { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .admin-table th { font-size: 6pt !important; padding: .3rem .55rem !important; }
    .admin-table td { font-size: 7pt !important; padding: .4rem .55rem !important; }
    .rank-badge { width: 1.1rem !important; height: 1.1rem !important; font-size: 6pt !important; }
    .rate-bar { height: 4px !important; }
    .rate-fill { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .kpi-card-bar, .unit-bar-fill, .rate-fill, .gender-card { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .section-break-before { break-before: page !important; page-break-before: always !important; margin-top: 0 !important; }
    .status-chip { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; font-size: 6pt !important; padding: .15rem .4rem !important; }
    .print-footer { display: flex !important; font-size: 6.5pt !important; margin-top: .65rem !important; padding-top: .45rem !important; border-top: 1px solid #e2e8f0 !important; }
}
</style>
@endpush

@section('content')
@php
    use Carbon\Carbon;

    $selesaiRate  = $totalLaporan > 0
        ? round(($distribusiStatus['selesai'] ?? 0) / $totalLaporan * 100, 1) : 0;
    $ditolakCount = $distribusiStatus['ditolak']  ?? 0;
    $prosesCount  = $distribusiStatus['diproses'] ?? 0;

    // Jenis kasus colors
    $kasusColors = [
        'Pengguna'  => ['bg' => '#fff7ed', 'border' => '#fed7aa', 'text' => '#ea580c', 'bar' => '#f97316'],
        'Pengedar'  => ['bg' => '#fff1f2', 'border' => '#fecdd3', 'text' => '#be123c', 'bar' => '#e11d48'],
        'Kurir'     => ['bg' => '#fffbeb', 'border' => '#fde68a', 'text' => '#b45309', 'bar' => '#d97706'],
        'Bandar'    => ['bg' => '#faf5ff', 'border' => '#e9d5ff', 'text' => '#7e22ce', 'bar' => '#9333ea'],
    ];

    $narkobaColors = ['#ef4444','#f97316','#eab308','#22c55e','#3b82f6','#8b5cf6','#ec4899','#14b8a6'];
    $katColors     = ['#6366f1','#10b981','#f59e0b','#3b82f6','#ef4444','#8b5cf6','#14b8a6','#f97316'];

    $katColorMap = [];
    foreach ($distribusiKategoriUnit as $idx => $kat) {
        $katColorMap[$kat->kategori_unit] = $katColors[$idx % count($katColors)];
    }

    $maxUnit     = $distribusiUnit->first()->jumlah ?? 1;
    $unitGrouped = $distribusiUnit->groupBy('kategori_unit');
    $chunkSize   = max((int) ceil($unitGrouped->count() / 2), 1);
    $chunks      = $unitGrouped->chunk($chunkSize);

    $periodeMap = [
        'harian'   => 'Harian',
        'mingguan' => 'Mingguan',
        'bulanan'  => 'Bulanan',
        'tahunan'  => 'Tahunan',
    ];
    $periodeLabel = $periodeMap[$periode] ?? $periode;
    $now          = Carbon::now();

    $statusConfig = [
        'terkirim'    => ['label' => 'Terkirim',    'bg' => '#eff6ff', 'text' => '#1d4ed8', 'icon' => 'fa-paper-plane'],
        'diverifikasi'=> ['label' => 'Diverifikasi','bg' => '#f0fdf4', 'text' => '#15803d', 'icon' => 'fa-badge-check'],
        'diproses'    => ['label' => 'Diproses',    'bg' => '#fffbeb', 'text' => '#b45309', 'icon' => 'fa-spinner'],
        'selesai'     => ['label' => 'Selesai',     'bg' => '#ecfdf5', 'text' => '#065f46', 'icon' => 'fa-circle-check'],
        'ditolak'     => ['label' => 'Ditolak',     'bg' => '#fff1f2', 'text' => '#be123c', 'icon' => 'fa-circle-xmark'],
    ];

    $topNarkobaItem = $distribusiNarkoba->first();
@endphp

<div id="stat-page">

{{-- ═══════════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════════ --}}
<div class="page-header">
    <div class="page-header-grid">
        <div>
            <h1 class="page-title">
                <span class="page-title-icon"><i class="fas fa-shield-halved"></i></span>
                Statistik Pelaporan Narkoba
            </h1>
            <p class="page-subtitle">SatgasP4GN · Analitik Kasus Narkoba Terlaporkan</p>
            <div class="page-meta">
                <span class="dot"></span>
                <strong>{{ $periodeLabel }}</strong>
                <span class="dot"></span>
                <span>{{ $from->format('d M Y') }} – {{ $to->format('d M Y') }}</span>
                <span class="dot"></span>
                <span>acuan: tanggal kejadian</span>
            </div>
            <div class="print-only-ts" style="display:none;">
                <i class="fas fa-download" style="opacity:.5;"></i>
                <span>Dicetak: <strong style="color:rgba(255,255,255,.8);">{{ $now->format('d M Y, H:i') }} WIB</strong></span>
            </div>
        </div>
        <div class="no-print" style="display:flex;align-items:flex-start;">
            <button class="btn-print" onclick="cetakPDF()">
                <i class="fas fa-print"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>
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
        <div class="kpi-card-bar" style="background:#ef4444;"></div>
        <div class="kpi-icon" style="background:#fff1f2;color:#ef4444;"><i class="fas fa-file-circle-exclamation"></i></div>
        <div class="kpi-label">Total Laporan</div>
        <div class="kpi-value">{{ number_format($totalLaporan) }}</div>
        <div class="kpi-sub">
            <div class="status-chips">
                @foreach($statusConfig as $sk => $sc)
                @if(isset($distribusiStatus[$sk]) && $distribusiStatus[$sk] > 0)
                <span class="status-chip" style="background:{{ $sc['bg'] }};color:{{ $sc['text'] }};">
                    {{ $distribusiStatus[$sk] }} {{ $sc['label'] }}
                </span>
                @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="kpi-card anim-card">
        <div class="kpi-card-bar" style="background:#059669;"></div>
        <div class="kpi-icon" style="background:#ecfdf5;color:#059669;"><i class="fas fa-circle-check"></i></div>
        <div class="kpi-label">Tingkat Penyelesaian</div>
        <div class="kpi-value">{{ $selesaiRate }}<span class="unit">%</span></div>
        <div class="kpi-sub"><strong>{{ $distribusiStatus['selesai'] ?? 0 }}</strong> laporan diselesaikan</div>
    </div>

    <div class="kpi-card anim-card">
        <div class="kpi-card-bar" style="background:#f97316;"></div>
        <div class="kpi-icon" style="background:#fff7ed;color:#f97316;"><i class="fas fa-flask"></i></div>
        <div class="kpi-label">Narkoba Terbanyak</div>
        <div class="kpi-value" style="font-size:1.05rem;font-family:var(--ff-base);font-weight:700;line-height:1.3;word-break:break-word;">
            {{ $topNarkobaItem->nama_narkoba ?? '—' }}
        </div>
        <div class="kpi-sub"><strong>{{ $topNarkobaItem->jumlah ?? 0 }}</strong> kasus dilaporkan</div>
    </div>

    <div class="kpi-card anim-card">
        <div class="kpi-card-bar" style="background:#4f46e5;"></div>
        <div class="kpi-icon" style="background:#eef2ff;color:#4f46e5;"><i class="fas fa-user-shield"></i></div>
        <div class="kpi-label">Rata-rata Penyelesaian</div>
        <div class="kpi-value">
            @if($rataWaktuSelesai)
                {{ round($rataWaktuSelesai) }}<span class="unit"> hr</span>
            @else
                —
            @endif
        </div>
        <div class="kpi-sub">hari sejak laporan masuk</div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ROW 2 — Tren + Jenis Kasus
═══════════════════════════════════════════ --}}
<div class="chart-row chart-row-3" style="margin-bottom:1.25rem;">

    {{-- Jenis Kasus --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-layer-group"></i> Jenis Kasus
            <span class="sec-badge">{{ $distribusiJenisKasus->count() }} jenis</span>
        </div>
        @forelse($distribusiJenisKasus as $kasus)
        @php
            $cfg = $kasusColors[$kasus->jenis_kasus] ?? ['bg'=>'#f8fafc','border'=>'#e2e8f0','text'=>'#475569','bar'=>'#94a3b8'];
            $pct = $totalLaporan > 0 ? round($kasus->jumlah / $totalLaporan * 100) : 0;
        @endphp
        <div style="padding:.75rem;border-radius:.625rem;background:{{ $cfg['bg'] }};border:1px solid {{ $cfg['border'] }};margin-bottom:.625rem;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:.375rem;">
                <span style="font-size:.8rem;font-weight:700;color:{{ $cfg['text'] }};">{{ $kasus->jenis_kasus }}</span>
                <span style="font-family:var(--ff-mono);font-size:.85rem;font-weight:500;color:{{ $cfg['text'] }};">{{ number_format($kasus->jumlah) }}</span>
            </div>
            <div style="background:rgba(0,0,0,.06);border-radius:999px;height:4px;overflow:hidden;">
                <div style="width:{{ $pct }}%;height:100%;background:{{ $cfg['bar'] }};border-radius:999px;-webkit-print-color-adjust:exact;print-color-adjust:exact;"></div>
            </div>
            <div style="font-size:.65rem;color:{{ $cfg['text'] }};opacity:.7;margin-top:.25rem;">{{ $pct }}% dari total</div>
        </div>
        @empty
        <div style="text-align:center;padding:2rem 0;color:var(--clr-ink-3);">
            <i class="fas fa-layer-group" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.35;"></i>
            <p style="font-size:.78rem;">Belum ada data</p>
        </div>
        @endforelse
    </div>

    {{-- Tren Bulanan --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-chart-line"></i> Tren Laporan (12 Bulan Terakhir)
        </div>
        <canvas id="chartTren" height="160"></canvas>
        <img id="imgTren" class="chart-img-pr" alt="Grafik Tren" />
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ROW 3 — Narkoba + Peran
═══════════════════════════════════════════ --}}
<div class="chart-row chart-row-2">

    {{-- Distribusi Jenis Narkoba --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-flask"></i> Distribusi Jenis Narkoba
            <span class="sec-badge">{{ $distribusiNarkoba->count() }} jenis</span>
        </div>
        @if($distribusiNarkoba->isEmpty())
        <div style="text-align:center;padding:3rem 0;color:var(--clr-ink-3);">
            <i class="fas fa-flask" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.35;"></i>
            <p style="font-size:.85rem;">Belum ada data</p>
        </div>
        @else
        <div style="display:flex;flex-wrap:wrap;align-items:center;gap:1.25rem;">
            <canvas id="chartNarkoba" width="160" height="160" style="flex-shrink:0;margin:0 auto;"></canvas>
            <img id="imgNarkoba" class="chart-img-pr" alt="Grafik Narkoba" style="width:140px;height:140px;flex-shrink:0;margin:0 auto;" />
            <div style="width:100%;">
                @foreach($distribusiNarkoba as $idx => $n)
                <div class="kat-legend-item">
                    <span class="kat-dot" style="background:{{ $narkobaColors[$idx % count($narkobaColors)] }};"></span>
                    <span class="kat-name">{{ $n->nama_narkoba }}</span>
                    <span class="kat-count">{{ number_format($n->jumlah) }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Peran Pelapor & Terlapor + Gender --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-users"></i> Demografi Laporan
        </div>

        {{-- Peran --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.25rem;">
            <div>
                <p style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--clr-ink-3);margin-bottom:.625rem;">Peran Pelapor</p>
                <canvas id="chartPeranPelapor" height="130"></canvas>
                <img id="imgPeranPelapor" class="chart-img-pr" alt="Grafik Peran Pelapor" />
            </div>
            <div>
                <p style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--clr-ink-3);margin-bottom:.625rem;">Peran Terlapor</p>
                <canvas id="chartPeranTerlapor" height="130"></canvas>
                <img id="imgPeranTerlapor" class="chart-img-pr" alt="Grafik Peran Terlapor" />
            </div>
        </div>

        {{-- Gender --}}
        <p style="font-size:.67rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--clr-ink-3);margin-bottom:.625rem;">Gender</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
            @php
                $gL_p = $genderPelapor['Laki-laki']  ?? 0;
                $gP_p = $genderPelapor['Perempuan']  ?? 0;
                $gL_t = $genderTerlapor['Laki-laki'] ?? 0;
                $gP_t = $genderTerlapor['Perempuan'] ?? 0;
            @endphp
            <div>
                <p style="font-size:.65rem;color:var(--clr-ink-3);margin-bottom:.4rem;">Pelapor</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.4rem;">
                    <div class="gender-card" style="background:#eff6ff;border-color:#bfdbfe;">
                        <i class="fas fa-mars" style="color:#60a5fa;font-size:1rem;"></i>
                        <div class="gender-num" style="color:#1d4ed8;font-size:1.25rem;">{{ number_format($gL_p) }}</div>
                        <div class="gender-label" style="color:#3b82f6;">L</div>
                    </div>
                    <div class="gender-card" style="background:#fdf4ff;border-color:#e9d5ff;">
                        <i class="fas fa-venus" style="color:#c084fc;font-size:1rem;"></i>
                        <div class="gender-num" style="color:#7e22ce;font-size:1.25rem;">{{ number_format($gP_p) }}</div>
                        <div class="gender-label" style="color:#a855f7;">P</div>
                    </div>
                </div>
            </div>
            <div>
                <p style="font-size:.65rem;color:var(--clr-ink-3);margin-bottom:.4rem;">Terlapor</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:.4rem;">
                    <div class="gender-card" style="background:#eff6ff;border-color:#bfdbfe;">
                        <i class="fas fa-mars" style="color:#60a5fa;font-size:1rem;"></i>
                        <div class="gender-num" style="color:#1d4ed8;font-size:1.25rem;">{{ number_format($gL_t) }}</div>
                        <div class="gender-label" style="color:#3b82f6;">L</div>
                    </div>
                    <div class="gender-card" style="background:#fdf4ff;border-color:#e9d5ff;">
                        <i class="fas fa-venus" style="color:#c084fc;font-size:1rem;"></i>
                        <div class="gender-num" style="color:#7e22ce;font-size:1.25rem;">{{ number_format($gP_t) }}</div>
                        <div class="gender-label" style="color:#a855f7;">P</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════
     ROW 4 — Top Unit per Kategori
═══════════════════════════════════════════ --}}
@if($chunks->isNotEmpty())
<div class="chart-row chart-row-2" style="margin-bottom:1.25rem;">
    @forelse($chunks as $chunkIndex => $unitsChunk)
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-building"></i> Top Unit / Fakultas
            @if($loop->first)
            <span class="sec-badge" style="background:#fef2f2;border-color:#fecaca;color:#991b1b;">TOP 10</span>
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
                    <div class="unit-bar-fill" style="width:{{ $pct }}%;background:{{ $katColorMap[$kategori] ?? '#ef4444' }};"></div>
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
     ROW 5 — Kategori Unit + Aktivitas Admin
     Page break saat print
═══════════════════════════════════════════ --}}
<div class="chart-row chart-row-3 section-break-before" style="margin-bottom:2rem;">

    {{-- Distribusi Kategori Unit --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-chart-pie"></i> Kategori Unit
            <span class="sec-badge">{{ $distribusiKategoriUnit->count() }} kategori</span>
        </div>
        @if($distribusiKategoriUnit->isEmpty())
        <div style="text-align:center;padding:3rem 0;color:var(--clr-ink-3);">
            <i class="fas fa-chart-pie" style="font-size:2.5rem;display:block;margin-bottom:.75rem;opacity:.35;"></i>
            <p style="font-size:.85rem;">Belum ada data</p>
        </div>
        @else
        <canvas id="chartKategoriUnit" width="160" height="160" style="margin:0 auto .875rem;"></canvas>
        <img id="imgKategoriUnit" class="chart-img-pr" alt="Grafik Kategori Unit" style="width:140px;height:140px;margin:0 auto .5rem;" />
        @foreach($distribusiKategoriUnit as $idx => $kat)
        <div class="kat-legend-item">
            <span class="kat-dot" style="background:{{ $katColors[$idx % count($katColors)] }};"></span>
            <span class="kat-name">{{ $kat->kategori_unit }}</span>
            <span class="kat-count">{{ number_format($kat->jumlah) }}</span>
        </div>
        @endforeach
        @endif
    </div>

    {{-- Aktivitas Admin / Penanganan --}}
    <div class="card anim-card" style="padding:1.375rem;">
        <div class="sec-header">
            <i class="fas fa-user-shield"></i> Aktivitas Penanganan Admin
            <span class="sec-badge">{{ $aktivitasAdmin->count() }} admin</span>
        </div>
        <div style="overflow-x:auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Admin</th>
                        <th style="text-align:center;">Total Laporan</th>
                        <th style="text-align:center;">Selesai</th>
                        <th>Completion Rate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($aktivitasAdmin as $i => $a)
                    <tr>
                        <td>
                            <span class="rank-badge" style="{{ $i===0?'background:#fef9c3;color:#a16207;':($i===1?'background:#f1f5f9;color:#475569;':($i===2?'background:#ffedd5;color:#c2410c;':'background:#f8fafc;color:#94a3b8;')) }}">
                                {{ $i + 1 }}
                            </span>
                        </td>
                        <td style="font-weight:600;color:var(--clr-ink-2);">{{ $a->nama }}</td>
                        <td style="text-align:center;font-family:var(--ff-mono);font-weight:500;color:var(--clr-ink);">{{ number_format($a->total_laporan) }}</td>
                        <td style="text-align:center;font-family:var(--ff-mono);font-weight:500;color:var(--clr-green);">{{ number_format($a->total_selesai) }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:.625rem;">
                                <div class="rate-bar">
                                    <div class="rate-fill"
                                         style="width:{{ $a->completion_rate }}%;
                                                background:{{ $a->completion_rate >= 70 ? '#059669' : ($a->completion_rate >= 40 ? '#d97706' : '#e11d48') }};
                                                -webkit-print-color-adjust:exact;print-color-adjust:exact;">
                                    </div>
                                </div>
                                <span style="font-family:var(--ff-mono);font-size:.75rem;font-weight:500;color:var(--clr-ink-2);width:2.75rem;text-align:right;">
                                    {{ $a->completion_rate }}%
                                </span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:2.5rem;color:var(--clr-ink-3);">
                            <i class="fas fa-inbox" style="font-size:2rem;display:block;margin-bottom:.5rem;opacity:.4;"></i>
                            Belum ada data admin
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Print footer --}}
<div class="print-footer" style="display:none;justify-content:space-between;align-items:center;font-size:.7rem;color:#94a3b8;">
    <div>
        <strong style="color:#475569;">SatgasP4GN</strong> · Statistik Pelaporan Narkoba
        · Periode: <strong style="color:#475569;">{{ $periodeLabel }}</strong>
        ({{ $from->format('d M Y') }} – {{ $to->format('d M Y') }})
    </div>
    <div>Dicetak: {{ $now->format('d M Y, H:i') }} WIB</div>
</div>

</div>{{-- /stat-page --}}

<div id="rt-toast" class="toast" role="status" aria-live="polite">
    <i class="fas fa-circle-check" style="color:#34d399;font-size:1rem;"></i>
    <span id="rt-msg">Data diperbarui</span>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.font.size   = 11;
Chart.defaults.color       = '#94a3b8';

const NARKOBA_COLORS = ['#ef4444','#f97316','#eab308','#22c55e','#3b82f6','#8b5cf6','#ec4899','#14b8a6'];
const KAT_COLORS     = ['#6366f1','#10b981','#f59e0b','#3b82f6','#ef4444','#8b5cf6','#14b8a6','#f97316'];
const PERAN_COLORS   = ['#6366f1','#f97316','#10b981','#ef4444'];

const CHART_IMG_MAP = {
    chartTren         : { imgId: 'imgTren',          maxH: 155 },
    chartNarkoba      : { imgId: 'imgNarkoba',        maxH: 140 },
    chartPeranPelapor : { imgId: 'imgPeranPelapor',   maxH: 125 },
    chartPeranTerlapor: { imgId: 'imgPeranTerlapor',  maxH: 125 },
    chartKategoriUnit : { imgId: 'imgKategoriUnit',   maxH: 140 },
};

const chartInstances = {};

/* ── Tren Bulanan ── */
const elTren = document.getElementById('chartTren');
if (elTren) {
    chartInstances.chartTren = new Chart(elTren, {
        type: 'line',
        data: {
            labels: {!! json_encode($trenLabels) !!},
            datasets: [{
                label: 'Laporan',
                data: {!! json_encode($trenValues) !!},
                borderColor: '#ef4444',
                backgroundColor: 'rgba(239,68,68,.08)',
                borderWidth: 2.5,
                pointBackgroundColor: '#ef4444',
                pointRadius: 4,
                pointHoverRadius: 6,
                tension: .35,
                fill: true,
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                x: { ticks: { maxRotation: 45, font: { size: 9 } }, grid: { display: false } },
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } }
            }
        }
    });
}

/* ── Narkoba Doughnut ── */
const elNark = document.getElementById('chartNarkoba');
if (elNark) {
    chartInstances.chartNarkoba = new Chart(elNark, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($distribusiNarkoba->pluck('nama_narkoba')->values()) !!},
            datasets: [{
                data: {!! json_encode($distribusiNarkoba->pluck('jumlah')->values()) !!},
                backgroundColor: NARKOBA_COLORS,
                borderWidth: 3, borderColor: '#fff', hoverOffset: 8
            }]
        },
        options: {
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID') + ' kasus' } }
            }
        }
    });
}

/* ── Peran Pelapor ── */
const elPelapor = document.getElementById('chartPeranPelapor');
if (elPelapor) {
    chartInstances.chartPeranPelapor = new Chart(elPelapor, {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribusiPeranPelapor->pluck('peran')->values()) !!},
            datasets: [{
                data: {!! json_encode($distribusiPeranPelapor->pluck('jumlah')->values()) !!},
                backgroundColor: PERAN_COLORS,
                borderRadius: 5, borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                y: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
}

/* ── Peran Terlapor ── */
const elTerlapor = document.getElementById('chartPeranTerlapor');
if (elTerlapor) {
    chartInstances.chartPeranTerlapor = new Chart(elTerlapor, {
        type: 'bar',
        data: {
            labels: {!! json_encode($distribusiPeranTerlapor->pluck('peran')->values()) !!},
            datasets: [{
                data: {!! json_encode($distribusiPeranTerlapor->pluck('jumlah')->values()) !!},
                backgroundColor: ['#e11d48','#f97316','#eab308','#8b5cf6'],
                borderRadius: 5, borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#f1f5f9' } },
                y: { grid: { display: false }, ticks: { font: { size: 10 } } }
            }
        }
    });
}

/* ── Kategori Unit Doughnut ── */
const elKatUnit = document.getElementById('chartKategoriUnit');
if (elKatUnit) {
    chartInstances.chartKategoriUnit = new Chart(elKatUnit, {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($distribusiKategoriUnit->pluck('kategori_unit')->values()) !!},
            datasets: [{
                data: {!! json_encode($distribusiKategoriUnit->pluck('jumlah')->values()) !!},
                backgroundColor: KAT_COLORS,
                borderWidth: 3, borderColor: '#fff', hoverOffset: 8
            }]
        },
        options: {
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: { callbacks: { label: ctx => ' ' + ctx.label + ': ' + ctx.parsed.toLocaleString('id-ID') + ' laporan' } }
            }
        }
    });
}

/* ── Cetak PDF ── */
function cetakPDF() {
    // Semua canvas ter-render dulu, baru konversi
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            const conversions = Object.entries(CHART_IMG_MAP).map(([canvasId, cfg]) => {
                return new Promise((resolve) => {
                    const canvas = document.getElementById(canvasId);
                    const img    = document.getElementById(cfg.imgId);

                    if (!canvas || !img) return resolve();

                    // Jika chart belum ter-render (tinggi 0), skip
                    if (canvas.width === 0 || canvas.height === 0) return resolve();

                    try {
                        const dataUrl = canvas.toDataURL('image/png', 1.0);
                        // Validasi: pastikan bukan canvas kosong
                        if (dataUrl && dataUrl !== 'data:,') {
                            img.src = dataUrl;
                        }
                    } catch (e) {
                        console.warn('Gagal convert canvas:', canvasId, e);
                    }

                    img.style.maxHeight = cfg.maxH + 'px';
                    img.style.width     = '100%';
                    img.style.height    = 'auto';
                    img.style.objectFit = 'contain';

                    // Tunggu gambar ter-load sebelum lanjut
                    if (img.complete) {
                        resolve();
                    } else {
                        img.onload  = resolve;
                        img.onerror = resolve;
                    }
                });
            });

            Promise.all(conversions).then(() => {
                window.print();
            });
        });
    });
}

/* ── Filter periode ── */
function gantiPeriode(periode) {
    document.querySelectorAll('.pill').forEach(el => {
        el.classList.toggle('active', el.dataset.p === periode);
    });
    showToast('Memuat data ' + periode + '…');
    window.location.href = '{{ route("admin.statistik.laporan") }}?periode=' + periode;
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