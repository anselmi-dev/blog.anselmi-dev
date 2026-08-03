<?php

namespace App\Livewire\Pages;

use App\Models\Tool;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Mis herramientas')]
class Tools extends Component
{
    public function render()
    {
        $tools = Tool::query()
            ->published()
            ->get()
            ->map(fn (Tool $tool): array => $tool->toViewArray())
            ->all();

        return view('livewire.pages.tools', [
            'tools' => $tools,
        ]);
    }
}
