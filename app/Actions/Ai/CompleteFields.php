<?php

declare(strict_types=1);

namespace App\Actions\Ai;

/**
 * Escribe con Claude los campos de texto vacíos de un formulario del panel
 * (en español) a partir de lo que ya tiene cargado, y su versión en inglés.
 */
class CompleteFields
{
    private const string SYSTEM_PROMPT = <<<'PROMPT'
        You help fill in the content of a personal website from its admin panel. The site belongs to a web developer and photographer from Montevideo, Uruguay: a portfolio with a blog, a photo gallery, a playlist, books and the tools and resources he uses.

        You get what the item is, what each field is for, and what is already filled in. Write the requested fields in Spanish, then give the English version of the requested fields and of the listed existing ones.

        - Spanish is Rioplatense with voseo, as spoken in Uruguay. English is natural, written as a native speaker would, faithful to the Spanish; keep proper nouns as they are.
        - Natural and direct, like the owner wrote it himself. Not marketing, not an encyclopedia. No emojis, no hashtags, no exclamation marks.
        - Follow each field's guidance and length. Short labels stay short labels.
        - Do not invent facts about the site owner (clients, prices, dates, experiences, opinions he did not state). Build on the context; when something is unknown, keep it general instead of guessing.
        - When web search is available, use it to confirm public facts (what a tool, a font or a book is, who made it, when) and leave out anything you could not confirm.
        - When an image is attached, describe what is actually in it; do not guess places or people you cannot see.
        PROMPT;

    public function __construct(private AskClaude $askClaude) {}

    /**
     * @param  string  $about  Qué es el registro (ej. "una herramienta que uso").
     * @param  array<string, string>  $guidance  Campo en español => qué es y cómo escribirlo.
     * @param  array<string, string>  $context  Etiqueta => valor ya cargado.
     * @param  list<string>  $write  Campos a escribir en español.
     * @param  array<string, string>  $translate  Campo => texto en español ya cargado, para pasar al inglés.
     * @param  array<string, mixed>|null  $image  Bloque de imagen (ver ImageForClaude).
     * @return array{es: array<string, string>, en: array<string, string>}
     *
     * @throws ClaudeRequestFailed
     */
    public function handle(string $about, array $guidance, array $context, array $write, array $translate = [], bool $webSearch = false, ?array $image = null): array
    {
        $write = array_values(array_intersect($write, array_keys($guidance)));
        $english = array_values(array_unique([...$write, ...array_keys($translate)]));

        if ($write === [] && $english === []) {
            return ['es' => [], 'en' => []];
        }

        $prompt = "The item: {$about}.\n\n"
            .$this->section('Already filled in', $context)
            .$this->section('Spanish fields to write (field: guidance)', array_intersect_key($guidance, array_flip($write)))
            .$this->section('Existing Spanish texts to put into English too', $translate)
            .($webSearch ? '' : "Web search is not available for this item: work only from the context.\n")
            .'The current date is '.now()->toDateString().'.';

        $result = $this->askClaude->handle(
            system: self::SYSTEM_PROMPT,
            prompt: $image !== null ? [$image, ['type' => 'text', 'text' => $prompt]] : $prompt,
            schema: [
                'type' => 'object',
                'properties' => [
                    'es' => $this->objectOf($write),
                    'en' => $this->objectOf($english),
                ],
                'required' => ['es', 'en'],
                'additionalProperties' => false,
            ],
            webSearches: $webSearch ? 2 : 0,
        );

        return [
            'es' => $this->clean($result['es'] ?? [], $write),
            'en' => $this->clean($result['en'] ?? [], $english),
        ];
    }

    /**
     * @param  array<string, string>  $lines
     */
    private function section(string $title, array $lines): string
    {
        if ($lines === []) {
            return '';
        }

        return "{$title}:\n".collect($lines)->map(fn (string $text, string $label): string => "- {$label}: {$text}")->implode("\n")."\n\n";
    }

    /**
     * @param  list<string>  $fields
     * @return array<string, mixed>
     */
    private function objectOf(array $fields): array
    {
        return [
            'type' => 'object',
            'properties' => (object) array_fill_keys($fields, ['type' => 'string']),
            'required' => $fields,
            'additionalProperties' => false,
        ];
    }

    /**
     * @param  list<string>  $fields
     * @return array<string, string>
     */
    private function clean(mixed $values, array $fields): array
    {
        return collect($fields)
            ->filter(fn (string $field): bool => is_array($values) && is_string($values[$field] ?? null) && trim($values[$field]) !== '')
            ->mapWithKeys(fn (string $field): array => [$field => trim($values[$field])])
            ->all();
    }
}
