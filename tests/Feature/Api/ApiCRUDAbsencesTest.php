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

    public function test_anEmployeeCanDeleteOwnAbsences()
    {
        // Create regular users
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

        //Create absences for user1 and user2
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
            'user_id' => $user2->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        // Login as regular user1
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        // List of regular user1's absences
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(1);

        // Regular user1 can delete only regular user1's absences
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/1");
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ausencia borrada correctamente']);

        // Verify absence1 has been deleted in database
        $this->assertDatabaseMissing('absences', [
            'id' => $absence1->id,
        ]);

        // Login again as regular user1
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        // List of regular user1's absences after delete
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(0);

        // Login again as regular user1
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        // Regular user1 can't delete user2's absence
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/2");
        $response->assertStatus(403)
                ->assertJson([
                    'message' => 'No tienes permiso para borrar esta ausencia']);

        // Login as regular user2
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user2@example.com',
            'password' => 'password',
        ]);

        // User2 still have absence2
        $response = $this->withHeaders([
            'Authorization' => $user2Token,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_adminCanDeleteAllAbsences()
    {
        // Create an admin and a regular user
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

        //Create absences for admin and user
        $absence1 = Absence::factory()->create([
            'id' => 1,
            'user_id' => $admin->id,
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
            'user_id' => $user->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
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

        // List of admin's absences
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(2);

        // Admin can delete own absences
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/1");
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ausencia borrada correctamente']);

        // Verify absence1 has been deleted in database
        $this->assertDatabaseMissing('absences', [
            'id' => $absence1->id,
        ]);

        // Login again as an admin
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // List of admin's absences after delete
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(1);

        // Login again as an admin
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Admin can delete an user's absence
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/2");
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ausencia borrada correctamente']);

        // Verify absence1 has been deleted in database
        $this->assertDatabaseMissing('absences', [
            'id' => $absence2->id,
        ]);

        // Login as an user
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // List of user's absences after delete
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_employeeCanCreateOwnAbsences()
    {
        // Create two regulars user
        $user = User::create([
            'isAdmin' => false,
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $otherUser = User::create([
            'isAdmin' => false,
            'name' => 'otherUserName',
            'surname' => 'otherUserSurname',
            'email' => 'otheruser@example.com',
            'password' => bcrypt('password'),
        ]);

        // Authenticate the user and get the token
        $userToken = $user->createToken('user-token')->plainTextToken;

        // Login as an user
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // Create an absence as regular user
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createAbsence', [
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Ausencia solicitada exitosamente',
                    'absence' => [
                        'user_id' => $user->id,
                        'startingDate' => '2023/03/01',
                        'startingTime' => '18:00:00',
                        'endingDate' => '2023/03/02',
                        'endingTime' => '18:00:00',
                        'description' => 'description test',
                    ]
                ]);

        // Create an other user's abscence as regular user
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createAbsence', [
            'user_id' => $otherUser->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test',
        ]);

        //Even if you pass the id of another user it creates the absence of the logged in user.
        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Ausencia solicitada exitosamente',
                    'absence' => [
                        'user_id' => $user->id,
                        'startingDate' => '2023/03/01',
                        'startingTime' => '18:00:00',
                        'endingDate' => '2023/03/02',
                        'endingTime' => '18:00:00',
                        'description' => 'description test',
                    ]
                ]);
    }

    public function test_adminCanCreateAbsencesForAllEmployeesAndAdmin()
    {
        // Create an admin
        $admin = User::create([
            'isAdmin' => true,
            'name' => 'adminName',
            'surname' => 'adminSurname',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        // Create a regular user
        $user = User::create([
            'isAdmin' => false,
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        // Authenticate the admin and get the token
        $adminToken = $admin->createToken('admin-token')->plainTextToken;

        // Login as an admin
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Create an own absence as admin
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createAbsence', [
            'user_id' => $admin->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Ausencia solicitada exitosamente',
                    'absence' => [
                        'user_id' => $admin->id,
                        'startingDate' => '2023/03/01',
                        'startingTime' => '18:00:00',
                        'endingDate' => '2023/03/02',
                        'endingTime' => '18:00:00',
                        'description' => 'description test',
                    ]
                ]);

        // Create an user's absence as admin
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createAbsence', [
            'user_id' => $user->id,
            'startingDate' => '2023/03/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/03/02',
            'endingTime' => '18:00:00',
            'description' => 'description test',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Ausencia solicitada exitosamente',
                    'absence' => [
                        'user_id' => $user->id,
                        'startingDate' => '2023/03/01',
                        'startingTime' => '18:00:00',
                        'endingDate' => '2023/03/02',
                        'endingTime' => '18:00:00',
                        'description' => 'description test',
                    ]
                ]);
    }
    
    public function test_allAbsencesCanBeSeenByAdminAndAnUserOnlyCanShowOwnAbsences()
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
            'description' => 'description test3',
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
            'description' => 'description test4',
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

    public function test_adminCanUpdateAllAbsences() 
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

        // Create absences
        $absence1 = Absence::factory()->create([
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

        $absence2 = Absence::factory()->create([
            'id' => 2,
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

        // Admin can update an user1's absence
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->putJson("api/auth/updateAbsence/1", [
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'user description updated by admin',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('user description updated by admin', $absence1->fresh()->description);

        // And Admin can update own absences
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->putJson("api/auth/updateAbsence/2", [
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'admin description updated by admin',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('admin description updated by admin', $absence2->fresh()->description);
    }

    public function test_anEmployeeOnlyCanUpdateOwnAbsences() 
    {
        // Create an user and an admin
        $user = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $userToken = $user->createToken('user-token')->plainTextToken;

        $admin = User::factory()->create([
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // Create absences
        $absence1 = Absence::factory()->create([
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

        $absence2 = Absence::factory()->create([
            'id' => 2,
            'user_id' => $admin->id,
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'description test2',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        // Login as an user
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // User only can update user's absences
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
        ])->putJson("api/auth/updateAbsence/1", [
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'user description updated by user',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $response->assertStatus(200);
        $this->assertEquals('user description updated by user', $absence1->fresh()->description);

        // User can't update other absences
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
        ])->putJson("api/auth/updateAbsence/2", [
            'startingDate' => '2023/04/01',
            'startingTime' => '18:00:00',
            'endingDate' => '2023/04/02',
            'endingTime' => '18:00:00',
            'description' => 'admin description updated by user',
            'addDocument' => 'https://pbs.twimg.com/media/EfIXHskX0AAZsQd.jpg',
            'status' => 'Resuelta: aceptada',
        ]);

        $response->assertStatus(403)
                ->assertJson([
                    'message' => 'No tienes permiso para modificar esta ausencia']);
    }
}

