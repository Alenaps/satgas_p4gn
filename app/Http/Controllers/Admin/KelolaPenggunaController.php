<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
class KelolaPenggunaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $role   = $request->role;

        $users = User::query()
            ->where('role', '!=', 'admin')
            ->when($search, function($q) use ($search){
                $q->where('nama', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            })
            ->when($role, function($q) use ($role){
                $q->where('role', $role);
            })
            ->latest()
            ->paginate(10);

        return view('admin.kelola_pengguna.index', compact('users'));
    }

    public function create()
    {
        return view('admin.kelola_pengguna.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama'     => 'required|string|min:3',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:konselor,konsuli',
            'password' => 'required|min:8|confirmed',
        ], [
            'nama.required' => 'Nama wajib diisi',
            'nama.min'      => 'Nama minimal 3 karakter',

            'email.required' => 'Email wajib diisi',
            'email.email'    => 'Format email tidak valid',
            'email.unique'   => 'Email sudah terdaftar',

            'role.required' => 'Role wajib dipilih',
            'role.in'       => 'Role tidak valid',

            'password.required'  => 'Password wajib diisi',
            'password.min'       => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak sama',
        ]);

        User::create([
            'nama'     => $request->nama,
            'email'    => $request->email,
            'role'     => $request->role,
            'password' => bcrypt($request->password),

            // default aman
            'npm_nip'        => '-',
            'jenis_kelamin'  => 'Perempuan',
            'no_telp'        => '-',
            'status_sivitas' => 'Aktif',
            'foto'           => null,
        ]);

        return redirect()
            ->route('admin.kelola_pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan!');
    }



    public function edit(User $user)
    {
        return view('admin.kelola_pengguna.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'nama'  => 'required|string',
            'email' => "required|email:rfc,dns|unique:users,email,{$user->id}",
            'role'  => 'required|in:konselor,konsuli',
        ],[
            'nama.required' => 'Nama wajib diisi',
            'nama.min' => 'Nama minimal 3 karakter',

            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah digunakan',

            'role.required' => 'Role wajib dipilih',
    ]);

        $data = [
            'nama'  => $request->nama,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        // Jika password diisi, update
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', Password::min(8), 'confirmed'],
            ],[
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ]);

            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.kelola_pengguna.index')
                         ->with('success', 'Pengguna berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}


