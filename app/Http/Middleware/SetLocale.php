<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Support\Localization;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fija el idioma del request según el grupo de rutas (locale:es / locale:en).
 * Es persistente en Livewire, así que las actualizaciones de componentes
 * conservan el idioma de la página que los montó.
 */
class SetLocale
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string $locale = Localization::DEFAULT_LOCALE): Response
    {
        if (! in_array($locale, Localization::LOCALES, true)) {
            $locale = Localization::DEFAULT_LOCALE;
        }

        app()->setLocale($locale);
        Carbon::setLocale($locale);

        return $next($request);
    }
}
