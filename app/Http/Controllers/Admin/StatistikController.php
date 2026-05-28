<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

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

    public function laporan()
    {
        return view('admin.statistik.laporan');
    }
}