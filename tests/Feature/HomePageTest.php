<?php

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('home hero shows updated copy', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Soy Anselmi')
        ->assertSee('Ayudo a construir productos web rápidos y confiables con Laravel & Livewire');
});

test('home hero keeps the second line inline with the mark on mobile', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('mt-2 block sm:mt-3 sm:flex', false)
        ->assertSee('impulsan tu presencia digital');
});

test('the header logo has the stroke layer for its drawing animation', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    expect($content)->toContain('data-animated-logo')
        ->and(substr_count($content, '<path pathLength="1"'))->toBe(5);
});

test('the logo is static when it is not animated', function () {
    $html = Blade::render('<x-logo.app />');

    expect($html)->not->toContain('data-animated-logo')
        ->not->toContain('data-logo-stroke')
        ->toContain('data-logo-fill');
});

test('the rising squares background is shown by default', function () {
    $content = $this->get(route('home'))->assertOk()->getContent();

    expect($content)->toContain('data-rising-squares')
        ->and(substr_count($content, '--duration:'))->toBe(10);
});

test('the rising squares background can be turned off in the settings', function () {
    Setting::putMany('site', ['background_squares' => false]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee('data-rising-squares', false);
});

test('admin can turn off the rising squares background', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->assertSchemaStateSet(['site.background_squares' => true])
        ->fillForm(['site.background_squares' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Setting::site()['background_squares'])->toBeFalse();
});

test('the rising squares use the opacity and speed from the settings', function () {
    Setting::putMany('site', ['background_squares_opacity' => 40, 'background_squares_speed' => 2]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('--squares-opacity: 40%; --squares-speed: 2;', false);
});

test('the rising squares keep the opacity and speed within range', function () {
    $html = Blade::render('<x-rising-squares :opacity="500" :speed="0" />');

    expect($html)->toContain('--squares-opacity: 100%; --squares-speed: 0.25;');
});

test('admin can set the opacity and speed of the rising squares', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm(['site.background_squares_opacity' => 30, 'site.background_squares_speed' => 1.5])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(Setting::site())
        ->background_squares_opacity->toEqual(30)
        ->background_squares_speed->toEqual(1.5);
});

test('the page content is marked for the navigation transition', function () {
    $content = $this->get(localized_route('home'))->assertOk()->getContent();

    expect($content)->toContain('<main class="flex-1" data-page-transition>');
});
