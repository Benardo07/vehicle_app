<?php

namespace App\Exports;

use App\Models\VehicleUsage;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class VehicleUsageExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return VehicleUsage::all(); 
    }

    public function headings(): array
    {
        return ["ID", "Vehicle ID","Employee ID", "Start Time","End Time","Kilometers Driven" ,"Purpose", "Notes", "Created_At", "Updated_At"]; 
    }
}
