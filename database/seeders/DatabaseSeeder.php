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

        User::factory()->create(['name' => 'María José', 'surname' => 'Santos', 'email' => 'mjsantos@gmail.com', 'isAdmin' => true, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679923946/mj.png', 'phone'=> '111111111', 'idNumber' => '11111111A', 'sector' => 'Hilo Doble', 'startingDate' => '2020-07-01']);
        User::factory()->create(['name' => 'Lola', 'surname' => 'Navarro', 'email' => 'lnavarro@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/lola.jpg', 'phone'=> '222222222', 'idNumber' => '22222222B', 'sector' => 'Jardinería', 'startingDate' => '2023-02-27', 'endingDate' => '2023-03-30', 'contractType' => false]);
        User::factory()->create(['name' => 'Ana', 'surname' => 'Rueda', 'email' => 'arueda@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/ana.jpg', 'phone'=> '333333333', 'idNumber' => '33333333C', 'sector' => 'Limpieza Málaga', 'startingDate' => '2023-02-27', 'endingDate' => '2023-03-30', 'contractType' => false]);
        User::factory()->create(['name' => 'Sierri', 'surname' => 'Pérez', 'email' => 'sperez@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/sierri.jpg', 'phone'=> '444444444', 'idNumber' => '44444444D', 'sector' => 'Limpieza Cádiz', 'startingDate' => '2023-02-27', 'endingDate' => '2023-03-30', 'contractType' => false]);
        User::factory()->create(['name' => 'Veronika', 'surname' => 'Komarova', 'email' => 'vkomarova@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689849/veronika.jpg', 'phone'=> '555555555', 'idNumber' => '55555555E', 'sector' => 'Hilo Doble', 'startingDate' => '2023-02-27', 'endingDate' => '2023-03-30', 'contractType' => false]);
        User::factory()->create(['name' => 'Camila', 'surname' => 'Ruiz', 'email' => 'cruiz@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689851/camila.jpg', 'phone'=> '666666666', 'idNumber' => '66666666F', 'sector' => 'Jardinería', 'startingDate' => '2023-02-27', 'endingDate' => '2023-03-30', 'contractType' => false]);
        User::factory()->create(['name' => 'Paloma', 'surname' => 'Ruiz', 'email' => 'pruiz@gmail.com', 'isAdmin' => false, 'image' => 'https://res.cloudinary.com/dkbwmuo7n/image/upload/v1679689850/paloma.jpg', 'phone'=> '777777777', 'idNumber' => '77777777G', 'sector' => 'Limpieza Málaga', 'startingDate' => '2023-02-27', 'endingDate' => '2023-03-30', 'contractType' => false]);
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



