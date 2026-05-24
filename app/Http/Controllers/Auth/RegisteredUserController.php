<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\StatusSivitas;
use App\Models\Unit;
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
    public function create(): View
    {
        $statusSivitasList = StatusSivitas::all();
        $units = Unit::all();

        return view('auth.register', compact('statusSivitasList', 'units'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'nama'              => ['required', 'string', 'max:255'],
            'jenis_kelamin'     => ['required', 'string'],
            'npm_nip' => ['required', 'string', 'max:50', 'unique:users'],
            'no_telp'           => ['nullable', 'string', 'max:20'],
            'status_sivitas_id' => ['required', 'exists:status_sivitas,id'],
            'unit_id'           => ['required', 'exists:units,id'],
            'email'             => ['required', 'email', 'max:255', 'unique:users'], 
            'password'          => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                   // ->uncompromised(),
            ],
            'terms'             => ['accepted'],
        ]);

        $user = User::create([
            'nama'              => $request->nama,
            'jenis_kelamin'     => $request->jenis_kelamin,
            'npm_nip'           => $request->npm_nip,
            'no_telp'           => $request->no_telp,
            'status_sivitas_id' => $request->status_sivitas_id,
            'unit_id'           => $request->unit_id,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}