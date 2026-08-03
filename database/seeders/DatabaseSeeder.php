<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! User::query()->where('email', 'admin@anselmi.dev')->exists()) {
            User::factory()->create([
                'name' => 'Admin',
                'email' => 'admin@anselmi.dev',
                'password' => 'password',
            ]);
        }

        $this->call(ContentSeeder::class);
    }
}
