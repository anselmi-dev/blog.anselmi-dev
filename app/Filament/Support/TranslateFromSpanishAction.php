<?php

declare(strict_types=1);

namespace App\Filament\Support;

use App\Actions\Translations\TranslateFields;
use App\Actions\Translations\TranslationFailed;
use App\Support\Localization;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Support\Icons\Heroicon;

/**
 * Botón que traduce con IA los campos en español a los demás idiomas del
 * sitio: lee "{campo}" y completa "{campo}_{idioma}" (ej. title → title_en).
 */
class TranslateFromSpanishAction
{
    /**
     * @param  list<string>  $fields  Campos en español que tienen versión "{campo}_{idioma}".
     */
    public static function make(array $fields): Action
    {
        $locales = self::targetLocales();

        return Action::make('translateFromSpanish')
            ->label('Traducir desde español')
            ->icon(Heroicon::OutlinedSparkles)
            ->color('gray')
            ->requiresConfirmation(fn (Get $get): bool => self::hasTranslations($get, $fields, $locales))
            ->modalHeading('¿Reemplazar las traducciones?')
            ->modalDescription('Los campos que ya tienen texto en los otros idiomas se van a reemplazar por la traducción de lo que haya en español.')
            ->modalSubmitActionLabel('Traducir')
            ->action(function (Get $get, Set $set) use ($fields, $locales): void {
                $spanish = collect($fields)->mapWithKeys(fn (string $field): array => [$field => $get($field)])
                    ->map(fn (mixed $value): ?string => is_string($value) ? $value : null)
                    ->all();

                if (collect($spanish)->filter()->isEmpty()) {
                    Notification::make()->warning()->title('No hay texto en español para traducir.')->send();

                    return;
                }

                try {
                    $translations = app(TranslateFields::class)->handle($spanish, $locales);
                } catch (TranslationFailed $exception) {
                    Notification::make()->danger()->title('No se pudo traducir')->body($exception->getMessage())->send();

                    return;
                }

                $count = 0;

                foreach ($translations as $locale => $values) {
                    foreach ($values as $field => $text) {
                        $set("{$field}_{$locale}", $text);
                        $count++;
                    }
                }

                Notification::make()
                    ->success()
                    ->title($count === 1 ? 'Se tradujo 1 campo' : "Se tradujeron {$count} campos")
                    ->body('Revisalos antes de guardar.')
                    ->send();
            });
    }

    /**
     * Todos los idiomas del sitio menos el español.
     *
     * @return list<string>
     */
    public static function targetLocales(): array
    {
        return array_values(array_diff(Localization::LOCALES, [Localization::DEFAULT_LOCALE]));
    }

    /**
     * @param  list<string>  $fields
     * @param  list<string>  $locales
     */
    private static function hasTranslations(Get $get, array $fields, array $locales): bool
    {
        foreach ($locales as $locale) {
            foreach ($fields as $field) {
                if (filled($get("{$field}_{$locale}"))) {
                    return true;
                }
            }
        }

        return false;
    }
}
