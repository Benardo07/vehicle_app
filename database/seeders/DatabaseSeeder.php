<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\VehicleUsageSeeder;
use Database\Seeders\VehicleSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\BookingSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(VehicleUsageSeeder::class);
        $this->call(VehicleSeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(BookingSeeder::class);
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
