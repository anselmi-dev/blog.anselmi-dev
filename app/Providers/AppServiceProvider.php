<?php

namespace App\Providers;

use App\Http\Controllers\LivewireFileUploadController;
use App\Models\Faq;
use App\Models\GalleryItem;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
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
        $this->shareSiteNavAvailability();
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
