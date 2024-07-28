<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\Photo;

class PhotosAdmin extends Component
{
    use WithPagination;

    public $filters = [
        'paginate' => 10
    ];

    public function render()
    {
        return view('livewire.admin.photos-admin', [
            'photos' => Photo::paginate($this->filters['paginate'])
        ])->layout('layouts.app');
    }
}
