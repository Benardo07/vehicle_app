<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'approver_id', 'vehicle_id', 'employee_id', 
        'end_time', 'purpose'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the driver associated with the booking.
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'employee_id');  // Adjust foreign key if needed
    }
}
