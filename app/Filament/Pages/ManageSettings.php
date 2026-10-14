<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use App\Support\CursorStyle;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Slider;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static string|UnitEnum|null $navigationGroup = 'Sitio';

    protected static ?string $navigationLabel = 'Ajustes';

    protected static ?string $title = 'Ajustes del sitio';

    protected static ?int $navigationSort = 99;

    protected string $view = 'filament.pages.manage-settings';

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site' => Setting::site(),
            'contact' => Setting::contact(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Tabs::make('Ajustes')
                        ->persistTabInQueryString()
                        ->tabs([
                            Tab::make('Sitio')
                                ->icon(Heroicon::OutlinedGlobeAlt)
                                ->schema([
                                    Section::make('SEO y marca')
                                        ->schema([
                                            TextInput::make('site.author_name')
                                                ->label('Nombre / autor')
                                                ->maxLength(120),
                                            Textarea::make('site.meta_description')
                                                ->label('Meta description')
                                                ->rows(3)
                                                ->helperText('Descripción por defecto para SEO y redes.'),
                                        ]),
                                    Section::make('Animaciones')
                                        ->schema([
                                            Toggle::make('site.tools_loader')
                                                ->label('Pantalla de carga en Herramientas')
                                                ->helperText('Muestra la barra de 0 a 100 % mientras cargan los frascos de /servicios/herramientas.')
                                                ->default(false),
                                            Toggle::make('site.background_squares')
                                                ->label('Cuadrados animados de fondo')
                                                ->helperText('Cuadrados que suben girando detrás del contenido de todas las páginas.')
                                                ->default(true)
                                                ->live(),
                                            Slider::make('site.background_squares_opacity')
                                                ->label('Opacidad de los cuadrados (%)')
                                                ->range(10, 100)
                                                ->step(5)
                                                ->tooltips()
                                                ->default(75)
                                                ->visible(fn (Get $get): bool => (bool) $get('site.background_squares')),
                                            Slider::make('site.background_squares_speed')
                                                ->label('Velocidad de los cuadrados')
                                                ->helperText('1 es la velocidad original; 2 va el doble de rápido y 0,5 a la mitad.')
                                                ->range(0.25, 3)
                                                ->step(0.25)
                                                ->decimalPlaces(2)
                                                ->tooltips()
                                                ->default(1)
                                                ->visible(fn (Get $get): bool => (bool) $get('site.background_squares')),
                                            Select::make('site.cursor_style')
                                                ->label('Estilo del cursor')
                                                ->helperText('Con el cursor normal se usa el puntero del sistema, sin efectos.')
                                                ->options(CursorStyle::class)
                                                ->default(CursorStyle::Circle->value)
                                                ->selectablePlaceholder(false)
                                                ->required(),
                                        ]),
                                ]),
                            Tab::make('Contacto')
                                ->icon(Heroicon::OutlinedEnvelope)
                                ->schema([
                                    Section::make('Correo')
                                        ->columns(2)
                                        ->schema([
                                            TextInput::make('contact.email')
                                                ->label('Email')
                                                ->email()
                                                ->required()
                                                ->maxLength(255),
                                            TextInput::make('contact.email_label')
                                                ->label('Título')
                                                ->maxLength(120),
                                            Textarea::make('contact.email_description')
                                                ->label('Descripción')
                                                ->rows(2)
                                                ->columnSpanFull(),
                                        ]),
                                    Section::make('Ubicación')
                                        ->schema([
                                            TextInput::make('contact.location_label')
                                                ->label('Título')
                                                ->maxLength(120),
                                            Textarea::make('contact.location_description')
                                                ->label('Descripción')
                                                ->rows(2),
                                        ]),
                                    Section::make('Horario')
                                        ->schema([
                                            TextInput::make('contact.schedule_label')
                                                ->label('Título')
                                                ->maxLength(120),
                                            Textarea::make('contact.schedule_description')
                                                ->label('Descripción')
                                                ->rows(2),
                                        ]),
                                    Section::make('Formulario')
                                        ->schema([
                                            TextInput::make('contact.form_title')
                                                ->label('Título')
                                                ->maxLength(200),
                                            Textarea::make('contact.form_description')
                                                ->label('Descripción')
                                                ->rows(2),
                                            TextInput::make('contact.submit_label')
                                                ->label('Texto del botón')
                                                ->maxLength(80),
                                            TextInput::make('contact.success_title')
                                                ->label('Título de éxito')
                                                ->maxLength(120),
                                            Textarea::make('contact.success_description')
                                                ->label('Mensaje de éxito')
                                                ->rows(2),
                                        ]),
                                    Section::make('Redes sociales')
                                        ->schema([
                                            Repeater::make('contact.social_links')
                                                ->label('Enlaces')
                                                ->helperText('Sin URL = se muestra como “próximamente”.')
                                                ->schema([
                                                    TextInput::make('label')
                                                        ->label('Nombre')
                                                        ->required()
                                                        ->maxLength(40),
                                                    TextInput::make('url')
                                                        ->label('URL')
                                                        ->url()
                                                        ->maxLength(500),
                                                ])
                                                ->columns(2)
                                                ->defaultItems(0)
                                                ->reorderable()
                                                ->collapsible(),
                                        ]),
                                ]),
                            Tab::make('Inglés')
                                ->icon(Heroicon::OutlinedLanguage)
                                ->schema([
                                    Section::make('Versión en inglés (/en)')
                                        ->description('Si dejás un campo vacío, se usa la traducción del texto por defecto o el texto en español.')
                                        ->columns(2)
                                        ->schema([
                                            Textarea::make('site.meta_description_en')
                                                ->label('Meta description')
                                                ->rows(3)
                                                ->columnSpanFull(),
                                            TextInput::make('contact.email_label_en')
                                                ->label('Correo — título')
                                                ->maxLength(120),
                                            Textarea::make('contact.email_description_en')
                                                ->label('Correo — descripción')
                                                ->rows(2),
                                            TextInput::make('contact.location_label_en')
                                                ->label('Ubicación — título')
                                                ->maxLength(120),
                                            Textarea::make('contact.location_description_en')
                                                ->label('Ubicación — descripción')
                                                ->rows(2),
                                            TextInput::make('contact.schedule_label_en')
                                                ->label('Horario — título')
                                                ->maxLength(120),
                                            Textarea::make('contact.schedule_description_en')
                                                ->label('Horario — descripción')
                                                ->rows(2),
                                            TextInput::make('contact.form_title_en')
                                                ->label('Formulario — título')
                                                ->maxLength(200),
                                            Textarea::make('contact.form_description_en')
                                                ->label('Formulario — descripción')
                                                ->rows(2),
                                            TextInput::make('contact.submit_label_en')
                                                ->label('Texto del botón')
                                                ->maxLength(80),
                                            TextInput::make('contact.success_title_en')
                                                ->label('Título de éxito')
                                                ->maxLength(120),
                                            Textarea::make('contact.success_description_en')
                                                ->label('Mensaje de éxito')
                                                ->rows(2),
                                        ]),
                                ]),
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
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        Setting::putMany('site', $data['site'] ?? []);
        Setting::putMany('contact', $data['contact'] ?? []);

        Notification::make()
            ->success()
            ->title('Ajustes guardados')
            ->send();
    }
}
