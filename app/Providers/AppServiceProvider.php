<?php

namespace App\Providers;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\LoginResponse as CustomLoginResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      //Memaksa URL dasar selalu merujuk ke .env (Menyelesaikan 403 Signature Mismatch)
        URL::forceRootUrl(config('app.url'));
       
       //Memaksa pembuatan tautan menggunakan HTTPS
       if (config('app.env') !== 'local') {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }
        
        //Mendaftarkan koneksi API Brevo ke dalam sistem Mail Laravel
        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory())->create(
                Dsn::fromString(env('BREVO_DSN'))
            );
        });

        // Mengarahkan email verifikasi ke view kustom
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Akun - ' . config('app.name'))
                ->view('emails.verify', [
                    'url' => $url,
                    'user' => $notifiable, // Mengirim data user ke blade
                ]);
        });

        // Mengarahkan email reset password ke view kustom
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            // Buat URL reset password
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Reset Password - ' . config('app.name'))
                ->view('emails.reset', [
                    'url' => $url,
                    'user' => $notifiable, // Mengirim data user ke blade
                ]);
        });

    }
}
