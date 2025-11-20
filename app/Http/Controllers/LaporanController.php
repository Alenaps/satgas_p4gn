<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index()
    {
        $data = LaporanModel::latest()->get();
        return view('laporan.index', compact('data'));
    }

    public function create()
    {
        return view('laporan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelapor' => 'required',
            'peran_pelapor' => 'required',
            'jk_pelapor' => 'required',
            'nama_terlapor' => 'required',
            'peran_terlapor' => 'required',
            'jk_terlapor' => 'required',
            'lokasi' => 'required',
            'tanggal' => 'required|date',
            'kronologi' => 'required',
            'foto_lokasi' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto_lokasi')) {
            $path = $request->file('foto_lokasi')->store('laporan_foto', 'public');
        }

        LaporanModel::create([
            'nama_pelapor' => $request->nama_pelapor,
            'peran_pelapor' => $request->peran_pelapor,
            'nip' => $request->nip,
            'telp_pelapor' => $request->no_telp,
            'email' => $request->email,
            'jk_pelapor' => $request->jk_pelapor,

            'nama_terlapor' => $request->nama_terlapor,
            'peran_terlapor' => $request->peran_terlapor,
            'telp_terlapor' => $request->no_telp_terlapor,
            'jk_terlapor' => $request->jk_terlapor,
            'alamat_terlapor' => $request->alamat_terlapor,

            'lokasi' => $request->lokasi,
            'foto_lokasi' => $path,
            'tanggal' => $request->tanggal,
            'jenis_narkoba' => $request->jenis_narkoba,
            'kronologi' => $request->kronologi,
        ]);

        return redirect()->route('laporan.index')->with('success', 'Laporan berhasil dikirim!');
    }
}
