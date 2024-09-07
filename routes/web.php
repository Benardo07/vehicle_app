<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleUsageController;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/', [VehicleController::class, 'dashboard'])->name('dashboard');
    Route::get('vehicles/usage-details', [VehicleController::class, 'vehicleUsageDetails']);
    Route::get('/approve-requests', [BookingController::class, 'pendingRequests'])->name('approve_requests');
    Route::patch('/vehicles/{vehicle}/done', [VehicleController::class, 'markAsDone'])->name('vehicles.markAsDone');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/bookings/{booking}/approve', [BookingController::class, 'approveBooking'])->name('bookings.approve');
    Route::post('/bookings/{booking}/reject', [BookingController::class, 'rejectBooking'])->name('bookings.reject');   
    Route::get('/pending-requests-count', [BookingController::class, 'getPendingRequestsCount']);
    Route::post('/return-vehicle/{vehicleId}', [VehicleController::class, 'returnVehicle'])->name('return-vehicle');
    Route::get('/vehicles/filter', [VehicleController::class, 'filterVehicles']);
    Route::get('/bookings/export', [BookingController::class, 'export'])->name('bookings_export');
    Route::get('/vehicle-usages/export', [VehicleUsageController::class, 'export'])->name('vehicle_usages_export');
    Route::post('/vehicles/{vehicle}/initiate-maintenance', [VehicleController::class, 'initiateMaintenance'])->name('vehicles.initiateMaintenance');
});

