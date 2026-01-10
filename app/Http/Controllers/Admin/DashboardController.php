<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // CARD STATISTIK
        $totalLaporan   = DB::table('laporans')->count();
        $totalKonseling = DB::table('konseling_sessions')->count();
        $totalPublikasi   = DB::table('publikasis')->count();
        $totalUser      = DB::table('users')->count();

        // BAR CHART (Jenis Kasus)
        $kasusBulanIni = DB::table('laporans')
            ->select('jenis_kasus', DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', now()->month)
            ->groupBy('jenis_kasus')
            ->pluck('total', 'jenis_kasus');

        $kasusBulanLalu = DB::table('laporans')
            ->select('jenis_kasus', DB::raw('COUNT(*) as total'))
            ->whereMonth('created_at', now()->subMonth()->month)
            ->groupBy('jenis_kasus')
            ->pluck('total', 'jenis_kasus');

        // PIE CHART (Role User)
        $userRole = DB::table('users')
            ->select('status_sivitas', DB::raw('COUNT(*) as total'))
            ->where('role', 'konsuli') // hanya konsuli yang dihitung
            ->groupBy('status_sivitas')
            ->pluck('total', 'status_sivitas');

        // LINE CHART (Konseling per Bulan)
        $konselingPerBulan = DB::table('konseling_sessions')
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        return view('admin.dashboard', compact(
            'totalLaporan',
            'totalKonseling',
            'totalPublikasi',
            'totalUser',
            'kasusBulanIni',
            'kasusBulanLalu',
            'userRole',
            'konselingPerBulan'
        ));
    }
}
