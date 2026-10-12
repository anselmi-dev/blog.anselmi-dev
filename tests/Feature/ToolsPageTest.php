<?php

use App\Filament\Resources\Tools\Pages\EditTool;
use App\Models\Setting;
use App\Models\Tool;
use App\Models\User;
use App\Support\JarPalette;
use App\Support\JarShape;
use Database\Seeders\ToolDescriptionSeeder;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function makeTool(array $attributes = []): Tool
{
    return Tool::create([
        'name' => 'Laravel',
        'tagline' => 'Backend & estructura de la app',
        'url' => 'https://laravel.com',
        'category' => 'Backend',
        'icon' => 'laravel',
        'sort_order' => 0,
        'is_published' => true,
        ...$attributes,
    ]);
}

test('tools page shows one labeled jar per published tool and no loader by default', function () {
    makeTool();
    makeTool(['name' => 'Tailwind CSS', 'category' => 'Frontend', 'icon' => 'tailwindcss', 'sort_order' => 1]);
    makeTool(['name' => 'Herramienta oculta', 'is_published' => false]);

    $response = $this->get(localized_route('services.tools', [], 'es'));

    $response->assertOk()
        ->assertDontSee('data-page-loader', false)
        ->assertSee('data-tools-wheel', false)
        ->assertSee('https://cdn.simpleicons.org/laravel/', false)
        ->assertSeeInOrder(['Laravel', 'Tailwind CSS'])
        ->assertSee('01 / 02')
        ->assertSee('Cada frasco es una herramienta de mi stack')
        ->assertDontSee('Herramienta oculta');

    expect(substr_count($response->getContent(), 'data-tools-wheel-item'))->toBe(2);
});

test('tools page is translated under /en', function () {
    makeTool(['tagline_en' => 'Backend & app structure']);

    $this->get(localized_route('services.tools', [], 'en'))
        ->assertOk()
        ->assertSee('Drag or use the arrows to spin')
        ->assertSee('Backend & app structure')
        ->assertDontSee('Backend & estructura de la app');
});

test('tools page without tools does not render the wheel', function () {
    $this->get(localized_route('services.tools'))
        ->assertOk()
        ->assertDontSee('data-tools-wheel', false);
});

test('tools page content continues the header without a gap', function () {
    makeTool();

    $this->get(localized_route('services.tools'))
        ->assertOk()
        ->assertSee('data-site-header', false)
        ->assertSee('data-joined-header', false);
});

test('tools page passes jar data to the 3d scene and has a detail panel', function () {
    makeTool([
        'tagline_en' => 'Backend & app structure',
        'description' => 'Framework PHP para aplicaciones web.',
        'description_en' => 'A PHP framework for web applications.',
    ]);

    $response = $this->get(localized_route('services.tools', [], 'en'))->assertOk();

    preg_match('/data-tools-wheel-jars="([^"]+)"/', $response->getContent(), $matches);
    $jars = json_decode(html_entity_decode($matches[1] ?? '[]'), true);

    expect($jars)->toHaveCount(1)
        ->and($jars[0])->toMatchArray([
            'name' => 'Laravel',
            'category' => 'Backend',
            'tagline' => 'Backend & app structure',
            'description' => 'A PHP framework for web applications.',
            'url' => 'https://laravel.com',
            'icon' => 'laravel',
        ])
        ->and($jars[0]['palette'])->toHaveCount(4);

    $response->assertSee('data-tools-wheel-panel', false)
        ->assertSee('data-tools-wheel-canvas', false)
        ->assertSee('See jar in detail');
});

test('tool description seeder fills only empty descriptions', function () {
    makeTool(['description' => 'Mi propia descripción.']);
    makeTool(['name' => 'Livewire', 'category' => 'Frontend', 'icon' => 'livewire']);

    $this->seed(ToolDescriptionSeeder::class);

    expect(Tool::query()->where('name', 'Laravel')->first())
        ->description->toBe('Mi propia descripción.')
        ->description_en->toStartWith('A PHP framework');

    expect(Tool::query()->where('name', 'Livewire')->value('description'))->toStartWith('Permite crear interfaces');
});

test('each jar uses the colors chosen in filament', function () {
    makeTool(['label_color' => '#1e5876', 'fill_color' => '#e0a64a']);
    makeTool(['name' => 'Alpine.js', 'label_color' => '#86bccb', 'sort_order' => 1]);
    makeTool(['name' => 'Sin color', 'sort_order' => 2]);

    $response = $this->get(localized_route('services.tools', [], 'es'))->assertOk();

    preg_match('/data-tools-wheel-jars="([^"]+)"/', $response->getContent(), $matches);
    $palettes = array_column(json_decode(html_entity_decode($matches[1]), true), 'palette');

    expect($palettes[0])->toBe(['#1e5876', '#47768f', '#f5efe0', '#e0a64a'])
        ->and($palettes[1][0])->toBe('#86bccb')
        ->and($palettes[1][2])->toBe('#1f2a12')
        ->and($palettes[1][3])->toBe(JarPalette::DEFAULT_FILL)
        ->and($palettes[2])->toBe(JarPalette::fallback(2));
});

test('jar palette ignores invalid colors', function () {
    expect(JarPalette::fromColors('rojo'))->toBeNull()
        ->and(JarPalette::fromColors(null))->toBeNull()
        ->and(JarPalette::fromColors('#FFF')[0])->toBe('#ffffff');
});

test('admin can set the jar colors of a tool', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    $tool = makeTool();

    Livewire::test(EditTool::class, ['record' => $tool->getRouteKey()])
        ->fillForm(['label_color' => '#6a4fae', 'fill_color' => '#e9c35b'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($tool->refresh())
        ->label_color->toBe('#6a4fae')
        ->fill_color->toBe('#e9c35b');
});

test('tools page shows the loader when it is enabled in the settings', function () {
    makeTool();
    Setting::putMany('site', ['tools_loader' => true]);
    Setting::forgetGroupCache('site');

    $this->get(localized_route('services.tools', [], 'es'))
        ->assertOk()
        ->assertSee('data-page-loader', false);
});

test('each jar uses the shape chosen in filament', function () {
    makeTool(['jar_shape' => JarShape::Hexagonal]);
    makeTool(['name' => 'Sin forma', 'sort_order' => 1]);

    $response = $this->get(localized_route('services.tools', [], 'es'))->assertOk();

    preg_match('/data-tools-wheel-jars="([^"]+)"/', $response->getContent(), $matches);
    $shapes = array_column(json_decode(html_entity_decode($matches[1]), true), 'shape');

    expect($shapes)->toBe(['hexagonal', JarShape::fallback(1)->value]);
    $response->assertSee('data-shape="hexagonal"', false);
});

test('admin can choose the jar shape of a tool', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);
    $this->actingAs($user);
    Filament::setCurrentPanel('admin');

    $tool = makeTool();

    Livewire::test(EditTool::class, ['record' => $tool->getRouteKey()])
        ->fillForm(['jar_shape' => 'round'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($tool->refresh()->jar_shape)->toBe(JarShape::Round);
});

test('tools carousel has its own loader while the jars load', function () {
    makeTool();

    $this->get(localized_route('services.tools', [], 'es'))
        ->assertOk()
        ->assertSee('data-tools-wheel-loader', false)
        ->assertSee('data-tools-wheel-jars-layer', false);
});
