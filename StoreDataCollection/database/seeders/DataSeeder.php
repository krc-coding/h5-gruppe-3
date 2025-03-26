<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Data;

class DataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10000 data records in batches of 1000
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
        Data::factory(1000)->create();
    }
}
