<?php

namespace App\Filament\Resources\Songs;

use App\Actions\Ai\ClaudeRequestFailed;
use App\Actions\Songs\DescribeSong;
use App\Filament\Resources\Songs\Pages\CreateSong;
use App\Filament\Resources\Songs\Pages\EditSong;
use App\Filament\Resources\Songs\Pages\ListSongs;
use App\Filament\Support\LanguageTabs;
use App\Models\Song;
use App\Support\SpotifyEmbed;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class SongResource extends Resource
{
    protected static ?string $model = Song::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMusicalNote;

    protected static string|UnitEnum|null $navigationGroup = 'Home';

    protected static ?string $navigationLabel = 'Playlist';

    protected static ?string $modelLabel = 'canción';

    protected static ?string $pluralModelLabel = 'Canciones';

    protected static ?int $navigationSort = 3;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                TextInput::make('artist')
                    ->label('Artista')
                    ->required()
                    ->maxLength(255),
                TextInput::make('album')
                    ->label('Álbum')
                    ->maxLength(255),
                Actions::make([self::describeWithAiAction()])
                    ->columnSpanFull(),
                TextInput::make('spotify_url')
                    ->label('Enlace de Spotify')
                    ->helperText('Pegá el enlace de la canción (Compartir → Copiar enlace). Ej: https://open.spotify.com/track/…')
                    ->url()
                    ->maxLength(255)
                    ->rules([
                        fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                            if (filled($value) && SpotifyEmbed::toEmbedUrl($value) === null) {
                                $fail('El enlace debe ser de open.spotify.com (canción, álbum o playlist).');
                            }
                        },
                    ]),
                FileUpload::make('image_path')
                    ->label('Portada')
                    ->image()
                    ->disk('public')
                    ->directory('songs')
                    ->visibility('public')
                    ->imageEditor()
                    ->columnSpanFull(),
                LanguageTabs::make(
                    spanish: [
                        Textarea::make('note')
                            ->label('Nota')
                            ->helperText('Opcional: por qué te gusta, cuándo la escuchás…')
                            ->rows(3)
                            ->columnSpanFull(),
                    ],
                    translations: [
                        'en' => [
                            Textarea::make('note_en')
                                ->label('Note')
                                ->rows(3),
                        ],
                    ],
                    translatableFields: ['note'],
                ),
                TextInput::make('sort_order')
                    ->label('Orden')
                    ->numeric()
                    ->default(0),
                Toggle::make('is_published')
                    ->label('Publicado')
                    ->default(true),
            ]);
    }

    /**
     * Completa el álbum (si falta) y escribe la nota en español e inglés con
     * Claude, que busca en la web para no inventar datos.
     */
    private static function describeWithAiAction(): Action
    {
        return Action::make('describeWithAi')
            ->label('Completar con IA')
            ->icon(Heroicon::OutlinedSparkles)
            ->color('gray')
            ->tooltip('Busca el álbum y escribe una nota corta (cuándo salió, quién la canta o un dato curioso), en español e inglés.')
            ->requiresConfirmation(fn (Get $get): bool => filled($get('note')) || filled($get('note_en')))
            ->modalHeading('¿Reemplazar la nota?')
            ->modalDescription('La nota en español y en inglés se van a reemplazar por las que escriba la IA.')
            ->modalSubmitActionLabel('Completar')
            ->action(function (Get $get, Set $set): void {
                $title = trim((string) $get('title'));
                $artist = trim((string) $get('artist'));

                if ($title === '' || $artist === '') {
                    Notification::make()->warning()->title('Completá el título y el artista primero.')->send();

                    return;
                }

                try {
                    $song = app(DescribeSong::class)->handle($title, $artist, $get('album'));
                } catch (ClaudeRequestFailed $exception) {
                    Notification::make()->danger()->title('No se pudo completar')->body($exception->getMessage())->send();

                    return;
                }

                if (blank($get('album')) && $song['album'] !== null) {
                    $set('album', $song['album']);
                }

                $set('note', $song['note']);
                $set('note_en', $song['note_en']);

                Notification::make()
                    ->success()
                    ->title('Listo')
                    ->body('Revisá la nota antes de guardar: los datos salen de una búsqueda web.')
                    ->send();
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Portada')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('artist')
                    ->label('Artista')
                    ->searchable(),
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
            'index' => ListSongs::route('/'),
            'create' => CreateSong::route('/create'),
            'edit' => EditSong::route('/{record}/edit'),
        ];
    }
}
