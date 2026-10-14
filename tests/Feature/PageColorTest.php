<?php

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\User;
use App\Support\PageColor;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

test('a page without its own color keeps the default brand color', function () {
    $this->get(localized_route('about'))
        ->assertOk()
        ->assertDontSee('--color-brand-lime:', false);
});

test('a page uses the color chosen for its route in both languages', function () {
    Setting::putMany('site', ['page_colors' => ['about' => '#e3f7fa']]);

    $this->get(route('about'))
        ->assertOk()
        ->assertSee('--color-brand-lime: #e3f7fa;', false)
        ->assertSee('--color-brand-lime-50: color-mix(in srgb, #e3f7fa 35%, white);', false)
        ->assertSee('--color-brand-lime-200: color-mix(in srgb, #e3f7fa 80%, white);', false);

    $this->get(route('en.about'))
        ->assertOk()
        ->assertSee('--color-brand-lime: #e3f7fa;', false);

    $this->get(route('faq'))
        ->assertDontSee('#e3f7fa', false);
});

test('an invalid page color is ignored', function () {
    Setting::putMany('site', ['page_colors' => ['gallery-show' => 'red;}', 'faq' => '#abc']]);

    expect(PageColor::for('gallery.show'))->toBeNull()
        ->and(PageColor::for('en.faq'))->toBe('#abc')
        ->and(PageColor::for(null))->toBeNull();
});

test('admin can choose a color per page in the settings', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm([
            'site.page_colors.home' => '#e3f7fa',
            'site.page_colors.blog-show' => '#ffd6e0',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    Setting::forgetGroupCache('site');

    expect(PageColor::for('home'))->toBe('#e3f7fa')
        ->and(PageColor::for('en.blog.show'))->toBe('#ffd6e0')
        ->and(PageColor::for('about'))->toBeNull();
});

test('the settings reject a color that is not hex', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm(['site.page_colors.home' => 'rgb(1, 2, 3)'])
        ->call('save')
        ->assertHasFormErrors(['site.page_colors.home']);
});
