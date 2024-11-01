<?php

namespace App\Http\Controllers\Livewire\Blog;

use Livewire\Component;
use App\Models\Post as Model;

class Post extends Component
{
    /**
     * Current Post
     *
     * @var Model
     */
    public Model $post;

    public function mount(Model $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        return view('livewire.blog.post');
    }
}
