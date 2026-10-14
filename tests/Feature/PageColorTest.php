<?php

use App\Filament\Pages\ManageSettings;
use App\Models\Setting;
use App\Models\SiteColor;
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

test('admin can choose a color per page from the colors resource', function () {
    SiteColor::query()->create(['name' => 'Celeste', 'hex' => '#e3f7fa']);
    SiteColor::query()->create(['name' => 'Rosa', 'hex' => '#ffd6e0']);

    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm([
            'site.page_colors.home' => '#E3F7FA',
            'site.page_colors.blog-show' => '#FFD6E0',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    Setting::forgetGroupCache('site');

    expect(PageColor::for('home'))->toBe('#E3F7FA')
        ->and(PageColor::for('en.blog.show'))->toBe('#FFD6E0')
        ->and(PageColor::for('about'))->toBeNull();
});

test('the settings reject a value that is not a color', function () {
    SiteColor::query()->create(['name' => 'Celeste', 'hex' => '#e3f7fa']);

    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    Livewire::test(ManageSettings::class)
        ->fillForm(['site.page_colors.home' => 'red;}'])
        ->call('save')
        ->assertHasFormErrors(['site.page_colors.home']);
});

test('the color options list the colors resource and keep a custom saved color', function () {
    SiteColor::query()->create(['name' => 'Celeste', 'hex' => '#e3f7fa']);

    $options = PageColor::options('#abcdef');

    expect(array_keys($options))->toBe(['#E3F7FA', '#abcdef'])
        ->and($options['#E3F7FA'])->toContain('Celeste')
        ->and($options['#abcdef'])->toContain('Personalizado');
});
