@extends('layouts.auth')

@section('title', 'Login - SATGAS P4GN UNILA')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-green-500 px-4 py-12">
    <div class="bg-white rounded-2xl shadow-lg flex flex-col md:flex-row overflow-hidden w-full max-w-4xl">
        <!-- Bagian Kiri -->
        <div class="md:w-1/2 flex flex-col items-center justify-center bg-emerald-100 p-8 text-center border-b md:border-b-0 md:border-r border-emerald-200">
            <img src="{{ asset('assets/logo_unila.png') }}" alt="Logo UNILA" class="w-24 h-23 mb-4">
            <h2 class="text-2xl font-bold text-emerald-700 mb-2">SATGAS P4GN UNILA</h2>
            <p class="text-gray-600 text-sm leading-relaxed max-w-xs">
                Sistem Informasi Konseling dan Pelaporan Kasus Narkoba Universitas Lampung.
                Silakan masuk untuk melanjutkan.
            </p>
        </div>

        <!-- Bagian Kanan -->
        <div class="md:w-1/2 p-8">
            <h2 class="text-xl font-bold text-blue-600 text-center text-gray-800 mb-6">LOGIN</h2>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full border border-gray-300 rounded-md px-2 py-1 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 block w-full border border-gray-300 rounded-md px-2 py-1 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                 {{-- Ingat Saya dan Lupa Password --}}
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="h-4 w-4 text-green-600 border-gray-300 rounded">
                        <span class="ml-2 text-gray-600">Ingat Saya</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-blue-600 hover:underline">
                            Lupa Password?
                        </a>
                    @endif
                </div>

                <!-- Tombol -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                        Masuk
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Belum punya akun?
                <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-semibold">Daftar di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
