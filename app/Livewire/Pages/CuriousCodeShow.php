<?php

namespace App\Livewire\Pages;

use App\CuriousCode\CuriousCodeCatalog;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.site')]
class CuriousCodeShow extends Component
{
    public string $slug = '';

    /** @var array<string, mixed> */
    public array $project = [];

    public function mount(string $slug): void
    {
        $entries = CuriousCodeCatalog::entries();

        if (! isset($entries[$slug])) {
            abort(404);
        }

        $this->slug = $slug;
        $this->project = $entries[$slug];
    }

    public function render()
    {
        return view('livewire.pages.curious-code-show')
            ->title($this->project['title'].' — '.__('Código curioso'))
            ->layoutData([
                'themeColor' => $this->project['color'] ?? null,
            ]);
    }
}
