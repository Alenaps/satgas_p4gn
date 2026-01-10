<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LaporanModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
     public function index(Request $request)
    {
        $laporans = LaporanModel::query();

        // SEARCH
        if ($request->filled('search')) {
            $laporans->where(function($q) use ($request){
                $q->where('nama_terlapor','like','%'.$request->search.'%')
                ->orWhere('lokasi','like','%'.$request->search.'%');
            });
        }

        // FILTER BULAN
        if ($request->filled('bulan')) {

            $bulan = date('m', strtotime($request->bulan));
            $tahun = date('Y', strtotime($request->bulan));

            $laporans->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun);
        }

        // FILTER PERAN TERLAPOR
        if ($request->filled('peran_terlapor')) {
            $laporans->where('peran_terlapor', $request->peran_terlapor);
        }

        $laporans = $laporans->latest()
                            ->paginate(10)
                            ->appends($request->all());

        return view('admin.laporan.index', compact('laporans'));
    }


    public function show(LaporanModel $laporan)
    {
        return view('admin.laporan.show', compact('laporan'));
    }

    public function cetakPdf($id)
    {
        $laporan = LaporanModel::findOrFail($id);

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporan'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('laporan_'.$laporan->id.'.pdf');
    }
}
