<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'type',
        'license_plate',
        'ownership',
        'fuel_consumption_per_km',
        'status',
        'model_name',
        'image_path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fuel_consumption_per_km' => 'double',
    ];

    /**
     * Get the path to the vehicle's image.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }

    public function service()
    {
        return $this->hasOne(Service::class);
    }

    public function usages()
    {
        return $this->hasMany(VehicleUsage::class);
    }
}
