<?php

declare(strict_types=1);

use Domains\Calendar\CalendarServiceProvider;
use Domains\Test\TestServiceProvider;

return [

    /*
    |--------------------------------------------------------------------------
    | Dominios registrados
    |--------------------------------------------------------------------------
    |
    | Cada entrada apunta al ServiceProvider del dominio. Ese provider carga
    | rutas, vistas, migraciones, Livewire y config del sitio.
    |
    | El portfolio principal (app/ + routes/web.php) NO se registra acá:
    | sigue siendo el sitio por defecto en cualquier host no listado abajo.
    |
    | connection: null = comparte DB global | string = conexión en database.php
    |
    */

    'sites' => [

        'calendar' => [
            'provider' => CalendarServiceProvider::class,
            'hosts' => array_values(array_filter(array_map(
                static fn (string $h): string => strtolower(trim($h)),
                explode(',', (string) env('DOMAIN_CALENDAR_HOSTS', 'calendar.anselmidev.test')),
            ))),
            'connection' => env('DOMAIN_CALENDAR_DB_CONNECTION'),
        ],

        'test' => [
            'provider' => TestServiceProvider::class,
            'hosts' => array_values(array_filter(array_map(
                static fn (string $h): string => strtolower(trim($h)),
                explode(',', (string) env('DOMAIN_TEST_HOSTS', 'test.anselmidev.test')),
            ))),
            'connection' => env('DOMAIN_TEST_DB_CONNECTION'), // null = shared
        ],

    ],

];
