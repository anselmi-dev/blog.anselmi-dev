<?php

declare(strict_types=1);

namespace App\CuriousCode;

use App\Support\Localization;
use Illuminate\Support\Facades\Lang;

/**
 * Proyectos ajenos de "Código curioso" (config/curious_code.php) en el idioma
 * actual. Los textos en inglés salen de lang/en/curious_code.php; lo que no
 * esté traducido se muestra en español.
 */
final class CuriousCodeCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function entries(): array
    {
        $entries = config('curious_code.entries', []);

        if (! Localization::isEnglish()) {
            return $entries;
        }

        $translations = Lang::get('curious_code', [], Localization::ENGLISH);

        if (! is_array($translations)) {
            return $entries;
        }

        foreach ($entries as $slug => $entry) {
            $entries[$slug] = self::translate($entry, $translations[$slug] ?? []);
        }

        return $entries;
    }

    /**
     * @param  array<string, mixed>  $entry
     * @param  array<string, mixed>  $translation
     * @return array<string, mixed>
     */
    private static function translate(array $entry, array $translation): array
    {
        foreach (['title', 'excerpt', 'description'] as $field) {
            if (filled($translation[$field] ?? null)) {
                $entry[$field] = $translation[$field];
            }
        }

        return $entry;
    }
}
