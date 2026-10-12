<?php

use App\Models\Tool;
use Database\Seeders\ToolDescriptionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

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

test('tools page shows a loader and one labeled jar per published tool', function () {
    makeTool();
    makeTool(['name' => 'Tailwind CSS', 'category' => 'Frontend', 'icon' => 'tailwindcss', 'sort_order' => 1]);
    makeTool(['name' => 'Herramienta oculta', 'is_published' => false]);

    $response = $this->get(localized_route('services.tools', [], 'es'));

    $response->assertOk()
        ->assertSee('data-page-loader', false)
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
        ->assertSee('Scroll or drag to spin')
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
