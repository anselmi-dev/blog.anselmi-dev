<?php

declare(strict_types=1);

namespace App\Filament\Support;

use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Pestañas por idioma para los textos traducibles de un formulario: español
 * primero y luego una por cada otro idioma, con el botón para traducir con IA.
 */
class LanguageTabs
{
    /**
     * @param  array<int, Component>  $spanish  Campos en español.
     * @param  array<string, array<int, Component>>  $translations  Idioma => campos "{campo}_{idioma}".
     * @param  list<string>  $translatableFields  Campos en español que se traducen con el botón.
     */
    public static function make(array $spanish, array $translations, array $translatableFields): Tabs
    {
        $tabs = [
            Tab::make('Español')
                ->icon('heroicon-o-language')
                ->columns(2)
                ->schema($spanish),
        ];

        foreach ($translations as $locale => $components) {
            $tabs[] = Tab::make(self::label($locale))
                ->columns(2)
                ->badge(strtoupper($locale))
                ->schema([
                    Actions::make([TranslateFromSpanishAction::make($translatableFields)])
                        ->columnSpanFull(),
                    ...$components,
                ]);
        }

        return Tabs::make('Idiomas')
            ->tabs($tabs)
            ->columnSpanFull();
    }

    private static function label(string $locale): string
    {
        return match ($locale) {
            'en' => 'English',
            default => strtoupper($locale),
        };
    }
}
