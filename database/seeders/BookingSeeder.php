<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $vehicleIds = DB::table('vehicles')->pluck('id');
        $employeeIds = [3, 4, 5, 6]; // Driver IDs
        $managerId = 1; // Manager ID

        foreach ($vehicleIds as $vehicleId) {
            foreach ($employeeIds as $employeeId) {
                DB::table('bookings')->insert([
                    'approver_id' => $managerId,
                    'vehicle_id'  => $vehicleId,
                    'employee_id' => $employeeId,
                    'end_time'    => Carbon::now()->subDays(rand(1, 30)), // Ensure this is in the past
                    'purpose'     => 'Business Trip',
                    'created_at'  => Carbon::now()->subDays(rand(31, 60)), // Even the creation date is in the past
                    'updated_at'  => Carbon::now()->subDays(rand(1, 30)),
                    'status'      => 'allowed'
                ]);
            }
        }
    }
}
