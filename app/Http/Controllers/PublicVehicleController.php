<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class PublicVehicleController extends Controller
{
    public function show(string $slug)
    {
        $vehicle = Vehicle::where('slug', $slug)->where('status','published')->firstOrFail();
        
        return view('vehicles.public-show', compact('vehicle'));
    }
}
