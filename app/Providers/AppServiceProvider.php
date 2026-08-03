<?php

namespace App\Providers;

use App\Http\Controllers\LivewireFileUploadController;
use App\Models\Faq;
use App\Models\GalleryItem;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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
        $this->configureDefaults();
        $this->shareSiteNavAvailability();
        $this->useWindowsSafeLivewireUploads();
    }

    /**
     * On Windows/Laragon, Livewire temp uploads can hit "Path must not be empty"
     * because getRealPath() returns false while the temp file still exists.
     */
    protected function useWindowsSafeLivewireUploads(): void
    {
        $this->app->booted(function (): void {
            $route = Route::getRoutes()->getByName('livewire.upload-file');

            if (! $route) {
                return;
            }

            $route->setAction(array_merge($route->getAction(), [
                'uses' => LivewireFileUploadController::class.'@handle',
                'controller' => LivewireFileUploadController::class.'@handle',
            ]));
        });
    }

    /**
     * Flags for optional nav / home sections driven by CMS content.
     */
    protected function shareSiteNavAvailability(): void
    {
        View::composer(['components.site-header', 'livewire.pages.home'], function ($view): void {
            $view->with([
                'hasPublishedFaqs' => Faq::query()->published()->exists(),
                'hasPublishedGalleryPhotos' => GalleryItem::query()
                    ->published()
                    ->where('kind', 'photo')
                    ->exists(),
            ]);
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
