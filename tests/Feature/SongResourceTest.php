<?php

use App\Filament\Resources\Songs\Pages\CreateSong;
use App\Models\Song;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $user = User::factory()->create();
    config(['app.admin_emails' => [strtolower($user->email)]]);

    $this->actingAs($user);
    Filament::setCurrentPanel('admin');
});

test('admin can add a song to the playlist', function () {
    Livewire::test(CreateSong::class)
        ->fillForm([
            'title' => 'Nueva canción',
            'artist' => 'Nuevo artista',
            'spotify_url' => 'https://open.spotify.com/track/6MjfEIHOMW6MaDO3LpFcmW',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Song::query()->where('title', 'Nueva canción')->where('artist', 'Nuevo artista')->exists())->toBeTrue();
});

test('song spotify link must point to spotify', function () {
    Livewire::test(CreateSong::class)
        ->fillForm([
            'title' => 'Nueva canción',
            'artist' => 'Nuevo artista',
            'spotify_url' => 'https://example.com/track/abc',
        ])
        ->call('create')
        ->assertHasFormErrors(['spotify_url']);
});
