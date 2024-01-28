<?php

namespace App\Http\Controllers\Livewire\Blog;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
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
        return view('livewire.blog.blog', [
            'posts' => Post::when($this->tag, function ($query) {
                $query->whereHas('tags', function ($q) {
                    $q->where('slug', $this->tag);
                });
            })->paginate($this->filters['paginate'])
        ]);
    }

    public function getPosts ()
    {
        return Post::when($this->tag, function ($query) {
            $query->whereHas('tags', function ($q) {
                $q->where('tag_id', $this->tag->id);
            });
        })->paginate($this->filters['paginate']);
    }
}
