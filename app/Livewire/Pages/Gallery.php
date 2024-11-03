<?php

namespace App\Livewire\Pages;

use Livewire\Component;
use App\Models\Photo;
use Livewire\WithPagination;

class Gallery extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.pages.gallery', [
            'photos' => Photo::paginate(10)
        ]);
    }
}
