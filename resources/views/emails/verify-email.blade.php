<!DOCTYPE html>
<html>
<head>
    <title>Verifikasi Email</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 8px;">
        
        <!-- Bagian Logo (Tengah) -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ asset('assets/logo_unila.png') }}" alt="{{ config('app.name') }}" style="max-height: 60px;">
        </div>

        <h2>Halo, {{ $user->nama }}!</h2>
        <p>Terima kasih telah mendaftar di <strong>{{ config('app.name') }}</strong>.</p>
        <p>Silakan klik tombol di bawah ini untuk memverifikasi email Anda:</p>
        
        <div style="text-align: center;">
        <a href="{{ $url }}" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">
            Verifikasi Akun Saya
        </a>
        </div>
        <p style="margin-top: 20px;">Jika tombol di atas tidak berfungsi, salin dan tempel link berikut di browser Anda:</p>
        <p><a href="{{ $url }}">{{ $url }}</a></p>
    </div>
</body>
</html>