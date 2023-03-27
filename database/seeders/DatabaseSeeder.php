<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Absence;
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

        User::factory()->create(['name' => 'María José', 'surname' => 'Santos', 'email' => 'mjsantos@gmail.com', 'isAdmin' => true, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679923946/mj.png']);
        User::factory()->create(['name' => 'Lola', 'surname' => 'Navarro', 'email' => 'lnavarro@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/lola.jpg']);
        User::factory()->create(['name' => 'Ana', 'surname' => 'Rueda', 'email' => 'arueda@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/ana.jpg']);
        User::factory()->create(['name' => 'Sierri', 'surname' => 'Pérez', 'email' => 'sperez@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/sierri.jpg']);
        User::factory()->create(['name' => 'Veronika', 'surname' => 'Komarova', 'email' => 'vkomarova@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689849/veronika.jpg']);
        User::factory()->create(['name' => 'Camila', 'surname' => 'Ruiz', 'email' => 'cruiz@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689851/camila.jpg']);
        User::factory()->create(['name' => 'Paloma', 'surname' => 'Ruiz', 'email' => 'pruiz@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/paloma.jpg']);
        /* User::factory(50)->create(); */
        
        Absence::factory()->create([
            'user_id' => 2,
            'startingDate' => '2023/03/21',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/27',
            'endingTime' => '18:00:00',
            'description' => 'Permiso de lactancia',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        Absence::factory()->create([
            'user_id' => 2,
            'startingDate' => '2023/04/04',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/08',
            'endingTime' => '20:00:00',
            'description' => 'Cuatro días para llevar al conejo al vet',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        Absence::factory()->create([
            'user_id' => 3,
            'startingDate' => '2023/03/21',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/27',
            'endingTime' => '18:00:00',
            'description' => 'Paloma precipitada',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        /* Absence::factory()->create(); */

        $users = User::all();

        foreach ($users as $user) {
            Absence::factory()
            ->count(1)
            ->create([
                'user_id' => $user->id
            ]);
        }
    }
}



