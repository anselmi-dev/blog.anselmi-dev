<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Borra las visitas con más de PageView::KEEP_MONTHS meses (y lo que tenga MassPrunable).
Schedule::command('model:prune')->daily();
