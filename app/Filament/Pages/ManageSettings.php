<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
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
