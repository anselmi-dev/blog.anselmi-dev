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
        $intro = [
            'type' => 'intro',
            'title' => 'Notas y borradores',
            'body' => 'Textos cortos sobre cómo trabajo, qué uso en el stack y por qué a veces conviene decir que no. Nada de manifiestos: contexto útil para futuro yo y para quien toque el mismo código.',
            'gridClass' => 'sm:col-span-2 xl:col-span-6',
        ];

        $posts = Post::query()
            ->published()
            ->inBento()
            ->get();

        $cells = [$intro];

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
