<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('home hero shows updated copy', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Soy Anselmi')
        ->assertSee('Ayudo a construir productos web rápidos y confiables con Laravel & Livewire');
});
