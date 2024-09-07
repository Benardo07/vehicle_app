<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Carbon\Carbon; // For handling dates
use App\Models\Service;
use App\Models\VehicleUsage;
use Illuminate\Support\Facades\Log;

class VehicleController extends Controller
{
    public function dashboard()
    {
        $vehicles = Vehicle::with('service')->get();

        $vehicles->each(function($vehicle) {
            $vehicle->total_km_used = $vehicle->usages->sum('kilometers_driven');
        });

        return view('dashboard', compact('vehicles'));
    }

    public function markAsDone(Request $request, $vehicleId)
    {
        Log::info('Entering the markAsDone method in VehicleController.');
        try {
            $vehicle = Vehicle::findOrFail($vehicleId);
            
            // Update vehicle status to 'available'
            $vehicle->status = 'available';
            $vehicle->save();
    
            // Check if there's already a service record for this vehicle
            $service = Service::where('vehicle_id', $vehicleId)->first();
    
            if ($service) {
                // Update existing service record
                $service->service_date = Carbon::today()->toDateString();
                $service->service_type = $request->input('service_type', 'Regular'); // Default to 'Regular'
                $service->description = 'Updated maintenance';
                $service->save();
            } else {
                // Create new service record
                $service = Service::create([
                    'vehicle_id' => $vehicleId,
                    'service_date' => Carbon::today()->toDateString(),
                    'service_type' => $request->input('service_type', 'Regular'), // Default to 'Regular'
                    'description' => 'Regular maintenance'
                ]);
            }
    
            return response()->json([
                'message' => 'Vehicle marked as done successfully and service recorded/updated.',
                'vehicle' => $vehicle
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to create or update service: ' . $e->getMessage());
            return response()->json([
                'error' => 'Vehicle not found or update failed',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function returnVehicle(Request $request, $vehicleId)
    {
        $request->validate([
            'kilometers_driven' => 'required|numeric',
            'notes' => 'nullable|string'
        ]);

        // Find the vehicle usage that doesn't have an end time yet.
        $vehicleUsage = VehicleUsage::where('vehicle_id', $vehicleId)
                                    ->whereNull('end_time')
                                    ->firstOrFail();

        // Update the vehicle usage details
        $vehicleUsage->update([
            'end_time' => now(),
            'kilometers_driven' => $request->kilometers_driven,
            'notes' => $request->notes
        ]);

        // Update the vehicle status
        $vehicle = Vehicle::findOrFail($vehicleId);
        $vehicle->status = 'available';
        $vehicle->save();

        return response()->json([
            'message' => 'Vehicle successfully returned and status updated to available.'
        ]);
    }

    public function vehicleUsageDetails(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        $vehicle = Vehicle::find($vehicleId);
        $vehicleUsages = VehicleUsage::where('vehicle_id', $vehicleId)
                            ->with('vehicle') // Eager load the vehicle relationship
                            ->whereNotNull('end_time') // Ensure we get completed usages
                            ->get();
    
        $dailyUsage = $vehicleUsages->groupBy(function($date) {
            return Carbon::parse($date->start_time)->format('Y-m-d'); // grouping by day
        });
    
        $dailyData = [];
        foreach ($dailyUsage as $date => $usages) {
            $totalKm = $usages->sum('kilometers_driven');
            $totalFuel = $usages->sum(function($usage) {
                return $usage->kilometers_driven * $usage->vehicle->fuel_consumption_per_km;
            });
            $dailyData[] = [
                'date' => $date,
                'kilometers' => $totalKm,
                'fuel' => $totalFuel
            ];
        }
    
        return view('usage-details', [
            'vehicle' => $vehicle,
            'dailyData' => $dailyData,
            'usages' => $vehicleUsages
        ]);
    }

    public function initiateMaintenance($vehicleId)
    {
        $vehicle = Vehicle::find($vehicleId);
        if (!$vehicle) {
            return response()->json(['message' => 'Vehicle not found', 'success' => false], 404);
        }

        if ($vehicle->status == 'inUsed') {
            return response()->json(['message' => 'Vehicle is currently in use and cannot be sent to maintenance', 'success' => false], 400);
        }

        // If vehicle is available, change status and save
        if ($vehicle->status == 'available') {
            $vehicle->status = 'maintenance';
            $vehicle->save();

            // Optionally add an entry to a maintenance log or update last_service_date
            return response()->json(['message' => 'Vehicle status updated to maintenance', 'success' => true], 200);
        }

        return response()->json(['message' => 'Vehicle status is not available for maintenance', 'success' => false], 400);
    }
}
