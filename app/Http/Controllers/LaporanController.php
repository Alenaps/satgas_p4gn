<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
use App\Models\JenisNarkoba;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\LaporanTerkirimMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $data = $this->mapData($request, $path);

        $data['kode_laporan'] = 'LPR-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $data['token_laporan'] = Str::random(40);
        $data['status'] = 'terkirim';
        $data['user_id'] = auth()->check() ? auth()->id() : null; // guest atau belum diklaim

        $laporan = LaporanModel::create($data);

        // timeline tracking awal
        $laporan->tindakLanjuts()->create([
            'status' => 'terkirim',
            'catatan' => 'Laporan berhasil dikirim'
        ]);

        // kirim email
        if($laporan->email){
            Mail::to($laporan->email)->send(new LaporanTerkirimMail($laporan));
        }

        return redirect()->route('guest.laporan.index')
            ->with('kode', $laporan->kode_laporan)
            ->with('token', $laporan->token_laporan)
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
        $data = $this->mapData($request, $path);

        $data['kode_laporan'] = 'LPR-' . date('Ymd') . '-' . strtoupper(Str::random(5));
        $data['token_laporan'] = Str::random(40);
        $data['user_id'] = auth()->id(); // konsuli

        $laporan = LaporanModel::create($data);

        // tracking 
        $laporan->tindakLanjuts()->create([
            'status' => 'terkirim',
            'catatan' => 'Laporan dikirim oleh user login'
        ]);

        return redirect()->route('konsuli.laporan.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }

    // VALIDASI CLIENT-SIDE
    private function validateLaporan(Request $request)
    {
        return $request->validate([

            'nama_pelapor'      => 'required|string|max:60',
            'peran_pelapor'     => 'required|in:Mahasiswa,Dosen,Tendik',
            'jk_pelapor'        => 'required|in:Laki-laki,Perempuan',

            'npm_nip'           => 'required|string|max:18',
            'no_telp'           => 'nullable|string|digits_between:10,15',
            'email'             => 'required|email|max:30',

            'nama_terlapor'     => 'required|string|max:60',
            'peran_terlapor'    => 'required|in:Mahasiswa,Dosen,Tendik',
            'no_telp_terlapor'  => 'nullable|string|digits_between:10,15',

            'jk_terlapor'       => 'required|in:Laki-laki,Perempuan',
            'alamat_terlapor'   => 'nullable|string|max:255',
            'jenis_kasus'       => 'required|in:Pengguna,Pengedar,Kurir,Bandar',

            'lokasi'            => 'required|string|max:150',
            'tanggal'           => 'required|date|before_or_equal:today',

            'jenis_narkoba_id'  => 'nullable|exists:jenis_narkobas,id',

            'kronologi'         => 'required|string|min:20|max:5000',

            'foto_lokasi'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [

            // Pesan custom validasi
            'nama_pelapor.required'    => 'Nama pelapor wajib diisi.',
            'peran_pelapor.required'   => 'Peran pelapor wajib diisi.',
            'jk_pelapor.required'      => 'Jenis kelamin wajib dipilih.',
            'email.required'           => 'Email wajib diisi.',
            'email.email'              => 'Format email tidak valid.',
            'email.max'                => 'Email maksimal 30 karakter.',

            'npm_nip.required'         => 'NPM/NIP wajib diisi.',
            'npm_nip.max'              => 'NPM/NIP maksimal 18 karakter.',
            'no_telp.digits_between'   => 'Nomor telepon harus 10-15 digit.',

            'no_telp_terlapor.digits_between' => 'Nomor telepon harus 10-15 digit.',

            'nama_terlapor.required'   => 'Nama terlapor wajib diisi.',
            'peran_terlapor.required'  => 'Peran terlapor wajib dipilih.',
            'jk_terlapor.required'     => 'Jenis kelamin wajib dipilih.',
            'jenis_kasus.required'     => 'Jenis kasus wajib dipilih.',

            'lokasi.required'          => 'Lokasi kejadian wajib diisi.',
            'foto_lokasi.image'        => 'File harus berupa gambar.',
            'foto_lokasi.mimes'        => 'Format foto harus jpg/jpeg/png.',
            'foto_lokasi.max'          => 'Ukuran foto maksimal 2MB.',
            'tanggal.required'         => 'Tanggal kejadian wajib diisi.',
            'tanggal.before_or_equal'  => 'Tanggal kejadian tidak boleh melebihi hari ini.',
            'kronologi.required'       => 'Kolom kronologi wajib diisi.',            
            'kronologi.min'            => 'Kronologi minimal harus berisi 20 karakter.',
           ]); 
    }

    // DATA MAPPER
    private function mapData(Request $request, $path = null)
    {
        $jenisId = $request->jenis_narkoba_id;

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

            'jenis_narkoba_id' => $request->jenis_narkoba_id,

            'kronologi'        => $request->kronologi,
            'foto_lokasi'      => $path,
        ];
    }

    public function konsuliKlaim(Request $request)
    {
        // Validasi input
        $request->validate([
            'kode_laporan'  => 'required|string',
            'token_laporan' => 'required|string',
        ]);

        // Harus sudah login
        if (!Auth::check()) {
            return back()->with('error', 'Silakan login terlebih dahulu sebelum mengklaim laporan.');
        }

        $user = Auth::user();

        // Cari laporan berdasarkan kode + token
        $laporan = LaporanModel::where('kode_laporan',  $request->kode_laporan)
                            ->where('token_laporan', $request->token_laporan)
                            ->first();

        if (!$laporan) {
            return back()->with('error', 'Kode laporan atau token tidak valid.');
        }

        // Cegah double claim
        // Jika sudah diklaim oleh orang LAIN - tolak
        // Jika sudah diklaim oleh user yang SAMA - anggap sukses (idempotent)
        if ($laporan->user_id) {
            if ((int) $laporan->user_id === (int) $user->id) {
                return back()->with('success', 'Laporan ini sudah terhubung ke akun Anda.');
            }
            return back()->with('error', 'Laporan ini sudah diklaim oleh pengguna lain.');
        }

        // Verifikasi kepemilikan via npm_nip
        // laporan menyimpan npm_nip pelapor dan user punya npm_nip,
        // memastikan data identitas cocok agar tidak ada user lain yang klaim milik orang lain.
             
        if ($laporan->npm_nip && $user->npm_nip) {
            if ($laporan->npm_nip !== $user->npm_nip) {
                return back()->with('error', 'Data identitas Anda tidak cocok dengan laporan ini.');
            }
        }

        // Hubungkan laporan ke user yang login
        // Setelah ini, query statistik unit akan langsung terbaca via:
        // laporans.user_id - users.unit_id - units
        $laporan->update(['user_id' => $user->id]);

        return back()->with('success', 'Laporan berhasil diklaim dan terhubung ke akun Anda.');
    }

    public function konsuliLaporanSaya()
    {
        $laporans = LaporanModel::where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('konsuli.laporan.saya', compact('laporans'));
    }

    public function konsuliShow(int $id)
    {
        $laporan = LaporanModel::with('tindakLanjuts')
            ->where('id',$id)
            ->where('user_id',auth()->id())
            ->firstOrFail();

        return view('konsuli.laporan.show',compact('laporan'));
    }
}
