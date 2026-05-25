<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisNarkoba;
class JenisNarkobaController extends Controller
{
     public function search(Request $request)
    {
        $search = $request->q;

        $data = JenisNarkoba::where('nama', 'like', "%$search%")
                    ->select('id', 'nama')
                    ->limit(10)
                    ->get();

        return response()->json($data);
    }
}
