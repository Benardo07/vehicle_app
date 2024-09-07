<?php

namespace App\Http\Controllers;
use App\Models\Vehicle; // Ensure you have the correct namespace for your Vehicle model
use App\Models\User;
use App\Models\Booking;
use App\Models\VehicleUsage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingsExport;


class BookingController extends Controller
{

    public function create(Request $request)
    {
        Log::info('Entering the create method in BookingController.');
        $selectedVehicleId = $request->query('vehicle_id');
        $selectedVehicle = null;
        $availableCars = Vehicle::where('status', 'available');

        if ($selectedVehicleId) {
            $selectedVehicle = Vehicle::find($selectedVehicleId);
            $availableCars = $availableCars->where('id', '!=', $selectedVehicleId);
        }

        $availableCars = $availableCars->get();
        $drivers = User::where('role', 'driver')->get();
        $managers = User::where('role', 'manager')->get();

        return view('bookings.create', [
            'selectedVehicle' => $selectedVehicle,
            'availableCars' => $availableCars,
            'drivers' => $drivers,
            'managers' => $managers
        ]);
    }
    public function store(Request $request)
    {
        Log::info('Entering the store method in BookingController.');
        // Validate the request
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'employee_id' => 'required|exists:users,id',
            'approver_id' => 'required|exists:users,id',
            'end_time' => 'required|date',
            'purpose' => 'required|string',
        ]);

        try {
            $booking = Booking::create($validated);
            return response()->json(['message' => 'Booking created successfully!', 'booking' => $booking]);
        } catch (\Exception $e) {
            Log::error('Failed to create booking: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to create booking', 'error' => $e->getMessage()], 500);
        }
    }

    public function getPendingRequestsCount()
    {
        // Assuming 'status' is a field in your bookings table where 'pending' is a status type
        $userId = auth()->id();
        $count = Booking::where('approver_id', $userId)
                        ->where('status', 'pending')
                        ->count();

        return response()->json(['count' => $count]);
    }

    public function pendingRequests()
    {
        $userId = auth()->id(); // assuming you're using default Laravel authentication
        Log::info($userId);
        $bookings = Booking::with('vehicle', 'driver')
                           ->where('approver_id', $userId)
                           ->where('status', 'pending')
                           ->get();

        return view('approve-requests', compact('bookings'));
    }

    // Approve a booking
    public function approveBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->status = 'allowed';
        $booking->save();

        $vehicle = Vehicle::findOrFail($booking->vehicle_id);
        $vehicle->status = 'inUsed';
        $vehicle->save();

        // Create a new vehicle usage without end_date and kilometers driven
        VehicleUsage::create([
            'vehicle_id' => $booking->vehicle_id,
            'employee_id' => $booking->employee_id,
            'start_time' => now(),
            'end_time' => null,       // Assuming this is nullable
            'kilometers_driven' => null  // Assuming this is nullable
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Booking approved successfully.'
        ]);
    }

    // Reject a booking
    public function rejectBooking(Request $request, $bookingId)
    {
        $booking = Booking::findOrFail($bookingId);
        $booking->status = 'rejected';
        $booking->save();

        return redirect()->route('approve.requests')->with('success', 'Booking rejected successfully.');
    }

    public function export()
    {
        return Excel::download(new BookingsExport, 'bookings.xlsx');
    }
}
