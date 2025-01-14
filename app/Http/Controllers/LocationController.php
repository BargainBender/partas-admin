<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Updates;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexAPI()
    {
        $locations = Location::select('location', 'longtitude', 'latitude')->get();
        return response()->json($locations);
    }

    public function index()
    {
        $locations = Location::all();
        return Inertia::render('Locations/List', [
          'locations' => $locations,
          'success' => session('success'),
          'delete' => session('delete')
        ]);
    }

    public function create()
    {
        return Inertia::render('Locations/Create', [
            'error' => session('error')
            ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
{
    Request::validate([
        "location" => 'required',
    ]);

    $existingLocation = Location::where('location', Request::get('location'))->first();

    if ($existingLocation) {
        $uniqueIdentifier = time();
        return to_route('location.create')->with(['error' => $uniqueIdentifier . ': Location already exists.']);
    }

    Location::create([
        "location" => Request::get("location"),
    ]);
    Updates::create([
      "message" => "Location created: ".Request::get('location')."."
    ]);

    return to_route('locations')->with('success', 'New location created.');
}

    /**
     * Display the specified resource.
     */
    public function show(Location $location)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $location = Location::find(Request::get("id"));
        return Inertia::render('Locations/Edit', ['location' => $location]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Location $location)
    {
        Request::validate([
            "location" => 'required',
        ]);

        Updates::create([
          "message" => "Location updated: ".$location->location." to ".Request::get('location')."."
        ]);
        Location::where('id', $location->id)
            ->update([
                "location" => Request::get("location"),
            ]);
        

        return to_route('locations')->with('success', 'Location updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Location $location)
    {
        Updates::create([
          "message" => "Location deleted: ".$location->location."."
        ]);
        Location::destroy($location->id);
        $uniqueIdentifier = time();
        return to_route('locations')->with('delete', $uniqueIdentifier . ':Deleted location.');
    }
}
