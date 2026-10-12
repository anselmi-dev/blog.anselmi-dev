<?php

namespace App\Livewire\Pages;

use App\Models\HomeSnapshot;
use App\Models\Song;
use App\Support\SpotifyEmbed;
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

        $snapshot = HomeSnapshot::current();

        return view('livewire.pages.playlist', [
            'songs' => $songs,
            'playlistUrl' => SpotifyEmbed::sanitize($snapshot->playlist_url),
            'playlistEmbedUrl' => SpotifyEmbed::toEmbedUrl($snapshot->playlist_url),
        ]);
    }
}
