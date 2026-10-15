<?php

namespace Database\Factories;

use App\Models\PageView;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PageView>
 */
class PageViewFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'path' => fake()->randomElement(['/', '/blog', '/galeria', '/sobre-mi', '/servicios/herramientas']),
            'route_name' => 'home',
            'locale' => 'es',
            'visitor_hash' => substr(hash('sha256', fake()->uuid()), 0, 16),
            'country' => fake()->randomElement(['UY', 'AR', 'ES', 'US']),
            'city' => fake()->city(),
            'device' => fake()->randomElement(['desktop', 'mobile', 'tablet']),
            'browser' => fake()->randomElement(['Chrome', 'Safari', 'Firefox']),
            'os' => fake()->randomElement(['Windows', 'macOS', 'iOS', 'Android']),
            'referrer_host' => fake()->randomElement([null, 'google.com', 'instagram.com']),
            'created_at' => now(),
        ];
    }
}
