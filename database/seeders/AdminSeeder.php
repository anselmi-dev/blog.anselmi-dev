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
        User::create([
            'name' => 'Anselmi',
            'email' => 'anselmi.dev@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('1990102055a+'),
            'remember_token' => \Str::random(10),
        ]);
    }
}
