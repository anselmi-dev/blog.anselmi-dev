<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Routing\Route;

/**
 * Idiomas del sitio público: español en las URLs sin prefijo e inglés bajo /en,
 * con rutas nombradas "{name}" y "en.{name}" respectivamente.
 */
final class Localization
{
    public const DEFAULT_LOCALE = 'es';

    public const ENGLISH = 'en';

    /**
     * @var list<string>
     */
    public const LOCALES = ['es', 'en'];

    /**
     * URL de una ruta del sitio en el idioma pedido (por defecto, el actual).
     *
     * @param  array<string, mixed>|string|int  $parameters
     */
    public static function route(string $name, array|string|int $parameters = [], ?string $locale = null): string
    {
        return route(self::routeName($name, $locale ?? app()->getLocale()), $parameters);
    }

    public static function routeName(string $name, string $locale): string
    {
        $baseName = self::baseRouteName($name);

        return $locale === self::ENGLISH ? self::ENGLISH.'.'.$baseName : $baseName;
    }

    public static function baseRouteName(string $name): string
    {
        return str_starts_with($name, self::ENGLISH.'.') ? substr($name, strlen(self::ENGLISH) + 1) : $name;
    }

    public static function isEnglish(): bool
    {
        return app()->getLocale() === self::ENGLISH;
    }

    /**
     * URL equivalente a la página actual en otro idioma. Si la página no tiene
     * versión traducida, devuelve la home de ese idioma.
     */
    public static function switchUrl(string $locale, ?Route $route = null): string
    {
        $route ??= request()->route();
        $name = $route?->getName();

        if ($name !== null && $route !== null) {
            $targetName = self::routeName($name, $locale);

            if (app('router')->has($targetName)) {
                return route($targetName, $route->parameters());
            }
        }

        return route(self::routeName('home', $locale));
    }

    /**
     * Idioma deducido de la URL, para respuestas sin ruta (por ejemplo, 404).
     */
    public static function fromPath(string $path): string
    {
        $firstSegment = explode('/', trim($path, '/'))[0];

        return $firstSegment === self::ENGLISH ? self::ENGLISH : self::DEFAULT_LOCALE;
    }
}
