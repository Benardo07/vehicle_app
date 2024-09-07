<?php

namespace App\Http\Controllers;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\VehicleUsageExport;
class VehicleUsageController extends Controller
{
    public function export()
    {
        return Excel::download(new VehicleUsageExport, 'vehicle_usages.xlsx');
    }
}
