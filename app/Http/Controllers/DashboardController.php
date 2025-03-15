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

    // Get selected year and source from request
    $selectedYear = $request->input('year', $years->first() ?? now()->year);
    $selectedSource = $request->input('source', null); // Default to "All Sources"

    // Initialize data queries
    $accountabilityQuery = AccountabilityRecord::whereYear('date', $selectedYear)
        ->selectRaw('description, SUM(quantity) as count, "Accountability" as source')
        ->groupBy('description');

    $gingoogQuery = Gingoog::whereYear('date', $selectedYear)
        ->selectRaw('description, SUM(quantity) as count, "Gingoog" as source')
        ->groupBy('description');

    $technicianQuery = Technician::whereYear('date', $selectedYear)
        ->selectRaw('description, SUM(quantity) as count, "Technician" as source')
        ->groupBy('description');

    // Apply source filter if a specific source is selected
    if ($selectedSource) {
        if ($selectedSource === 'Accountability') {
            $gingoogQuery = collect(); // Empty the other datasets
            $technicianQuery = collect();
        } elseif ($selectedSource === 'Gingoog') {
            $accountabilityQuery = collect();
            $technicianQuery = collect();
        } elseif ($selectedSource === 'Technician') {
            $accountabilityQuery = collect();
            $gingoogQuery = collect();
        }
    }

    // Retrieve data from database
    $accountabilityData = is_a($accountabilityQuery, 'Illuminate\Database\Eloquent\Builder') ? $accountabilityQuery->get() : $accountabilityQuery;
    $gingoogData = is_a($gingoogQuery, 'Illuminate\Database\Eloquent\Builder') ? $gingoogQuery->get() : $gingoogQuery;
    $technicianData = is_a($technicianQuery, 'Illuminate\Database\Eloquent\Builder') ? $technicianQuery->get() : $technicianQuery;

    // Merge all filtered data collections
    $mergedData = $accountabilityData->concat($gingoogData)->concat($technicianData);

    // Fetch unique sources for the dropdown
    $sources = ['Accountability', 'Gingoog', 'Technician', 'BC-CDO LIST', 'TURN-OVER LIST', 'AWOL LIST', 'RESIGN LIST'];

    // If no records exist, display a message
    $message = $mergedData->isEmpty() ? "No records found for year: $selectedYear and source: $selectedSource" : null;

    return view('dashboard', compact('mergedData', 'selectedYear', 'selectedSource', 'years', 'sources', 'message'));
}

}
