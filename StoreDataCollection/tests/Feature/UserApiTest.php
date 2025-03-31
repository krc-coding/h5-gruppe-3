<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    const base = '/api/user';

    public function test_create_user()
    {
        $user = User::factory()->make();

        $response = $this->postJson($this::base, [
            'username' => $user->username,
            'password' => $user->password,
            'password_confirmation' => $user->password,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'username',
                    'created_at',
                    'updated_at'
                ],
                'token'
            ]);
    }

    public function test_get_all_users()
    {
        User::factory()->count(5)->create();

        $this->get($this::base)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'username',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_single_user()
    {
        $user = User::factory()->create();

        $this->get($this::base . "/{$user->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_update_user()
    {
        $user = User::factory()->create();
        $response = $this->putJson($this::base . "/{$user->id}", [
            'username' => 'this is an updated username, :: test',
            'password' => 'Password1!',
            'password_confirmation' => 'Password1!',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'username',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_dalete_user()
    {
        $user = User::factory()->create();

        $this->delete($this::base . "/{$user->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
