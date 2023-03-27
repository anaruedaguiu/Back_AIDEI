<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Absence;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCRUDAbsencesTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    /* public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    } */

    use RefreshDatabase;

    public function test_onlyAdminsCanAccessAbsencesFullList()
    {
        // Create an admin and regular users
        $admin = User::factory()->create([
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminToken = $admin->createToken('admin-token')->plainTextToken;
        
        $user = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $userToken = $user->createToken('user-token')->plainTextToken;

        $user1 = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);
        $user1Token = $user1->createToken('user1-token')->plainTextToken;

        $user2 = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);
        $user2Token = $user2->createToken('user2-token')->plainTextToken;

        // Create absences
        $absence1 = Absence::factory()->create([
            'user_id' => $user->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test1',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $absence2 = Absence::factory()->create([
            'user_id' => $user1->id,
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $absence3 = Absence::factory()->create([
            'user_id' => $user2->id,
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $absence4 = Absence::factory()->create([
            'user_id' => $user2->id,
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]); 

        // Login as an admin
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    
        // Admin can access absences full list of all users
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(4);

        // Login as a regular user
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // Regular user can access only regular user's absences
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(1);

        // Login as a regular user
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user2@example.com',
            'password' => 'password',
        ]);

        // Regular user2 can access only regular user2's absences
        $response = $this->withHeaders([
            'Authorization' => $user2Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(2);
    }

    public function test_anUserCanDeleteOwnAbsences()
    {
        // Create a regular user called user1
        $user = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $userToken = $user->createToken('user-token')->plainTextToken;

        //Create an user's absence
        $absence = Absence::factory()->create([
            'id' => 1,
            'user_id' => $user->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test1',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        // Login as a regular user
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // List of user's absences
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(1);

        // Regular user can delete only regular user1's absences
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/{$absence->id}");
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ausencia borrada correctamente']);

        // List of user's absences after delete
        /* $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => ''
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(0); */

        // Verify absence has been deleted in database
        $this->assertDatabaseMissing('absences', [
            'id' => $absence->id,
        ]);
    }
    
    public function test_allAbsencesCanBeShowedForAdminAndAnUserOnlyCanShowOwnAbsences()
    {
        // Create an admin and regular users
        $admin = User::factory()->create([
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $adminToken = $admin->createToken('admin-token')->plainTextToken;

        $user1 = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => bcrypt('password'),
        ]);
        $user1Token = $user1->createToken('user1-token')->plainTextToken;

        $user2 = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user2@example.com',
            'password' => bcrypt('password'),
        ]);
        $user2Token = $user2->createToken('user2-token')->plainTextToken;

        // Create absences
        $absence1 = Absence::factory()->create([
            'id' => 1,
            'user_id' => $user1->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test1',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $absence2 = Absence::factory()->create([
            'id' => 2,
            'user_id' => $user1->id,
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $absence3 = Absence::factory()->create([
            'id' => 3,
            'user_id' => $user2->id,
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $absence4 = Absence::factory()->create([
            'id' => 4,
            'user_id' => $admin->id,
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]); 

        // Login as an admin
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    
        // Admin can access absences full list of all users
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(4);

        // Admin can access all absences' show
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/showAbsence/1");

        $response->assertStatus(200);

        $this->assertNotEmpty($response);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/showAbsence/4");

        $response->assertStatus(200);

        $this->assertNotEmpty($response);

        // Login as an user1
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        // User1 only can access user1's absences 
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(2);
        
        // User1 only can access user1's absences show
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/showAbsence/1");

        $response->assertStatus(200);

        $this->assertNotEmpty($response);

        // User1 can't access another user's absence show
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/showAbsence/3");

        $response->assertStatus(403)
        ->assertJson([
            'message' => 'No tienes permiso para ver esta ausencia']);
    }
}

