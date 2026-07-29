<?php

namespace App\Providers;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\LoginResponse as CustomLoginResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

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
      // Memaksa URL dasar selalu merujuk ke .env (Menyelesaikan 403 Signature Mismatch)
        URL::forceRootUrl(config('app.url'));
       
       //Memaksa pembuatan tautan menggunakan HTTPS
       if (config('app.env') !== 'local') {
            URL::forceScheme('https');
            request()->server->set('HTTPS', 'on');
        }
        
        // Mendaftarkan koneksi API Brevo ke dalam sistem Mail Laravel
        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory())->create(
                Dsn::fromString(env('BREVO_DSN'))
            );
        });
    }
}
