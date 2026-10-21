<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('curious code page lists projects with the disclaimer', function () {
    $response = $this->get(route('curious-code'));

    $response->assertOk()
        ->assertSee('Código curioso')
        ->assertSee('No son proyectos míos')
        ->assertSee('ascii-sharks');
});

test('curious code show page links to the original project', function () {
    $response = $this->get(route('curious-code.show', 'ascii-sharks'));

    $response->assertOk()
        ->assertSee('ascii-sharks')
        ->assertSee('https://github.com/stackedlol/ascii-sharks', false);
});

test('curious code show page 404s for an unknown slug', function () {
    $this->get(route('curious-code.show', 'does-not-exist'))->assertNotFound();
});
