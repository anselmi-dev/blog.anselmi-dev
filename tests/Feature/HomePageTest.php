<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

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
