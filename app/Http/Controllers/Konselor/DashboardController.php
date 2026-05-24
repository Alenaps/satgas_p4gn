<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

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
    ->leftJoin('status_sivitas', 'users.status_sivitas_id', '=', 'status_sivitas.id')
    ->select(
        DB::raw('COALESCE(status_sivitas.nama, "Belum Diisi") as nama'),
        DB::raw('COUNT(*) as total')
    )
    ->where('users.role', 'konsuli')
    ->groupBy('status_sivitas.nama')
    ->pluck('total', 'nama');
        // LINE CHART (Konseling per Bulan)
        $konselingPerBulan = DB::table('konseling_sessions')
            ->select(
                DB::raw('MONTH(created_at) as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->whereYear('created_at', now()->year)
            ->groupBy('bulan')
            ->pluck('total', 'bulan');

        return view('konselor.dashboard', compact(
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
