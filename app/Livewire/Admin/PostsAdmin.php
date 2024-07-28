<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Post;

class PostsAdmin extends Component
{
    use WithPagination;

    /**
     * Current Tag
     *
     * @var App\Models\Tag
     */
    public $tag;

    public $filters = [
        'paginate' => 10
    ];

    public function render()
    {
        return view('livewire.admin.posts-admin', [
            'posts' => Post::when($this->tag, function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->where('slug', $this->tag);
                });
            })->orderByDesc('published_at')->paginate($this->filters['paginate'])
        ])->layout('layouts.app');
    }
}
