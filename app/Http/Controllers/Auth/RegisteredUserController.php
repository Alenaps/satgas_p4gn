<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:60',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'npm_nip' => ['required','string','regex:/^(\d{10,12}|\d{18})$/','unique:users,npm_nip'],
            'no_telp' => 'nullable|string|max:15',
            'email' => 'required|string|email|max:40|unique:users,email',
            'password' => 'required|string|confirmed|min:8|max:16',
        ], [
            'nama.required' => 'Nama lengkap belum diisi.',
            'jenis_kelamin.required' => 'Jenis kelamin belum dipilih.',
            'npm_nip.required' => 'NPM/NIP tidak boleh kosong.',
            'npm_nip.numeric' => 'NPM/NIP harus berupa angka.',
            'npm_nip.unique' => 'NPM/NIP ini sudah terdaftar.',
            'npm_nip.regex' => 'Format NPM harus 10-12 digit atau NIP 18 digit.',
            'no_telp.required' => 'Nomor telepon tidak boleh kosong.',
            'no_telp.numeric' => 'Nomor telepon harus berupa angka.',
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password belum diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.max' => 'Password maksimal 16 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
        ]);

        // Simpan user baru
        $user = User::create([
            'nama' => $validated['nama'],
            'jenis_kelamin' => $validated['jenis_kelamin'],
            'npm_nip' => $validated['npm_nip'],
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'konsuli', 
        ]);

        //Auth::login($user);

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan login.');
        //return redirect(RouteServiceProvider::HOME);
    }

}
