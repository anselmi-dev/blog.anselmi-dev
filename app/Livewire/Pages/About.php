<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Sobre mí')]
class About extends Component
{
    public function render()
    {
        return view('livewire.pages.about');
    }
}
