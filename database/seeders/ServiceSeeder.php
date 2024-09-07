<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ServiceSeeder extends Seeder
{
    public function run()
    {
        $services = [
            [
                'vehicle_id' => 1, // Assuming your vehicles have sequential IDs starting at 1
                'service_date' => Carbon::now()->subMonths(2), // 2 months ago
                'service_type' => 'regular',
                'description' => 'Regular maintenance check',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'vehicle_id' => 2,
                'service_date' => Carbon::now()->subMonths(1), // 1 month ago
                'service_type' => 'regular',
                'description' => 'Oil change and tire rotation',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'vehicle_id' => 3,
                'service_date' => Carbon::now()->subMonths(3)->subDays(1), // Over 3 months ago
                'service_type' => 'regular',
                'description' => 'Engine tune-up and brake inspection',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'vehicle_id' => 4,
                'service_date' => Carbon::now()->subMonths(2), // Exactly 2 months ago
                'service_type' => 'regular',
                'description' => 'Battery check and replacement of brake pads',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'vehicle_id' => 5,
                'service_date' => Carbon::now()->subMonths(2)->subWeeks(1), // 2 months and 1 week ago
                'service_type' => 'regular',
                'description' => 'Suspension check and wheel alignment',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('services')->insert($services);
    }
}
