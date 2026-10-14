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
use Illuminate\Support\Str;

/**
 * Botón que traduce con IA los campos en español a los demás idiomas del
 * sitio: lee "{campo}" y completa "{campo}_{idioma}" (ej. title → title_en).
 * Los campos de texto se traducen tal cual; las listas (un repetidor de
 * párrafos, por ejemplo) se traducen elemento por elemento.
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
                // $itemKeys: en un repetidor "simple", cada elemento es ['campo' => texto].
                [$spanish, $lists, $itemKeys] = self::flatten($get, $fields);

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
                    foreach ($fields as $field) {
                        if (array_key_exists($field, $lists)) {
                            $items = collect(range(0, $lists[$field] - 1))
                                ->map(fn (int $index): ?string => $values["{$field}.{$index}"] ?? null)
                                ->filter(fn (?string $text): bool => filled($text))
                                ->mapWithKeys(fn (string $text): array => [
                                    (string) Str::uuid() => isset($itemKeys[$field]) ? [$itemKeys[$field] => $text] : $text,
                                ]);

                            if ($items->isNotEmpty()) {
                                $set("{$field}_{$locale}", $items->all());
                                $count++;
                            }

                            continue;
                        }

                        if (isset($values[$field])) {
                            $set("{$field}_{$locale}", $values[$field]);
                            $count++;
                        }
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
     * Textos a traducir, con las listas desarmadas en "{campo}.{n}", la
     * cantidad de elementos de cada lista para volver a armarlas y, si los
     * elementos son ['paragraph' => texto], la clave de ese texto.
     *
     * @param  list<string>  $fields
     * @return array{0: array<string, string|null>, 1: array<string, int>, 2: array<string, string>}
     */
    private static function flatten(Get $get, array $fields): array
    {
        $texts = [];
        $lists = [];
        $itemKeys = [];

        foreach ($fields as $field) {
            $value = $get($field);

            if (! is_array($value)) {
                $texts[$field] = is_string($value) ? $value : null;

                continue;
            }

            $items = [];

            foreach ($value as $item) {
                if (is_array($item)) {
                    $itemKey = array_key_first($item);
                    $item = $item[$itemKey] ?? null;

                    if (is_string($itemKey)) {
                        $itemKeys[$field] = $itemKey;
                    }
                }

                if (is_string($item) && filled($item)) {
                    $items[] = $item;
                }
            }

            if ($items === []) {
                continue;
            }

            $lists[$field] = count($items);

            foreach ($items as $index => $item) {
                $texts["{$field}.{$index}"] = $item;
            }
        }

        return [$texts, $lists, $itemKeys];
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
