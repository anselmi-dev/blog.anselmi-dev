<?php

namespace App\Filament\Resources\SiteColors;

use App\Filament\Resources\SiteColors\Pages\CreateSiteColor;
use App\Filament\Resources\SiteColors\Pages\EditSiteColor;
use App\Filament\Resources\SiteColors\Pages\ListSiteColors;
use App\Models\SiteColor;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SiteColorResource extends Resource
{
    protected static ?string $model = SiteColor::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSwatch;

    protected static string|UnitEnum|null $navigationGroup = 'Servicios';

    protected static ?string $navigationLabel = 'Colores';

    protected static ?string $modelLabel = 'color';

    protected static ?string $pluralModelLabel = 'Colores';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                ColorPicker::make('hex')
                    ->label('Hex')
                    ->required(),
                Select::make('span')
                    ->label('Altura visual')
                    ->options([
                        'sm' => 'Chica',
                        'md' => 'Media',
                        'lg' => 'Grande',
                        'xl' => 'Extra',
                    ])
                    ->required()
                    ->default('md'),
                Select::make('ink')
                    ->label('Tinta del texto')
                    ->options([
                        'dark' => 'Oscura',
                        'light' => 'Clara',
                    ])
                    ->required()
                    ->default('dark'),
                TextInput::make('column_index')
                    ->label('Columna (0–3)')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(10)
                    ->required()
                    ->default(0)
                    ->helperText('Agrupa colores en la misma columna visual.'),
                TextInput::make('sort_order')
                    ->label('Orden en columna')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->label('Publicado')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('column_index')
            ->reorderable('sort_order')
            ->columns([
                ColorColumn::make('hex')
                    ->label('Color'),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('hex')
                    ->label('Hex')
                    ->copyable(),
                TextColumn::make('column_index')
                    ->label('Col.')
                    ->sortable(),
                TextColumn::make('span')
                    ->label('Span')
                    ->badge(),
                IconColumn::make('is_published')
                    ->label('Pub.')
                    ->boolean(),
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
            'index' => ListSiteColors::route('/'),
            'create' => CreateSiteColor::route('/create'),
            'edit' => EditSiteColor::route('/{record}/edit'),
        ];
    }
}
