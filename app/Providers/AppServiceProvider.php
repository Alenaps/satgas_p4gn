<?php

namespace App\Providers;
use Laravel\Fortify\Contracts\LoginResponse;
use App\Http\Responses\LoginResponse as CustomLoginResponse;
use Illuminate\Support\ServiceProvider;

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
        //
    }
}
