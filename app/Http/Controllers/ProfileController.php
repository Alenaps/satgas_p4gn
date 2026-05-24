<?php

namespace App\Http\Controllers;

use App\Models\Instansi;
use App\Models\Jabatan;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman profil user.
     */
    public function index(Request $request): View
    {
        $user = Auth::user()->load('konselorProfile.instansi', 'konselorProfile.jabatan');
        return view('profile.index', compact('user'));
    }

    /**
     * Tampilkan halaman edit profil user.
     */
    public function edit(Request $request): View
{
    $user = $request->user();

    // Data untuk semua role
    $data = ['user' => $user];

    // Tambah data sivitas jika role sivitas
    if ($user->role === 'konsuli') {
        $data['statusSivitasList'] = \App\Models\StatusSivitas::all();
        $data['unitList']          = \App\Models\Unit::all();
    }

    // Tambah data konselor jika role konselor
    if ($user->role === 'konselor') {
        $data['instansiList'] = \App\Models\Instansi::all();
        $data['jabatanList']  = \App\Models\Jabatan::all();
    }

    return view('profile.edit', $data);
}

public function update(Request $request): RedirectResponse
{
    $user = $request->user();

    $rules = [
        'nama'          => ['required', 'string', 'max:255'],
        'no_telp'       => ['nullable', 'string', 'max:20'],
        'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
        'foto'          => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
    ];

    if ($user->role === 'konsuli') {
        $rules['status_sivitas_id'] = ['nullable', 'exists:status_sivitas,id'];
        $rules['unit_id']           = ['nullable', 'exists:units,id'];
    }

    if ($user->role === 'konselor') {
        $rules['nomor_lisensi']      = ['nullable', 'string'];
        $rules['spesialisasi']       = ['nullable', 'string'];
        $rules['pengalaman_kerja']   = ['nullable', 'integer', 'min:0', 'max:50'];
        $rules['pendidikan_terakhir']= ['nullable', 'string'];
        $rules['id_instansi']        = ['nullable', 'exists:instansis,id'];
        $rules['id_jabatan']         = ['nullable', 'exists:jabatans,id'];
        $rules['sertifikasi_P4GN']   = ['nullable', 'boolean'];
        $rules['bio_singkat']        = ['nullable', 'string'];
    }

    $validated = $request->validate($rules);

    // Update foto jika ada
    if ($request->hasFile('foto')) {
        if ($user->foto) {
            \Storage::disk('public')->delete($user->foto);
        }
        $user->foto = $request->file('foto')->store('foto_profil', 'public');
    }

    // Update data user
    $user->nama          = $validated['nama'];
    $user->no_telp       = $validated['no_telp'] ?? null;
    $user->jenis_kelamin = $validated['jenis_kelamin'];

    if ($user->role === 'konsuli') {
        $user->status_sivitas_id = $request->status_sivitas_id;
        $user->unit_id           = $request->unit_id;
    }

    $user->save();

    // Update profil konselor
    if ($user->role === 'konselor') {
        $user->konselorProfile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'nomor_lisensi'       => $request->nomor_lisensi,
                'spesialisasi'        => $request->spesialisasi,
                'pengalaman_kerja'    => $request->pengalaman_kerja,
                'pendidikan_terakhir' => $request->pendidikan_terakhir,
                'id_instansi'         => $request->id_instansi,
                'id_jabatan'          => $request->id_jabatan,
                'sertifikasi_P4GN'    => $request->boolean('sertifikasi_P4GN'),
                'bio_singkat'         => $request->bio_singkat,
            ]
        );
    }

    return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui!');
}
    /**
     * Hapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->foto && Storage::disk('public')->exists($user->foto)) {
            Storage::disk('public')->delete($user->foto);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}