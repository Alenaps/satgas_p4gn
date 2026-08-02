<?php

namespace App\Http\Controllers\Konsuli;

use App\Http\Controllers\Controller;
use App\Models\PublikasiModel;

class DashboardController extends Controller
{
    public function index()
    {
        $publikasis = PublikasiModel::where('status', 'Publish')
            ->latest()
            ->take(4)
            ->get();

        return view('konsuli.dashboard', compact('publikasis'));
    }
}
