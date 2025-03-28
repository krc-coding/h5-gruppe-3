<?php

namespace Tests\Feature;

use App\Models\Data;
use App\Models\Devices;
use App\Models\Groups;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DataApiTest extends TestCase
{
    use RefreshDatabase;
    const base = '/api/data';

    public function test_create_data()
    {
        $device = Devices::factory()->create();

        $response = $this->postJson($this::base . '/create', [
            'people' => 12,
            'products_pr_person' => 12,
            'total_value' => 13,
            'product_categories' => "{\"category\": 1}",
            'packages_received' => 1,
            'packages_delivered' => 1,
            'device_uuid' => $device->uuid,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'people',
                    'products_pr_person',
                    'total_value',
                    'product_categories',
                    'packages_received',
                    'packages_delivered',
                    'device_id'
                ]
            ]);
    }

    public function test_get_all_data()
    {
        Devices::factory()->create();
        Data::factory()->count(5)->create();
        $this->get($this::base)
            ->assertStatus(200)
            ->assertJsonCount(5)
            ->assertJsonStructure([
                '*' => [
                    'people',
                    'products_pr_person',
                    'total_value',
                    'product_categories',
                    'packages_received',
                    'packages_delivered',
                    'device_id'
                ]
            ]);
    }

    public function test_get_data_by_device()
    {
        $device = Devices::factory()->create();
        Data::factory()->count(6)->create();

        $this->get($this::base . "/device/{$device->id}")
            ->assertStatus(200)
            ->assertJsonCount(6)
            ->assertJsonStructure([
                '*' => [
                    'people',
                    'products_pr_person',
                    'total_value',
                    'product_categories',
                    'packages_received',
                    'packages_delivered',
                    'device_id'
                ]
            ]);
    }

    public function test_get_data_by_group()
    {
        $user = User::factory()->create();
        $device = Devices::factory()->create();
        $group = Groups::create([ // no factory to groups...
            'name' => 'hey',
            'user_id' => $user->id,
            'uuid' =>  Str::uuid(),
        ]);

        $group->devices()->attach($device);
        Data::factory()->count(10)->create();

        $this->get($this::base . "/group/{$group->id}")
            ->assertStatus(200)
            ->assertJsonCount(10)
            ->assertJsonStructure([
                '*' => [
                    'people',
                    'products_pr_person',
                    'total_value',
                    'product_categories',
                    'packages_received',
                    'packages_delivered',
                    'device_id'
                ]
            ]);
    }
}
