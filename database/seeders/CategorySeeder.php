<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Category::factory()->count(3)->create();
        Category::firstOrCreate([
            'name' => 'Laravel',
        ]);

        Category::firstOrCreate([
            'name' => 'PHP',
        ]);

        Category::firstOrCreate([
            'name' => 'Tailwind',
        ]);

        Category::firstOrCreate([
            'name' => 'HTML',
        ]);

        Category::firstOrCreate([
            'name' => 'VUE',
        ]);

        Category::firstOrCreate([
            'name' => 'Javascript',
        ]);
    }
}
