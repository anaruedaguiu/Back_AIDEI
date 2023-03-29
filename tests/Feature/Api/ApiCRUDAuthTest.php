<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiCRUDAuthTest extends TestCase
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

    public function test_adminAndEmployeesCanLogin() 
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $response = $this->json('POST', 'api/auth/login', [
            'email' => 'user@example.com',
            'password' => 'password',
        ]);
        
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'access_token',
            'token_type',
            'expires_in',
        ]);
    }

    public function test_adminAndEmployeesCanLogout()
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = auth()->login($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer' . $token,
        ])->json('POST', 'api/auth/logout');

        $response->assertStatus(200)
                ->assertJson([
                    'message' => 'Sesión cerrada exitosamente',
                ]);

        $this->assertGuest();
    }

    public function test_adminAndEmployeesProfileCanBeShowed()
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

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/dashboard");
        $response->assertStatus(200)
                ->assertJsonCount(2);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/profile/1");
        $response->assertStatus(200);
        
        $this->assertNotEmpty($response);

        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/profile/2");
        $response->assertStatus(200);
        
        $this->assertNotEmpty($response);

        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/profile/2");
        $response->assertStatus(200);
        
        $this->assertNotEmpty($response);

        $response = $this->withHeaders([
            'Authorization' => $userToken,
            'Accept' => '*/*'
        ])->postJson("api/auth/profile/1");
        $response->assertStatus(403);
    }
}