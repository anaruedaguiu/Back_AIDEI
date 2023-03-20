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

    public function test_onlyAdminsCanAccessUsersList()
    {
        // Crea dos usuarios no administradores y dos administradores
        $user1 = User::factory()->create(['isAdmin' => false]);
        $user2 = User::factory()->create(['isAdmin' => false]);
        $admin1 = User::factory()->create(['isAdmin' => true]);
        $admin2 = User::factory()->create(['isAdmin' => true]);
    
        // Caso en que el usuario es administrador
        $response = $this->actingAs($admin1)->post(route('index'));
        $response->assertStatus(200)
                ->assertJsonCount(4);
    
        // Caso en que el usuario no es administrador
        $response = $this->actingAs($user1)->post(route('index'));
        $response->assertStatus(200)
                ->assertJsonFragment([
                    'id' => $user1->id,
                    'name' => $user1->name,
                    'email' => $user1->email,
                ]);
    }

    public function test_onlyAdminsCanRegisterUsers() 
    {
        $user1 = User::factory()->create(['isAdmin' => false]);
        $admin1 = User::factory()->create(['isAdmin' => true]);
        $adminToken = $admin1->createToken('admin-token')->plainTextToken;
        $user1Token = $user1->createToken('user1-token')->plainTextToken;

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $adminToken,
                        'Accept' => '*/*' 
                    ])
                    ->postJson(route('register'), [
                        'name' => 'name',
                        'surname' => 'surname',
                        'email' => 'admin@email.com',
                        'password' => 'password',
                    ]);
        
        $response->assertStatus(201); 

        $response = $this->withHeaders([
                        'Authorization' => 'Bearer ' . $user1Token,
                        'Accept' => '*/*' 
                    ])
                    ->postJson(route('register'), [
                        'name' => 'name',
                        'surname' => 'surname',
                        'email' => 'user@email.com',
                        'password' => 'password',
                    ]);

        $response->assertRedirect();
    }

    public function test_onlyAdminsCanUpdateUsers() 
    {
        /* $user1 = User::factory()->create(['isAdmin' => false]); */
        $admin1 = User::factory()->create(['isAdmin' => true]);
        $adminToken = $admin1->createToken('admin-token')->plainTextToken;
        /* $user1Token = $user1->createToken('user1-token')->plainTextToken; */

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $adminToken,
            'Accept' => '*/*' 
        ])
        ->postJson(route('register'), [
            'name' => 'name',
            'surname' => 'surname',
            'email' => 'admin@email.com',
            'password' => 'password',
        ]);

        $data = ['name' => 'name'];

        $response = $this->postJson(route('index'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
        
        $response = $this->putJson('api/auth/update/1', [
            'name' => 'nameTest',
            'surname' => 'surname',
            'email' => 'admin@email.com',
            'password' => 'password',
        ]);

        $data = ['name' => 'nameTest'];

        $response = $this->postJson(route('index'));
        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment($data);
    }
}
