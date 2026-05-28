<?php

namespace App\Http\Controllers\Konsuli;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KonselingSession;
use Illuminate\Support\Facades\Auth;

class KonselorListController extends Controller
{
    public function index()
    {
            $konselors = User::where('role', 'konselor')
        ->with([
            'konselorProfile',
            'konselorProfile.instansi',
            'konselorProfile.jabatan',
        ])
        ->orderBy('nama', 'asc')
        ->get();

        // Sesi milik user yang sedang login
        $sessions = KonselingSession::where('konseli_id', Auth::id())
            ->whereIn('status', ['pending', 'active'])
            ->get();

        // Semua konselor yang sedang sibuk (dari siapapun)
        $busyKonselorIds = KonselingSession::whereIn('status', ['pending', 'active'])
            ->pluck('konselor_id')
            ->unique()
            ->toArray();

        return view('konsuli.konselor.index', compact('konselors', 'sessions', 'busyKonselorIds'));
    }
}