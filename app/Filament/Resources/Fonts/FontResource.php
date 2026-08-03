<?php

namespace App\Filament\Resources\Fonts;

use App\Filament\Resources\Fonts\Pages\CreateFont;
use App\Filament\Resources\Fonts\Pages\EditFont;
use App\Filament\Resources\Fonts\Pages\ListFonts;
use App\Models\Font;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class FontResource extends Resource
{
    protected static ?string $model = Font::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static string|UnitEnum|null $navigationGroup = 'Servicios';

    protected static ?string $navigationLabel = 'Fuentes';

    protected static ?string $modelLabel = 'fuente';

    protected static ?string $pluralModelLabel = 'Fuentes';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, ?string $state, ?string $old, Get $get): void {
                        if (filled($get('slug')) && $get('slug') !== Str::slug((string) $old)) {
                            return;
                        }
                        $set('slug', Str::slug((string) $state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true),
                TextInput::make('family')
                    ->label('CSS family')
                    ->required()
                    ->placeholder('"Plus Jakarta Sans", sans-serif'),
                TextInput::make('tailwind')
                    ->label('Clase Tailwind')
                    ->placeholder('font-sans'),
                TextInput::make('category')
                    ->label('Categoría')
                    ->placeholder('Sans-serif'),
                TextInput::make('weights')
                    ->label('Pesos')
                    ->placeholder('400, 500, 600, 700'),
                TextInput::make('sample')
                    ->label('Texto de muestra')
                    ->default('Hakuna Matata'),
                TextInput::make('google_url')
                    ->label('URL Google Fonts')
                    ->url(),
                TextInput::make('bunny_url')
                    ->label('URL Bunny Fonts')
                    ->url(),
                TextInput::make('css')
                    ->label('Snippet CSS')
                    ->placeholder("font-family: 'Plus Jakarta Sans', sans-serif;"),
                Textarea::make('note')
                    ->label('Nota')
                    ->rows(2)
                    ->columnSpanFull(),
                TextInput::make('sort_order')
                    ->label('Orden')
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
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->badge(),
                TextColumn::make('weights')
                    ->label('Pesos')
                    ->toggleable(),
                IconColumn::make('is_published')
                    ->label('Pub.')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
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
            'index' => ListFonts::route('/'),
            'create' => CreateFont::route('/create'),
            'edit' => EditFont::route('/{record}/edit'),
        ];
    }
}
