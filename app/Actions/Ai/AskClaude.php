<?php

declare(strict_types=1);

namespace App\Actions\Ai;

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
 * Un pedido a Claude que devuelve JSON con la forma de $schema. Usa la clave
 * de Ajustes → IA (cifrada) y el modelo de services.anthropic, y traduce los
 * errores de la API a mensajes que se pueden mostrar en el panel.
 */
class AskClaude
{
    /** Veces que se retoma una respuesta pausada por la búsqueda web. */
    private const int MAX_RESUMES = 2;

    /**
     * @param  array<string, mixed>  $schema  JSON schema de la respuesta.
     * @param  int  $webSearches  Búsquedas web que puede hacer (0 = sin búsqueda).
     * @return array<string, mixed>
     *
     * @throws ClaudeRequestFailed
     */
    public function handle(string $system, string $prompt, array $schema, int $webSearches = 0): array
    {
        $apiKey = Setting::anthropicApiKey()
            ?? throw new ClaudeRequestFailed('Falta la API key de Anthropic: cargala en Ajustes → IA.');

        $json = json_decode($this->send($apiKey, $system, $prompt, $schema, $webSearches), true);

        if (! is_array($json)) {
            throw new ClaudeRequestFailed('La respuesta de la IA no se pudo leer. Probá de nuevo.');
        }

        return $json;
    }

    /**
     * Hace el pedido (retomándolo si la búsqueda lo dejó en pausa) y devuelve el JSON de la respuesta.
     *
     * @param  array<string, mixed>  $schema
     *
     * @throws ClaudeRequestFailed
     */
    protected function send(string $apiKey, string $system, string $prompt, array $schema, int $webSearches): string
    {
        $messages = [['role' => 'user', 'content' => $prompt]];
        $withFallbacks = $this->modelHasFallbacks();

        try {
            for ($resume = 0; ; $resume++) {
                try {
                    $message = $this->request($apiKey, $system, $messages, $schema, $webSearches, $withFallbacks);
                } catch (BadRequestException $exception) {
                    // Si la cuenta no tiene habilitado el fallback (beta), se pide sin él.
                    if (! $withFallbacks || ! $this->isAboutFallbacks($exception)) {
                        throw $exception;
                    }

                    $withFallbacks = false;
                    $message = $this->request($apiKey, $system, $messages, $schema, $webSearches, $withFallbacks);
                }

                // La búsqueda web puede pausar el turno: se reenvía tal cual y la API lo retoma.
                if ($message->stopReason !== 'pause_turn' || $resume >= self::MAX_RESUMES) {
                    break;
                }

                $messages = [$messages[0], ['role' => 'assistant', 'content' => json_decode((string) json_encode($message->content), true)]];
            }
        } catch (AuthenticationException|PermissionDeniedException) {
            throw new ClaudeRequestFailed('Anthropic rechazó la API key. Revisala en Ajustes → IA.');
        } catch (RateLimitException) {
            throw new ClaudeRequestFailed('Se alcanzó el límite de pedidos de la API. Esperá un momento y probá de nuevo.');
        } catch (APIConnectionException) {
            throw new ClaudeRequestFailed('No se pudo conectar con la API de Anthropic. Probá de nuevo en un rato.');
        } catch (APIStatusException $exception) {
            $reason = $this->apiErrorMessage($exception);

            Log::warning('El pedido a Claude falló', [
                'status' => $exception->status,
                'error' => $reason,
                'request_id' => $exception->response?->getHeaderLine('request-id'),
            ]);

            throw new ClaudeRequestFailed(
                "La API de Anthropic devolvió un error ({$exception->status})".($reason !== null ? ": {$reason}" : '.')
            );
        } catch (APIException) {
            throw new ClaudeRequestFailed('El pedido a la IA falló. Probá de nuevo.');
        }

        return $this->textOf($message);
    }

    /**
     * @param  list<array<string, mixed>>  $messages
     * @param  array<string, mixed>  $schema
     */
    protected function request(string $apiKey, string $system, array $messages, array $schema, int $webSearches, bool $withFallbacks): BetaMessage
    {
        $client = new Client(apiKey: $apiKey);

        return $client->beta->messages->create(
            model: $this->model(),
            maxTokens: 16000,
            system: $system,
            messages: $messages,
            outputConfig: [
                'effort' => 'low',
                'format' => ['type' => 'json_schema', 'schema' => $schema],
            ],
            tools: $webSearches > 0 ? [$this->webSearchTool($webSearches)] : null,
            // Si el modelo declina el pedido, la API lo reintenta con otro modelo
            // (solo en los modelos que lo permiten; Haiku no).
            betas: $withFallbacks ? ['server-side-fallback-2026-07-01'] : null,
            fallbacks: $withFallbacks ? 'default' : null,
            // Solo para claves que no pertenecen a un workspace.
            workspaceID: Setting::anthropicWorkspaceId(),
        );
    }

    /**
     * Haiku usa la versión básica de la búsqueda web; los demás, la que filtra resultados.
     *
     * @return array<string, mixed>
     */
    private function webSearchTool(int $maxUses): array
    {
        return [
            'type' => $this->isHaiku() ? 'web_search_20250305' : 'web_search_20260209',
            'name' => 'web_search',
            'max_uses' => $maxUses,
        ];
    }

    /**
     * El texto final: con búsqueda web, la respuesta trae bloques de búsqueda
     * antes y el JSON puede venir partido en varios bloques de texto.
     *
     * @throws ClaudeRequestFailed
     */
    private function textOf(BetaMessage $message): string
    {
        if ($message->stopReason === 'refusal') {
            throw new ClaudeRequestFailed('La IA no quiso responder a este pedido.');
        }

        if ($message->stopReason === 'max_tokens') {
            throw new ClaudeRequestFailed('La respuesta quedó cortada. Probá con un texto más corto.');
        }

        $text = '';

        foreach ($message->content as $block) {
            $text = $block->type === 'text' ? $text.$block->text : '';
        }

        if (trim($text) === '') {
            throw new ClaudeRequestFailed('La IA no devolvió ninguna respuesta. Probá de nuevo.');
        }

        return $text;
    }

    private function model(): string
    {
        return (string) config('services.anthropic.model');
    }

    private function isHaiku(): bool
    {
        return str_starts_with($this->model(), 'claude-haiku-');
    }

    /**
     * Haiku no tiene fallback del lado del servidor: con él no se envía.
     */
    private function modelHasFallbacks(): bool
    {
        return ! $this->isHaiku();
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
}
