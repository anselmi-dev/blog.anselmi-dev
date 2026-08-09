<?php

declare(strict_types=1);

use Domains\Calendar\Livewire\Home;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Home::class)->name('calendar.home');
