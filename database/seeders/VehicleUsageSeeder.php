<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleUsageSeeder extends Seeder
{
    public function run()
    {
        $vehicleIds = DB::table('vehicles')->pluck('id');
        $employeeIds = [3, 4, 5, 6]; // Driver IDs

        foreach ($vehicleIds as $vehicleId) {
            foreach ($employeeIds as $employeeId) {
                for ($i = 0; $i < 5; $i++) { // 5 usages per vehicle
                    $start = Carbon::now()->subDays(rand(10, 100)); // Start date in the past
                    $end = (clone $start)->addHours(rand(1, 48)); // End date after start, still in the past
                    DB::table('vehicle_usages')->insert([
                        'vehicle_id'        => $vehicleId,
                        'employee_id'       => $employeeId,
                        'start_time'        => $start,
                        'end_time'          => $end,
                        'kilometers_driven' => rand(100, 500),
                        'purpose'           => 'Business Trip',
                        'notes'             => 'Regular usage',
                        'created_at'        => $start->subDays(rand(1, 5)), // Created before the usage start
                        'updated_at'        => $end
                    ]);
                }
            }
        }
    }
}
