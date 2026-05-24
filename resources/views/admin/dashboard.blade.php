@extends('layouts.admin')

@section('title', 'Dashboard')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Bricolage+Grotesque:wght@600;700;800&display=swap" rel="stylesheet">
<style>
    /* ===== BASE ===== */
    body { font-family: 'DM Sans', sans-serif; }

    /* ===== VARIABLES ===== */
    :root {
        --blue-50:   #e6f1fb; --blue-100: #b5d4f4;
        --blue-400:  #378add; --blue-600: #185fa5;
        --teal-50:   #e1f5ee; --teal-100: #9fe1cb;
        --teal-400:  #1d9e75; --teal-600: #0f6e56;
        --green-50:  #eaf3de; --green-100: #c0dd97;
        --green-400: #639922; --green-600: #3b6d11;
        --amber-400: #e8970a; --amber-600: #854f0b;
        --rose-400:  #d4537e; --rose-600: #993556;
        --card-bg:   #ffffff;
        --page-bg:   #f4f6f9;
        --text:      #1e293b;
        --muted:     #64748b;
        --border:    #e5e7eb;
        --radius:    14px;
        --shadow:    0 1px 3px rgba(0,0,0,.06), 0 4px 14px rgba(0,0,0,.04);
    }

    /* ===== LAYOUT ===== */
    .db-wrap  { padding: 24px; background: var(--page-bg); min-height: 100vh; }
    .db-grid  { display: grid; grid-template-columns: repeat(12, 1fr); gap: 16px; }
    .col-12   { grid-column: span 12; }
    .col-8    { grid-column: span 8; }
    .col-7    { grid-column: span 7; }
    .col-6    { grid-column: span 6; }
    .col-5    { grid-column: span 5; }
    .col-4    { grid-column: span 4; }
    .col-3    { grid-column: span 3; }

    /* ===== TOPBAR ===== */
    .topbar {
        background: linear-gradient(135deg, #0f4c75 0%, #1a6fa8 55%, #0b8457 100%);
        border-radius: var(--radius);
        padding: 20px 26px;
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 16px;
    }
    .topbar h1 {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 22px; font-weight: 800; color: #fff; margin: 0;
    }
    .topbar p { font-size: 12px; color: rgba(255,255,255,.6); margin: 3px 0 0; }
    .topbar-badges { display: flex; gap: 8px; align-items: center; }
    .topbar-badge {
        background: rgba(255,255,255,.15);
        border: 1px solid rgba(255,255,255,.25);
        border-radius: 99px; padding: 5px 14px;
        font-size: 11px; color: rgba(255,255,255,.85); font-weight: 500;
        display: flex; align-items: center; gap: 6px;
    }
    .live-dot { width: 7px; height: 7px; border-radius: 50%; background: #4ade80; }

    /* ===== CARD ===== */
    .card {
        background: var(--card-bg);
        border-radius: var(--radius);
        box-shadow: var(--shadow);
        border: 0.5px solid var(--border);
        overflow: hidden;
    }
    .card-header {
        padding: 16px 18px 0;
        display: flex; justify-content: space-between; align-items: center;
    }
    .card-title {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 13px; font-weight: 700; color: var(--text);
        display: flex; align-items: center; gap: 6px;
    }
    .card-pill {
        font-family: 'DM Sans', sans-serif;
        font-size: 10px; font-weight: 500; padding: 3px 10px;
        border-radius: 99px; background: #f1f5f9; color: var(--muted);
        border: 0.5px solid var(--border);
    }
    .card-body { padding: 14px 18px 18px; }

    /* ===== KPI CARDS ===== */
    .kpi-card {
        padding: 18px 20px;
        border-top: 3px solid transparent;
        display: flex; flex-direction: column; gap: 4px;
    }
    .kpi-card.blue  { border-top-color: #1a6fa8; }
    .kpi-card.teal  { border-top-color: #0b8457; }
    .kpi-card.amber { border-top-color: var(--amber-400); }
    .kpi-card.rose  { border-top-color: var(--rose-400); }
    .kpi-label {
        font-size: 10px; font-weight: 600; text-transform: uppercase;
        letter-spacing: .07em; color: var(--muted);
    }
    .kpi-num {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 38px; font-weight: 800; line-height: 1;
    }
    .kpi-num.blue  { color: #185fa5; }
    .kpi-num.teal  { color: var(--teal-600); }
    .kpi-num.amber { color: var(--amber-600); }
    .kpi-num.rose  { color: var(--rose-600); }
    .kpi-sub { font-size: 11px; color: var(--muted); }

    /* ===== USER BREAKDOWN BOXES ===== */
    .ub-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .ub-box  { border-radius: 10px; padding: 12px 14px; display: flex; flex-direction: column; gap: 3px; }
    .ub-box.blue  { background: var(--blue-50);  border: 1px solid var(--blue-100); }
    .ub-box.green { background: var(--green-50); border: 1px solid var(--green-100); }
    .ub-box-num {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 28px; font-weight: 800; line-height: 1;
    }
    .ub-box-num.blue  { color: var(--blue-600); }
    .ub-box-num.green { color: var(--green-600); }
    .ub-box-lbl {
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em;
    }
    .ub-box-lbl.blue  { color: var(--blue-400); }
    .ub-box-lbl.green { color: var(--green-400); }
    .ub-box-pct { font-size: 11px; color: var(--muted); }

    /* ===== FILTER TABS ===== */
    .filter-tabs {
        display: flex; gap: 3px;
        background: #f1f5f9; border-radius: 8px; padding: 3px;
    }
    .ftab {
        padding: 4px 12px; border-radius: 6px;
        font-size: 11px; font-weight: 600;
        border: none; cursor: pointer;
        background: transparent; color: var(--muted); transition: all .15s;
    }
    .ftab.active {
        background: #fff; color: var(--text);
        border: 0.5px solid var(--border);
        box-shadow: 0 1px 3px rgba(0,0,0,.07);
    }

    /* ===== PROGRESS BAR ===== */
    .prog-track { background: #e5e7eb; border-radius: 99px; height: 7px; overflow: hidden; }
    .prog-fill  { height: 100%; border-radius: 99px; transition: width .6s cubic-bezier(.4,0,.2,1); }

    /* ===== BAR ROWS ===== */
    .bar-list { display: flex; flex-direction: column; gap: 10px; }
    .bar-item { display: flex; align-items: center; gap: 8px; }
    .bar-item-lbl {
        font-size: 11px; color: var(--muted); width: 90px;
        flex-shrink: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .bar-item-val { font-size: 11px; font-weight: 700; color: var(--text); width: 18px; text-align: right; }

    /* ===== KATEGORI UNIT CHIPS ===== */
    .kat-grid { display: flex; gap: 8px; margin-top: 12px; }
    .kat-chip { flex: 1; text-align: center; padding: 10px 8px; border-radius: 10px; }
    .kat-chip.akademik { background: var(--blue-50);  border: 1px solid var(--blue-100); }
    .kat-chip.admin    { background: var(--green-50); border: 1px solid var(--green-100); }
    .kat-chip.kosong   { background: #f8fafc; border: 1px solid var(--border); }
    .kat-num {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 22px; font-weight: 800;
    }
    .kat-num.akademik { color: var(--blue-600); }
    .kat-num.admin    { color: var(--green-600); }
    .kat-num.kosong   { color: var(--muted); }
    .kat-lbl {
        font-size: 9px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .05em; margin-top: 1px;
    }
    .kat-lbl.akademik { color: var(--blue-400); }
    .kat-lbl.admin    { color: var(--green-400); }
    .kat-lbl.kosong   { color: var(--muted); }

    /* ===== JENIS KELAMIN ===== */
    .jk-chips { display: flex; flex-direction: column; gap: 8px; }
    .jk-chip  { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px; }
    .jk-chip.lk { background: var(--blue-50);  border: 1px solid var(--blue-100); }
    .jk-chip.pr { background: #fbeaf0; border: 1px solid #f4c0d1; }
    .jk-chip-icon { font-size: 20px; }
    .jk-chip-num  {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 20px; font-weight: 800;
    }
    .jk-chip-num.lk { color: var(--blue-600); }
    .jk-chip-num.pr { color: var(--rose-600); }
    .jk-chip-lbl {
        font-size: 10px; font-weight: 700;
        text-transform: uppercase; letter-spacing: .04em;
    }
    .jk-chip-lbl.lk { color: var(--blue-400); }
    .jk-chip-lbl.pr { color: var(--rose-400); }
    .jk-split-bar   { display: flex; height: 8px; border-radius: 99px; overflow: hidden; margin-top: 12px; gap: 2px; }
    .jk-split-lk    { background: var(--blue-400); border-radius: 99px 0 0 99px; }
    .jk-split-pr    { background: var(--rose-400); border-radius: 0 99px 99px 0; }
    .jk-split-lbl   { display: flex; justify-content: space-between; margin-top: 4px; font-size: 10px; color: var(--muted); }

    /* ===== TOP 5 KONSELOR ===== */
    .top5 { display: flex; flex-direction: column; gap: 11px; }
    .top5-row { display: flex; align-items: center; gap: 10px; }
    .rank-badge {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 16px; font-weight: 800; width: 22px; text-align: center; flex-shrink: 0;
    }
    .rank-badge.r1 { color: #d97706; }
    .rank-badge.r2 { color: #9ca3af; }
    .rank-badge.r3 { color: #c2682b; }
    .rank-badge.rn { color: var(--muted); font-size: 13px; }
    .rank-name { font-size: 12px; font-weight: 600; color: var(--text); }
    .rank-sesi {
        font-family: 'Bricolage Grotesque', sans-serif;
        font-size: 15px; font-weight: 800; color: var(--text);
    }

    /* ===== LEGEND ===== */
    .chart-legend { display: flex; flex-wrap: wrap; gap: 8px 16px; margin-top: 10px; }
    .legend-item  { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--muted); }
    .legend-dot   { width: 9px; height: 9px; border-radius: 2px; flex-shrink: 0; }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1100px) {
        .col-8, .col-7, .col-5 { grid-column: span 12; }
        .col-4 { grid-column: span 6; }
    }
    @media (max-width: 768px) {
        .col-3, .col-4, .col-6 { grid-column: span 12; }
        .db-wrap { padding: 12px; }
    }
</style>
@endpush

@section('content')
<div class="db-wrap">

    {{-- ===== TOPBAR ===== --}}
    <div class="topbar">
        <div>
            <h1>Dashboard <span style="opacity:.55;font-weight:600">Satgas P4GN</span></h1>
            <p>Universitas Lampung · Sistem Informasi Konseling & Pelaporan</p>
        </div>
        <div class="topbar-badges">
            <div class="topbar-badge"><span class="live-dot"></span> Live Data</div>
            <div class="topbar-badge">{{ \Carbon\Carbon::now()->isoFormat('D MMM YYYY') }}</div>
        </div>
    </div>

    <div class="db-grid">

        {{-- ===== 4 KPI CARDS ===== --}}
        @php
            $jmlKonsuli  = $userBreakdown['konsuli']  ?? 0;
            $jmlKonselor = $userBreakdown['konselor'] ?? 0;
            $totalUser   = $jmlKonsuli + $jmlKonselor;
            $totalSesi   = DB::table('konseling_sessions')->count();
        @endphp

        <div class="card col-3 kpi-card blue">
            <div class="kpi-label">Total Laporan</div>
            <div class="kpi-num blue">{{ number_format($totalLaporan) }}</div>
            <div class="kpi-sub">Semua laporan masuk</div>
        </div>

        <div class="card col-3 kpi-card teal">
            <div class="kpi-label">Total Sesi</div>
            <div class="kpi-num teal">{{ $totalSesi }}</div>
            <div class="kpi-sub">Sesi konseling tercatat</div>
        </div>

        <div class="card col-3 kpi-card amber">
            <div class="kpi-label">Konsuli</div>
            <div class="kpi-num amber">{{ $jmlKonsuli }}</div>
            <div class="kpi-sub">Pengguna terdaftar</div>
        </div>

        <div class="card col-3 kpi-card rose">
            <div class="kpi-label">Konselor</div>
            <div class="kpi-num rose">{{ $jmlKonselor }}</div>
            <div class="kpi-sub">Konselor aktif</div>
        </div>

        {{-- ===== SESI KONSELING INTERAKTIF ===== --}}
        <div class="card col-8">
            <div class="card-header">
                <div class="card-title">Sesi Konseling</div>
                <div class="filter-tabs">
                    <button class="ftab active" data-mode="mingguan">Mingguan</button>
                    <button class="ftab" data-mode="bulanan">Bulanan</button>
                    <button class="ftab" data-mode="tahunan">Tahunan</button>
                </div>
            </div>
            <div class="card-body">
                <canvas id="chartSesiKonseling" style="width:100%;height:200px"></canvas>
            </div>
        </div>

        {{-- ===== PENGGUNA SISTEM ===== --}}
        <div class="card col-4">
            <div class="card-header">
                <div class="card-title">Pengguna Sistem</div>
            </div>
            <div class="card-body">
                <div class="ub-grid">
                    <div class="ub-box blue">
                        <div class="ub-box-num blue">{{ $jmlKonsuli }}</div>
                        <div class="ub-box-lbl blue">Konsuli</div>
                        <div class="ub-box-pct">{{ $totalUser > 0 ? round($jmlKonsuli/$totalUser*100) : 0 }}% dari total</div>
                    </div>
                    <div class="ub-box green">
                        <div class="ub-box-num green">{{ $jmlKonselor }}</div>
                        <div class="ub-box-lbl green">Konselor</div>
                        <div class="ub-box-pct">{{ $totalUser > 0 ? round($jmlKonselor/$totalUser*100) : 0 }}% dari total</div>
                    </div>
                </div>
                <div style="position:relative;height:130px;margin-top:12px">
                    <canvas id="chartUserBreakdown"></canvas>
                </div>
            </div>
        </div>

        {{-- ===== KONSULI PER STATUS SIVITAS ===== --}}
        <div class="card col-5">
            <div class="card-header">
                <div class="card-title">
                    Konsuli per Status Sivitas
                    <span class="card-pill">Role breakdown</span>
                </div>
            </div>
            <div class="card-body">
                <div style="position:relative;height:160px">
                    <canvas id="chartUserRole"></canvas>
                </div>
                <div class="chart-legend" id="legendUserRole" style="justify-content:center;margin-top:12px"></div>
            </div>
        </div>

        {{-- ===== KONSULI PER UNIT + KATEGORI ===== --}}
        <div class="card col-7">
            <div class="card-header">
                <div class="card-title">Konsuli Berdasarkan Unit</div>
            </div>
            <div class="card-body">
                @php
                    $maxUnit    = $konsulPerUnit->max() ?: 1;
                    $unitColors = ['#1a6fa8','#0b8457','#e8970a','#d4537e','#8b5cf6','#14b8a6'];
                    $ci = 0;
                @endphp
                <div class="bar-list">
                    @forelse($konsulPerUnit as $unit => $jml)
                    @php $pct = round($jml / $maxUnit * 100); $clr = $unitColors[$ci % count($unitColors)]; $ci++; @endphp
                    <div class="bar-item">
                        <div class="bar-item-lbl" title="{{ $unit }}">{{ $unit }}</div>
                        <div class="prog-track" style="flex:1">
                            <div class="prog-fill" style="width:{{ $pct }}%;background:{{ $clr }}"></div>
                        </div>
                        <div class="bar-item-val">{{ $jml }}</div>
                    </div>
                    @empty
                    <div style="font-size:12px;color:var(--muted);padding:10px 0">Belum ada data</div>
                    @endforelse
                </div>

                <div style="border-top:0.5px solid var(--border);margin-top:14px;padding-top:12px">
                    <div style="font-size:10px;font-weight:700;color:var(--muted);margin-bottom:8px;text-transform:uppercase;letter-spacing:.05em">Konseling per Kategori Unit</div>
                    <div class="kat-grid">
                        <div class="kat-chip akademik">
                            <div class="kat-num akademik">{{ $konselingPerKategoriUnit['Akademik'] ?? 0 }}</div>
                            <div class="kat-lbl akademik">Akademik</div>
                        </div>
                        <div class="kat-chip admin">
                            <div class="kat-num admin">{{ $konselingPerKategoriUnit['Administrasi'] ?? 0 }}</div>
                            <div class="kat-lbl admin">Administrasi</div>
                        </div>
                        <div class="kat-chip kosong">
                            <div class="kat-num kosong">{{ $konselingPerKategoriUnit['Belum Diisi'] ?? 0 }}</div>
                            <div class="kat-lbl kosong">Belum Diisi</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== DISTRIBUSI JENIS KELAMIN ===== --}}
        <div class="card col-4">
            <div class="card-header">
                <div class="card-title">
                    Distribusi Jenis Kelamin
                    <span class="card-pill">Pengguna konseling</span>
                </div>
            </div>
            <div class="card-body">
                @php
                    $jkLk  = $jenisKelaminKonseling['Laki-laki'] ?? 0;
                    $jkPr  = $jenisKelaminKonseling['Perempuan'] ?? 0;
                    $jkTot = $jkLk + $jkPr;
                @endphp
                <div style="display:flex;gap:14px;align-items:center">
                    <div style="position:relative;width:110px;height:110px;flex-shrink:0">
                        <canvas id="chartJK"></canvas>
                    </div>
                    <div class="jk-chips" style="flex:1">
                        <div class="jk-chip lk">
                            <span class="jk-chip-icon">👨</span>
                            <div>
                                <div class="jk-chip-num lk">{{ $jkLk }}</div>
                                <div class="jk-chip-lbl lk">Laki-laki</div>
                            </div>
                        </div>
                        <div class="jk-chip pr">
                            <span class="jk-chip-icon">👩</span>
                            <div>
                                <div class="jk-chip-num pr">{{ $jkPr }}</div>
                                <div class="jk-chip-lbl pr">Perempuan</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="jk-split-bar">
                    <div class="jk-split-lk" style="flex:{{ $jkLk ?: 1 }}"></div>
                    <div class="jk-split-pr" style="flex:{{ $jkPr ?: 1 }}"></div>
                </div>
                <div class="jk-split-lbl">
                    <span>{{ $jkTot > 0 ? round($jkLk/$jkTot*100) : 0 }}% Laki-laki</span>
                    <span>{{ $jkTot > 0 ? round($jkPr/$jkTot*100) : 0 }}% Perempuan</span>
                </div>
            </div>
        </div>

        {{-- ===== TOP 5 KONSELOR ===== --}}
        <div class="card col-4">
            <div class="card-header">
                <div class="card-title">Top 5 Konselor <span class="card-pill">Beban sesi</span></div>
            </div>
            <div class="card-body">
                @php $maxSesi = $topKonselor->max('total_sesi') ?: 1; @endphp
                <div class="top5">
                    @forelse($topKonselor as $i => $k)
                    @php
                        $rankClass = $i === 0 ? 'r1' : ($i === 1 ? 'r2' : ($i === 2 ? 'r3' : 'rn'));
                        $rankIcon  = $i === 0 ? '🥇' : ($i === 1 ? '🥈' : ($i === 2 ? '🥉' : ($i+1)));
                    @endphp
                    <div class="top5-row">
                        <div class="rank-badge {{ $rankClass }}">{{ $rankIcon }}</div>
                        <div style="flex:1">
                            <div style="display:flex;justify-content:space-between;align-items:center">
                                <span class="rank-name">{{ $k->nama }}</span>
                                <span class="rank-sesi">{{ $k->total_sesi }}</span>
                            </div>
                            <div class="prog-track" style="margin-top:4px">
                                <div class="prog-fill" style="width:{{ round($k->total_sesi/$maxSesi*100) }}%;background:linear-gradient(90deg,#1a6fa8,#0b8457)"></div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div style="font-size:12px;color:var(--muted);text-align:center;padding:16px 0">Belum ada data</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- ===== PERTUMBUHAN KONSULI BARU ===== --}}
        <div class="card col-4">
            <div class="card-header">
                <div class="card-title">Pertumbuhan Konsuli Baru</div>
            </div>
            <div class="card-body">
                <canvas id="chartPertumbuhan" style="width:100%;height:160px"></canvas>
            </div>
        </div>

        {{-- ===== LAPORAN JENIS KASUS (DIPERTAHANKAN) ===== --}}
        <div class="card col-6">
            <div class="card-header">
                <div class="card-title">Laporan — Jenis Kasus</div>
            </div>
            <div class="card-body">
                <canvas id="chartKasus" style="width:100%;height:160px"></canvas>
            </div>
        </div>

        {{-- ===== SESI KONSELING PER BULAN TAHUN INI (DIPERTAHANKAN) ===== --}}
        <div class="card col-6">
            <div class="card-header">
                <div class="card-title">Sesi Konseling Tahun Ini</div>
            </div>
            <div class="card-body">
                <canvas id="chartKonselingBulan" style="width:100%;height:160px"></canvas>
            </div>
        </div>

    </div>{{-- end .db-grid --}}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
/* ─── WARNA ─────────────────────────────────────────────── */
const C = {
    blue:   '#1a6fa8',
    teal:   '#0b8457',
    amber:  '#e8970a',
    rose:   '#d4537e',
    sky:    '#378add',
    green:  '#639922',
    purple: '#8b5cf6',
    gray:   '#9ca3af',
};
const PALETTE = Object.values(C);
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.color = '#64748b';
const GRID = 'rgba(0,0,0,.05)';

/* ─── USER BREAKDOWN DOUGHNUT ────────────────────────────── */
new Chart(document.getElementById('chartUserBreakdown'), {
    type: 'doughnut',
    data: {
        labels: ['Konsuli', 'Konselor'],
        datasets: [{
            data: [{{ $jmlKonsuli }}, {{ $jmlKonselor }}],
            backgroundColor: [C.blue, C.teal],
            borderWidth: 0, hoverOffset: 4
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.label}: ${c.parsed}` } }
        }
    }
});

/* ─── ROLE KONSULI PIE ───────────────────────────────────── */
const roleLabels = @json($userRole->keys());
const roleData   = @json($userRole->values());
new Chart(document.getElementById('chartUserRole'), {
    type: 'doughnut',
    data: {
        labels: roleLabels,
        datasets: [{
            data: roleData,
            backgroundColor: PALETTE,
            borderWidth: 0, hoverOffset: 4
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        cutout: '60%',
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.label}: ${c.parsed}` } }
        }
    },
    plugins: [{
        id: 'buildLegend',
        afterRender(chart) {
            const el = document.getElementById('legendUserRole');
            if (el.children.length) return;
            chart.data.labels.forEach((lbl, i) => {
                const d = document.createElement('div');
                d.className = 'legend-item';
                d.innerHTML = `<span class="legend-dot" style="background:${PALETTE[i]}"></span>${lbl} (${roleData[i]})`;
                el.appendChild(d);
            });
        }
    }]
});

/* ─── JENIS KELAMIN PIE ──────────────────────────────────── */
const jkLabels = @json($jenisKelaminKonseling->keys());
const jkData   = @json($jenisKelaminKonseling->values());
new Chart(document.getElementById('chartJK'), {
    type: 'pie',
    data: {
        labels: jkLabels,
        datasets: [{
            data: jkData,
            backgroundColor: [C.sky, C.rose],
            borderWidth: 0, hoverOffset: 5
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: { callbacks: { label: c => ` ${c.label}: ${c.parsed}` } }
        }
    }
});

/* ─── PERTUMBUHAN KONSULI LINE ───────────────────────────── */
const tumbuhLabels = @json($pertumbuhanKonsuli->keys());
const tumbuhData   = @json($pertumbuhanKonsuli->values());
new Chart(document.getElementById('chartPertumbuhan'), {
    type: 'line',
    data: {
        labels: tumbuhLabels,
        datasets: [{
            label: 'Konsuli Baru',
            data: tumbuhData,
            borderColor: C.teal,
            backgroundColor: 'rgba(11,132,87,.08)',
            tension: 0.4, fill: true,
            pointBackgroundColor: C.teal,
            pointRadius: 4, pointHoverRadius: 6,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { maxRotation: 30 } },
            y: { grid: { color: GRID }, ticks: { stepSize: 1, precision: 0 }, border: { display: false } }
        }
    }
});

/* ─── LAPORAN JENIS KASUS BAR ────────────────────────────── */
const kasusIni  = @json($kasusBulanIni);
const kasusLalu = @json($kasusBulanLalu);
const kasusKeys = [...new Set([...Object.keys(kasusIni), ...Object.keys(kasusLalu)])];
new Chart(document.getElementById('chartKasus'), {
    type: 'bar',
    data: {
        labels: kasusKeys,
        datasets: [
            {
                label: 'Bulan Ini',
                data: kasusKeys.map(k => kasusIni[k] || 0),
                backgroundColor: C.blue, borderRadius: 6
            },
            {
                label: 'Bulan Lalu',
                data: kasusKeys.map(k => kasusLalu[k] || 0),
                backgroundColor: 'rgba(26,111,168,.2)', borderRadius: 6
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { position: 'top', labels: { boxWidth: 10, padding: 12 } } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: GRID }, ticks: { stepSize: 1, precision: 0 }, border: { display: false } }
        }
    }
});

/* ─── SESI PER BULAN TAHUN INI ───────────────────────────── */
@php
    $bulanLabels = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    $konselingBulanArr = [];
    for ($b = 1; $b <= 12; $b++) {
        $konselingBulanArr[] = $konselingPerBulan[$b] ?? 0;
    }
@endphp
new Chart(document.getElementById('chartKonselingBulan'), {
    type: 'bar',
    data: {
        labels: @json($bulanLabels),
        datasets: [{
            label: 'Sesi',
            data: @json($konselingBulanArr),
            backgroundColor: C.sky, borderRadius: 6,
            hoverBackgroundColor: C.blue,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: GRID }, ticks: { stepSize: 1, precision: 0 }, border: { display: false } }
        }
    }
});

/* ─── SESI KONSELING INTERAKTIF ──────────────────────────── */
const rawSesi = @json($konselingRawData);

function aggregateData(rawData, mode) {
    const map = {};
    rawData.forEach(row => {
        const d = new Date(row.tanggal);
        let key;
        if (mode === 'tahunan') {
            key = String(d.getFullYear());
        } else if (mode === 'bulanan') {
            key = `${d.getFullYear()}-${String(d.getMonth()+1).padStart(2,'0')}`;
        } else {
            const startOfYear = new Date(d.getFullYear(), 0, 1);
            const weekNo = Math.ceil(((d - startOfYear) / 86400000 + startOfYear.getDay() + 1) / 7);
            key = `${d.getFullYear()}-W${String(weekNo).padStart(2,'0')}`;
        }
        map[key] = (map[key] || 0) + row.total;
    });
    const sorted = Object.entries(map).sort((a, b) => a[0].localeCompare(b[0]));
    return { labels: sorted.map(e => e[0]), data: sorted.map(e => e[1]) };
}

let { labels: sesiL, data: sesiD } = aggregateData(rawSesi, 'mingguan');

const sesiChart = new Chart(document.getElementById('chartSesiKonseling'), {
    type: 'bar',
    data: {
        labels: sesiL,
        datasets: [{
            label: 'Sesi Konseling',
            data: sesiD,
            backgroundColor: C.sky,
            borderRadius: 6,
            hoverBackgroundColor: C.blue,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { autoSkip: false, maxRotation: 30 } },
            y: { grid: { color: GRID }, ticks: { stepSize: 1, precision: 0 }, border: { display: false } }
        }
    }
});

document.querySelectorAll('.ftab').forEach(btn => {
    btn.addEventListener('click', function () {
        document.querySelectorAll('.ftab').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        const { labels, data } = aggregateData(rawSesi, this.dataset.mode);
        sesiChart.data.labels = labels;
        sesiChart.data.datasets[0].data = data;
        sesiChart.update();
    });
});
</script>
@endpush