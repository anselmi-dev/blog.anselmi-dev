<?php

namespace App\Providers;

use App\Http\Controllers\LivewireFileUploadController;
use App\Support\SiteNavAvailability;
use Carbon\CarbonImmutable;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Livewire\Features\SupportFileUploads\FileUploadController as LivewireUploadController;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Windows/Laragon: getRealPath() can be false for valid upload temps.
        $this->app->bind(LivewireUploadController::class, LivewireFileUploadController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->configureRateLimiting();
        $this->shareSiteNavAvailability();
    }

    /**
     * Flags for optional nav / home sections driven by CMS content.
     */
    protected function shareSiteNavAvailability(): void
    {
        View::composer(
            ['components.site-header', 'livewire.pages.home', 'components.home.intro-columns'],
            function ($view): void {
                $view->with(SiteNavAvailability::flags());
            },
        );
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('contact', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
