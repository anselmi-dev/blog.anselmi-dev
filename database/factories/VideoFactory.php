<?php

namespace Database\Factories;

use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Video>
 */
class VideoFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'video_path' => 'videos/'.fake()->uuid().'.mp4',
            'title' => fake()->sentence(3),
            'category' => fake()->word(),
            'location' => fake()->city(),
            'released_at' => fake()->date('d M Y'),
            'description' => fake()->sentence(),
            'width' => 1920,
            'height' => 1080,
        ];
    }

    public function withPoster(): static
    {
        return $this->state(fn (): array => ['poster_path' => 'videos/posters/'.fake()->uuid().'.jpg']);
    }
}
