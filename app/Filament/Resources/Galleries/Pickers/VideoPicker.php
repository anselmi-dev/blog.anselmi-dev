<?php

namespace App\Filament\Resources\Galleries\Pickers;

use App\Models\Video;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Grilla de videos (con su portada) para elegir el contenido de un ítem de galería.
 */
class VideoPicker
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(Video::query()->latest('id'))
            ->columns([
                Stack::make([
                    ImageColumn::make('poster_path')
                        ->disk('public')
                        ->defaultImageUrl('data:image/svg+xml;utf8,'.rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 9"><rect width="16" height="9" fill="#e4e4e7"/><path d="M6.5 3v3l2.6-1.5z" fill="#71717a"/></svg>'))
                        ->imageHeight('8rem')
                        ->imageWidth('100%')
                        ->extraImgAttributes(['style' => 'object-fit:cover;border-radius:0.5rem;', 'loading' => 'lazy']),
                    TextColumn::make('title')
                        ->weight('medium')
                        ->placeholder('Sin título')
                        ->icon('heroicon-m-video-camera')
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
