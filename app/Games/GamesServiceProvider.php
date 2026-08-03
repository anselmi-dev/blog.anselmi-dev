<?php

namespace App\Games;

use App\Games\TicTactToe\Livewire\TicTacToe;
use App\Games\CardModal\CardModal;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class GamesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/TicTactToe/Resources/Views', 'games');
        $this->loadViewsFrom(__DIR__ . '/CardModal', 'games');

        Livewire::component('games.tic-tac-toe', TicTacToe::class);
        Livewire::component('games.card-modal', CardModal::class);
    }
}
