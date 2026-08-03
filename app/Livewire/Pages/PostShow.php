<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.site')]
class PostShow extends Component
{
    public string $slug = '';

    /** @var array<string, mixed> */
    public array $entry = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;

        $post = Post::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        $this->entry = $post->toEntryArray();
    }

    public function render()
    {
        return view('livewire.pages.post-show', [
            'entry' => $this->entry,
        ])->title(($this->entry['title'] ?? 'Post').' — Blog');
    }
}
