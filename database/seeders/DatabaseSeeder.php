<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Direktur',
            'email' => 'direktur@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'direktur',
        ]);
        
        User::factory()->create([
            'name' => 'Keuangan',
            'email' => 'keuangan@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'keuangan',
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
        ]);
    }
}
