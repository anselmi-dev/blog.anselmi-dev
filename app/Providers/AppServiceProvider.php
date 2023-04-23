<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Providers\Blade\AlpinejsAnimationsProvider;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(
            AlpinejsAnimationsProvider::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setUTF8(true);

        Carbon::setLocale(config('app.locale'));
    }
}
