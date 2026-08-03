<?php

namespace App\Filament\Pages;

use App\Models\HomeSnapshot;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageHomeSnapshot extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static string|UnitEnum|null $navigationGroup = 'Home';

    protected static ?string $navigationLabel = 'Vitrina';

    protected static ?string $title = 'Vitrina del home';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.pages.manage-home-snapshot';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getRecord()->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make('Ubicación')
                        ->columns(2)
                        ->schema([
                            FileUpload::make('map_image_path')
                                ->label('Imagen del mapa')
                                ->image()
                                ->disk('public')
                                ->directory('snapshot')
                                ->visibility('public')
                                ->imageEditor()
                                ->columnSpanFull(),
                            TextInput::make('maps_url')
                                ->label('URL de Google Maps')
                                ->url()
                                ->maxLength(500),
                            TextInput::make('map_label')
                                ->label('Etiqueta')
                                ->placeholder('MONTEVIDEO · URUGUAY')
                                ->maxLength(120),
                        ]),
                    Section::make('Música')
                        ->schema([
                            TextInput::make('spotify_embed_url')
                                ->label('URL embed de Spotify')
                                ->url()
                                ->helperText('Pegá el link de “Incorporar episodio/canción” de Spotify.')
                                ->maxLength(500)
                                ->columnSpanFull(),
                        ]),
                    Section::make('Carrusel de libros')
                        ->schema([
                            TextInput::make('carousel_interval')
                                ->label('Intervalo (ms)')
                                ->numeric()
                                ->minValue(1500)
                                ->default(4500)
                                ->helperText('Tiempo entre slides del carrusel de lectura.'),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Guardar')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $record = $this->getRecord();
        $record->fill($data);
        $record->save();

        Notification::make()
            ->success()
            ->title('Vitrina guardada')
            ->send();
    }

    public function getRecord(): HomeSnapshot
    {
        return HomeSnapshot::current();
    }
}
