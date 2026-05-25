@extends('layouts.auth')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-green-500 py-8 px-4">
        <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-8 border border-green-100">
            
            <h2 class="text-2xl font-bold text-blue-700 text-center mb-6">
                DAFTAR
            </h2>

            <form method="POST" action="{{ route('register') }}" class="relative z-50" novalidate>
                @csrf

                <!-- Nama -->
                <div>
                    <label for="nama" class="block text-sm font-semibold text-gray-700">Nama Lengkap</label>
                    <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required autofocus
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('nama')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NPM/NIP -->
                <div>
                    <label for="npm_nip" class="block text-sm font-semibold text-gray-700">NPM / NIP</label>
                    <input id="npm_nip" type="text" name="npm_nip" value="{{ old('npm_nip') }}" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('npm_nip')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- No Telp -->
                <div>
                    <label for="no_telp" class="block text-sm font-semibold text-gray-700">No. Telepon</label>
                    <input id="no_telp" type="text" name="no_telp" value="{{ old('no_telp') }}"
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('no_telp')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                    <input id="password" type="password" name="password" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                        class="mt-1 block w-full rounded-md px-2 py-1 border border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 text-gray-800">
                </div>

                <!-- Tombol -->
                <div class="pt-4">
                    <button type="submit"
                        class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all duration-200 shadow-md">
                        Daftar
                    </button>
                </div>
            </form>

            <p class="text-center text-sm text-gray-600 mt-6">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold">Login di sini</a>
            </p>
            
        </div>
    </div>
@endsection
