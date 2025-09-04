<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleImage extends Model
{
    protected $fillable = ['vehicle_id','path','position'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
