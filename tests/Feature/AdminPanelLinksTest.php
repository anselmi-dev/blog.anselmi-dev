<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visitors do not see the admin panel link', function () {
    $this->get('/')
        ->assertOk()
        ->assertDontSee('data-admin-panel-link', escape: false);
});

test('logged in users without panel access do not see the admin panel link', function () {
    config(['app.admin_emails' => ['otra@example.com']]);

    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertOk()
        ->assertDontSee('data-admin-panel-link', escape: false);
});

test('admins see a link to the panel on the site in both languages', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);

    $this->actingAs($user)
        ->get('/')
        ->assertOk()
        ->assertSee('data-admin-panel-link', escape: false)
        ->assertSee('href="'.url('/admin').'"', escape: false)
        ->assertSee('Ir al panel');

    $this->actingAs($user)
        ->get('/en/about')
        ->assertOk()
        ->assertSee('Go to admin panel');
});

test('the panel has a link to the public site', function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertOk()
        ->assertSee('Ver el sitio')
        ->assertSee('href="'.route('home').'"', escape: false);
});

test('the header buttons explain themselves with tooltips', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('data-tooltip="View this page in English"', escape: false);

    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);

    $this->actingAs($user)
        ->get('/')
        ->assertSee('data-tooltip="Ir al panel"', escape: false);
});
