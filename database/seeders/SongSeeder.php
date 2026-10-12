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
     * completa la portada cuando la canción todavía no tiene una.
     */
    public function run(): void
    {
        $songs = [
            ['title' => 'Need You Tonight', 'artist' => 'INXS', 'cover' => 'need-you-tonight.jpg'],
            ['title' => 'Easy Lover', 'artist' => 'Philip Bailey, Phil Collins', 'cover' => 'easy-lover.jpg'],
            ['title' => "Don't Stop Believin' (2022 Remaster)", 'artist' => 'Journey', 'cover' => 'dont-stop-believin.jpg'],
            ['title' => 'The Power Of Love', 'artist' => 'Huey Lewis & The News', 'cover' => 'the-power-of-love.jpg'],
            ['title' => "Can't Stand Losing You", 'artist' => 'The Police', 'cover' => 'cant-stand-losing-you.jpg'],
            ['title' => "Don't Stand So Close To Me", 'artist' => 'The Police', 'cover' => 'dont-stand-so-close-to-me.jpg'],
            ['title' => 'Losing My Religion', 'artist' => 'R.E.M.', 'cover' => 'losing-my-religion.jpg'],
            ['title' => 'Dream On', 'artist' => 'Aerosmith', 'cover' => 'dream-on.jpg'],
            ['title' => 'Another Brick in the Wall, Pt. 2', 'artist' => 'Pink Floyd', 'cover' => 'another-brick-in-the-wall.jpg'],
            ['title' => 'Stairway to Heaven - Remaster', 'artist' => 'Led Zeppelin', 'cover' => 'stairway-to-heaven.jpg'],
            ['title' => 'Hotel California - 2013 Remaster', 'artist' => 'Eagles', 'cover' => 'hotel-california.jpg'],
        ];

        foreach ($songs as $index => $song) {
            $record = Song::query()->firstOrCreate(
                ['title' => $song['title'], 'artist' => $song['artist']],
                ['sort_order' => $index, 'is_published' => true],
            );

            if (blank($record->image_path)) {
                $record->update(['image_path' => $this->storeCover($song['cover'])]);
            }
        }
    }

    private function storeCover(string $filename): string
    {
        $path = 'songs/'.$filename;

        Storage::disk('public')->put($path, file_get_contents(__DIR__.'/covers/'.$filename));

        return $path;
    }
}
