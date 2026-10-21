<?php

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function setUnderConstruction(array $values): void
{
    Setting::putMany('site', $values);
    Setting::forgetGroupCache('site');
}

test('the site shows no construction notice by default', function () {
    $this->get('/')
        ->assertOk()
        ->assertDontSee('data-under-construction', escape: false);
});

test('the site shows the construction notice through the notifications module when it is on', function () {
    setUnderConstruction([
        'under_construction' => true,
        'under_construction_title' => 'Renovando el sitio',
        'under_construction_message' => 'Vuelvo pronto con todo listo.',
    ]);

    $this->get('/')
        ->assertOk()
        ->assertSee('data-under-construction', escape: false)
        ->assertSee('window.notify(', escape: false)
        ->assertSee('Renovando el sitio')
        ->assertSee('Vuelvo pronto con todo listo.')
        ->assertSee('En construcción')
        ->assertSee('under-construction:', escape: false);
});

test('the construction notice uses the english texts on english pages', function () {
    setUnderConstruction([
        'under_construction' => true,
        'under_construction_title' => 'Renovando el sitio',
        'under_construction_title_en' => 'Reworking the site',
    ]);

    $this->get('/en')
        ->assertOk()
        ->assertSee('Reworking the site')
        ->assertSee('Under construction')
        ->assertDontSee('Renovando el sitio');
});

test('the construction notice falls back to the default texts', function () {
    setUnderConstruction(['under_construction' => true, 'under_construction_title' => '']);

    $this->get('/')
        ->assertOk()
        ->assertSee('Sitio en construcción');

    $this->get('/en')
        ->assertOk()
        ->assertSee('Site under construction')
        ->assertSee('reworking a few sections');
});

test('admin can turn the construction notice on from the settings', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->assertSchemaStateSet(['site.under_construction' => false])
        ->fillForm([
            'site.under_construction' => true,
            'site.under_construction_title' => 'Renovando el sitio',
            'site.under_construction_message' => 'Vuelvo pronto.',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    Setting::forgetGroupCache('site');

    expect(Setting::site())
        ->under_construction->toBeTrue()
        ->under_construction_title->toBe('Renovando el sitio');
});

test('the construction title is required while the notice is on', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm(['site.under_construction' => true, 'site.under_construction_title' => ''])
        ->call('save')
        ->assertHasFormErrors(['site.under_construction_title' => 'required']);
});
