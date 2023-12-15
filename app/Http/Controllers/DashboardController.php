<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Bus;
use App\Models\Busroute;
use App\Models\BusSchedule;
use App\Models\Updates;
use App\Models\Location;
use Inertia\Inertia;
use Carbon\Carbon;

class DashboardController extends Controller
{

  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $dbSizeQuery = "SELECT table_schema 'database', sum(data_length + index_length) / 1024 / 1024 'size_in_mb'
    FROM information_schema.tables
    WHERE table_schema = ?
    GROUP BY table_schema";

    $databaseSize = DB::select($dbSizeQuery, ["partas_admin"]);
    $latestUpdates = Updates::latest()->take(10)->get();
    $currentDateTime = Carbon::now();
    $endOfDay = Carbon::now()->endOfDay();
    $tomorrow = Carbon::now()->addDay()->startOfDay();
    $upcomingSchedulesTomorrow = BusSchedule::with('bus', 'route')->where('departure_time', '>=', $tomorrow)
    ->get();

    $currentDate = Carbon::now();

    // Fetch schedules grouped by day of the week
    $schedulesByDay = BusSchedule::selectRaw('DAYOFWEEK(departure_time) as day_of_week, COUNT(*) as schedule_count')
        ->where('departure_time', '>', $currentDate)
        ->groupBy('day_of_week')
        ->get();

    // Map the day of the week to the actual day name
    $schedulesByDay = $schedulesByDay->map(function ($item) {
        // Adjust the day_of_week to match Carbon's convention
        $carbonDate = Carbon::now()->startOfWeek()->addDays($item['day_of_week'] - 1);
        $item['day_name'] = $carbonDate->isoFormat('dddd');
        return $item;
    });

    // If you want to include days without schedules, you can fill in the gaps
    $daysOfWeek = collect([
        ['day_of_week' => 1, 'day_name' => 'Monday'],
        ['day_of_week' => 2, 'day_name' => 'Tuesday'],
        ['day_of_week' => 3, 'day_name' => 'Wednesday'],
        ['day_of_week' => 4, 'day_name' => 'Thursday'],
        ['day_of_week' => 5, 'day_name' => 'Friday'],
        ['day_of_week' => 6, 'day_name' => 'Saturday'],
        ['day_of_week' => 0, 'day_name' => 'Sunday'],
    ]);

    $mergedDays = $daysOfWeek->map(function ($day) use ($schedulesByDay) {
        $foundDay = $schedulesByDay->firstWhere('day_of_week', $day['day_of_week']);
        return $foundDay ?: array_merge($day, ['schedule_count' => 0]);
    });

    $upcomingSchedules = BusSchedule::with('bus', 'route')->whereBetween('departure_time', [$currentDateTime, $endOfDay])
        ->get();

    $busTypesWithCounts = Bus::selectRaw('type, COUNT(*) as count')
    ->groupBy('type')
    ->get()
    ->toArray();
  
    return Inertia::render('Dashboard', [
      "totalBuses" => Bus::count(),
      "totalRoutes" => Busroute::count(),
      "totalLocations" => Location::count(),
      "busTypes" => $busTypesWithCounts,
      "latestUpdates" => $latestUpdates,
      "upcomingSchedulesCount" => $upcomingSchedules->count(),
      "upcomingSchedulesTomorrowCount" => $upcomingSchedulesTomorrow->count(),
      "upcomingSchedules" => $upcomingSchedules,
      "upcomingSchedulesTomorrow" => $upcomingSchedulesTomorrow,
      "databaseSize" => $databaseSize,
      "weekSchedules" => $mergedDays
    ]);
  }
}
