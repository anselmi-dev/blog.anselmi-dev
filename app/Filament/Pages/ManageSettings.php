<?php

namespace App\Filament\Pages;

use App\Filament\Support\CompleteWithAiAction;
use App\Filament\Support\LanguageTabs;
use App\Models\Setting;
use App\Support\CursorStyle;
use App\Support\PageColor;
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
use Filament\Schemas\Components\Fieldset;
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
            'ai' => [
                'anthropic_api_key' => null,
                'anthropic_workspace_id' => Setting::get(Setting::ANTHROPIC_WORKSPACE_ID),
            ],
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
                                            LanguageTabs::make(
                                                key: 'seo-idiomas',
                                                spanish: [
                                                    Textarea::make('site.meta_description')
                                                        ->label('Meta description')
                                                        ->rows(3)
                                                        ->helperText('Descripción por defecto para SEO y redes.')
                                                        ->columnSpanFull(),
                                                ],
                                                translations: [
                                                    'en' => [
                                                        Textarea::make('site.meta_description_en')
                                                            ->label('Meta description')
                                                            ->rows(3)
                                                            ->helperText('Si queda vacía, se usa la traducción del texto por defecto o el texto en español.')
                                                            ->columnSpanFull(),
                                                    ],
                                                ],
                                                translatableFields: ['site.meta_description'],
                                                completion: CompleteWithAiAction::make(
                                                    about: 'la descripción por defecto del sitio para buscadores y redes sociales',
                                                    guidance: [
                                                        'site.meta_description' => 'Meta description de 140 a 160 caracteres que presente el sitio: portfolio de desarrollo web, fotografía y blog.',
                                                    ],
                                                    context: ['site.author_name' => 'Nombre / autor'],
                                                ),
                                            ),
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
                                    Section::make('Color por página')
                                        ->description('Fondo y texto de cada página, elegidos entre los del recurso Colores. El fondo tiñe los acentos y sus tonos claros; el texto cambia la tinta de lo que va encima (header, portada, pie y tarjetas de color). Vacío = el de siempre. El color de un proyecto pisa el fondo de su página.')
                                        ->collapsible()
                                        ->schema(collect(PageColor::PAGES)
                                            ->map(fn (string $label, string $routeName): Fieldset => Fieldset::make($label)
                                                ->columns(2)
                                                ->schema([
                                                    Select::make('site.page_colors.'.PageColor::settingKey($routeName))
                                                        ->label('Fondo')
                                                        ->placeholder('Color por defecto')
                                                        ->options(fn (?string $state): array => PageColor::options($state))
                                                        ->allowHtml()
                                                        ->searchable()
                                                        ->native(false),
                                                    Select::make('site.page_inks.'.PageColor::settingKey($routeName))
                                                        ->label('Texto')
                                                        ->placeholder('Texto por defecto')
                                                        ->options(fn (?string $state): array => PageColor::inkOptions($state))
                                                        ->allowHtml()
                                                        ->searchable()
                                                        ->native(false),
                                                ]))
                                            ->values()
                                            ->all()),
                                ]),
                            Tab::make('Contacto')
                                ->icon(Heroicon::OutlinedEnvelope)
                                ->schema([
                                    Section::make('Correo')
                                        ->schema([
                                            TextInput::make('contact.email')
                                                ->label('Email')
                                                ->email()
                                                ->required()
                                                ->maxLength(255),
                                        ]),
                                    LanguageTabs::make(
                                        key: 'contacto-idiomas',
                                        spanish: [
                                            Section::make('Correo')
                                                ->columns(2)
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('contact.email_label')
                                                        ->label('Título')
                                                        ->maxLength(120),
                                                    Textarea::make('contact.email_description')
                                                        ->label('Descripción')
                                                        ->rows(2),
                                                ]),
                                            Section::make('Ubicación')
                                                ->columns(2)
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('contact.location_label')
                                                        ->label('Título')
                                                        ->maxLength(120),
                                                    Textarea::make('contact.location_description')
                                                        ->label('Descripción')
                                                        ->rows(2),
                                                ]),
                                            Section::make('Horario')
                                                ->columns(2)
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('contact.schedule_label')
                                                        ->label('Título')
                                                        ->maxLength(120),
                                                    Textarea::make('contact.schedule_description')
                                                        ->label('Descripción')
                                                        ->rows(2),
                                                ]),
                                            Section::make('Formulario')
                                                ->columns(2)
                                                ->columnSpanFull()
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
                                        ],
                                        translations: [
                                            'en' => [
                                                Section::make('Email')
                                                    ->columns(2)
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        TextInput::make('contact.email_label_en')
                                                            ->label('Title')
                                                            ->maxLength(120),
                                                        Textarea::make('contact.email_description_en')
                                                            ->label('Description')
                                                            ->rows(2),
                                                    ]),
                                                Section::make('Location')
                                                    ->columns(2)
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        TextInput::make('contact.location_label_en')
                                                            ->label('Title')
                                                            ->maxLength(120),
                                                        Textarea::make('contact.location_description_en')
                                                            ->label('Description')
                                                            ->rows(2),
                                                    ]),
                                                Section::make('Schedule')
                                                    ->columns(2)
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        TextInput::make('contact.schedule_label_en')
                                                            ->label('Title')
                                                            ->maxLength(120),
                                                        Textarea::make('contact.schedule_description_en')
                                                            ->label('Description')
                                                            ->rows(2),
                                                    ]),
                                                Section::make('Form')
                                                    ->columns(2)
                                                    ->columnSpanFull()
                                                    ->schema([
                                                        TextInput::make('contact.form_title_en')
                                                            ->label('Title')
                                                            ->maxLength(200),
                                                        Textarea::make('contact.form_description_en')
                                                            ->label('Description')
                                                            ->rows(2),
                                                        TextInput::make('contact.submit_label_en')
                                                            ->label('Button text')
                                                            ->maxLength(80),
                                                        TextInput::make('contact.success_title_en')
                                                            ->label('Success title')
                                                            ->maxLength(120),
                                                        Textarea::make('contact.success_description_en')
                                                            ->label('Success message')
                                                            ->rows(2),
                                                    ]),
                                            ],
                                        ],
                                        translatableFields: [
                                            'contact.email_label',
                                            'contact.email_description',
                                            'contact.location_label',
                                            'contact.location_description',
                                            'contact.schedule_label',
                                            'contact.schedule_description',
                                            'contact.form_title',
                                            'contact.form_description',
                                            'contact.submit_label',
                                            'contact.success_title',
                                            'contact.success_description',
                                        ],
                                        completion: CompleteWithAiAction::make(
                                            about: 'los textos del bloque de contacto del sitio (formulario para escribirme y datos de contacto)',
                                            guidance: [
                                                'contact.email_label' => 'Título corto para el correo (2 a 4 palabras).',
                                                'contact.email_description' => 'Una oración corta sobre escribir por correo.',
                                                'contact.location_label' => 'Título corto para la ubicación.',
                                                'contact.location_description' => 'Una oración corta sobre desde dónde trabajo, sin inventar datos que no estén en el contexto.',
                                                'contact.schedule_label' => 'Título corto para el horario.',
                                                'contact.schedule_description' => 'Una oración corta sobre el horario, sin inventar horarios que no estén en el contexto.',
                                                'contact.form_title' => 'Título del formulario de contacto, corto y cercano.',
                                                'contact.form_description' => 'Una oración que invite a contar el proyecto.',
                                                'contact.submit_label' => 'Texto del botón de enviar (1 a 3 palabras).',
                                                'contact.success_title' => 'Título corto cuando el mensaje se envió.',
                                                'contact.success_description' => 'Una oración de agradecimiento después de enviar.',
                                            ],
                                            context: ['contact.email' => 'Email'],
                                        ),
                                    ),
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
                            Tab::make('IA')
                                ->icon(Heroicon::OutlinedSparkles)
                                ->schema([
                                    Section::make('Traducciones con Claude')
                                        ->description('La clave de la API de Anthropic se usa para el botón "Traducir desde español" de los formularios. Se guarda cifrada con la APP_KEY de la aplicación: con solo una copia de la base de datos no se puede leer.')
                                        ->schema([
                                            TextInput::make('ai.anthropic_api_key')
                                                ->label('API key de Anthropic')
                                                ->password()
                                                ->revealable()
                                                ->autocomplete('off')
                                                ->placeholder(fn (): string => Setting::hasStoredAnthropicApiKey() ? '•••••••• guardada' : 'sk-ant-...')
                                                ->helperText(fn (): string => Setting::hasStoredAnthropicApiKey()
                                                    ? 'Hay una clave guardada. Dejá el campo vacío para mantenerla o pegá una nueva para reemplazarla.'
                                                    : 'Todavía no hay una clave guardada. La creás en console.anthropic.com → API keys.'),
                                            TextInput::make('ai.anthropic_workspace_id')
                                                ->label('Workspace ID (opcional)')
                                                ->placeholder('wrkspc_...')
                                                ->regex('/^wrkspc_[A-Za-z0-9]+$/')
                                                ->validationMessages(['regex' => 'El ID del workspace empieza con "wrkspc_".'])
                                                ->helperText('Solo hace falta si la clave no pertenece a un workspace (la API lo pide con un error 400). Lo encontrás en console.anthropic.com → Settings → Workspaces. Lo más simple es crear la clave dentro de un workspace y dejar este campo vacío.'),
                                            Actions::make([
                                                Action::make('forgetAnthropicApiKey')
                                                    ->label('Borrar la clave guardada')
                                                    ->color('danger')
                                                    ->link()
                                                    ->requiresConfirmation()
                                                    ->visible(fn (): bool => Setting::hasStoredAnthropicApiKey())
                                                    ->action(function (): void {
                                                        Setting::storeAnthropicApiKey(null);

                                                        Notification::make()->success()->title('Clave borrada')->send();
                                                    }),
                                            ]),
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

        Setting::put(Setting::ANTHROPIC_WORKSPACE_ID, filled($data['ai']['anthropic_workspace_id'] ?? null) ? trim($data['ai']['anthropic_workspace_id']) : null);

        // La clave nunca se vuelve a mostrar: solo se guarda si se pegó una nueva.
        if (filled($data['ai']['anthropic_api_key'] ?? null)) {
            Setting::storeAnthropicApiKey($data['ai']['anthropic_api_key']);
            $this->data['ai']['anthropic_api_key'] = null;
        }

        Notification::make()
            ->success()
            ->title('Ajustes guardados')
            ->send();
    }
}
