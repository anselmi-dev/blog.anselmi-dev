<?php

namespace App\Filament\Resources\Galleries\Pickers;

use App\Models\Quote;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Tarjetas de citas para elegir el contenido de un ítem de galería.
 */
class QuotePicker
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Quote::query()->latest('id'))
            ->columns([
                Stack::make([
                    TextColumn::make('quote')
                        ->icon('heroicon-m-chat-bubble-bottom-center-text')
                        ->iconColor('warning')
                        ->lineClamp(4)
                        ->wrap()
                        ->searchable(['quote', 'attribution']),
                    TextColumn::make('attribution')
                        ->color('gray')
                        ->size('xs'),
                ])->space(2),
            ])
            ->contentGrid(['sm' => 2, 'xl' => 3])
            ->paginated([12, 24, 48])
            ->defaultPaginationPageOption(12);
    }
}
