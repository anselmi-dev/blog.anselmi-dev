<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Actions\Ai\ClaudeRequestFailed;
use App\Actions\Ai\CompleteFields;
use App\Support\ImageForClaude;
use Closure;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;

/**
 * Botón "Completar con IA": escribe los campos en español que estén vacíos
 * a partir de lo que ya tiene el formulario y completa su versión en inglés
 * ("{campo}_en"). Si todos tienen texto, ofrece reescribirlos.
 */
class CompleteWithAiAction
{
    /**
     * @param  string  $about  Qué es el registro, para la IA (ej. "una herramienta que uso para programar").
     * @param  array<string, string|array{0: string, 1: Closure}>  $guidance  Campo en español => qué es y cómo
     *                                                                        escribirlo; con [texto, fn (Get): bool] solo se escribe cuando la condición se cumple.
     * @param  array<string, string>  $context  Campo que no se escribe => etiqueta (ej. 'url' => 'Sitio web').
     * @param  bool  $webSearch  Si puede buscar en la web (para datos públicos, no para contenido personal).
     * @param  string|null  $image  Campo de imagen que la IA puede ver.
     */
    public static function make(string $about, array $guidance, array $context = [], bool $webSearch = false, ?string $image = null): Action
    {
        return Action::make('completeWithAi')
            ->label('Completar con IA')
            ->icon(Heroicon::OutlinedSparkles)
            ->color('gray')
            ->tooltip('Escribe los campos vacíos a partir de lo que ya cargaste y completa el inglés.')
            ->requiresConfirmation(fn (Get $get): bool => self::allFilled($get, array_keys(self::activeGuidance($get, $guidance))))
            ->modalHeading('¿Reescribir los textos?')
            ->modalDescription('Todos los campos ya tienen texto: la IA los va a reescribir, en español y en inglés.')
            ->modalSubmitActionLabel('Reescribir')
            ->action(function (Get $get, Set $set) use ($about, $guidance, $context, $webSearch, $image): void {
                $guidance = self::activeGuidance($get, $guidance);
                $fields = array_keys($guidance);
                $write = array_values(array_filter($fields, fn (string $field): bool => blank($get($field))));
                $write = $write === [] ? $fields : $write;

                $known = collect($context)
                    ->mapWithKeys(fn (string $label, string $field): array => [$label => self::text($get($field))])
                    ->filter(fn (string $text): bool => $text !== '')
                    ->all();

                // Lo que ya está escrito en español (y no se reescribe) también es contexto.
                $translate = collect($fields)
                    ->diff($write)
                    ->mapWithKeys(fn (string $field): array => [$field => self::text($get($field))])
                    ->filter(fn (string $text): bool => $text !== '')
                    ->all();

                try {
                    $result = app(CompleteFields::class)->handle(
                        about: $about,
                        guidance: $guidance,
                        context: [...$known, ...$translate],
                        write: $write,
                        translate: collect($translate)->filter(fn (string $text, string $field): bool => blank($get("{$field}_en")))->all(),
                        webSearch: $webSearch,
                        image: $image !== null ? ImageForClaude::fromState($get($image)) : null,
                    );
                } catch (ClaudeRequestFailed $exception) {
                    Notification::make()->danger()->title('No se pudo completar')->body($exception->getMessage())->send();

                    return;
                }

                foreach ($result['es'] as $field => $text) {
                    $set($field, $text);
                }

                foreach ($result['en'] as $field => $text) {
                    $set("{$field}_en", $text);
                }

                $count = count($result['es']);

                Notification::make()
                    ->success()
                    ->title($count === 1 ? 'Se escribió 1 campo' : "Se escribieron {$count} campos")
                    ->body('También se completó el inglés. Revisalos antes de guardar.')
                    ->send();
            });
    }

    /**
     * Las indicaciones de los campos que corresponden según el formulario.
     *
     * @param  array<string, string|array{0: string, 1: Closure}>  $guidance
     * @return array<string, string>
     */
    private static function activeGuidance(Get $get, array $guidance): array
    {
        return collect($guidance)
            ->filter(fn (string|array $item): bool => is_string($item) || (bool) $item[1]($get))
            ->map(fn (string|array $item): string => is_string($item) ? $item : $item[0])
            ->all();
    }

    /**
     * @param  list<string>  $fields
     */
    private static function allFilled(Get $get, array $fields): bool
    {
        return collect($fields)->every(fn (string $field): bool => filled($get($field)));
    }

    /**
     * Texto de un campo; los repetidores (p. ej. los párrafos de un post) se unen.
     */
    private static function text(mixed $value): string
    {
        if (is_array($value)) {
            return collect($value)
                ->map(fn (mixed $item): mixed => is_array($item) ? reset($item) : $item)
                ->filter(fn (mixed $item): bool => is_string($item) && trim($item) !== '')
                ->implode("\n\n");
        }

        return is_scalar($value) ? trim((string) $value) : '';
    }
}
