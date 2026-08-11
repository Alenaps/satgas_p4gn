<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 8px;">
        
        <!-- Bagian Logo (Tengah) -->
        <div style="text-align: center; margin-bottom: 20px;">
            <img src="{{ asset('assets/logo_unila.png') }}" alt="{{ config('app.name') }}" style="max-height: 60px; max-width: 100%;">
        </div>

        <h2>Permintaan Reset Password</h2>
        <p>Halo, kami menerima permintaan untuk mereset password akun Anda.</p>
        
        <div style="text-align: center;">
        <a href="{{ $url }}" style="display: inline-block; padding: 10px 20px; background-color: #dc3545; color: #fff; text-decoration: none; border-radius: 5px;">
            Reset Password
        </a>
        </div>
        
        <p style="margin-top: 20px; color: #666; font-size: 12px;">
            Link ini akan kedaluwarsa dalam 60 menit. Jika Anda tidak meminta reset password, abaikan email ini.
        </p>
    </div>
</body>
</html>