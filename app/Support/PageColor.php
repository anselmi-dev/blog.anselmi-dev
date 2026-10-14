<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use App\Models\SiteColor;

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

    /**
     * Opciones para elegir el color: los colores del recurso Colores (con su
     * muestra) y, si ya había guardado uno que no está ahí, ese también.
     *
     * @return array<string, string>
     */
    public static function options(?string $current = null): array
    {
        $options = once(fn (): array => SiteColor::query()
            ->orderBy('column_index')
            ->orderBy('sort_order')
            ->get(['name', 'hex'])
            ->filter(fn (SiteColor $color): bool => CssColor::isHex($color->hex))
            ->mapWithKeys(fn (SiteColor $color): array => [
                $color->hex => self::optionLabel($color->hex, $color->name),
            ])
            ->all());

        $current = CssColor::hexOrNull($current);

        if ($current !== null && ! array_key_exists($current, $options)) {
            $options[$current] = self::optionLabel($current, 'Personalizado');
        }

        return $options;
    }

    protected static function optionLabel(string $hex, string $name): string
    {
        return '<span style="display:inline-flex;align-items:center;gap:.5rem">'
            .'<span style="width:1rem;height:1rem;border-radius:.25rem;border:1px solid rgb(0 0 0 / .15);background:'.e($hex).'"></span>'
            .e($name).' <span style="opacity:.6">'.e($hex).'</span></span>';
    }

    public static function current(): ?string
    {
        return self::for(request()->route()?->getName());
    }
}
