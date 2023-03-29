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

        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
    
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/holidays");
        $response->assertStatus(200)
                ->assertJsonCount(4);

        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'user1@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/holidays");
        $response->assertStatus(200)
                ->assertJsonCount(1);

        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'user2@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user2Token,
            'Accept' => '*/*'
        ])->postJson("api/auth/holidays");
        $response->assertStatus(200)
                ->assertJsonCount(2);
        
    }
}
