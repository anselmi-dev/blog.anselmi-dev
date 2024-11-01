<?php

namespace App\Http\Controllers\Livewire\Blog;

use Livewire\Component;
use App\Models\Post as Model;
use App\Models\Like;

class Post extends Component
{
    /**
     * Current Post
     *
     * @var Model
     */
    public $post;

    public function mount(Model $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.blog.post');
    }
}
