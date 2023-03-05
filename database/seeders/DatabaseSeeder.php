<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        User::factory()->create(['userName' => 'admin', 'email' => 'admin@admin.com', 'isAdmin' => true]);

        User::factory()->create(['userName' => 'user1', 'email' => 'user1@user1.com', 'isAdmin' => false]);
    }
}
