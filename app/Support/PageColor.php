<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;

/**
 * Color de marca de cada página pública, elegido en Filament (Ajustes → Sitio →
 * Color por página). El layout lo aplica sobre --color-brand-lime y sus tonos
 * claros; una página con color propio (un proyecto) lo pisa con $themeColor.
 */
final class PageColor
{
    /**
     * Rutas públicas (sin el prefijo "en.") que admiten un color propio.
     *
     * @var array<string, string>
     */
    public const PAGES = [
        'home' => 'Inicio',
        'about' => 'Sobre mí',
        'faq' => 'FAQ',
        'gallery' => 'Galería',
        'gallery.show' => 'Galería — álbum',
        'playlist' => 'Playlist',
        'projects' => 'Proyectos',
        'projects.show' => 'Proyectos — detalle',
        'blog' => 'Blog',
        'blog.show' => 'Blog — post',
        'services.fonts' => 'Servicios — fuentes',
        'services.colors' => 'Servicios — colores',
        'services.tools' => 'Servicios — herramientas',
    ];

    /**
     * Color guardado para la ruta (en español o "en.*"), o null si no tiene.
     */
    public static function for(?string $routeName): ?string
    {
        if ($routeName === null) {
            return null;
        }

        $routeName = str_starts_with($routeName, 'en.') ? substr($routeName, 3) : $routeName;
        $colors = Setting::site()['page_colors'] ?? [];
        $color = is_array($colors) ? ($colors[self::settingKey($routeName)] ?? null) : null;

        return is_string($color) ? CssColor::hexOrNull($color) : null;
    }

    /**
     * Clave dentro de site.page_colors: sin puntos, para que el formulario no
     * la anide ("gallery.show" → "gallery-show").
     */
    public static function settingKey(string $routeName): string
    {
        return str_replace('.', '-', $routeName);
    }

    public static function current(): ?string
    {
        return self::for(request()->route()?->getName());
    }
}
