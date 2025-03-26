<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call the DeviceSeeder
        $this->call(DeviceSeeder::class);

        // Call the DataSeeder
        $this->call(DataSeeder::class);
    }
}
