<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('about page shows updated copy', function () {
    $response = $this->get(route('about'));

    $response->assertOk()
        ->assertSee('Carlos Anselmi')
        ->assertSee('Desarrollador fullstack')
        ->assertSee('Ayudo a construir productos web rápidos y confiables con Laravel & Livewire')
        ->assertSee('Sobre mí')
        ->assertSee('plataformas de gestión financiera')
        ->assertSee('Filament, Flux')
        ->assertSee('Stripe, Sumsub, Mailchimp');
});

test('about page lists projects without images', function () {
    $projects = config('projects.entries', []);

    expect($projects)->not->toBeEmpty();

    $response = $this->get(route('about'));

    $response->assertOk()
        ->assertSee('Proyectos')
        ->assertDontSee('/images/proyectos/', false);

    foreach ($projects as $slug => $project) {
        $response
            ->assertSee($project['title'])
            ->assertSee($project['excerpt'])
            ->assertSee(route('projects.show', $slug), false);
    }
});
