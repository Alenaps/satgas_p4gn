<?php

namespace App\Http\Controllers;

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
     * Tampilkan halaman profil user dengan layout dinamis berdasarkan role
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Tampilkan halaman edit profil user dengan layout dinamis berdasarkan role
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update data profil user.
     */
    public function update(Request $request)
    {
        /** @var \App\Models\User $user */

        $user = auth()->user();
        
        // Validasi
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:15',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048', // max 2MB
        ]);
        
        try {
            // Update data dasar
            $user->nama = $validated['nama'];
            $user->no_telp = $validated['no_telp'];
            $user->jenis_kelamin = $validated['jenis_kelamin'];
            
            // Handle upload foto
            if ($request->hasFile('foto')) {
                // Hapus foto lama jika ada
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }
                
                // Simpan foto baru
                $file = $request->file('foto');
                $filename = 'profile_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('profile_photos', $filename, 'public');
                
                $user->foto = $path;
            }
            
            $user->save();
            
            return redirect()->route('profile.index')
                ->with('success', 'Profil berhasil diperbarui!');
                
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
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

        // Hapus foto jika ada
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