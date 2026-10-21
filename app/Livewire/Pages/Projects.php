<?php

namespace App\Livewire\Pages;

use App\Support\ProjectCatalog;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Proyectos')]
class Projects extends Component
{
    public function render()
    {
        return view('livewire.pages.projects', [
            'projects' => ProjectCatalog::entries(),
        ]);
    }
}
