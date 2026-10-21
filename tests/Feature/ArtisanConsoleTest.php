<?php

use App\Actions\Console\RunArtisanCommand;
use App\Filament\Pages\ArtisanConsole;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');
});

test('admin can open the artisan console', function () {
    $this->get(ArtisanConsole::getUrl())->assertOk()->assertSee('Consola Artisan');
});

test('a command runs and shows its output', function () {
    Livewire::test(ArtisanConsole::class)
        ->fillForm(['command' => 'php artisan migrate:status'])
        ->call('run')
        ->assertHasNoFormErrors()
        ->assertSet('runs.0.command', 'migrate:status --no-interaction')
        ->assertSet('runs.0.exit_code', 0)
        ->assertSee('Migration name');
});

test('a quick command can be launched with one click', function () {
    Livewire::test(ArtisanConsole::class)
        ->call('execute', 'about')
        ->assertSet('runs.0.exit_code', 0)
        ->assertSee('Environment');
});

test('an unknown command shows the error instead of failing', function () {
    $result = app(RunArtisanCommand::class)->handle('nope:nothing');

    expect($result['exit_code'])->toBe(1)
        ->and($result['output'])->toContain('nope:nothing');
});

test('commands that never finish are blocked', function () {
    $result = app(RunArtisanCommand::class)->handle('artisan tinker');

    expect($result['exit_code'])->toBe(1)
        ->and($result['output'])->toContain('no se puede ejecutar desde el panel');
});

test('the history keeps only the latest runs', function () {
    $page = Livewire::test(ArtisanConsole::class);

    foreach (range(1, ArtisanConsole::HISTORY_LIMIT + 2) as $ignored) {
        $page->call('execute', 'env');
    }

    expect($page->get('runs'))->toHaveCount(ArtisanConsole::HISTORY_LIMIT);

    $page->call('clearRuns')->assertSet('runs', []);
});

test('guests cannot open the artisan console', function () {
    auth()->logout();

    $this->get(ArtisanConsole::getUrl())->assertRedirect();
});
