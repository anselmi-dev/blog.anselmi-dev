<?php

namespace App\Livewire\Pages;

use App\Models\Song;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.site')]
#[Title('Mi playlist')]
class Playlist extends Component
{
    public function render()
    {
        $songs = Song::query()
            ->published()
            ->get()
            ->map(fn (Song $song): array => $song->toViewArray())
            ->all();

        return view('livewire.pages.playlist', [
            'songs' => $songs,
        ]);
    }
}
