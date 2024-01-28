<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Photo;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Photo>
 */
class PhotoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->sentence(),
            "description" => fake()->text(200),
            "text" => fake()->text(500),
            "size" => 1000,
            "height" => 10,
            "width" => 10,
            "name" => 'name',
            "type" => 'jpg',
            "path" => 'path',
            "localization" => [],
        ];
    }
}
