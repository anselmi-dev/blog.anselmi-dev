<?php

namespace App\Http\Controllers\Livewire\Blog;

use App\Models\Post;
use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class PostList extends Component
{
    use WithPagination;

    /**
     * Current Tag
     *
     * @var App\Models\Tag
     */
    public $tag;

    public $filters = [
        'paginate' => 5
    ];

    public function render()
    {
        return view('livewire.blog.post-list', [
            'posts' => $this->getPosts()
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
