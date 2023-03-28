<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use App\Models\Holiday;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCRUDHolidaysTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_onlyAdminsCanAccessHolidaysFullList()
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

        // Create holidays
        $holiday1 = Holiday::factory()->create([
            'user_id' => $admin->id,
            'startingDate' => '2023/03/01',
            'endingDate' => '2023/03/15',
            'status' => 'Resuelta: aceptada',
        ]);

        $holiday2 = Holiday::factory()->create([
            'user_id' => $user1->id,
            'startingDate' => '2023/03/15',
            'endingDate' => '2023/03/30',
            'status' => 'Resuelta: aceptada',
        ]);

        $holiday3 = Holiday::factory()->create([
            'user_id' => $user2->id,
            'startingDate' => '2023/04/01',
            'endingDate' => '2023/04/15',
            'status' => 'Resuelta: aceptada',
        ]);

        $holiday4 = Holiday::factory()->create([
            'user_id' => $user2->id,
            'startingDate' => '2023/04/16',
            'endingDate' => '2023/04/30',
            'status' => 'Resuelta: aceptada',
        ]);

        // Login as an admin
        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    
        // Admin can access holidays full list of all users
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/holidays");
        $response->assertStatus(200)
                ->assertJsonCount(4);

        // Login as a regular user
        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        // Regular user can access only regular user1's holidays
        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/holidays");
        $response->assertStatus(200)
                ->assertJsonCount(1);

        // Login as a regular user
        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'user2@example.com',
            'password' => 'password',
        ]);

        // Regular user2 can access only regular user2's holidays
        $response = $this->withHeaders([
            'Authorization' => $user2Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/holidays");
        $response->assertStatus(200)
                ->assertJsonCount(2);
        
    }

    public function test_employeeCanCreateOwnHolidays()
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
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        // Create an holiday as regular user
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createHoliday', [
            'startingDate' => '2023/03/01',
            'endingDate' => '2023/03/15',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Vacaciones solicitadas exitosamente',
                    'holiday' => [
                        'user_id' => $user->id,
                        'startingDate' => '2023/03/01',
                        'endingDate' => '2023/03/15',
                    ]
                ]);

        // Create an other user's abscence as regular user
        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createHoliday', [
            'user_id' => $otherUser->id,
            'startingDate' => '2023/03/01',
            'endingDate' => '2023/03/15',
        ]);

        //Even if you pass the id of another user it creates the holiday of the logged in user.
        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Vacaciones solicitadas exitosamente',
                    'holiday' => [
                        'user_id' => $user->id,
                        'startingDate' => '2023/03/01',
                        'endingDate' => '2023/03/15',
                    ]
                ]);
    }

    public function test_adminCanCreateHolidaysForAllEmployeesAndAdmin()
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
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        // Create an own holiday as admin
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createHoliday', [
            'user_id' => $admin->id,
            'startingDate' => '2023/03/01',
            'endingDate' => '2023/03/15',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Vacaciones solicitadas exitosamente',
                    'holiday' => [
                        'user_id' => $admin->id,
                        'startingDate' => '2023/03/01',
                        'endingDate' => '2023/03/15',
                    ]
                ]);

        // Create an user's holiday as admin
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*' 
        ])->postJson('api/auth/createHoliday', [
            'user_id' => $user->id,
            'startingDate' => '2023/03/01',
            'endingDate' => '2023/03/15',
        ]);

        $response->assertStatus(201)
                ->assertJson([
                    'message' => 'Vacaciones solicitadas exitosamente',
                    'holiday' => [
                        'user_id' => $user->id,
                        'startingDate' => '2023/03/01',
                        'endingDate' => '2023/03/15',
                    ]
                ]);
    }
}
