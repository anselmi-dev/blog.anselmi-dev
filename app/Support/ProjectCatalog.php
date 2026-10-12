<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\Lang;

/**
 * Proyectos del portafolio (config/projects.php) en el idioma actual. Los
 * textos en inglés salen de lang/en/projects.php; lo que no esté traducido
 * se muestra en español.
 */
final class ProjectCatalog
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function entries(): array
    {
        $entries = config('projects.entries', []);

        if (! Localization::isEnglish()) {
            return $entries;
        }

        $translations = Lang::get('projects', [], Localization::ENGLISH);

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
        foreach (['title', 'excerpt', 'role', 'client'] as $field) {
            if (filled($translation[$field] ?? null)) {
                $entry[$field] = $translation[$field];
            }
        }

        foreach ($entry['sections'] ?? [] as $index => $section) {
            $translatedSection = $translation['sections'][$index] ?? null;

            if (is_array($translatedSection)) {
                $entry['sections'][$index] = array_merge($section, array_filter($translatedSection));
            }
        }

        foreach ($entry['images'] ?? [] as $index => $image) {
            if (filled($translation['image_alts'][$index] ?? null)) {
                $entry['images'][$index]['alt'] = $translation['image_alts'][$index];
            }
        }

        return $entry;
    }
}
