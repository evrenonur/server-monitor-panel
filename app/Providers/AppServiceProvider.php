<?php

namespace App\Providers;

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
        // WebSocket ayarlarını config'e ekle
        config([
            'app.websocket_host' => env('WEBSOCKET_HOST', '127.0.0.1'),
            'app.websocket_port' => env('WEBSOCKET_PORT', 8090),
            'app.websocket_secure' => env('WEBSOCKET_SECURE', false)
        ]);
    }
}
