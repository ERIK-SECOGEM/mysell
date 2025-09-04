<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','seller'])->except(['showPublic']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehicles = Vehicle::ownedBy(Auth::user())->latest()->paginate(12);
        return view('vehicles.index', compact('vehicles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vehicles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:120',
            'make' => 'required|string|max:60',
            'model' => 'required|string|max:60',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'mileage' => 'nullable|integer|min:0',
            'vin' => 'nullable|string|max:32',
            'location' => 'nullable|string|max:120',
            'currency' => 'required|string|size:3',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'cover' => 'nullable|image|max:4096',
        ]);

        $data['user_id'] = Auth::id();
        $data['slug'] = Str::slug($data['title'].'-'.Str::random(6));


        if ($request->hasFile('cover')) {
            $data['cover_image_path'] = $request->file('cover')->store('vehicles','public');
        }

        $vehicle = Vehicle::create($data);

        return redirect()->route('vehicles.show', $vehicle)->with('success','Vehículo creado.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        return view('vehicles.show', compact('vehicle'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);

        return view('vehicles.edit', compact('vehicle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $data = $request->validate([
            'title' => 'required|string|max:120',
            'make' => 'required|string|max:60',
            'model' => 'required|string|max:60',
            'year' => 'required|integer|min:1900|max:'.(date('Y')+1),
            'mileage' => 'nullable|integer|min:0',
            'vin' => 'nullable|string|max:32',
            'location' => 'nullable|string|max:120',
            'currency' => 'required|string|size:3',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:draft,published,sold',
            'cover' => 'nullable|image|max:4096',
        ]);


        if ($request->hasFile('cover')) {
            if ($vehicle->cover_image_path) {
                Storage::disk('public')->delete($vehicle->cover_image_path);
            }

            $data['cover_image_path'] = $request->file('cover')->store('vehicles','public');
        }

        $vehicle->update($data);

        return redirect()->route('vehicles.show', $vehicle)->with('success','Vehículo actualizado.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('success','Vehículo eliminado.');
    }

    public function publish(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $vehicle->update(['status' => 'published']);

        return back()->with('success','Vehículo publicado.');
    }

    public function qr(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $url = $vehicle->public_url;
        /*$png = QrCode::format('png')->size(512)->margin(1)->generate($url);

        return response($png)->header('Content-Type', 'image/png');*/

        // Generar QR en formato SVG (no necesita imagick)
        $svg = QrCode::format('svg')->size(512)->margin(1)->generate($url);

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }

    public function qrDownload(Vehicle $vehicle)
    {
        $this->authorizeOwner($vehicle);
        $url = $vehicle->public_url;
        /*$png = QrCode::format('png')->size(1024)->margin(1)->generate($url);

        return response()->streamDownload(function() use ($png) {
            echo $png;
        }, 'qr-vehiculo-'.$vehicle->id.'.png', [
            'Content-Type' => 'image/png'
        ]);*/
        $svg = QrCode::format('svg')->size(1024)->margin(1)->generate($url);

        return response()->streamDownload(function() use ($svg) {
            echo $svg;
        }, 'qr-vehiculo-'.$vehicle->id.'.svg', [
            'Content-Type' => 'image/svg+xml'
        ]);
    }

    protected function authorizeOwner(Vehicle $vehicle)
    {
        abort_unless($vehicle->user_id === Auth::id(), 403);
    }
}
