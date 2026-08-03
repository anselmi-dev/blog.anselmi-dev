<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.site')]
class ProjectShow extends Component
{
    public string $slug = '';

    /** @var array<string, mixed> */
    public array $project = [];

    /** @var array{slug: string, title: string}|null */
    public ?array $previous = null;

    /** @var array{slug: string, title: string}|null */
    public ?array $next = null;

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $entries = config('projects.entries', []);

        if (! isset($entries[$slug])) {
            abort(404);
        }

        $this->project = $entries[$slug];

        $slugs = array_keys($entries);
        $index = array_search($slug, $slugs, true);

        if ($index !== false && $index > 0) {
            $prevSlug = $slugs[$index - 1];
            $this->previous = [
                'slug' => $prevSlug,
                'title' => $entries[$prevSlug]['title'],
            ];
        }

        if ($index !== false && $index < count($slugs) - 1) {
            $nextSlug = $slugs[$index + 1];
            $this->next = [
                'slug' => $nextSlug,
                'title' => $entries[$nextSlug]['title'],
            ];
        }
    }

    public function render()
    {
        return view('livewire.pages.project-show')
            ->title($this->project['title'].' — Proyectos')
            ->layoutData([
                'themeColor' => $this->project['color'] ?? null,
            ]);
    }
}
