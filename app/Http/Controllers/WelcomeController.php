<?php

namespace App\Http\Controllers;

use App\Models\PublikasiModel;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $publikasis = PublikasiModel::where('status', 'Publish')
            ->latest()
            ->take(4)
            ->get();

        return view('welcome', compact('publikasis'));
    }
}
