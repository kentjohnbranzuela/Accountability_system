<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountabilityRecord;
use App\Models\Gingoog;
use App\Models\Technician;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
{
    // Fetch available years from all tables
    $years = AccountabilityRecord::selectRaw('YEAR(date) as year')
        ->union(Gingoog::selectRaw('YEAR(date) as year'))
        ->union(Technician::selectRaw('YEAR(date) as year'))
        ->distinct()
        ->orderBy('year', 'desc')
        ->pluck('year');

    // Determine selected year: use request input, otherwise use latest available year
    $selectedYear = $request->input('year', $years->first() ?? now()->year);

    // Fetch data from all sources
    $accountabilityData = AccountabilityRecord::whereYear('date', $selectedYear)
        ->selectRaw('description, SUM(quantity) as count, "Accountability" as source')
        ->groupBy('description')
        ->get();

    $gingoogData = Gingoog::whereYear('date', $selectedYear)
        ->selectRaw('description, SUM(quantity) as count, "Gingoog" as source')
        ->groupBy('description')
        ->get();

    $technicianData = Technician::whereYear('date', $selectedYear)
        ->selectRaw('description, SUM(quantity) as count, "Technician" as source')
        ->groupBy('description')
        ->get();

    // Merge all data collections
    $mergedData = $accountabilityData->concat($gingoogData)->concat($technicianData);

    // If no records exist, display a message
    $message = $mergedData->isEmpty() ? "No records found for year: " . $selectedYear : null;

    return view('dashboard', compact('mergedData', 'selectedYear', 'years', 'message'));
}

}
