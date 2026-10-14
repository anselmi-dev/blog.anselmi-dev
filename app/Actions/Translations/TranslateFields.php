<?php

declare(strict_types=1);

namespace App\Actions\Translations;

use App\Actions\Ai\AskClaude;
use App\Actions\Ai\ClaudeRequestFailed;

/**
 * Traduce textos del español a los demás idiomas del sitio con Claude.
 * Devuelve una traducción por idioma y campo, con las mismas claves.
 */
class TranslateFields
{
    public function __construct(private AskClaude $askClaude) {}

    /**
     * @var array<string, string>
     */
    public const array LANGUAGES = [
        'en' => 'English',
    ];

    private const string SYSTEM_PROMPT = <<<'PROMPT'
        You translate the content of a personal portfolio website (photography, web development, a blog) from Spanish (Rioplatense, Uruguay) into other languages.

        You receive a JSON object with the Spanish texts, one per field, and return the same fields translated into each requested language.

        - Keep the meaning, tone and register; do not add, explain or drop anything.
        - Keep proper nouns, brand names, place names and camera or technical values (f/8, 1/125s, ISO 400) as they are.
        - Keep line breaks, Markdown and HTML exactly where they are.
        - Short labels (a title, a category, a tag) stay short labels.
        PROMPT;

    /**
     * @param  array<string, string|null>  $fields  Nombre del campo => texto en español.
     * @param  list<string>  $locales  Idiomas destino (claves de LANGUAGES).
     * @return array<string, array<string, string>> Idioma => (campo => traducción).
     *
     * @throws TranslationFailed
     */
    public function handle(array $fields, array $locales = ['en']): array
    {
        $fields = array_filter($fields, fn (?string $text): bool => filled($text));
        $locales = array_values(array_intersect($locales, array_keys(self::LANGUAGES)));

        if ($fields === [] || $locales === []) {
            return [];
        }

        $languages = implode(', ', array_map(fn (string $locale): string => self::LANGUAGES[$locale]." ({$locale})", $locales));
        $request = "Translate these Spanish texts into: {$languages}.\n\n"
            .json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        try {
            $translations = $this->askClaude->handle(self::SYSTEM_PROMPT, $request, $this->schemaFor(array_keys($fields), $locales));
        } catch (ClaudeRequestFailed $exception) {
            throw new TranslationFailed($exception->getMessage(), previous: $exception);
        }

        return collect($locales)
            ->mapWithKeys(fn (string $locale): array => [
                $locale => collect($fields)
                    ->keys()
                    ->filter(fn (string $field): bool => is_string($translations[$locale][$field] ?? null))
                    ->mapWithKeys(fn (string $field): array => [$field => trim($translations[$locale][$field])])
                    ->all(),
            ])
            ->all();
    }

    /**
     * @param  list<string>  $fields
     * @param  list<string>  $locales
     * @return array<string, mixed>
     */
    private function schemaFor(array $fields, array $locales): array
    {
        $localeSchema = [
            'type' => 'object',
            'properties' => array_fill_keys($fields, ['type' => 'string']),
            'required' => $fields,
            'additionalProperties' => false,
        ];

        return [
            'type' => 'object',
            'properties' => array_fill_keys($locales, $localeSchema),
            'required' => $locales,
            'additionalProperties' => false,
        ];
    }
}
