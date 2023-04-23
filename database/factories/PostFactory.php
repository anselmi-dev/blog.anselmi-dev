<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Tag;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
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
            "content" => $this->html(),
            "published_at" => fake()->dateTimeThisMonth(),
            "status" => "published"
        ];
    }

    protected function html () : string
    {
        return '<p>'.fake()->text(500).'</p> <br>' . '<p>'.fake()->text(200).'</p> <br>' . '<p>'.fake()->text(100).'</p> <br>' . '<p>'.fake()->text(3000).'</p>';
    }
}
