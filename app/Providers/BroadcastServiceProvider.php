<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    // BroadcastServiceProvider.php
    public function boot(): void
{
    Broadcast::routes(['middleware' => ['web', 'auth']]); // ← tambahkan ini
    
    require base_path('routes/channels.php');
}
}
