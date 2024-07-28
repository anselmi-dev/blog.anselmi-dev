<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Models\Post;
use App\Models\Tag;

class PostFormAdmin extends Component
{
    use WithFileUploads;

    /**
     * Current post
     *
     * @var Post
     */
    public $post;

    public $cover;

    public $tags;

    public function mount(Post $post)
    {
        $this->post = $post;

        $this->tags = $this->post->tags->pluck('id')->toArray();
    }

    protected $rules = [
        'post.title' => 'required|min:6|max:200',
        "post.status" => 'required',
        "post.description" => 'required|max:400',
        "post.content" => 'required|min:100',
        "post.published_at" => 'required',
        'cover' => 'max:1024',
        'tags' => '',
    ];

    public function render()
    {
        return view('livewire.admin.post-form-admin')->layout('layouts.app');
    }

    public function submit ()
    {
        $this->validate();

        $this->post->save();

        $this->post->tags()->sync($this->tags);

        if ($this->cover) {
            $this->post->getMedia('cover')->each->delete();
            $this->post
                ->addMedia($this->cover->getRealPath())
                ->usingName($this->cover->getClientOriginalName())
                ->toMediaCollection('cover');
        }

        return redirect()->route('admin.posts');
    }

    public function getListTagsProperty ()
    {
        return Tag::all(['id', 'name'])->toArray();
    }
}
