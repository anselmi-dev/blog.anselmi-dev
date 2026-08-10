<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('projects page shows updated hero copy', function () {
    $response = $this->get(route('projects'));

    $response->assertOk()
        ->assertSee('Trabajos')
        ->assertSee('Proyectos')
        ->assertSee('Productos web en los que participé como fullstack')
        ->assertSee('Abrí cada ficha para ver contexto, stack y capturas')
        ->assertDontSee('Selección de trabajos recientes');
});
