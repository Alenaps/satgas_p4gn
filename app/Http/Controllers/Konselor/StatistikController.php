<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StatistikController extends Controller
{
    /**
     * Resolve date range based on period selector.
     * Periods: harian | mingguan | bulanan | tahunan
     */
    private function resolveDateRange(string $periode): array
    {
        $now = Carbon::now();

        return match ($periode) {
            'harian'   => [$now->copy()->startOfDay(),  $now->copy()->endOfDay()],
            'mingguan' => [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()],
            'bulanan'  => [$now->copy()->startOfMonth(),$now->copy()->endOfMonth()],
            'tahunan'  => [$now->copy()->startOfYear(), $now->copy()->endOfYear()],
            default    => [$now->copy()->startOfMonth(),$now->copy()->endOfMonth()],
        };
    }

    /**
     * Human-readable label for each period.
     */
    private function periodLabel(string $periode, Carbon $from, Carbon $to): string
    {
        return match ($periode) {
            'harian'   => $from->translatedFormat('l, d F Y'),
            'mingguan' => $from->translatedFormat('d F Y') . ' – ' . $to->translatedFormat('d F Y'),
            'bulanan'  => $from->translatedFormat('F Y'),
            'tahunan'  => $from->format('Y'),
            default    => $from->translatedFormat('F Y'),
        };
    }

    public function konseling(Request $request)
    {
        $konselorId = Auth::id();

        // ── PERIODE ─────────────────────────────────────────
        $periode    = $request->get('periode', 'bulanan');
        $validPeriod = ['harian', 'mingguan', 'bulanan', 'tahunan'];
        if (! in_array($periode, $validPeriod)) {
            $periode = 'bulanan';
        }

        [$dateFrom, $dateTo] = $this->resolveDateRange($periode);
        $labelPeriode        = $this->periodLabel($periode, $dateFrom, $dateTo);
        $downloadedAt        = now()->translatedFormat('d F Y, H:i') . ' WIB';

        // Helper closure: apply period scope to a query builder
        $inPeriod = fn($q) => $q->whereBetween('konseling_sessions.created_at', [$dateFrom, $dateTo]);

        // ══════════════════════════════════════════
        // 1. SCORECARD
        // ══════════════════════════════════════════
        $base = fn() => DB::table('konseling_sessions')->where('konselor_id', $konselorId);

        $totalPending = (clone $base())
            ->where('status', 'pending')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $totalActive = (clone $base())
            ->where('status', 'active')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $unreadMessages = DB::table('konseling_messages')
            ->join('konseling_sessions', 'konseling_messages.session_id', '=', 'konseling_sessions.id')
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->where('konseling_sessions.status', 'active')
            ->where('konseling_messages.sender_id', '!=', $konselorId)
            ->where('konseling_messages.is_read', 0)
            ->whereBetween('konseling_sessions.created_at', [$dateFrom, $dateTo])
            ->count();

        $totalCompleted = (clone $base())
            ->where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        // ══════════════════════════════════════════
        // 2. PERFORMA PRIBADI
        // ══════════════════════════════════════════
        $rataRataDurasi = DB::table('konseling_sessions')
            ->where('konselor_id', $konselorId)
            ->where('status', 'completed')
            ->whereNotNull('started_at')
            ->whereNotNull('ended_at')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, started_at, ended_at)) as rata_menit'))
            ->value('rata_menit');

        $totalDiterima = (clone $base())
            ->whereIn('status', ['active', 'completed'])
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $totalDitolak = (clone $base())
            ->where('status', 'rejected')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        // ══════════════════════════════════════════
        // 3. DEMOGRAFI KLIEN
        // ══════════════════════════════════════════
        $distribusiSivitas = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('status_sivitas', 'konseli.status_sivitas_id', '=', 'status_sivitas.id')
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->whereBetween('konseling_sessions.created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('COALESCE(status_sivitas.nama, "Tidak Diketahui") as nama'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw('COALESCE(status_sivitas.nama, "Tidak Diketahui")'))
            ->get();

        $distribusiUnit = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('units', 'konseli.unit_id', '=', 'units.id')
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->whereBetween('konseling_sessions.created_at', [$dateFrom, $dateTo])
            ->select(
                DB::raw('COALESCE(units.nama_unit, "Tidak Diketahui") as nama_unit'),
                DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui") as kategori_unit'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(
                DB::raw('COALESCE(units.nama_unit, "Tidak Diketahui")'),
                DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui")')
            )
            ->orderByDesc('jumlah')
            ->get();

        $genderRaw = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->whereBetween('konseling_sessions.created_at', [$dateFrom, $dateTo])
            ->select('konseli.jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->groupBy('konseli.jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $genderL = 0;
        $genderP = 0;
        foreach ($genderRaw as $key => $val) {
            $key = strtolower(trim((string)$key));
            if (in_array($key, ['l', 'laki-laki', 'laki laki', 'male', 'pria'])) {
                $genderL += $val;
            } elseif (in_array($key, ['p', 'perempuan', 'wanita', 'female'])) {
                $genderP += $val;
            }
        }
        $distribusiGender = ['L' => (int)$genderL, 'P' => (int)$genderP];

        // ══════════════════════════════════════════
        // 4. TREN & INTENSITAS
        // ══════════════════════════════════════════
        $totalSesiKonselor = (clone $base())
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->count();

        $totalPesanKonselor = DB::table('konseling_messages')
            ->join('konseling_sessions', 'konseling_messages.session_id', '=', 'konseling_sessions.id')
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->whereBetween('konseling_sessions.created_at', [$dateFrom, $dateTo])
            ->count();

        $rataRataPesan = $totalSesiKonselor > 0
            ? round($totalPesanKonselor / $totalSesiKonselor, 1)
            : 0;

        // Tren: granularity depends on period
        $trenFormat = match ($periode) {
            'harian'   => ['%H:00', 'Jam'],
            'mingguan' => ['%W-%Y', 'Minggu'],    // grouped by week number
            'tahunan'  => ['%Y-%m', 'Bulan'],
            default    => ['%Y-%m', 'Bulan'],      // bulanan → per month
        };

        // For 'harian', group by hour; otherwise by month
        $trenGroupBy = match ($periode) {
            'harian' => DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d %H")'),
            default  => DB::raw('DATE_FORMAT(created_at, "%Y-%m")'),
        };

        $trenLabelExpr = match ($periode) {
            'harian' => DB::raw('DATE_FORMAT(created_at, "%H:00") as bulan'),
            default  => DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
        };

        $trenBebanKerja = DB::table('konseling_sessions')
            ->where('konselor_id', $konselorId)
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->select($trenLabelExpr, DB::raw('COUNT(*) as jumlah'))
            ->whereNotNull('created_at')
            ->groupBy($trenGroupBy)
            ->orderBy($trenGroupBy)
            ->get();

        return view('konselor.statistik.konseling', compact(
            'totalPending',
            'totalActive',
            'unreadMessages',
            'totalCompleted',
            'rataRataDurasi',
            'totalDiterima',
            'totalDitolak',
            'distribusiSivitas',
            'distribusiUnit',
            'distribusiGender',
            'rataRataPesan',
            'totalSesiKonselor',
            'totalPesanKonselor',
            'trenBebanKerja',
            // period meta
            'periode',
            'labelPeriode',
            'downloadedAt',
            'dateFrom',
            'dateTo',
        ));
    }

    // ══════════════════════════════════════════════
    // STATISTIK LAPORAN
    // ══════════════════════════════════════════════

        private function getLaporanData(string $periode): array
    {
        [$from, $to] = $this->resolveDateRange($periode);
 
        // ── 1. METRIK UTAMA ──────────────────────────────────
 
        $totalLaporan = DB::table('laporans')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->count();
 
        // Distribusi status laporan
        $distribusiStatus = DB::table('laporans')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->pluck('jumlah', 'status');
 
        // Distribusi jenis kasus (Pengguna / Pengedar / Kurir / Bandar)
        $distribusiJenisKasus = DB::table('laporans')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->select('jenis_kasus', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('jenis_kasus')
            ->orderByDesc('jumlah')
            ->get();
 
        // ── 2. JENIS NARKOBA ─────────────────────────────────
 
        $distribusiNarkoba = DB::table('laporans')
            ->whereBetween('laporans.tanggal', [$from->toDateString(), $to->toDateString()])
            ->leftJoin('jenis_narkobas', 'laporans.jenis_narkoba_id', '=', 'jenis_narkobas.id')
            ->select(
                DB::raw('COALESCE(jenis_narkobas.nama, "Tidak Disebutkan") as nama_narkoba'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw('COALESCE(jenis_narkobas.nama, "Tidak Disebutkan")'))
            ->orderByDesc('jumlah')
            ->get();
 
        $topNarkoba = $distribusiNarkoba->first();
 
        // ── 3. PERAN PELAPOR & TERLAPOR ──────────────────────
 
        $distribusiPeranPelapor = DB::table('laporans')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->select('peran_pelapor as peran', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('peran_pelapor')
            ->orderByDesc('jumlah')
            ->get();
 
        $distribusiPeranTerlapor = DB::table('laporans')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->select('peran_terlapor as peran', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('peran_terlapor')
            ->orderByDesc('jumlah')
            ->get();
 
        // ── 4. GENDER PELAPOR & TERLAPOR ─────────────────────
 
        $genderPelapor = DB::table('laporans')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->select('jk_pelapor', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('jk_pelapor')
            ->pluck('jumlah', 'jk_pelapor');
 
        $genderTerlapor = DB::table('laporans')
            ->whereBetween('tanggal', [$from->toDateString(), $to->toDateString()])
            ->select('jk_terlapor', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('jk_terlapor')
            ->pluck('jumlah', 'jk_terlapor');
 
        // ── 5. DISTRIBUSI UNIT/FAKULTAS ──────────────────────
        // Rute: laporans.user_id → users.unit_id → units
 
        $distribusiUnit = DB::table('laporans')
            ->whereBetween('laporans.tanggal', [$from->toDateString(), $to->toDateString()])
            ->leftJoin('users', 'laporans.user_id', '=', 'users.id')
            ->leftJoin('units', 'users.unit_id', '=', 'units.id')
            ->select(
                DB::raw('COALESCE(units.nama_unit, "Tidak Diketahui") as nama_unit'),
                DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui") as kategori_unit'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(
                DB::raw('COALESCE(units.nama_unit, "Tidak Diketahui")'),
                DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui")')
            )
            ->orderByDesc('jumlah')
            ->limit(10)
            ->get();
 
        $distribusiKategoriUnit = DB::table('laporans')
            ->whereBetween('laporans.tanggal', [$from->toDateString(), $to->toDateString()])
            ->leftJoin('users', 'laporans.user_id', '=', 'users.id')
            ->leftJoin('units', 'users.unit_id', '=', 'units.id')
            ->select(
                DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui") as kategori_unit'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui")'))
            ->orderByDesc('jumlah')
            ->get();
 
        $topUnit = $distribusiUnit->first();
 
        // ── 6. TREN BULANAN (12 bulan terakhir) ──────────────
        // Selalu tampil 12 bulan terakhir terlepas dari filter periode
        $trenBulanan = DB::table('laporans')
            ->whereBetween('tanggal', [
                now()->subMonths(11)->startOfMonth()->toDateString(),
                now()->endOfMonth()->toDateString(),
            ])
            ->select(
                DB::raw('DATE_FORMAT(tanggal, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');
 
        // Isi bulan yang kosong dengan 0
        $trenLabels = [];
        $trenValues = [];
        for ($i = 11; $i >= 0; $i--) {
            $key = now()->subMonths($i)->format('Y-m');
            $trenLabels[] = now()->subMonths($i)->translatedFormat('M Y');
            $trenValues[] = $trenBulanan->get($key)?->jumlah ?? 0;
        }
 
        // ── 7. TINDAK LANJUT — waktu rata-rata penyelesaian ──
        $rataWaktuSelesai = DB::table('laporans')
            ->whereBetween('laporans.tanggal', [$from->toDateString(), $to->toDateString()])
            ->where('laporans.status', 'selesai')
            ->join('tindak_lanjuts', function ($join) {
                $join->on('tindak_lanjuts.laporan_id', '=', 'laporans.id')
                     ->where('tindak_lanjuts.status', 'selesai');
            })
            ->select(DB::raw('AVG(DATEDIFF(tindak_lanjuts.created_at, laporans.created_at)) as rata_hari'))
            ->value('rata_hari');
 
        // ── 8. KONSELOR PALING AKTIF ─────────────────────────────
        $aktivitasKonselor = DB::table('tindak_lanjuts')
            ->join('laporans', 'tindak_lanjuts.laporan_id', '=', 'laporans.id')
            ->whereBetween('laporans.tanggal', [$from->toDateString(), $to->toDateString()])
            ->whereNotNull('tindak_lanjuts.konselor_id')
            ->join('users as konselor', 'tindak_lanjuts.konselor_id', '=', 'konselor.id')
            ->select(
                'konselor.nama',
                DB::raw('COUNT(DISTINCT tindak_lanjuts.laporan_id) as total_laporan'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.status = "selesai" THEN 1 ELSE 0 END) as total_selesai')
            )
            ->groupBy('tindak_lanjuts.konselor_id', 'konselor.nama')
            ->orderByDesc('total_laporan')
            ->get()
            ->map(function ($row) {
                $row->completion_rate = $row->total_laporan > 0
                    ? round(($row->total_selesai / $row->total_laporan) * 100, 1) : 0;
                return $row;
            });
 
        return compact(
            'totalLaporan', 'distribusiStatus', 'distribusiJenisKasus',
            'distribusiNarkoba', 'topNarkoba',
            'distribusiPeranPelapor', 'distribusiPeranTerlapor',
            'genderPelapor', 'genderTerlapor',
            'distribusiUnit', 'distribusiKategoriUnit', 'topUnit',
            'trenLabels', 'trenValues',
            'rataWaktuSelesai', 'aktivitasKonselor',
            'periode', 'from', 'to'
        );
    }
 
    /**
     * Halaman statistik laporan narkoba (SSR + JS charts).
     */
    public function laporan(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        return view('konselor.statistik.laporan', $this->getLaporanData($periode));
    }
 
    /**
     * JSON endpoint untuk refresh data via AJAX (opsional).
     */
    public function laporanData(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        $d = $this->getLaporanData($periode);
 
        $selesaiRate = $d['totalLaporan'] > 0
            ? round(($d['distribusiStatus']['selesai'] ?? 0) / $d['totalLaporan'] * 100, 1) : 0;
 
        return response()->json([
            'totalLaporan'         => $d['totalLaporan'],
            'selesaiRate'          => $selesaiRate,
            'rataWaktuSelesai'     => $d['rataWaktuSelesai'] ? round($d['rataWaktuSelesai'], 1) : null,
            'topNarkoba'           => ['nama' => $d['topNarkoba']->nama_narkoba ?? '—', 'jumlah' => $d['topNarkoba']->jumlah ?? 0],
            'topUnit'              => ['nama' => $d['topUnit']->nama_unit ?? '—', 'jumlah' => $d['topUnit']->jumlah ?? 0, 'kategori' => $d['topUnit']->kategori_unit ?? ''],
            'aktivitasKonselor'       => $d['aktivitasKonselor']->count(),
            'status'               => $d['distribusiStatus'],
            'jenisKasus'           => ['labels' => $d['distribusiJenisKasus']->pluck('jenis_kasus'), 'values' => $d['distribusiJenisKasus']->pluck('jumlah')],
            'narkoba'              => ['labels' => $d['distribusiNarkoba']->pluck('nama_narkoba'), 'values' => $d['distribusiNarkoba']->pluck('jumlah')],
            'peranPelapor'         => ['labels' => $d['distribusiPeranPelapor']->pluck('peran'), 'values' => $d['distribusiPeranPelapor']->pluck('jumlah')],
            'peranTerlapor'        => ['labels' => $d['distribusiPeranTerlapor']->pluck('peran'), 'values' => $d['distribusiPeranTerlapor']->pluck('jumlah')],
            'genderPelapor'        => $d['genderPelapor'],
            'genderTerlapor'       => $d['genderTerlapor'],
            'kategoriUnit'         => ['labels' => $d['distribusiKategoriUnit']->pluck('kategori_unit'), 'values' => $d['distribusiKategoriUnit']->pluck('jumlah')],
            'tren'                 => ['labels' => $d['trenLabels'], 'values' => $d['trenValues']],
        ]);
    }
}