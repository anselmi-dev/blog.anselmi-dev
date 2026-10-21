<?php

namespace App\Livewire\Pages;

use App\CuriousCode\CuriousCodeCatalog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Código curioso')]
class CuriousCode extends Component
{
    public function render()
    {
        return view('livewire.pages.curious-code', [
            'projects' => CuriousCodeCatalog::entries(),
        ]);
    }
}
