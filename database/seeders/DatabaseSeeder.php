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

        User::factory()->create(['name' => 'María José', 'surname' => 'Santos', 'email' => 'mjsantos@gmail.com', 'isAdmin' => true]);
        User::factory()->create(['name' => 'Lola', 'surname' => 'Navarro', 'email' => 'lnavarro@gmail.com', 'isAdmin' => false]);
        User::factory()->create(['name' => 'Ana', 'surname' => 'Rueda', 'email' => 'arueda@gmail.com', 'isAdmin' => false]);
        User::factory()->create(['name' => 'Sierri', 'surname' => 'Pérez', 'email' => 'sperez@gmail.com', 'isAdmin' => false]);
        User::factory()->create(['name' => 'Veronika', 'surname' => 'Komarova', 'email' => 'vkomarova@gmail.com', 'isAdmin' => false]);
        User::factory()->create(['name' => 'Camila', 'surname' => 'Ruiz', 'email' => 'cruiz@gmail.com', 'isAdmin' => false]);
        User::factory()->create(['name' => 'Paloma', 'surname' => 'Ruiz', 'email' => 'pruiz@gmail.com', 'isAdmin' => false]);
    }
}

