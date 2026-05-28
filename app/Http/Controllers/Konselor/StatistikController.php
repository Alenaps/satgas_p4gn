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

    public function laporan()
    {
        return view('konselor.statistik.laporan');
    }
}