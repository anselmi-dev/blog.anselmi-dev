<?php

namespace App\Filament\Resources\Media;

use App\Actions\Media\ExtractExifData;
use App\Actions\Media\GenerateThumbnail;
use App\Filament\Resources\Media\Pages\CreateMedia;
use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Filament\Resources\Tags\TagResource;
use App\Filament\Support\LanguageTabs;
use App\Models\Media as MediaModel;
use App\Models\Tag;
use BackedEnum;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
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
use Illuminate\Database\Eloquent\Collection;
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
                    ->columnSpanFull()
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('Imagen')
                            ->helperText('Al guardar se genera la miniatura y, si están vacíos, se completan el ancho y el alto.')
                            ->image()
                            ->disk('public')
                            ->directory('media')
                            ->visibility('public')
                            ->imageEditor()
                            ->columnSpanFull(),
                        DatePicker::make('released_on')
                            ->label('Fecha')
                            ->native(false)
                            ->displayFormat('j M Y')
                            ->closeOnDateSelection(),
                        Select::make('tags')
                            ->label('Etiquetas')
                            ->relationship('tags', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn (Tag $record): string => filled($record->name_en) ? "{$record->name} · {$record->name_en}" : $record->name)
                            ->createOptionForm(TagResource::fields())
                            ->createOptionModalHeading('Nueva etiqueta'),
                    ]),
                LanguageTabs::make(
                    spanish: [
                        TextInput::make('title')
                            ->label('Título'),
                        TextInput::make('category')
                            ->label('Categoría'),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),
                        TextInput::make('location')
                            ->label('Lugar'),
                    ],
                    translations: [
                        'en' => [
                            TextInput::make('title_en')
                                ->label('Title')
                                ->maxLength(255),
                            TextInput::make('category_en')
                                ->label('Category')
                                ->maxLength(255),
                            Textarea::make('description_en')
                                ->label('Description')
                                ->rows(3)
                                ->columnSpanFull(),
                            TextInput::make('location_en')
                                ->label('Location')
                                ->maxLength(255),
                        ],
                    ],
                    translatableFields: ['title', 'category', 'description', 'location'],
                ),
                Section::make('Datos EXIF / cámara')
                    ->columns(3)
                    ->columnSpanFull()
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
                TextColumn::make('tags.name')
                    ->label('Etiquetas')
                    ->badge()
                    ->toggleable(),
                TextColumn::make('released_on')
                    ->label('Fecha')
                    ->date('j M Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('camera')
                    ->label('Cámara')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    BulkAction::make('regenerateThumbnails')
                        ->label('Regenerar miniaturas')
                        ->icon(Heroicon::OutlinedPhoto)
                        ->action(function (Collection $records): void {
                            $records->each(function (MediaModel $media): void {
                                $media->thumb_path = filled($media->image_path)
                                    ? app(GenerateThumbnail::class)->handle($media->image_path)
                                    : null;
                                $media->saveQuietly();
                            });

                            Notification::make()
                                ->success()
                                ->title('Miniaturas regeneradas')
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
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
