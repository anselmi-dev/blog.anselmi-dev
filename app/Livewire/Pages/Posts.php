<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Blog')]
class Posts extends Component
{
    /**
     * Celdas del bento enriquecidas con la entrada por slug.
     *
     * @var list<array<string, mixed>>
     */
    public array $cells = [];

    public function mount(): void
    {
        $posts = Post::query()
            ->published()
            ->inBento()
            ->get();

        $cells = [];

        foreach ($posts as $post) {
            $cells[] = [
                'type' => $post->bento_type ?: ($post->kind === 'image' ? 'image' : 'card'),
                'slug' => $post->slug,
                'gridClass' => $post->bento_grid_class,
                'entry' => $post->toEntryArray(),
            ];
        }

        $this->cells = $cells;
    }

    public function render()
    {
        return view('livewire.pages.posts');
    }
}
