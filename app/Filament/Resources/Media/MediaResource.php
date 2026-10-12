<?php

namespace App\Filament\Resources\Media;

use App\Actions\Media\ExtractExifData;
use App\Filament\Resources\Media\Pages\CreateMedia;
use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Models\Media as MediaModel;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use UnitEnum;

class MediaResource extends Resource
{
    protected static ?string $model = MediaModel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Media';

    protected static ?string $modelLabel = 'foto';

    protected static ?string $pluralModelLabel = 'Media';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Foto')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('Título'),
                        TextInput::make('category')
                            ->label('Categoría'),
                        FileUpload::make('image_path')
                            ->label('Imagen')
                            ->image()
                            ->disk('public')
                            ->directory('media')
                            ->visibility('public')
                            ->imageEditor()
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
                        TagsInput::make('tags')
                            ->label('Tags')
                            ->columnSpanFull(),
                    ]),
                Section::make('Datos EXIF / cámara')
                    ->columns(3)
                    ->collapsed()
                    ->schema([
                        FileUpload::make('exif_source')
                            ->label('Archivo RAW / NEF')
                            ->helperText('Subí el archivo original de la cámara (.raw, .nef, .cr2, .arw, .dng, .orf, .rw2) para autocompletar estos datos. El archivo no se guarda, solo se leen sus metadatos.')
                            ->rules(['extensions:raw,nef,cr2,arw,dng,orf,rw2'])
                            ->maxSize(102400)
                            ->dehydrated(false)
                            ->live()
                            ->afterStateUpdated(function (mixed $state, Set $set): void {
                                if (! $state instanceof TemporaryUploadedFile) {
                                    return;
                                }

                                $path = $state->getRealPath();
                                $data = app(ExtractExifData::class)->handle($path);
                                @unlink($path);

                                if ($data === []) {
                                    Notification::make()
                                        ->warning()
                                        ->title('No se pudieron leer datos EXIF de ese archivo.')
                                        ->send();

                                    return;
                                }

                                foreach ($data as $field => $value) {
                                    $set($field, (string) $value);
                                }

                                Notification::make()
                                    ->success()
                                    ->title('Datos EXIF extraídos correctamente.')
                                    ->send();
                            })
                            ->columnSpanFull(),
                        TextInput::make('camera')->label('Cámara'),
                        TextInput::make('iso')->label('ISO'),
                        TextInput::make('aperture')->label('Apertura'),
                        TextInput::make('shutter')->label('Obturador'),
                        TextInput::make('focal_length')->label('Focal'),
                        TextInput::make('width')->label('Ancho px')->numeric(),
                        TextInput::make('height')->label('Alto px')->numeric(),
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
                        TagsInput::make('tags_en')
                            ->label('Etiquetas (EN)'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                ImageColumn::make('thumb_path')
                    ->label('Img')
                    ->disk('public')
                    ->getStateUsing(fn (MediaModel $record): ?string => $record->thumb_path ?? $record->image_path)
                    ->square(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->toggleable(),
                TextColumn::make('camera')
                    ->label('Cámara')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Creada')
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
            'index' => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }
}
