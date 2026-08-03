<?php

namespace App\Livewire\Pages;

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
            'projects' => config('projects.entries', []),
        ]);
    }
}
