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

    /** @var array{slug: string, title: string, excerpt: string|null, color: string|null, tags: list<string>}|null */
    public ?array $previous = null;

    /** @var array{slug: string, title: string, excerpt: string|null, color: string|null, tags: list<string>}|null */
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
            $this->previous = $this->neighborPayload($prevSlug, $entries[$prevSlug]);
        }

        if ($index !== false && $index < count($slugs) - 1) {
            $nextSlug = $slugs[$index + 1];
            $this->next = $this->neighborPayload($nextSlug, $entries[$nextSlug]);
        }
    }

    /**
     * @param  array<string, mixed>  $entry
     * @return array{slug: string, title: string, excerpt: string|null, color: string|null, tags: list<string>}
     */
    private function neighborPayload(string $slug, array $entry): array
    {
        /** @var list<string> $tags */
        $tags = array_values(array_slice($entry['tags'] ?? [], 0, 4));

        return [
            'slug' => $slug,
            'title' => $entry['title'],
            'excerpt' => $entry['excerpt'] ?? null,
            'color' => $entry['color'] ?? null,
            'tags' => $tags,
        ];
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
