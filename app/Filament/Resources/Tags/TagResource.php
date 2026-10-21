<?php

namespace App\Filament\Resources\Tags;

use App\Filament\Resources\Tags\Pages\CreateTag;
use App\Filament\Resources\Tags\Pages\EditTag;
use App\Filament\Resources\Tags\Pages\ListTags;
use App\Filament\Support\LanguageTabs;
use App\Models\Tag;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Etiquetas';

    protected static ?string $modelLabel = 'etiqueta';

    protected static ?string $pluralModelLabel = 'Etiquetas';

    protected static ?int $navigationSort = 4;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                LanguageTabs::make(
                    spanish: [
                        self::nameField(),
                        self::slugField(),
                    ],
                    translations: [
                        'en' => [
                            TextInput::make('name_en')
                                ->label('Name')
                                ->maxLength(255),
                        ],
                    ],
                    translatableFields: ['name'],
                ),
            ]);
    }

    /**
     * Campos para crear una etiqueta desde otro formulario (p. ej. el de una foto).
     *
     * @return array<int, Component>
     */
    public static function fields(): array
    {
        return [
            self::nameField(),
            self::slugField(),
            TextInput::make('name_en')
                ->label('Nombre en inglés')
                ->maxLength(255),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name_en')
                    ->label('Inglés')
                    ->placeholder('Sin traducir')
                    ->searchable(),
                TextColumn::make('media_count')
                    ->label('Fotos')
                    ->counts('media')
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
            'index' => ListTags::route('/'),
            'create' => CreateTag::route('/create'),
            'edit' => EditTag::route('/{record}/edit'),
        ];
    }

    private static function nameField(): TextInput
    {
        return TextInput::make('name')
            ->label('Nombre')
            ->required()
            ->maxLength(255)
            ->live(onBlur: true)
            ->afterStateUpdated(function (Set $set, Get $get, ?string $state, ?string $old): void {
                if (filled($get('slug')) && $get('slug') !== Str::slug((string) $old)) {
                    return;
                }

                $set('slug', Str::slug((string) $state));
            });
    }

    private static function slugField(): TextInput
    {
        return TextInput::make('slug')
            ->required()
            ->maxLength(255)
            ->unique(Tag::class, 'slug', ignoreRecord: true);
    }
}
