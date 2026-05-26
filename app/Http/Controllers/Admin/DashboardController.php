<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =============================================
        // CARD STATISTIK (hanya laporan yang dipertahankan)
        // =============================================
        $totalLaporan = DB::table('laporans')->count();

        // =============================================
        // GRAFIK USER: Breakdown Konsuli & Konselor
        // =============================================
        $userBreakdown = DB::table('users')
            ->select('role', DB::raw('COUNT(*) as total'))
            ->whereIn('role', ['konsuli', 'konselor'])
            ->groupBy('role')
            ->pluck('total', 'role');

        // =============================================
        // BAR CHART: Jenis Kasus Laporan (dipertahankan, bukan ranah)
        // =============================================
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

        // =============================================
        // PIE CHART: Perbandingan Role Konsuli berdasarkan Status Sivitas
        // =============================================
        $userRole = DB::table('users')
            ->leftJoin('status_sivitas', 'users.status_sivitas_id', '=', 'status_sivitas.id')
            ->select(
                DB::raw('COALESCE(status_sivitas.nama, "Belum Diisi") as nama'),
                DB::raw('COUNT(*) as total')
            )
            ->where('users.role', 'konsuli')
            ->groupBy('status_sivitas.nama')
            ->pluck('total', 'nama');

        // =============================================
        // STATISTIK PENGGUNA KONSELING DARI KATEGORI UNIT
        // =============================================
        $konselingPerKategoriUnit = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('units', 'konseli.unit_id', '=', 'units.id')
            ->select(
                DB::raw('COALESCE(units.kategori_unit, "Belum Diisi") as kategori'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('units.kategori_unit')
            ->pluck('total', 'kategori');

        // =============================================
        // GRAFIK SESI KONSELING INTERAKTIF (per tahun tersedia, default tahun ini)
        // Data dikirim lengkap per-tanggal, filter dilakukan di frontend
        // =============================================
        $konselingRawData = DB::table('konseling_sessions')
            ->select(DB::raw('DATE(created_at) as tanggal'), DB::raw('COUNT(*) as total'))
            ->whereNotNull('created_at')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('tanggal')
            ->get()
            ->map(fn($row) => ['tanggal' => $row->tanggal, 'total' => $row->total]);

        // =============================================
        // BAR CHART: Konsuli Berdasarkan Unit (nama_unit)
        // =============================================
        $konsulPerUnit = DB::table('users')
            ->join('units', 'users.unit_id', '=', 'units.id')
            ->select('units.nama_unit', DB::raw('COUNT(*) as total'))
            ->where('users.role', 'konsuli')
            ->groupBy('units.nama_unit')
            ->orderByDesc('total')
            ->pluck('total', 'nama_unit');

        // =============================================
        // PIE CHART: Distribusi Jenis Kelamin Pengguna Konseling (konsuli)
        // =============================================
        $jenisKelaminKonseling = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->select('konseli.jenis_kelamin', DB::raw('COUNT(*) as total'))
            ->groupBy('konseli.jenis_kelamin')
            ->pluck('total', 'jenis_kelamin');

        // =============================================
        // TOP 5 KONSELOR: Beban Kerja (jumlah sesi ditangani)
        // =============================================
        $topKonselor = DB::table('konseling_sessions')
            ->join('users as konselor', 'konseling_sessions.konselor_id', '=', 'konselor.id')
            ->select('konselor.nama', DB::raw('COUNT(*) as total_sesi'))
            ->groupBy('konselor.id', 'konselor.nama')
            ->orderByDesc('total_sesi')
            ->limit(5)
            ->get();

        // =============================================
        // LINE CHART: Pertumbuhan Konsuli Baru (tren pendaftar)
        // =============================================
        $pertumbuhanKonsuli = DB::table('users')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
                DB::raw('COUNT(*) as total')
            )
            ->where('role', 'konsuli')
            ->whereNotNull('created_at')
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy('bulan')
            ->pluck('total', 'bulan');

        return view('admin.dashboard', compact(
            // Statistik laporan (tidak diubah)
            'totalLaporan',
            'kasusBulanIni',
            'kasusBulanLalu',
            // Grafik user breakdown
            'userBreakdown',
            // Grafik role konsuli per status sivitas
            'userRole',
            // Statistik konseling per kategori unit
            'konselingPerKategoriUnit',
            // Data sesi konseling interaktif (raw)
            'konselingRawData',
            // Konsuli per unit
            'konsulPerUnit',
            // Distribusi jenis kelamin
            'jenisKelaminKonseling',
            // Top 5 konselor
            'topKonselor',
            // Pertumbuhan konsuli
            'pertumbuhanKonsuli'
        ));
    }
}