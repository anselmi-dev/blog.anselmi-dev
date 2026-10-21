<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SongSeeder extends Seeder
{
    /**
     * Carga la playlist inicial. Es idempotente: no duplica canciones
     * existentes ni pisa lo que se haya editado desde Filament; solo
     * completa la portada y el enlace de Spotify cuando faltan, o
     * reemplaza un enlace que sembró una versión anterior del seeder.
     */
    public function run(): void
    {
        $songs = [
            ['title' => 'Need You Tonight', 'artist' => 'INXS', 'cover' => 'need-you-tonight.jpg', 'spotify' => '5eU8qMd0TpaLqTGDZJaLDs'],
            ['title' => 'Easy Lover', 'artist' => 'Philip Bailey, Phil Collins', 'cover' => 'easy-lover.jpg', 'spotify' => '5LYJ631w9ps5h9tdvac7yP'],
            ['title' => "Don't Stop Believin' (2022 Remaster)", 'artist' => 'Journey', 'cover' => 'dont-stop-believin.jpg', 'spotify' => '5RKQ5NdjSh2QzD4MaunT91'],
            ['title' => 'The Power Of Love', 'artist' => 'Huey Lewis & The News', 'cover' => 'the-power-of-love.jpg', 'spotify' => '2olVm1lHicpveMAo4AUDRB'],
            ['title' => "Can't Stand Losing You", 'artist' => 'The Police', 'cover' => 'cant-stand-losing-you.jpg', 'spotify' => '34C45Cc2tD38KUaFL2rqUM'],
            ['title' => "Don't Stand So Close To Me", 'artist' => 'The Police', 'cover' => 'dont-stand-so-close-to-me.jpg', 'spotify' => '5veJDT0MLsLbhYsx42GXUD'],
            ['title' => 'Losing My Religion', 'artist' => 'R.E.M.', 'cover' => 'losing-my-religion.jpg', 'spotify' => '6bCPryuQjsts5cEybA3Nb5', 'replaces' => '0TuMqSmrKZNH4CeRg6I9er'],
            ['title' => 'Dream On', 'artist' => 'Aerosmith', 'cover' => 'dream-on.jpg', 'spotify' => '5MxNLUsfh7uzROypsoO5qe'],
            ['title' => 'Another Brick in the Wall, Pt. 2', 'artist' => 'Pink Floyd', 'cover' => 'another-brick-in-the-wall.jpg', 'spotify' => '4gMgiXfqyzZLMhsksGmbQV'],
            ['title' => 'Stairway to Heaven - Remaster', 'artist' => 'Led Zeppelin', 'cover' => 'stairway-to-heaven.jpg', 'spotify' => '5CQ30WqJwcep0pYcV4AMNc'],
            ['title' => 'Hotel California - 2013 Remaster', 'artist' => 'Eagles', 'cover' => 'hotel-california.jpg', 'spotify' => '5BLOXCKSpriar8wPQe93Rk'],
        ];

        foreach ($songs as $index => $song) {
            $record = Song::query()->firstOrCreate(
                ['title' => $song['title'], 'artist' => $song['artist']],
                ['sort_order' => $index, 'is_published' => true],
            );

            if (blank($record->image_path)) {
                $record->image_path = $this->storeCover($song['cover']);
            }

            $previousSpotifyUrl = isset($song['replaces'])
                ? 'https://open.spotify.com/track/'.$song['replaces']
                : null;

            if (blank($record->spotify_url) || $record->spotify_url === $previousSpotifyUrl) {
                $record->spotify_url = 'https://open.spotify.com/track/'.$song['spotify'];
            }

            $record->save();
        }
    }

    private function storeCover(string $filename): string
    {
        $path = 'songs/'.$filename;

        Storage::disk('public')->put($path, file_get_contents(__DIR__.'/covers/'.$filename));

        return $path;
    }
}
