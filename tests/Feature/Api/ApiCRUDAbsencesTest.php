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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(4);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(1);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user2@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user2Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(2);
    }

    public function test_anEmployeeCanDeleteOwnAbsences()
    {
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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(1);

        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/1");
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ausencia borrada correctamente']);

        $this->assertDatabaseMissing('absences', [
            'id' => $absence1->id,
        ]);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(0);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/2");
        $response->assertStatus(403)
                ->assertJson([
                    'message' => 'No tienes permiso para borrar esta ausencia']);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user2@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user2Token,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(1);
    }

    public function test_adminCanDeleteAllAbsences()
    {
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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(2);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/1");
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ausencia borrada correctamente']);

        $this->assertDatabaseMissing('absences', [
            'id' => $absence1->id,
        ]);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(1);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->delete("api/auth/deleteAbsence/2");
        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Ausencia borrada correctamente']);

        $this->assertDatabaseMissing('absences', [
            'id' => $absence2->id,
        ]);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
            ])->postJson("api/auth/absences");
        $response->assertStatus(200)
            ->assertJsonCount(0);
    }

    public function test_employeeCanCreateOwnAbsences()
    {
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

        $userToken = $user->createToken('user-token')->plainTextToken;

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

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
        $admin = User::create([
            'isAdmin' => true,
            'name' => 'adminName',
            'surname' => 'adminSurname',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        $user = User::create([
            'isAdmin' => false,
            'name' => 'userName',
            'surname' => 'userSurname',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $adminToken = $admin->createToken('admin-token')->plainTextToken;

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(4);

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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/absences");
        $response->assertStatus(200)
                ->assertJsonCount(2);
        
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/showAbsence/1");

        $response->assertStatus(200);

        $this->assertNotEmpty($response);

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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

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
