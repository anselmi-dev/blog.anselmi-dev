<?php

namespace App\Filament\Resources\GalleryItems;

use App\Filament\Resources\GalleryItems\Pages\CreateGalleryItem;
use App\Filament\Resources\GalleryItems\Pages\EditGalleryItem;
use App\Filament\Resources\GalleryItems\Pages\ListGalleryItems;
use App\Models\GalleryItem;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class GalleryItemResource extends Resource
{
    protected static ?string $model = GalleryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Galería';

    protected static ?string $modelLabel = 'ítem de galería';

    protected static ?string $pluralModelLabel = 'Galería';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Ítem')
                    ->columns(2)
                    ->schema([
                        Select::make('kind')
                            ->label('Tipo')
                            ->options([
                                'photo' => 'Foto',
                                'quote' => 'Cita',
                            ])
                            ->required()
                            ->live()
                            ->default('photo'),
                        Select::make('span')
                            ->label('Ancho')
                            ->options([
                                'tall' => 'Normal (1 col)',
                                'wide' => 'Ancho (2 cols)',
                            ])
                            ->required()
                            ->default('tall'),
                        TextInput::make('title')
                            ->label('Título')
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo'),
                        TextInput::make('category')
                            ->label('Categoría')
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo'),
                        FileUpload::make('image_path')
                            ->label('Imagen')
                            ->image()
                            ->disk('public')
                            ->directory('gallery')
                            ->visibility('public')
                            ->imageEditor()
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo')
                            ->columnSpanFull(),
                        Textarea::make('quote')
                            ->label('Cita')
                            ->rows(3)
                            ->visible(fn (Get $get): bool => $get('kind') === 'quote')
                            ->columnSpanFull(),
                        TextInput::make('attribution')
                            ->label('Atribución')
                            ->visible(fn (Get $get): bool => $get('kind') === 'quote'),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo')
                            ->columnSpanFull(),
                        TextInput::make('location')
                            ->label('Lugar')
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo'),
                        TextInput::make('released_at')
                            ->label('Fecha (texto)')
                            ->placeholder('12 mar 2025')
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo'),
                        TagsInput::make('tags')
                            ->label('Tags')
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo')
                            ->columnSpanFull(),
                        Toggle::make('featured')
                            ->label('Destacada')
                            ->default(false)
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo'),
                        Toggle::make('play')
                            ->label('Mostrar play')
                            ->default(false)
                            ->visible(fn (Get $get): bool => $get('kind') === 'photo'),
                    ]),
                Section::make('Datos EXIF / cámara')
                    ->columns(3)
                    ->collapsed()
                    ->visible(fn (Get $get): bool => $get('kind') === 'photo')
                    ->schema([
                        TextInput::make('camera')->label('Cámara'),
                        TextInput::make('iso')->label('ISO'),
                        TextInput::make('aperture')->label('Apertura'),
                        TextInput::make('shutter')->label('Obturador'),
                        TextInput::make('focal_length')->label('Focal'),
                        TextInput::make('width')->label('Ancho px')->numeric(),
                        TextInput::make('height')->label('Alto px')->numeric(),
                    ]),
                Section::make('Publicación')
                    ->columns(2)
                    ->schema([
                        TextInput::make('sort_order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                        Toggle::make('is_published')
                            ->label('Publicado')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Img')
                    ->disk('public')
                    ->square(),
                TextColumn::make('kind')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'quote' ? 'Cita' : 'Foto'),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->placeholder(fn (GalleryItem $record): string => $record->kind === 'quote'
                        ? Str::limit((string) $record->quote, 40)
                        : '—'),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->toggleable(),
                IconColumn::make('featured')
                    ->label('★')
                    ->boolean(),
                IconColumn::make('is_published')
                    ->label('Pub.')
                    ->boolean(),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kind')
                    ->options([
                        'photo' => 'Foto',
                        'quote' => 'Cita',
                    ]),
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
            'index' => ListGalleryItems::route('/'),
            'create' => CreateGalleryItem::route('/create'),
            'edit' => EditGalleryItem::route('/{record}/edit'),
        ];
    }
}
