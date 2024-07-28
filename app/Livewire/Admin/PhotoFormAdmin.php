<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Photo;

use Livewire\WithFileUploads;
use App\Models\Tag;

class PhotoFormAdmin extends Component
{
    use WithFileUploads;

    /**
     * Current photo
     *
     * @var Photo
     */
    public $photo;

    public $cover;

    public $tags;

    protected $rules = [
        'photo.title' => 'required|min:6|max:200',
        "photo.description" => '',
        "photo.text" => '',
        'cover' => 'max:1024',
        'tags' => '',
    ];

    public function mount(Photo $photo)
    {
        $this->photo = $photo;

        $this->tags = $this->photo->tags->pluck('id')->toArray();
    }

    public function render()
    {
        return view('livewire.admin.photo-form-admin')
                ->layout('layouts.app');
    }

    public function submit ()
    {
        $this->validate();

        $this->photo->save();

        $this->photo->tags()->sync($this->tags);

        if ($this->cover) {
            $this->photo->getMedia('photos')->each->delete();
            $this->photo
                ->addMedia($this->cover->getRealPath())
                ->usingName($this->cover->getClientOriginalName())
                ->toMediaCollection('photos');
        }

        return redirect()->route('admin.photos');
    }

    public function getListTagsProperty ()
    {
        return Tag::all(['id', 'name'])->toArray();
    }
}
