@extends('layouts.auth')

@section('title', 'Verifikasi Email - SATGAS P4GN UNILA')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-green-500 py-8">
    <div class="w-full max-w-md bg-white shadow-md rounded-2xl p-8 border border-green-100">

        <div class="mb-4 text-sm text-gray-600">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik tautan yang telah kami kirimkan. Jika Anda belum menerima emailnya, kami siap mengirimkan yang baru.
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                Tautan verifikasi baru telah dikirimkan ke alamat email yang Anda daftarkan.
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-primary-button>
                        Kirim Ulang Email Verifikasi
                    </x-primary-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Keluar
                </button>
            </form>
        </div>

    </div>
</div>
@endsection