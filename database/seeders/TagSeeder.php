<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tag::factory()->count(10)->create();
        Tag::firstOrCreate([
            'name' => 'Laravel'
        ]);

        Tag::firstOrCreate([
            'name' => 'PHP'
        ]);

        Tag::firstOrCreate([
            'name' => 'Tailwind'
        ]);

        Tag::firstOrCreate([
            'name' => 'HTML'
        ]);

        Tag::firstOrCreate([
            'name' => 'VUE'
        ]);

        Tag::firstOrCreate([
            'name' => 'Javascript'
        ]);
    }
}
