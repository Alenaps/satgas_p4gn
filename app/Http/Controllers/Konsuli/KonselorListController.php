<?php

namespace App\Http\Controllers\Konsuli;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KonselingSession;
use Illuminate\Support\Facades\Auth;

class KonselorListController extends Controller
{
    /**
     * Menampilkan daftar konselor yang tersedia
     */
    public function index()
    {
        // Ambil semua user dengan role konselor
        $konselors = User::where('role', 'konselor')
            ->orderBy('nama', 'asc')
            ->get();

        // Ambil sesi konseling user yang sedang login
        // untuk mengecek status dengan masing-masing konselor
        $sessions = KonselingSession::where('konseli_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->get();

        return view('konsuli.konselor.index', compact('konselors', 'sessions'));
    }
}