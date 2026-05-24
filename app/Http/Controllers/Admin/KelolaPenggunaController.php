<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instansi;
use App\Models\Jabatan;
use App\Models\KonselorProfile;
use App\Models\StatusSivitas;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class KelolaPenggunaController extends Controller
{
    /* ------------------------------------------------------------------ */
    /*  INDEX                                                               */
    /* ------------------------------------------------------------------ */
    public function index(Request $request)
    {
        $search = $request->search;
        $role   = $request->role;

        $users = User::query()
            ->where('role', '!=', 'admin')
            ->with(['statusSivitas', 'unit', 'konselorProfile'])
            ->when($search, function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('npm_nip', 'like', "%$search%");
            })
            ->when($role, function ($q) use ($role) {
                $q->where('role', $role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.kelola_pengguna.index', compact('users'));
    }

    /* ------------------------------------------------------------------ */
    /*  CREATE                                                              */
    /* ------------------------------------------------------------------ */
    public function create()
    {
        // Ambil semua data yang dibutuhkan oleh form
        $statusSivitasList = StatusSivitas::all(); 
        $units = Unit::all(); 
        
        // Ambil data untuk mengatasi error sekarang & error berikutnya
        $instansiList = Instansi::all(); 
        $jabatanList = Jabatan::all(); 

        // Kirim SEMUA variabelnya ke view
        return view('admin.kelola_pengguna.create', compact(
            'statusSivitasList', 
            'units', 
            'instansiList', 
            'jabatanList'
        ));
    }

    /* ------------------------------------------------------------------ */
    /*  STORE                                                               */
    /* ------------------------------------------------------------------ */
    public function store(Request $request)
    {
        /* ---------- validasi dasar (sama untuk konselor & konsuli) ------ */
        $request->validate([
            'nama'              => 'required|string|min:3|max:100',
            'email'             => 'required|email|unique:users,email',
            'jenis_kelamin'     => 'required|in:Laki-laki,Perempuan',
            'npm_nip'           => 'required|string|max:50|unique:users,npm_nip',
            'no_telp'           => 'nullable|string|max:20',
            'status_sivitas_id' => 'required|exists:status_sivitas,id',
            'unit_id'           => 'required|exists:units,id',
            'role'              => 'required|in:konselor,konsuli',
            'password'          => 'required|min:8|confirmed',
        ], $this->pesanValidasi());

        /* ---------- validasi tambahan khusus konselor ------------------- */
        if ($request->role === 'konselor') {
            $request->validate([
                'nomor_lisensi'     => 'required|string|max:100',
                'spesialisasi'      => 'required|string|max:255',
                'pengalaman_kerja'  => 'required|string',
                'pendidikan_terakhir' => 'required|string|max:100',
                'sertifikasi_P4GN'  => 'nullable|boolean',
                'bio_singkat'       => 'nullable|string',
                'id_instansi'       => 'required|exists:instansi,id',
                'id_jabatan'        => 'required|exists:jabatan,id',
            ], [
                'nomor_lisensi.required'      => 'Nomor lisensi wajib diisi.',
                'spesialisasi.required'       => 'Spesialisasi wajib diisi.',
                'pengalaman_kerja.required'   => 'Pengalaman kerja wajib diisi.',
                'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi.',
                'id_instansi.required'        => 'Instansi wajib dipilih.',
                'id_jabatan.required'         => 'Jabatan wajib dipilih.',
            ]);
        }

        /* ---------- simpan user ---------------------------------------- */
        $user = User::create([
            'nama'              => $request->nama,
            'email'             => $request->email,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'npm_nip'           => $request->npm_nip,
            'no_telp'           => $request->no_telp,
            'status_sivitas_id' => $request->status_sivitas_id,
            'unit_id'           => $request->unit_id,
            'role'              => $request->role,
            'password'          => Hash::make($request->password),
            'foto'              => null,
        ]);

        /* ---------- simpan profil konselor jika relevan ----------------- */
        if ($request->role === 'konselor') {
            KonselorProfile::create([
                'user_id'             => $user->id,
                'nomor_lisensi'       => $request->nomor_lisensi,
                'spesialisasi'        => $request->spesialisasi,
                'pengalaman_kerja'    => $request->pengalaman_kerja,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'sertifikasi_P4GN'    => $request->boolean('sertifikasi_P4GN'),
                'bio_singkat'         => $request->bio_singkat,
                'id_instansi'         => $request->id_instansi,
                'id_jabatan'          => $request->id_jabatan,
            ]);
        }

        return redirect()
            ->route('admin.kelola_pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }

    /* ------------------------------------------------------------------ */
    /*  EDIT                                                                */
    /* ------------------------------------------------------------------ */
    public function edit(User $user)
    {
        $statusSivitasList = StatusSivitas::all();
        $units             = Unit::orderBy('kategori_unit')->orderBy('nama_unit')->get();
        $instansiList      = Instansi::orderBy('nama_instansi')->get();
        $jabatanList       = Jabatan::orderBy('nama_jabatan')->get();

        // eager-load profil konselor jika ada
        $user->load('konselorProfile');

        return view('admin.kelola_pengguna.edit', compact(
            'user', 'statusSivitasList', 'units', 'instansiList', 'jabatanList'
        ));
    }

    /* ------------------------------------------------------------------ */
    /*  UPDATE                                                              */
    /* ------------------------------------------------------------------ */
    public function update(Request $request, User $user)
    {
        /* ---------- validasi dasar ------------------------------------- */
        $request->validate([
            'nama'              => 'required|string|min:3|max:100',
            'email'             => "required|email|unique:users,email,{$user->id}",
            'jenis_kelamin'     => 'required|in:Laki-laki,Perempuan',
            'npm_nip'           => "required|string|max:50|unique:users,npm_nip,{$user->id}",
            'no_telp'           => 'nullable|string|max:20',
            'status_sivitas_id' => 'required|exists:status_sivitas,id',
            'unit_id'           => 'required|exists:units,id',
            'role'              => 'required|in:konselor,konsuli',
        ], $this->pesanValidasi());

        /* ---------- validasi password (opsional) ----------------------- */
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Password::min(8), 'confirmed'],
            ], [
                'password.min'       => 'Password minimal 8 karakter.',
                'password.confirmed' => 'Konfirmasi password tidak cocok.',
            ]);
        }

        /* ---------- validasi profil konselor (jika role konselor) ------ */
        if ($request->role === 'konselor') {
            $request->validate([
                'nomor_lisensi'       => 'required|string|max:100',
                'spesialisasi'        => 'required|string|max:255',
                'pengalaman_kerja'    => 'required|string',
                'pendidikan_terakhir' => 'required|string|max:100',
                'sertifikasi_P4GN'    => 'nullable|boolean',
                'bio_singkat'         => 'nullable|string',
                'id_instansi'         => 'required|exists:instansi,id',
                'id_jabatan'          => 'required|exists:jabatan,id',
            ], [
                'nomor_lisensi.required'      => 'Nomor lisensi wajib diisi.',
                'spesialisasi.required'       => 'Spesialisasi wajib diisi.',
                'pengalaman_kerja.required'   => 'Pengalaman kerja wajib diisi.',
                'pendidikan_terakhir.required' => 'Pendidikan terakhir wajib diisi.',
                'id_instansi.required'        => 'Instansi wajib dipilih.',
                'id_jabatan.required'         => 'Jabatan wajib dipilih.',
            ]);
        }

        /* ---------- update kolom user ---------------------------------- */
        $data = [
            'nama'              => $request->nama,
            'email'             => $request->email,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'npm_nip'           => $request->npm_nip,
            'no_telp'           => $request->no_telp,
            'status_sivitas_id' => $request->status_sivitas_id,
            'unit_id'           => $request->unit_id,
            'role'              => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        /* ---------- update / buat / hapus profil konselor -------------- */
        if ($request->role === 'konselor') {
            // updateOrCreate agar bisa dipakai meski user baru di-upgrade ke konselor
            KonselorProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nomor_lisensi'       => $request->nomor_lisensi,
                    'spesialisasi'        => $request->spesialisasi,
                    'pengalaman_kerja'    => $request->pengalaman_kerja,
                    'pendidikan_terakhir' => $request->pendidikan_terakhir,
                    'sertifikasi_P4GN'    => $request->boolean('sertifikasi_P4GN'),
                    'bio_singkat'         => $request->bio_singkat,
                    'id_instansi'         => $request->id_instansi,
                    'id_jabatan'          => $request->id_jabatan,
                ]
            );
        } else {
            // Jika role diubah dari konselor → konsuli, hapus profil konselor
            $user->konselorProfile?->delete();
        }

        return redirect()
            ->route('admin.kelola_pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    /* ------------------------------------------------------------------ */
    /*  DESTROY                                                             */
    /* ------------------------------------------------------------------ */
    public function destroy(User $user)
    {
        // Profil konselor akan terhapus otomatis jika ada cascade di migration,
        // atau hapus manual:
        $user->konselorProfile?->delete();
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    /* ------------------------------------------------------------------ */
    /*  HELPER: pesan validasi bersama                                      */
    /* ------------------------------------------------------------------ */
    private function pesanValidasi(): array
    {
        return [
            'nama.required'              => 'Nama wajib diisi.',
            'nama.min'                   => 'Nama minimal 3 karakter.',
            'email.required'             => 'Email wajib diisi.',
            'email.email'                => 'Format email tidak valid.',
            'email.unique'               => 'Email sudah terdaftar.',
            'jenis_kelamin.required'     => 'Jenis kelamin wajib dipilih.',
            'npm_nip.required'           => 'NPM/NIP wajib diisi.',
            'npm_nip.unique'             => 'NPM/NIP sudah terdaftar.',
            'status_sivitas_id.required' => 'Status sivitas wajib dipilih.',
            'status_sivitas_id.exists'   => 'Status sivitas tidak valid.',
            'unit_id.required'           => 'Unit wajib dipilih.',
            'unit_id.exists'             => 'Unit tidak valid.',
            'role.required'              => 'Role wajib dipilih.',
            'role.in'                    => 'Role tidak valid.',
            'password.required'          => 'Password wajib diisi.',
            'password.min'               => 'Password minimal 8 karakter.',
            'password.confirmed'         => 'Konfirmasi password tidak cocok.',
        ];
    }
}