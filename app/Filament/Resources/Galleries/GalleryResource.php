<?php

namespace App\Filament\Resources\Galleries;

use App\Filament\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Resources\Galleries\Pages\EditGallery;
use App\Filament\Resources\Galleries\Pages\ListGalleries;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Quote;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\MorphToSelect\Type;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Ítems de galería';

    protected static ?string $modelLabel = 'ítem de galería';

    protected static ?string $pluralModelLabel = 'Ítems de galería';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('collection_id')
                    ->label('Colección')
                    ->relationship('collection', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                MorphToSelect::make('galleryable')
                    ->label('Contenido')
                    ->types([
                        Type::make(Media::class)
                            ->titleColumnName('title')
                            ->getOptionLabelFromRecordUsing(fn (Media $record): string => $record->title ?: "Foto #{$record->id}"),
                        Type::make(Quote::class)
                            ->titleColumnName('quote')
                            ->getOptionLabelFromRecordUsing(fn (Quote $record): string => Str::limit($record->quote, 40)),
                    ])
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
                Select::make('span')
                    ->label('Ancho')
                    ->options([
                        'tall' => 'Normal (1 col)',
                        'wide' => 'Ancho (2 cols)',
                    ])
                    ->required()
                    ->default('tall'),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
                Toggle::make('featured')
                    ->label('Destacada')
                    ->default(false),
                Toggle::make('play')
                    ->label('Mostrar play')
                    ->default(false),
                Toggle::make('is_published')
                    ->label('Publicado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('collection.name')
                    ->label('Colección')
                    ->sortable(),
                TextColumn::make('galleryable_type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === Media::class ? 'Foto' : 'Cita'),
                TextColumn::make('preview')
                    ->label('Contenido')
                    ->getStateUsing(function (Gallery $record): string {
                        $item = $record->galleryable;

                        return match (true) {
                            $item instanceof Media => $item->title ?: "Foto #{$item->id}",
                            $item instanceof Quote => Str::limit($item->quote, 50),
                            default => '—',
                        };
                    }),
                TextColumn::make('span')
                    ->label('Ancho')
                    ->badge(),
                IconColumn::make('featured')
                    ->label('★')
                    ->boolean(),
                IconColumn::make('play')
                    ->label('Play')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Pub.')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('collection_id')
                    ->label('Colección')
                    ->relationship('collection', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListGalleries::route('/'),
            'create' => CreateGallery::route('/create'),
            'edit' => EditGallery::route('/{record}/edit'),
        ];
    }
}
