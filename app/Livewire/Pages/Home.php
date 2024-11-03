<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Post;

class Home extends Component
{
    public function render()
    {
        $main_posts = Post::where('status', 'published')->orderByDesc('published_at')->get()->take(2);

        $posts = Post::where('status', 'published')->orderByDesc('published_at')->whereNotIn('id', $main_posts->pluck('id')->toArray())->get()->take(6);

        return view('livewire.pages.home', [
            'main_posts' => $main_posts,
            'posts' => $posts
        ]);
    }
}
