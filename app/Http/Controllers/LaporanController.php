<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    
    // GUEST (PUBLIC)
    public function guestCreate()
    {
        return view('guest.laporan.create');
    }
    
    public function guestIndex()
    {
        return view('guest.laporan.index');
    }

    public function guestStore(Request $request)
    {
        $this->validateLaporan($request);

        $path = $request->hasFile('foto_lokasi')
                        ? $request->file('foto_lokasi')->store('laporan_foto', 'public')
                        : null;

        LaporanModel::create($this->mapData($request, $path));

        return redirect()->route('guest.laporan.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }

    // KONSULI
    public function konsuliCreate()
    {
        return view('konsuli.laporan.create');
    }

    public function konsuliIndex()
    {
        return view('konsuli.laporan.index');
    }

    public function konsuliStore(Request $request)
    {
        $this->validateLaporan($request);

        $path = $request->hasFile('foto_lokasi')
                        ? $request->file('foto_lokasi')->store('laporan_foto', 'public')
                        : null;

        LaporanModel::create($this->mapData($request, $path));

        return redirect()->route('konsuli.laporan.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }

    // VALIDASI CLIENT-SIDE
    private function validateLaporan(Request $request)
    {
        return $request->validate([
            'nama_pelapor'      => 'required|string|max:100',
            'peran_pelapor'     => 'required|in:Mahasiswa, Dosen, Tendik',
            'jk_pelapor'        => 'required|in:Laki-laki,Perempuan',

            'npm_nip'           => 'nullable|string|max:30',
            'no_telp'           => 'nullable|numeric|digits_between:10,15',
            'email'             => 'nullable|email|max:100',

            'nama_terlapor'     => 'required|string|max:100',
            'peran_terlapor'    => 'required|in:Mahasiswa, Dosen, Tendik',
            'no_telp_terlapor'  => 'nullable|numeric|digits_between:10,15',
            'jk_terlapor'       => 'required|in:Laki-laki,Perempuan',
            'alamat_terlapor'   => 'nullable|string|max:255',
            'jenis_kasus'       => 'required|in:Pengguna,Pengedar,Kurir,Bandar',

            'lokasi'            => 'required|string|max:150',
            'tanggal'           => 'required|date|before_or_equal:today',

            'jenis_narkoba'     => 'nullable|string|max:100',

            'kronologi'         => 'required|string|min:20|max:5000',

            'foto_lokasi'       => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ], [

            // Pesan custom validasi
            'nama_pelapor.required'    => 'Nama pelapor wajib diisi.',
            'nama_terlapor.required'   => 'Nama terlapor wajib diisi.',
            'lokasi.required'          => 'Lokasi kejadian wajib diisi.',
            'tanggal.before_or_equal'  => 'Tanggal kejadian tidak boleh melebihi hari ini.',
            'kronologi.min'            => 'Kronologi minimal harus berisi 20 karakter.',
            'foto_lokasi.mimes'        => 'Format foto harus jpg/jpeg/png.',
            'foto_lokasi.max'          => 'Ukuran foto maksimal 4MB.',
        ]);
    }

    // DATA MAPPER
    private function mapData(Request $request, $path = null)
    {
        return [
            'nama_pelapor'     => $request->nama_pelapor,
            'peran_pelapor'    => $request->peran_pelapor,
            'npm_nip'          => $request->npm_nip,
            'telp_pelapor'     => $request->no_telp,
            'email'            => $request->email,
            'jk_pelapor'       => $request->jk_pelapor,

            'nama_terlapor'    => $request->nama_terlapor,
            'peran_terlapor'   => $request->peran_terlapor,
            'telp_terlapor'    => $request->no_telp_terlapor,
            'jk_terlapor'      => $request->jk_terlapor,
            'alamat_terlapor'  => $request->alamat_terlapor,
            'jenis_kasus'      => $request->jenis_kasus,

            'lokasi'           => $request->lokasi,
            'tanggal'          => $request->tanggal,
            'jenis_narkoba'    => $request->jenis_narkoba,

            'kronologi'        => $request->kronologi,
            'foto_lokasi'      => $path,
        ];
    }
}
