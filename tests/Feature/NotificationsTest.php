<?php

use App\Livewire\ContactModal;
use App\Support\NotificationVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()->setLocale('es');
});

test('the notification card renders its variant, badge, icon and actions', function () {
    $html = Blade::render(<<<'BLADE'
        <x-notification variant="warning" title="Configuración incompleta" message="Faltan campos.">
            <x-slot:actions>
                <a href="/revisar" class="notification-action notification-action-primary">Revisar</a>
            </x-slot:actions>
        </x-notification>
    BLADE);

    expect($html)
        ->toContain('data-variant="warning"')
        ->toContain('role="alert"')
        ->toContain('notification-badge')
        ->toContain('Atención')
        ->toContain('Configuración incompleta')
        ->toContain('Faltan campos.')
        ->toContain('href="/revisar"');
});

test('the neutral card has no badge and a custom badge or none can be chosen', function () {
    expect(Blade::render('<x-notification variant="neutral" title="Hola" />'))
        ->not->toContain('notification-badge')
        ->toContain('role="status"');

    expect(Blade::render('<x-notification variant="pending" title="Hola" badge="Subiendo" />'))
        ->toContain('Subiendo')
        ->not->toContain('Pendiente');

    expect(Blade::render('<x-notification variant="success" title="Hola" :badge="false" />'))
        ->not->toContain('notification-badge');
});

test('an unknown variant falls back to neutral', function () {
    expect(Blade::render('<x-notification variant="raro" title="Hola" />'))
        ->toContain('data-variant="neutral"');
});

test('the site layout includes the notification stack with badges in the page language', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('notification-stack', escape: false)
        ->assertSee('Pendiente');

    $this->get('/en')
        ->assertOk()
        ->assertSee('notification-stack', escape: false)
        ->assertSee('Pending');
});

test('every variant has its badge text except the neutral one', function () {
    expect(NotificationVariant::badges())->toBe([
        'pending' => 'Pendiente',
        'warning' => 'Atención',
        'success' => 'Listo',
        'error' => 'Error',
        'neutral' => null,
    ]);
});

test('the contact form warns with a notification when too many messages are sent', function () {
    RateLimiter::shouldReceive('attempt')->andReturn(false);
    RateLimiter::shouldReceive('availableIn')->andReturn(42);

    Livewire::test(ContactModal::class)
        ->set('name', 'Ana')
        ->set('email', 'ana@example.com')
        ->set('message', 'Hola, quiero hacer una web.')
        ->call('submit')
        ->assertHasErrors('email')
        ->assertDispatched('notify', variant: 'warning', title: 'Muchos mensajes seguidos', message: 'Esperá 42 segundos y volvé a enviarlo.', key: 'contact');
});
