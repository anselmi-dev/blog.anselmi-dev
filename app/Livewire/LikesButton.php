<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Post;

class LikesButton extends Component
{

    public $likes;

    public $hasLike = false;

    public $user_ip = null;

    /**
     * Current post
     *
     * @var Post
     */
    public Post $post;

    public function mount (Post $post)
    {
        $this->likes = $post->likes->count();

        $this->user_ip = request()->ip();

        $this->hasLike = $this->post->likes()->where([
            'ip' => $this->user_ip
        ])->exists();
    }

    public function render()
    {
        return view('livewire.likes-button');
    }

    #[On('like')]
    public function like ()
    {
        if ($this->hasLike) {
            $this->post->likes()->where([
                'ip' => $this->user_ip
            ])->delete();
            $this->likes--;
        } else {
            $this->post->likes()->create([
                'ip' => request()->ip()
            ]);
            $this->likes++;
        }

        $this->hasLike = !$this->hasLike;

        // $this->dispatch('like-success');
    }
}
