<?php

namespace App\Filament\Pages;

use App\Actions\Console\RunArtisanCommand;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
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
class ArtisanConsole extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCommandLine;

    protected static string|UnitEnum|null $navigationGroup = 'Sitio';

    protected static ?string $navigationLabel = 'Consola Artisan';

    protected static ?string $title = 'Consola Artisan';

    protected static ?int $navigationSort = 100;

    protected string $view = 'filament.pages.artisan-console';

    /** Cuántas ejecuciones anteriores se muestran debajo. */
    public const int HISTORY_LIMIT = 10;

    /**
     * Comandos frecuentes para lanzar con un clic: comando => texto del botón.
     *
     * @var array<string, string>
     */
    public const QUICK_COMMANDS = [
        'about' => 'Info de la app',
        'optimize:clear' => 'Limpiar cachés',
        'optimize' => 'Cachear config y rutas',
        'migrate:status' => 'Estado de migraciones',
        'migrate --force' => 'Migrar',
        'storage:link' => 'Enlazar storage',
        'queue:restart' => 'Reiniciar colas',
        'route:list --except-vendor' => 'Rutas',
    ];

    /**
     * @var array<string, mixed>|null
     */
    public ?array $data = [];

    /**
     * Ejecuciones de esta sesión, la más reciente primero.
     *
     * @var list<array{command: string, exit_code: int, output: string, duration_ms: int, ran_at: string}>
     */
    public array $runs = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    Section::make()
                        ->description('Corre el comando en el servidor, sin interacción (se agrega --no-interaction). En producción, los comandos que modifican la base piden --force.')
                        ->schema([
                            TextInput::make('command')
                                ->label('Comando')
                                ->prefix('php artisan')
                                ->placeholder('migrate:status')
                                ->autocomplete('off')
                                ->required()
                                ->maxLength(500)
                                ->extraInputAttributes(['class' => 'font-mono']),
                            Actions::make(collect(self::QUICK_COMMANDS)
                                ->map(fn (string $label, string $command): Action => Action::make('quick'.md5($command))
                                    ->label($label)
                                    ->tooltip("php artisan {$command}")
                                    ->color('gray')
                                    ->size('sm')
                                    ->outlined()
                                    ->requiresConfirmation(str_contains($command, '--force'))
                                    ->modalDescription("Se va a ejecutar php artisan {$command}.")
                                    ->action(fn () => $this->execute($command)))
                                ->values()
                                ->all()),
                        ]),
                ])
                    ->livewireSubmitHandler('run')
                    ->footer([
                        Actions::make([
                            Action::make('run')
                                ->label('Ejecutar')
                                ->icon(Heroicon::OutlinedPlay)
                                ->submit('run')
                                ->keyBindings(['mod+enter']),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function run(): void
    {
        $this->execute((string) $this->form->getState()['command']);
    }

    public function execute(string $command): void
    {
        $result = app(RunArtisanCommand::class)->handle($command);

        array_unshift($this->runs, [...$result, 'ran_at' => now()->format('H:i:s')]);
        $this->runs = array_slice($this->runs, 0, self::HISTORY_LIMIT);
    }

    public function clearRuns(): void
    {
        $this->runs = [];
    }
}
