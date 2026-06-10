<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class StatistikController extends Controller
{
    // ── Helper: ambil rentang tanggal berdasarkan filter ──
    private function getDateRange(string $periode): array
    {
        return match($periode) {
            'harian'  => [now()->startOfDay(),   now()->endOfDay()],
            'mingguan'=> [now()->startOfWeek(),  now()->endOfWeek()],
            'bulanan' => [now()->startOfMonth(), now()->endOfMonth()],
            default   => [now()->startOfYear(),  now()->endOfYear()], // tahunan
        };
    }

    public function getData(string $periode = 'bulanan'): array
    {
        [$from, $to] = $this->getDateRange($periode);

        // ══════════════════════════════════════════════
        // 1. METRIK UTAMA
        // ══════════════════════════════════════════════

        $totalSesi = DB::table('konseling_sessions')
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $totalKlien = DB::table('konseling_sessions')
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('COUNT(DISTINCT konseli_id) as total'))
            ->value('total') ?? 0;

        $distribusiStatus = DB::table('konseling_sessions')
            ->whereBetween('created_at', [$from, $to])
            ->select('status', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $rataRataDurasi = DB::table('konseling_sessions')
            ->whereBetween('created_at', [$from, $to])
            ->where('status', 'completed')
            ->whereNotNull('started_at')
            ->whereNotNull('ended_at')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, started_at, ended_at)) as rata_menit'))
            ->value('rata_menit');

        // ══════════════════════════════════════════════
        // 2. DISTRIBUSI KATEGORI UNIT
        // ══════════════════════════════════════════════

        $distribusiKategori = DB::table('konseling_sessions')
            ->whereBetween('konseling_sessions.created_at', [$from, $to])
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('units', 'konseli.unit_id', '=', 'units.id')
            ->select(
                DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui") as kategori_unit'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw('COALESCE(units.kategori_unit, "Tidak Diketahui")'))
            ->orderByDesc('jumlah')
            ->get();

        // ══════════════════════════════════════════════
        // 3. TOP 10 UNIT
        // ══════════════════════════════════════════════

        $distribusiUnit = DB::table('konseling_sessions')
            ->whereBetween('konseling_sessions.created_at', [$from, $to])
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('units', 'konseli.unit_id', '=', 'units.id')
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

        $topUnit = $distribusiUnit->first();

        // ══════════════════════════════════════════════
        // 4. SIVITAS
        // ══════════════════════════════════════════════

        $distribusiSivitas = DB::table('konseling_sessions')
            ->whereBetween('konseling_sessions.created_at', [$from, $to])
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('status_sivitas', 'konseli.status_sivitas_id', '=', 'status_sivitas.id')
            ->select(
                DB::raw('COALESCE(status_sivitas.nama, "Tidak Diketahui") as nama'),
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(DB::raw('COALESCE(status_sivitas.nama, "Tidak Diketahui")'))
            ->get();

        // ══════════════════════════════════════════════
        // 5. GENDER
        // ══════════════════════════════════════════════

        $genderRaw = DB::table('konseling_sessions')
            ->whereBetween('konseling_sessions.created_at', [$from, $to])
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->select('konseli.jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->groupBy('konseli.jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        $genderL = 0; $genderP = 0;
        foreach ($genderRaw as $key => $val) {
            $k = strtolower(trim((string)$key));
            if (in_array($k, ['l','laki-laki','laki laki','male','pria'])) $genderL += $val;
            elseif (in_array($k, ['p','perempuan','wanita','female','pr'])) $genderP += $val;
        }
        $distribusiGender = ['L' => (int)$genderL, 'P' => (int)$genderP];

        // ══════════════════════════════════════════════
        // 6. PESAN
        // ══════════════════════════════════════════════

        $totalPesan = DB::table('konseling_messages')
            ->whereBetween('created_at', [$from, $to])
            ->count();

        $rataRataPesan = $totalSesi > 0 ? round($totalPesan / $totalSesi, 1) : 0;

        $peakHours = DB::table('konseling_messages')
            ->whereBetween('created_at', [$from, $to])
            ->select(DB::raw('HOUR(created_at) as jam'), DB::raw('COUNT(*) as jumlah'))
            ->groupBy('jam')
            ->orderBy('jam')
            ->get();

        // ══════════════════════════════════════════════
        // 7. BEBAN KONSELOR
        // ══════════════════════════════════════════════

        $bebanKonselor = DB::table('konseling_sessions')
            ->whereBetween('konseling_sessions.created_at', [$from, $to])
            ->whereNotNull('konseling_sessions.konselor_id')
            ->join('users as konselor', 'konseling_sessions.konselor_id', '=', 'konselor.id')
            ->select(
                'konselor.nama',
                DB::raw('COUNT(*) as total_sesi'),
                DB::raw('SUM(CASE WHEN konseling_sessions.status = "completed" THEN 1 ELSE 0 END) as sesi_selesai')
            )
            ->groupBy('konseling_sessions.konselor_id', 'konselor.nama')
            ->orderByDesc('total_sesi')
            ->get()
            ->map(function ($row) {
                $row->completion_rate = $row->total_sesi > 0
                    ? round(($row->sesi_selesai / $row->total_sesi) * 100, 1) : 0;
                return $row;
            });

        return compact(
            'totalSesi', 'totalKlien', 'distribusiStatus', 'rataRataDurasi',
            'distribusiKategori', 'distribusiUnit', 'topUnit',
            'distribusiSivitas', 'distribusiGender',
            'totalPesan', 'rataRataPesan', 'peakHours', 'bebanKonselor',
            'periode', 'from', 'to'
        );
    }

    public function konseling(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        return view('admin.statistik.konseling', $this->getData($periode));
    }

    public function konselingData(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        $d = $this->getData($periode);

        $completionRate = $d['totalSesi'] > 0
            ? round(($d['distribusiStatus']['completed'] ?? 0) / $d['totalSesi'] * 100, 1) : 0;

        $peakArr = array_fill(0, 24, 0);
        foreach ($d['peakHours'] as $row) $peakArr[(int)$row->jam] = $row->jumlah;

        return response()->json([
            'totalKlien'     => $d['totalKlien'],
            'totalSesi'      => $d['totalSesi'],
            'totalPesan'     => $d['totalPesan'],
            'rataRataPesan'  => $d['rataRataPesan'],
            'completionRate' => $completionRate,
            'konselorAktif'  => $d['bebanKonselor']->count(),
            'topUnit'        => ['nama' => $d['topUnit']->nama_unit ?? '—', 'jumlah' => $d['topUnit']->jumlah ?? 0, 'kategori' => $d['topUnit']->kategori_unit ?? ''],
            'status'         => ['pending' => $d['distribusiStatus']['pending'] ?? 0, 'active' => $d['distribusiStatus']['active'] ?? 0, 'completed' => $d['distribusiStatus']['completed'] ?? 0, 'rejected' => $d['distribusiStatus']['rejected'] ?? 0],
            'kategori'       => ['labels' => $d['distribusiKategori']->pluck('kategori_unit'), 'values' => $d['distribusiKategori']->pluck('jumlah')],
            'gender'         => $d['distribusiGender'],
            'sivitas'        => ['labels' => $d['distribusiSivitas']->pluck('nama'), 'values' => $d['distribusiSivitas']->pluck('jumlah')],
            'peakHours'      => $peakArr,
        ]);
    }


    // ══════════════════════════════════════════════
    // STATISTIK LAPORAN
    // ══════════════════════════════════════════════

        private function getLaporanData(string $periode): array
    {
        [$from, $to] = $this->getDateRange($periode);
 
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
 
        // ── 8. ADMIN PALING AKTIF ─────────────────────────────
        $aktivitas = DB::table('tindak_lanjuts')
            ->join('laporans', 'tindak_lanjuts.laporan_id', '=', 'laporans.id')
            ->leftJoin('users as admin', 'tindak_lanjuts.admin_id', '=', 'admin.id')
            ->leftJoin('users as konselor', 'tindak_lanjuts.konselor_id', '=', 'konselor.id')
            ->whereBetween('laporans.tanggal', [$from->toDateString(), $to->toDateString()])
            ->select(
                DB::raw('COALESCE(admin.nama, konselor.nama) as nama'),
                DB::raw('CASE 
                    WHEN tindak_lanjuts.admin_id IS NOT NULL THEN "Admin"
                    ELSE "Konselor"
                END as role'),
                DB::raw('COUNT(DISTINCT tindak_lanjuts.laporan_id) as total_laporan'),
                DB::raw('SUM(CASE WHEN tindak_lanjuts.status = "selesai" THEN 1 ELSE 0 END) as total_selesai')
            )
            ->groupBy('nama', 'role')
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
            'rataWaktuSelesai', 'aktivitas',
            'periode', 'from', 'to'
        );
    }
 
    /**
     * Halaman statistik laporan narkoba (SSR + JS charts).
     */
    public function laporan(Request $request)
    {
        $periode = $request->get('periode', 'bulanan');
        return view('admin.statistik.laporan', $this->getLaporanData($periode));
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
            'aktivitasAdmin'       => $d['aktivitasAdmin']->count(),
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