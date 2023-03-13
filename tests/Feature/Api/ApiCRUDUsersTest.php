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
}
