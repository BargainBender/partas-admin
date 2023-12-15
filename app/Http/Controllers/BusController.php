<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Updates;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Request;

use Inertia\Inertia;

class BusController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $buses = Bus::all();
    return Inertia::render('Buses/List', [
      'buses' => $buses,
      'success' => session('success'),
      'delete' => session('delete')
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    \error_log("session('error'): " . session('error'));
    return Inertia::render('Buses/Create', [
      'error' => session('error')
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store()
  {
    Request::validate([
      'code' => 'required',
      'type' => 'required',
      'capacity' => 'required',
    ]);


    try {
      Bus::create([
        'code' => Request::get('code'),
        'type' => Request::get('type'),
        'capacity' => Request::get('capacity'),
      ]);
      Updates::create([
        "message" => "Bus Created: ".Request::get('code')." (".Request::get('type').")."
      ]);
      return redirect()->route('buses')->with('success', 'New Bus created.');
    } catch (QueryException $e) {
      // Handle the error
      if ($e->errorInfo[1] == 1062) {
        $uniqueIdentifier = time();
        return back()->with('error', $uniqueIdentifier . ':This bus code already exists.');
      }
      throw $e;
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(Bus $buses)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(Bus $bus)
  {
    $id = Request::get("id");
    $bus = Bus::find($id);
    return Inertia::render('Buses/Edit', ['bus' => $bus]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Bus $bus)
  {
    Request::validate([
      'code' => 'required',
      'type' => 'required',
      'capacity' => 'required',
    ]);


    Bus::where('id', $bus->id)
      ->update([
        'code' => Request::get('code'),
        'type' => Request::get('type'),
        'capacity' => Request::get('capacity'),
      ]);
    Updates::create([
        "message" => "Bus updated: ".Request::get('code')." (".Request::get('type').")."
      ]);
    return to_route('buses')->with('success', 'Route  edited.');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(Bus $bus)
  {
    Updates::create([
      "message" => "Bus deleted: ".$bus->code." (".$bus->type.")."
    ]);
    Bus::destroy($bus->id);
    $uniqueIdentifier = time();
    to_route('busroutes')->with('delete', $uniqueIdentifier . ':Deleted bus.');
  }
}
