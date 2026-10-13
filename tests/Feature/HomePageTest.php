<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;

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
