<?php

namespace Tests\Feature;

use App\Models\Devices;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class GroupApiTest extends TestCase
{
    const base = '/api/group';

    public function test_create_group()
    {
        $user = User::factory()->create();
        $response = $this->postJson($this::base . '/create', [
            'name' => "Integration testing",
            'user_id' => $user->id
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                "data" => [
                    'name',
                    'user_id',
                    'uuid',
                    'updated_at',
                    'created_at',
                    'id'
                ]
            ]);
    }

    public function test_add_many_device_to_group()
    {
        $user = User::factory()->create();
        $devices = Devices::factory()->count(12)->create()->pluck('id')->toArray();
        $group = Groups::create([ // no factory to groups...
            'name' => 'hey',
            'user_id' => $user->id,
            'uuid' =>  Str::uuid(),
        ]);

        $response = $this->post($this::base . "/{$group->id}/add", [
            'devicesIds' => $devices
        ]);

        $response->assertStatus(200);
    }

    public function test_get_all_groups()
    {
        $user = User::factory()->create();
        for ($i = 1; $i <= 10; $i++) {
            Groups::create([ // no factory to groups...
                'name' => 'hey',
                'user_id' => $user->id,
                'uuid' =>  Str::uuid(),
            ]);
        }

        $this->get($this::base)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'uuid',
                    'name',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_get_group_by_group_id()
    {
        $user = User::factory()->create();
        $group = Groups::create([ // no factory to groups...
            'name' => 'hey',
            'user_id' => $user->id,
            'uuid' =>  Str::uuid(),
        ]);

        $this->get($this::base . "/{$group->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'uuid',
                    'name',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_get_groups_by_user_id()
    {
        $user = User::factory()->create();
        for ($i = 1; $i <= 10; $i++) {
            Groups::create([ // no factory to groups...
                'name' => 'hey',
                'user_id' => $user->id,
                'uuid' =>  Str::uuid(),
            ]);
        }

        $this->get($this::base . "/user/{$user->id}")
            ->assertStatus(200)
            ->assertJsonCount(10)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'uuid',
                    'name',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_update_group()
    {
        $user = User::factory()->create();
        $group = Groups::create([ // no factory to groups...
            'name' => 'hey',
            'user_id' => $user->id,
            'uuid' =>  Str::uuid(),
        ]);

        $response = $this->putJson($this::base . "/{$group->id}", [
            'name' => 'updated by intagration!'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'uuid',
                    'name',
                    'user_id',
                    'created_at',
                    'updated_at'
                ]
            ]);
    }

    public function test_remove_device_from_group()
    {
        $user = User::factory()->create();
        $device = Devices::factory()->create();
        $group = Groups::create([ // no factory to groups...
            'name' => 'hey',
            'user_id' => $user->id,
            'uuid' =>  Str::uuid(),
        ]);
        $group->devices()->attach($device);

        $this->patch($this::base . "/{$group->id}/remove/{$device->id}")
            ->assertStatus(200);
    }

    public function test_delete_group()
    {
        $user = User::factory()->create();
        $group = Groups::create([ // no factory to groups...
            'name' => 'hey',
            'user_id' => $user->id,
            'uuid' =>  Str::uuid(),
        ]);

        $this->delete($this::base . "/{$group->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
}
