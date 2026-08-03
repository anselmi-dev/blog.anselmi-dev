<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Inicio')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.pages.home');
    }
}
