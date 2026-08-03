<?php

namespace App\Games\CardModal;

use Livewire\Component;

class CardModal extends Component
{
    public bool $show = false;

    public string $component = '';

    public string $title = '';

    public string $description = '';

    public string $icon = '';

    public string $index = '';

    public function mount(string $component, string $title = '', string $description = '', string $icon = '', string $index = ''): void
    {
        $this->component = $component;
        $this->title = $title;
        $this->description = $description;
        $this->icon = $icon;
        $this->index = $index;
    }

    public function open(): void
    {
        $this->show = true;
    }

    public function close(): void
    {
        $this->show = false;
    }

    public function render()
    {
        return view('games::modal-game');
    }
}
