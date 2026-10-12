<?php

use App\Models\HomeSnapshot;
use App\Models\Song;
use App\Support\SpotifyEmbed;
use Database\Seeders\SongSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('playlist page lists published songs in order', function () {
    Song::create([
        'title' => 'Segunda canción',
        'artist' => 'Artista B',
        'sort_order' => 2,
        'is_published' => true,
    ]);

    Song::create([
        'title' => 'Primera canción',
        'artist' => 'Artista A',
        'spotify_url' => 'https://open.spotify.com/track/6MjfEIHOMW6MaDO3LpFcmW?si=abc',
        'sort_order' => 1,
        'is_published' => true,
    ]);

    Song::create([
        'title' => 'Canción oculta',
        'artist' => 'Artista C',
        'is_published' => false,
    ]);

    $this->get(route('playlist'))
        ->assertOk()
        ->assertSeeInOrder(['Primera canción', 'Segunda canción'])
        ->assertSee('https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW', false)
        ->assertDontSee('Canción oculta');
});

test('playlist page embeds the full spotify playlist', function () {
    HomeSnapshot::current()->update([
        'playlist_url' => 'https://open.spotify.com/playlist/6zZEmocQXHdXIFunj8UJUS?si=abc&utm_source=copy-link',
    ]);

    $this->get(route('playlist'))
        ->assertOk()
        ->assertSee('https://open.spotify.com/embed/playlist/6zZEmocQXHdXIFunj8UJUS', false)
        ->assertDontSee('Todavía no hay canciones en la playlist.');
});

test('home listening block links to the playlist when a spotify playlist is set', function () {
    HomeSnapshot::current()->update(['playlist_url' => 'https://open.spotify.com/playlist/6zZEmocQXHdXIFunj8UJUS']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('playlist'), false);
});

test('playlist page renders when there are no songs', function () {
    HomeSnapshot::current()->update(['playlist_url' => null]);

    $this->get(route('playlist'))
        ->assertOk()
        ->assertSee('Todavía no hay canciones en la playlist.');
});

test('home listening block links to the playlist when songs are published', function () {
    HomeSnapshot::current()->update(['playlist_url' => null]);

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSee(route('playlist'), false);

    Song::create([
        'title' => 'Una canción',
        'artist' => 'Un artista',
        'is_published' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee(route('playlist'), false)
        ->assertSee('Ver más');
});

test('spotify links are converted to embed urls', function (?string $url, ?string $expected) {
    expect(SpotifyEmbed::toEmbedUrl($url))->toBe($expected);
})->with([
    'track' => ['https://open.spotify.com/track/6MjfEIHOMW6MaDO3LpFcmW?si=1', 'https://open.spotify.com/embed/track/6MjfEIHOMW6MaDO3LpFcmW'],
    'intl playlist' => ['https://open.spotify.com/intl-es/playlist/37i9dQZF1DXcBWIGoYBM5M', 'https://open.spotify.com/embed/playlist/37i9dQZF1DXcBWIGoYBM5M'],
    'already embed' => ['https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3', 'https://open.spotify.com/embed/album/1DFixLWuPkv3KT3TnV35m3'],
    'other host' => ['https://evil.example.com/track/abc', null],
    'empty' => [null, null],
]);

test('song seeder loads the playlist once without duplicating songs', function () {
    $this->seed(SongSeeder::class);
    $this->seed(SongSeeder::class);

    expect(Song::query()->count())->toBe(11);

    $this->get(route('playlist'))
        ->assertOk()
        ->assertSeeInOrder(['Need You Tonight', 'Losing My Religion', 'Hotel California - 2013 Remaster']);
});
