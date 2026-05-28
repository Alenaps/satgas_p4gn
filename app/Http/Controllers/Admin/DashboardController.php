<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // 1. Laporan menunggu verifikasi
        $laporanMenunggu = DB::table('laporans')
            ->whereIn('status', ['terkirim', 'menunggu'])
            ->count();

        // 2. Total laporan masuk
        $totalLaporan = DB::table('laporans')->count();

        // 3. Total sesi aktif hari ini
        $sesiAktifHariIni = DB::table('konseling_sessions')
            ->where('status', 'active')
            ->whereDate('started_at', $today)
            ->count();

        // 4. Pengguna baru hari ini
        $penggunaBaru = DB::table('users')
            ->whereDate('created_at', $today)
            ->count();

        // Tabel laporan terkini
        $laporanTerkini = DB::table('laporans')
            ->leftJoin('users', 'laporans.user_id', '=', 'users.id')
            ->select(
                'laporans.id',
                'laporans.created_at',
                'laporans.jenis_kasus',
                'laporans.status',
                DB::raw('COALESCE(users.nama, "Anonim") as nama_pelapor')
            )
            ->whereIn('laporans.status', ['terkirim', 'diproses'])
            ->orderByDesc('laporans.created_at')
            ->limit(5)
            ->get();

        // Log aktivitas sesi
        $aktivitasSesi = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->join('users as konselor', 'konseling_sessions.konselor_id', '=', 'konselor.id')
            ->leftJoin('status_sivitas', 'konseli.status_sivitas_id', '=', 'status_sivitas.id')
            ->select(
                'konseling_sessions.updated_at as waktu',
                'konseling_sessions.status',
                'konselor.nama as nama_konselor',
                DB::raw('COALESCE(status_sivitas.nama, "Pengguna") as sivitas_konseli'),
                DB::raw('"sesi" as tipe')
            )
            ->orderByDesc('konseling_sessions.updated_at')
            ->limit(8)
            ->get();

        // Log pengguna baru
        $penggunaBaruLog = DB::table('users')
            ->leftJoin('units', 'users.unit_id', '=', 'units.id')
            ->select(
                'users.created_at as waktu',
                'users.nama',
                'users.role',
                DB::raw('COALESCE(units.nama_unit, "unit tidak diketahui") as nama_unit'),
                DB::raw('"user_baru" as tipe')
            )
            ->orderByDesc('users.created_at')
            ->limit(5)
            ->get();

        $logAktivitas = collect()
            ->merge($aktivitasSesi)
            ->merge($penggunaBaruLog)
            ->sortByDesc('waktu')
            ->take(10)
            ->values();

        return view('admin.dashboard', compact(
            'laporanMenunggu',
            'totalLaporan',
            'sesiAktifHariIni',
            'penggunaBaru',
            'laporanTerkini',
            'logAktivitas',
        ));
    }
}