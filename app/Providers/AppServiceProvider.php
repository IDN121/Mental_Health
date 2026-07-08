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
        // Vercel Serverless environment requires views to be compiled in /tmp
        if (isset($_ENV['VERCEL']) || env('VERCEL') == 1) {
            \Config::set('view.compiled', '/tmp');
        }
    }
}
