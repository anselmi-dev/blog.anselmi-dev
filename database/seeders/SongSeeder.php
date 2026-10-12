<?php

namespace Database\Seeders;

use App\Models\Song;
use Illuminate\Database\Seeder;

class SongSeeder extends Seeder
{
    /**
     * Carga la playlist inicial. Es idempotente: no duplica canciones
     * existentes ni pisa lo que se haya editado desde Filament.
     */
    public function run(): void
    {
        $songs = [
            ['title' => 'Need You Tonight', 'artist' => 'INXS'],
            ['title' => 'Easy Lover', 'artist' => 'Philip Bailey, Phil Collins'],
            ['title' => "Don't Stop Believin' (2022 Remaster)", 'artist' => 'Journey'],
            ['title' => 'The Power Of Love', 'artist' => 'Huey Lewis & The News'],
            ['title' => "Can't Stand Losing You", 'artist' => 'The Police'],
            ['title' => "Don't Stand So Close To Me", 'artist' => 'The Police'],
            ['title' => 'Losing My Religion', 'artist' => 'R.E.M.'],
            ['title' => 'Dream On', 'artist' => 'Aerosmith'],
            ['title' => 'Another Brick in the Wall, Pt. 2', 'artist' => 'Pink Floyd'],
            ['title' => 'Stairway to Heaven - Remaster', 'artist' => 'Led Zeppelin'],
            ['title' => 'Hotel California - 2013 Remaster', 'artist' => 'Eagles'],
        ];

        foreach ($songs as $index => $song) {
            Song::query()->firstOrCreate(
                ['title' => $song['title'], 'artist' => $song['artist']],
                ['sort_order' => $index, 'is_published' => true],
            );
        }
    }
}
