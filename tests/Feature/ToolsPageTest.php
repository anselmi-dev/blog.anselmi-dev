<?php

use App\Models\Tool;
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

    $response = $this->get(localized_route('services.tools'));

    $response->assertOk()
        ->assertSee('data-page-loader', false)
        ->assertSee('data-tools-wheel', false)
        ->assertSee('https://cdn.simpleicons.org/laravel/', false)
        ->assertSeeInOrder(['Laravel', 'Tailwind CSS'])
        ->assertSee('01 / 02')
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
