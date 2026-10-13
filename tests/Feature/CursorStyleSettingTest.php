<?php

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use App\Support\CursorStyle;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('the site uses the circle cursor by default', function () {
    $this->get(localized_route('home'))
        ->assertOk()
        ->assertSee('data-cursor="circle"', false);
});

test('the site uses the system cursor when the circle is turned off', function () {
    Setting::putMany('site', ['cursor_style' => CursorStyle::Default->value]);
    Setting::forgetGroupCache('site');

    $this->get(localized_route('home'))
        ->assertOk()
        ->assertSee('data-cursor="default"', false);
});

test('an unknown cursor style falls back to the circle', function () {
    expect(CursorStyle::fromSetting('sparkles'))->toBe(CursorStyle::Circle)
        ->and(CursorStyle::fromSetting(null))->toBe(CursorStyle::Circle);
});

test('admin can choose the cursor style in the settings', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->assertSchemaStateSet(['site.cursor_style' => CursorStyle::Circle])
        ->fillForm(['site.cursor_style' => CursorStyle::Default->value])
        ->call('save')
        ->assertHasNoFormErrors();

    Setting::forgetGroupCache('site');

    expect(CursorStyle::current())->toBe(CursorStyle::Default);
});
