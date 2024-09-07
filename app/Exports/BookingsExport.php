<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class BookingsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Booking::all(); 
    }

    public function headings(): array
    {
        return ["ID","Approver ID", "Vehicle ID","Employee ID", "End Time","Purpose", "Created_At" , "Updated_At" ,"Status"];
    }
}
