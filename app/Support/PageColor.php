<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Setting;
use App\Models\SiteColor;

/**
 * Color de marca de cada página pública, elegido en Filament (Ajustes → Sitio →
 * Color por página). El layout lo aplica sobre --color-brand-lime y sus tonos
 * claros; una página con color propio (un proyecto) lo pisa con $themeColor.
 * El color del texto va en --page-ink y tiñe los textos zinc que están sobre
 * bg-brand-lime (ver app.css).
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
     * Colores rápidos para el texto, además de los del recurso Colores.
     *
     * @var array<string, string>
     */
    public const INK_PRESETS = [
        '#FFFFFF' => 'Blanco',
        '#18181B' => 'Negro',
    ];

    /**
     * Color de fondo guardado para la ruta (en español o "en.*"), o null si no tiene.
     */
    public static function for(?string $routeName): ?string
    {
        return self::stored('page_colors', $routeName);
    }

    /**
     * Color del texto guardado para la ruta, o null si usa el de siempre.
     */
    public static function inkFor(?string $routeName): ?string
    {
        return self::stored('page_inks', $routeName);
    }

    protected static function stored(string $settingName, ?string $routeName): ?string
    {
        if ($routeName === null) {
            return null;
        }

        $routeName = str_starts_with($routeName, 'en.') ? substr($routeName, 3) : $routeName;
        $colors = Setting::site()[$settingName] ?? [];
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

    /**
     * Opciones para el color del texto: blanco, negro y los del recurso Colores.
     *
     * @return array<string, string>
     */
    public static function inkOptions(?string $current = null): array
    {
        $presets = collect(self::INK_PRESETS)
            ->mapWithKeys(fn (string $name, string $hex): array => [$hex => self::optionLabel($hex, $name)])
            ->all();

        return $presets + self::options($current);
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

    public static function currentInk(): ?string
    {
        return self::inkFor(request()->route()?->getName());
    }
}
