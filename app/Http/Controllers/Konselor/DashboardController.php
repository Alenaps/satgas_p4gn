<?php

namespace App\Http\Controllers\Konselor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $konselorId = Auth::id();

        // ── ANTREAN PENDING ──────────────────────────────────────
        $antreanPending = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('units', 'konseli.unit_id', '=', 'units.id')
            ->leftJoin('status_sivitas', 'konseli.status_sivitas_id', '=', 'status_sivitas.id')
            ->select(
                'konseling_sessions.id',
                'konseling_sessions.created_at',
                'konseli.nama as nama_konseli',
                'konseli.jenis_kelamin',
                DB::raw('COALESCE(units.nama_unit, "—") as nama_unit'),
                DB::raw('COALESCE(status_sivitas.nama, "—") as sivitas'),
            )
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->where('konseling_sessions.status', 'pending')
            ->orderBy('konseling_sessions.created_at')
            ->get();

        // ── ACTIVE CHATS (ada unread) ────────────────────────────
        $activeChats = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('units', 'konseli.unit_id', '=', 'units.id')
            ->leftJoin(DB::raw('(
                SELECT session_id,
                       COUNT(*) as unread_count,
                       MAX(created_at) as last_message_at
                FROM konseling_messages
                WHERE is_read = 0
                  AND sender_id != ' . $konselorId . '
                GROUP BY session_id
            ) as unread'), 'unread.session_id', '=', 'konseling_sessions.id')
            ->select(
                'konseling_sessions.id',
                'konseli.nama as nama_konseli',
                DB::raw('COALESCE(units.nama_unit, "—") as nama_unit'),
                DB::raw('COALESCE(unread.unread_count, 0) as unread_count'),
                DB::raw('COALESCE(unread.last_message_at, konseling_sessions.updated_at) as last_activity'),
            )
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->where('konseling_sessions.status', 'active')
            ->orderByDesc('unread_count')
            ->orderByDesc('last_activity')
            ->get();

        // ── SEMUA KLIEN AKTIF ────────────────────────────────────
        $kliensAktif = DB::table('konseling_sessions')
            ->join('users as konseli', 'konseling_sessions.konseli_id', '=', 'konseli.id')
            ->leftJoin('units', 'konseli.unit_id', '=', 'units.id')
            ->leftJoin('status_sivitas', 'konseli.status_sivitas_id', '=', 'status_sivitas.id')
            ->select(
                'konseling_sessions.id',
                'konseling_sessions.started_at',
                'konseli.nama as nama_konseli',
                'konseli.jenis_kelamin',
                DB::raw('COALESCE(units.nama_unit, "—") as nama_unit'),
                DB::raw('COALESCE(status_sivitas.nama, "—") as sivitas'),
            )
            ->where('konseling_sessions.konselor_id', $konselorId)
            ->where('konseling_sessions.status', 'active')
            ->orderBy('konseling_sessions.started_at')
            ->get();

        // ── SCORECARD RINGKAS ────────────────────────────────────
        $totalPending   = $antreanPending->count();
        $totalAktif     = $kliensAktif->count();
        $totalUnread    = $activeChats->sum('unread_count');
        $totalSelesai   = DB::table('konseling_sessions')
            ->where('konselor_id', $konselorId)
            ->where('status', 'completed')
            ->count();

        return view('konselor.dashboard', compact(
            'antreanPending',
            'activeChats',
            'kliensAktif',
            'totalPending',
            'totalAktif',
            'totalUnread',
            'totalSelesai',
        ));
    }
}