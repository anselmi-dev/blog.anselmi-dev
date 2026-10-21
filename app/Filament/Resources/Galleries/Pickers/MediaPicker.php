<?php

namespace App\Filament\Resources\Galleries\Pickers;

use App\Models\Media;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Grilla de fotos para elegir el contenido de un ítem de galería.
 */
class MediaPicker
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Media::query()->latest('id'))
            ->columns([
                Stack::make([
                    ImageColumn::make('thumb_path')
                        ->disk('public')
                        ->getStateUsing(fn (Media $record): ?string => $record->thumb_path ?? $record->image_path)
                        ->defaultImageUrl(fn (Media $record): string => $record->imageUrl(320, 320))
                        ->imageHeight('8rem')
                        ->imageWidth('100%')
                        ->extraImgAttributes(['style' => 'object-fit:cover;border-radius:0.5rem;', 'loading' => 'lazy']),
                    TextColumn::make('title')
                        ->weight('medium')
                        ->placeholder('Sin título')
                        ->searchable(['title', 'category', 'location']),
                    TextColumn::make('category')
                        ->color('gray')
                        ->size('xs'),
                ])->space(2),
            ])
            ->contentGrid(['sm' => 2, 'md' => 3, 'xl' => 4])
            ->paginated([12, 24, 48])
            ->defaultPaginationPageOption(12);
    }
}
