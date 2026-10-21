<?php

use App\Models\Faq;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('faq page lists published questions and answers without a toggle', function () {
    Faq::create([
        'question' => '¿Cuánto tarda un proyecto?',
        'answer' => 'Entre dos y seis semanas según el alcance.',
        'sort_order' => 1,
        'is_published' => true,
    ]);

    Faq::create([
        'question' => 'Pregunta sin publicar',
        'answer' => 'Respuesta sin publicar',
        'sort_order' => 2,
        'is_published' => false,
    ]);

    $response = $this->get(route('faq'));

    $response->assertOk()
        ->assertSee('¿Cuánto tarda un proyecto?', false)
        ->assertSee('Entre dos y seis semanas según el alcance.', false)
        ->assertDontSee('Pregunta sin publicar')
        ->assertDontSee('faq-trigger-', false)
        ->assertDontSee('faq-panel-', false)
        ->assertSee('data-scroll-drift', false);
});
