<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehicleSeeder extends Seeder
{
    public function run()
    {
        $vehicles = [
            [
                'type' => 'personnel carrier',
                'model_name' => 'Toyota Corolla',
                'image_path' => 'path/to/image1.jpg',
                'license_plate' => 'B 1232 AO',
                'ownership' => 'owned',
                'fuel_consumption_per_km' => 0.1,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'type' => 'personnel carrier',
                'model_name' => 'Honda CRV',
                'image_path' => 'path/to/image2.jpg',
                'license_plate' => 'B 982 UI',
                'ownership' => 'owned',
                'fuel_consumption_per_km' => 0.08,
                'status' => 'maintenance',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'type' => 'cargo transporter',
                'model_name' => 'Ford Focus',
                'image_path' => 'path/to/image3.jpg',
                'license_plate' => 'B 2231 U',
                'ownership' => 'owned',
                'fuel_consumption_per_km' => 0.15,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'type' => 'cargo transporter',
                'model_name' => 'Chevrolet Camaro',
                'image_path' => 'path/to/image4.jpg',
                'license_plate' => 'B 1023 UW',
                'ownership' => 'owned',
                'fuel_consumption_per_km' => 0.2,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'type' => 'personnel carrier',
                'model_name' => 'BMW 2 Series',
                'image_path' => 'path/to/image5.jpg',
                'license_plate' => 'B 1003 QM',
                'ownership' => 'leased',
                'fuel_consumption_per_km' => 0.09,
                'status' => 'available',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ];

        DB::table('vehicles')->insert($vehicles);
    }
}
