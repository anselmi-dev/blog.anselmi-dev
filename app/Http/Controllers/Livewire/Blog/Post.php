<?php

namespace App\Http\Controllers\Livewire\Blog;

use App\Models\LikesModel;
use Livewire\Component;
use App\Models\Post as Model;

class Post extends Component
{
    /**
     * Current Post
     *
     * @var Model
     */
    public $post;

    public $hasLike = false;

    public function mount(Model $post)
    {
        $this->post = $post;

        $this->hasLike = $this->post->likes()->where([
            'ip' => request()->ip()
        ])->exists();
    }

    public function render()
    {
        return view('livewire.blog.post');
    }

    public function like ()
    {
        $likes = $this->post->likes()->where([
            'ip' => request()->ip()
        ])->first();

        if ($likes) {
            $likes->delete();
            $this->hasLike = false;
        } else {
            $this->post->likes()->create([
                'ip' => request()->ip()
            ]);

            $this->hasLike = true;
        }
    }
}
