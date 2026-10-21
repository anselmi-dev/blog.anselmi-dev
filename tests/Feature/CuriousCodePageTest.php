<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('curious code page lists projects with the disclaimer', function () {
    $response = $this->get(route('curious-code'));

    $response->assertOk()
        ->assertSee('Código curioso')
        ->assertSee('No son proyectos míos')
        ->assertSee('ascii-sharks')
        ->assertSee('images/curious-code/ascii-sharks.jpg', false);
});

test('curious code show page links to the original project and mounts its demo', function () {
    $response = $this->get(route('curious-code.show', 'ascii-sharks'));

    $response->assertOk()
        ->assertSee('ascii-sharks')
        ->assertSee('https://github.com/stackedlol/ascii-sharks', false)
        ->assertSee('data-ascii-ocean', false)
        ->assertSee('data-ascii-ocean-kind="mako"', false)
        ->assertSee('Elegí hasta cuatro especies: la primera queda más grande. Mové el mouse sobre el agua o hacé clic para chapotear.', false)
        ->assertSee('stackedlol');
});

test('curious code show page translates the ascii sharks demo', function () {
    $this->get(route('en.curious-code.show', 'ascii-sharks'))
        ->assertOk()
        ->assertSee('data-ascii-ocean', false)
        ->assertSee('Pick up to four species: the first one stays the largest. Move the mouse over the water or click to splash.', false)
        ->assertSee('Hammerhead')
        ->assertSeeText('The animation on this page is the original project\'s demo.');
});

test('curious code show page 404s for an unknown slug', function () {
    $this->get(route('curious-code.show', 'does-not-exist'))->assertNotFound();
});
