<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Paleta de un frasco de /servicios/herramientas: [etiqueta, manchas, texto, contenido].
 * Desde Filament se eligen la etiqueta y el contenido; las manchas y el texto se
 * calculan a partir de la etiqueta para que siempre haya contraste.
 */
final class JarPalette
{
    public const DEFAULT_FILL = '#c99a4b';

    private const DARK_INK = '#1f2a12';

    private const LIGHT_INK = '#f5efe0';

    /**
     * Paletas por defecto para herramientas sin colores, en orden.
     *
     * @var list<array{0: string, 1: string, 2: string, 3: string}>
     */
    public const FALLBACKS = [
        ['#1f4d33', '#3f7a52', '#f5efe0', '#c99a4b'],
        ['#7a8f2a', '#a9b84a', '#1f2a12', '#b4893e'],
        ['#e3dccb', '#c9bfa6', '#2d3b2a', '#8f9b4b'],
        ['#a8462f', '#c96a4c', '#f8ecdc', '#d29a54'],
        ['#26405f', '#3f5f85', '#eef1f4', '#a8774a'],
        ['#5e2f45', '#8a4a66', '#f6e8ef', '#c08a5a'],
    ];

    /**
     * @return array{0: string, 1: string, 2: string, 3: string}|null
     */
    public static function fromColors(?string $labelColor, ?string $fillColor = null): ?array
    {
        $label = self::normalize($labelColor);

        if ($label === null) {
            return null;
        }

        $isLight = self::luminance($label) > 0.45;

        return [
            $label,
            $isLight ? self::mix($label, '#000000', 0.12) : self::mix($label, '#ffffff', 0.18),
            $isLight ? self::DARK_INK : self::LIGHT_INK,
            self::normalize($fillColor) ?? self::DEFAULT_FILL,
        ];
    }

    /**
     * @return array{0: string, 1: string, 2: string, 3: string}
     */
    public static function fallback(int $index): array
    {
        return self::FALLBACKS[$index % count(self::FALLBACKS)];
    }

    /**
     * Convierte #rgb / #rrggbb / #rrggbbaa a #rrggbb en minúsculas.
     */
    private static function normalize(?string $value): ?string
    {
        if (! CssColor::isHex($value)) {
            return null;
        }

        $hex = ltrim((string) $value, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        return '#'.strtolower(substr($hex, 0, 6));
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private static function rgb(string $hex): array
    {
        return [
            (int) hexdec(substr($hex, 1, 2)),
            (int) hexdec(substr($hex, 3, 2)),
            (int) hexdec(substr($hex, 5, 2)),
        ];
    }

    /**
     * Luminancia relativa (0 = negro, 1 = blanco).
     */
    private static function luminance(string $hex): float
    {
        [$r, $g, $b] = array_map(function (int $channel): float {
            $value = $channel / 255;

            return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, self::rgb($hex));

        return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
    }

    private static function mix(string $hex, string $with, float $amount): string
    {
        [$r1, $g1, $b1] = self::rgb($hex);
        [$r2, $g2, $b2] = self::rgb($with);

        $channel = fn (int $a, int $b): string => str_pad(dechex((int) round($a + ($b - $a) * $amount)), 2, '0', STR_PAD_LEFT);

        return '#'.$channel($r1, $r2).$channel($g1, $g2).$channel($b1, $b2);
    }
}
