<?php

namespace App\Livewire\Blog;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Post;

class Posts extends Component
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
    
    public function paginationView()
    {
        // return 'vendor.pagination.livewire-tailwind';
        return 'vendor.livewire.links-tailwind';
    }

    public function render()
    {
        return view('livewire.blog.posts', [
            'posts' => Post::when($this->tag, function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->where('slug', $this->tag);
                });
            })->where('status', 'published')->orderByDesc('published_at')->paginate($this->filters['paginate'])
        ]);
    }
}
