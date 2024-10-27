<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class Project extends Component
{
    public string $project;

    public function moun(string $project)
    {
        $this->project = $project;
    }

    public function render()
    {
        $view = 'livewire.pages.projects.' . $this->project;

        return view()->exists($view) ? view($view) : abort(404);
    }
}
