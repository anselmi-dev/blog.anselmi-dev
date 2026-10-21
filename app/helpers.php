<?php

declare(strict_types=1);

use App\Support\Localization;

if (! function_exists('localized_route')) {
    /**
     * URL de una ruta pública del sitio en el idioma actual (o en el indicado).
     *
     * @param  array<string, mixed>|string|int  $parameters
     */
    function localized_route(string $name, array|string|int $parameters = [], ?string $locale = null): string
    {
        return Localization::route($name, $parameters, $locale);
    }
}
