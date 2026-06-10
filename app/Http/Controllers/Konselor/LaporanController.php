<?php

namespace App\Http\Controllers\Konselor;

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

        return view('konselor.laporan.index', compact('laporans'));
    }
    public function show(int $id)
    {
        $laporan = LaporanModel::findOrFail($id);
        return view('konselor.laporan.show', compact('laporan'));
    }

     public function cetakPdf(int $id)
    {
        $laporan = LaporanModel::findOrFail($id);

        $pdf = Pdf::loadView('konselor.laporan.pdf', compact('laporan'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('laporan_'.$laporan->id.'.pdf');
    }
    public function tindakLanjut(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:diverifikasi,diproses,selesai,ditolak',
            'catatan' => 'required|min:10'
        ],[
            'status.required' => 'Status wajib diisi.',
            'status.in' => 'Status tidak valid.',
            'catatan.required' => 'Catatan wajib diisi.',
            'catatan.min' => 'Catatan minimal 10 karakter.'
        ]);

        $laporan = LaporanModel::findOrFail($id);

        // SIMPAN KE TIMELINE
        $laporan->tindakLanjuts()->create([
            'status' => $request->status,
            'catatan' => $request->catatan,
            'konselor_id' => auth()->id()
        ]);

        // UPDATE STATUS UTAMA
        $laporan->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Tindak lanjut berhasil ditambahkan');
    }
}
