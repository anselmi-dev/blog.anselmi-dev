<?php

declare(strict_types=1);

namespace App\Support;

use Locale;

/**
 * Nombre en español y bandera de un país a partir de su código (ISO 3166-1,
 * como lo manda Cloudflare en CF-IPCountry).
 */
final class Country
{
    /** Códigos de Cloudflare que no son países: desconocido y red Tor. */
    private const array NOT_COUNTRIES = ['XX', 'T1'];

    public static function isCountry(?string $code): bool
    {
        return is_string($code) && preg_match('/^[A-Z]{2}$/', $code) === 1 && ! in_array($code, self::NOT_COUNTRIES, true);
    }

    public static function name(?string $code): string
    {
        if (! self::isCountry($code)) {
            return 'Desconocido';
        }

        $name = class_exists(Locale::class) ? Locale::getDisplayRegion("-{$code}", 'es') : '';

        return $name !== '' && $name !== $code ? $name : $code;
    }

    /**
     * Bandera emoji (dos letras regionales); vacío si no es un país.
     */
    public static function flag(?string $code): string
    {
        if (! self::isCountry($code)) {
            return '';
        }

        return mb_chr(0x1F1E6 + ord($code[0]) - 65).mb_chr(0x1F1E6 + ord($code[1]) - 65);
    }
}
