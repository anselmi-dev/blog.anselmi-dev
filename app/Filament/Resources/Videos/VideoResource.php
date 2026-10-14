<?php

namespace App\Filament\Resources\Videos;

use App\Filament\Resources\Videos\Pages\CreateVideo;
use App\Filament\Resources\Videos\Pages\EditVideo;
use App\Filament\Resources\Videos\Pages\ListVideos;
use App\Models\Video;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class VideoResource extends Resource
{
    protected static ?string $model = Video::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedVideoCamera;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Videos';

    protected static ?string $modelLabel = 'video';

    protected static ?string $pluralModelLabel = 'Videos';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Video')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title')
                            ->label('Título'),
                        TextInput::make('category')
                            ->label('Categoría'),
                        FileUpload::make('video_path')
                            ->label('Archivo de video')
                            ->helperText('MP4 o WebM (hasta 100 MB). MP4 en H.264 es el que mejor se ve en todos los navegadores.')
                            ->acceptedFileTypes(['video/mp4', 'video/webm', 'video/quicktime'])
                            ->maxSize(102400)
                            ->disk('public')
                            ->directory('videos')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        FileUpload::make('poster_path')
                            ->label('Portada')
                            ->helperText('Imagen que se ve antes de reproducir. Si no subís una, se usa el primer cuadro del video.')
                            ->image()
                            ->disk('public')
                            ->directory('videos/posters')
                            ->visibility('public')
                            ->columnSpanFull(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('location')
                            ->label('Lugar'),
                        TextInput::make('released_at')
                            ->label('Fecha (texto)')
                            ->placeholder('12 mar 2025'),
                        TextInput::make('width')
                            ->label('Ancho px')
                            ->numeric(),
                        TextInput::make('height')
                            ->label('Alto px')
                            ->numeric(),
                    ]),
                Section::make('Inglés')
                    ->description('Textos para la versión en inglés del sitio (/en). Si dejás un campo vacío, se muestra el texto en español.')
                    ->collapsible()
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('title_en')
                            ->label('Título (EN)')
                            ->maxLength(255),
                        TextInput::make('category_en')
                            ->label('Categoría (EN)')
                            ->maxLength(255),
                        Textarea::make('description_en')
                            ->label('Descripción (EN)')
                            ->rows(3),
                        TextInput::make('location_en')
                            ->label('Ubicación (EN)')
                            ->maxLength(255),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('poster_path')
                    ->label('Portada')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime()
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
            'index' => ListVideos::route('/'),
            'create' => CreateVideo::route('/create'),
            'edit' => EditVideo::route('/{record}/edit'),
        ];
    }
}
