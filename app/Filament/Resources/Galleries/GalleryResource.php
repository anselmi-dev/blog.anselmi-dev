<?php

namespace App\Filament\Resources\Galleries;

use App\Filament\Resources\Galleries\Pages\CreateGallery;
use App\Filament\Resources\Galleries\Pages\EditGallery;
use App\Filament\Resources\Galleries\Pages\ListGalleries;
use App\Filament\Resources\Galleries\Pickers\MediaPicker;
use App\Filament\Resources\Galleries\Pickers\QuotePicker;
use App\Filament\Resources\Galleries\Pickers\VideoPicker;
use App\Models\Gallery;
use App\Models\Media;
use App\Models\Quote;
use App\Models\Video;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
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

    /**
     * Tipos de contenido que se pueden agregar a una colección.
     *
     * @var array<class-string, string>
     */
    public const array TYPES = [
        Media::class => 'Foto',
        Video::class => 'Video',
        Quote::class => 'Cita',
    ];

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
                ToggleButtons::make('galleryable_type')
                    ->label('Tipo de contenido')
                    ->options(self::TYPES)
                    ->icons([
                        Media::class => Heroicon::OutlinedPhoto,
                        Video::class => Heroicon::OutlinedVideoCamera,
                        Quote::class => Heroicon::OutlinedChatBubbleBottomCenterText,
                    ])
                    ->inline()
                    ->required()
                    ->default(Media::class)
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('galleryable_id', null))
                    ->columnSpanFull(),
                ModalTableSelect::make('galleryable_id')
                    ->label('Contenido')
                    ->required()
                    ->tableConfiguration(fn (Get $get): string => match ($get('galleryable_type')) {
                        Video::class => VideoPicker::class,
                        Quote::class => QuotePicker::class,
                        default => MediaPicker::class,
                    })
                    ->getOptionLabelUsing(fn (Get $get, mixed $value): ?HtmlString => self::selectedLabel($get('galleryable_type'), $value))
                    ->selectAction(fn (Action $action): Action => $action->label('Elegir')->modalHeading('Elegir contenido'))
                    ->rules([
                        fn (Get $get): Closure => function (string $attribute, mixed $value, Closure $fail) use ($get): void {
                            $type = $get('galleryable_type');

                            if (! array_key_exists($type, self::TYPES) || ! $type::query()->whereKey($value)->exists()) {
                                $fail('Elegí un contenido de la lista.');
                            }
                        },
                    ])
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

    /**
     * Miniatura y nombre del contenido elegido, para mostrar en el formulario.
     */
    private static function selectedLabel(?string $type, mixed $id): ?HtmlString
    {
        if (blank($id) || ! array_key_exists((string) $type, self::TYPES)) {
            return null;
        }

        $preview = Gallery::previewOf($type::query()->find($id));

        return new HtmlString(Blade::render(
            '<span style="display:inline-flex;align-items:center;gap:0.75rem;"><x-admin.gallery-preview :preview="$preview" :size="48" /><span>{{ $preview[\'label\'] }}</span></span>',
            ['preview' => $preview],
        ));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query): Builder => $query->with('galleryable'))
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ViewColumn::make('thumbnail')
                    ->label('Vista')
                    ->view('filament.gallery.preview-column'),
                TextColumn::make('collection.name')
                    ->label('Colección')
                    ->sortable(),
                TextColumn::make('galleryable_type')
                    ->label('Tipo')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => self::TYPES[$state] ?? $state),
                TextColumn::make('preview')
                    ->label('Contenido')
                    ->getStateUsing(fn (Gallery $record): string => $record->adminPreview()['label'])
                    ->wrap(),
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
