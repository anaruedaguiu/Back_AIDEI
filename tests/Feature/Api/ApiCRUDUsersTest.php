<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ApiCRUDUsersTest extends TestCase
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

    public function test_onlyAdminsCanAccessEmployeesFullList()
    {
        $user1 = User::factory()->create(['isAdmin' => false]);
        $user2 = User::factory()->create(['isAdmin' => false]);
        $admin1 = User::factory()->create(['isAdmin' => true]);
        $admin2 = User::factory()->create(['isAdmin' => true]);
    
        $response = $this->actingAs($admin1)->post(route('dashboard'));
        $response->assertStatus(200)
                ->assertJsonCount(4);
    
        $response = $this->actingAs($user1)->post(route('dashboard'));
        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user1->id,
                    'name' => $user1->name,
                    'email' => $user1->email,
                ]);
    }

    public function test_onlyAdminsCanRegisterEmployees() 
    {
        $admin = User::factory()->create([
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $user1 = User::factory()->create(['isAdmin' => false]);
        $admin = User::factory()->create(['isAdmin' => true]);
        $adminToken = $admin->createToken('admin-token')->plainTextToken;
        $user1Token = $user1->createToken('user1-token')->plainTextToken;

        $response = $this->withHeaders([
                        'Authorization' => $adminToken,
                        'Accept' => '*/*' 
                    ])
                    ->postJson('api/registerEmployee', [
                        'name' => 'name',
                        'surname' => 'surname',
                        'email' => 'admin@email.com',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                        'phone' => 'required',
                        'idNumber' => 'required',
                        'sector' => 'required',
                        'startingDate' => 'required',
                        'status' => 'required',
                        'contractType' => 'required',
                        'isAdmin' => 'required'
                    ]);
        
        $response->assertStatus(201); 

        $response = $this->withHeaders([
                        'Authorization' => $user1Token,
                        'Accept' => '*/*' 
                    ])
                    ->postJson('api/registerEmployee', [
                        'name' => 'name',
                        'surname' => 'surname',
                        'email' => 'user1@email.com',
                        'password' => 'password',
                        'password_confirmation' => 'password',
                        'phone' => 'required',
                        'idNumber' => 'required',
                        'sector' => 'required',
                        'startingDate' => 'required',
                        'status' => 'required',
                        'contractType' => 'required',
                        'isAdmin' => 'required'
                    ]);

        $response->assertRedirect();
    }

    public function test_onlyAdminsCanDeleteEmployees()
    {
        $admin = User::factory()->create(['isAdmin' => true]);

        $user = User::factory()->create(['isAdmin' => false]);

        $response = $this->actingAs($admin, 'api')->delete(route('deleteEmployee', $user->id));

        $response = $this->actingAs($user, 'api')->delete(route('deleteEmployee', $admin->id));

        $response->assertForbidden();

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_onlyAdminsCanUpdateEmployees() 
    {
        $admin = User::factory()->create([
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);
        
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => true,
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);
        
        $user1 = User::factory()->create(['isAdmin' => false]);
        $admin1 = User::factory()->create(['isAdmin' => true]);
        $adminToken = $admin1->createToken('admin-token')->plainTextToken;
        $user1Token = $user1->createToken('user1-token')->plainTextToken;
    
        $response = $this->withHeaders([
            'Authorization' => $adminToken,
            'Accept' => '*/*'
        ])->putJson("api/updateEmployee/{$user1->id}", [
            'name' => 'Test User',
            'surname' => 'surname',
            'email' => 'admin@email.com',
            'password' => 'password',
        ]);
        $response->assertStatus(200);
        $this->assertEquals('Test User', $user1->fresh()->name);

        $user = User::factory()->create([
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ]);
        $response = $this->json('POST', 'api/auth/login', [
            'isAdmin' => false,
            'email' => 'user@example.com',
            'password' => 'password',
        ]);

        $response = $this->withHeaders([
            'Authorization' => $user1Token,
            'Accept' => '*/*'
        ])->putJson("api/updateEmployee/{$user1->id}", [
            'name' => 'Test User',
            'surname' => 'surname',
            'email' => 'admin@email.com',
            'password' => 'password',
        ]);
        $response->assertStatus(403);
    }
}

