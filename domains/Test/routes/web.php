<?php

declare(strict_types=1);

use Domains\Test\Livewire\Home;
use Illuminate\Support\Facades\Route;

Route::livewire('/', Home::class)->name('test.home');
