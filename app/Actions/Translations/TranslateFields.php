<?php

declare(strict_types=1);

namespace App\Actions\Translations;

use Anthropic\Beta\Messages\BetaMessage;
use Anthropic\Client;
use Anthropic\Core\Exceptions\APIConnectionException;
use Anthropic\Core\Exceptions\APIException;
use Anthropic\Core\Exceptions\APIStatusException;
use Anthropic\Core\Exceptions\AuthenticationException;
use Anthropic\Core\Exceptions\BadRequestException;
use Anthropic\Core\Exceptions\PermissionDeniedException;
use Anthropic\Core\Exceptions\RateLimitException;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;

/**
 * Traduce textos del español a los demás idiomas del sitio con Claude.
 * Devuelve una traducción por idioma y campo, con las mismas claves.
 */
class TranslateFields
{
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

        $apiKey = Setting::anthropicApiKey()
            ?? throw new TranslationFailed('Falta la API key de Anthropic: cargala en Ajustes → IA.');

        $languages = implode(', ', array_map(fn (string $locale): string => self::LANGUAGES[$locale]." ({$locale})", $locales));
        $request = "Translate these Spanish texts into: {$languages}.\n\n"
            .json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        $json = $this->send($apiKey, $request, $this->schemaFor(array_keys($fields), $locales));

        $translations = json_decode($json, true);

        if (! is_array($translations)) {
            throw new TranslationFailed('La respuesta de la IA no se pudo leer. Probá de nuevo.');
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
     * Pide la traducción a Claude y devuelve el JSON de la respuesta.
     *
     * @param  array<string, mixed>  $schema
     *
     * @throws TranslationFailed
     */
    protected function send(string $apiKey, string $request, array $schema): string
    {
        try {
            try {
                $message = $this->request($apiKey, $request, $schema, withFallbacks: true);
            } catch (BadRequestException $exception) {
                // Si la cuenta no tiene habilitado el fallback (beta), se pide sin él.
                if (! $this->isAboutFallbacks($exception)) {
                    throw $exception;
                }

                $message = $this->request($apiKey, $request, $schema, withFallbacks: false);
            }
        } catch (AuthenticationException|PermissionDeniedException) {
            throw new TranslationFailed('Anthropic rechazó la API key. Revisala en Ajustes → IA.');
        } catch (RateLimitException) {
            throw new TranslationFailed('Se alcanzó el límite de pedidos de la API. Esperá un momento y probá de nuevo.');
        } catch (APIConnectionException) {
            throw new TranslationFailed('No se pudo conectar con la API de Anthropic. Probá de nuevo en un rato.');
        } catch (APIStatusException $exception) {
            $reason = $this->apiErrorMessage($exception);

            Log::warning('La traducción con Claude falló', [
                'status' => $exception->status,
                'error' => $reason,
                'request_id' => $exception->response?->getHeaderLine('request-id'),
            ]);

            throw new TranslationFailed(
                "La API de Anthropic devolvió un error ({$exception->status})".($reason !== null ? ": {$reason}" : '.')
            );
        } catch (APIException) {
            throw new TranslationFailed('La traducción falló. Probá de nuevo.');
        }

        return $this->textOf($message);
    }

    /**
     * @param  array<string, mixed>  $schema
     */
    protected function request(string $apiKey, string $request, array $schema, bool $withFallbacks): BetaMessage
    {
        $client = new Client(apiKey: $apiKey);

        return $client->beta->messages->create(
            model: (string) config('services.anthropic.translation_model'),
            maxTokens: 16000,
            system: self::SYSTEM_PROMPT,
            messages: [['role' => 'user', 'content' => $request]],
            outputConfig: [
                'effort' => 'low',
                'format' => ['type' => 'json_schema', 'schema' => $schema],
            ],
            // Si el modelo declina el pedido, la API lo reintenta con otro modelo.
            betas: $withFallbacks ? ['server-side-fallback-2026-07-01'] : null,
            fallbacks: $withFallbacks ? 'default' : null,
            // Solo para claves que no pertenecen a un workspace.
            workspaceID: Setting::anthropicWorkspaceId(),
        );
    }

    /**
     * El motivo que da la API ("error.message" del cuerpo de la respuesta).
     */
    private function apiErrorMessage(APIStatusException $exception): ?string
    {
        $message = is_array($exception->body) ? ($exception->body['error']['message'] ?? null) : null;

        return is_string($message) && $message !== '' ? $message : null;
    }

    private function isAboutFallbacks(BadRequestException $exception): bool
    {
        $message = (string) $this->apiErrorMessage($exception);

        return str_contains($message, 'anthropic-beta') || str_contains($message, 'fallback');
    }

    /**
     * @throws TranslationFailed
     */
    private function textOf(BetaMessage $message): string
    {
        if ($message->stopReason === 'refusal') {
            throw new TranslationFailed('La IA no quiso traducir este texto.');
        }

        if ($message->stopReason === 'max_tokens') {
            throw new TranslationFailed('El texto es demasiado largo para traducirlo de una vez.');
        }

        foreach ($message->content as $block) {
            if ($block->type === 'text') {
                return $block->text;
            }
        }

        throw new TranslationFailed('La IA no devolvió ninguna traducción. Probá de nuevo.');
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
