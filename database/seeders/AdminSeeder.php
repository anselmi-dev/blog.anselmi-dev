<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate([
            'email' => 'anselmi.dev@gmail.com',
        ], [
            'name' => 'Anselmi',
            'email_verified_at' => now(),
            'remember_token' => \Str::random(10),
            'password' => Hash::make('1990102055a+'),
        ]);
    }
}
