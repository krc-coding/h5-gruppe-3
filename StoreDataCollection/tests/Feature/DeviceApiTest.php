<?php

namespace Tests\Feature;

use App\Models\Devices;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeviceApiTest extends TestCase
{
    const base = '/api/device';

    public function test_create_device()
    {
        $this->post($this::base . '/create')
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'uuid'
                ]
            ]);
    }

    public function test_get_all_devices()
    {
        Devices::factory()->count(5)->create();

        $this->get($this::base)
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'uuid'
                ]
            ]);
    }

    public function test_get_device_by_id()
    {
        $device = Devices::factory()->create();

        $this->get($this::base . "/{$device->id}")
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'uuid'
                ]
            ]);
    }

    public function test_get_devices_by_group_id()
    {
        $user = User::factory()->create();
        $devices = Devices::factory()->count(5)->create();
        $group = Groups::create([ // no factory to groups...
            'name' => 'hey',
            'user_id' => $user->id,
            'uuid' =>  Str::uuid(),
        ]);

        $group->devices()->attach($devices);

        $this->get($this::base . "/group/{$group->id}")
            ->assertStatus(200)
            ->assertJsonCount(5)
            ->assertJsonStructure([
                '*' => [
                    'id',
                    'uuid'
                ]
            ]);
    }

    public function test_if_device_exits() // isn't on the brance...
    {
        $device = Devices::factory()->create();
        $this->get($this::base . "/exits?uuid={$device->uuid}")
            ->assertStatus(404);
        // ->assertJsonStructure([

        // ]);
    }

    public function test_delete_device()
    {
        $device = Devices::factory()->create();

        $this->delete($this::base . "/{$device->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('devices', ['id' => $device->id]);
    }
}
