<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountabilityRecord;
use App\Models\Gingoog;
use App\Models\Technician;
use App\Models\Cdo;
use App\Models\TurnOver;
use App\Models\AwolRecord;
use App\Models\ResignRecord;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
{
    // Fetch available years, handling NULL dates
    $years = collect([
        AccountabilityRecord::class,
        Gingoog::class,
        Technician::class,
        Cdo::class,
        TurnOver::class,
        AwolRecord::class,
        ResignRecord::class,
    ])->flatMap(fn($model) => $model::whereNotNull('date')
        ->selectRaw('DISTINCT YEAR(date) as year')
        ->pluck('year'))
      ->unique()
      ->sortDesc()
      ->values();

    // Get selected year and source from request
    $selectedYear = $request->input('year', null);
    $selectedSource = $request->input('source', null);

    // Define models and sources
    $models = [
        'BRO' => AccountabilityRecord::class,
        'Gingoog' => Gingoog::class,
        'Technician' => Technician::class,
        'BC-CDO LIST' => Cdo::class,
        'TURN-OVER LIST' => TurnOver::class,
        'AWOL LIST' => AwolRecord::class,
        'RESIGN-LIST' => ResignRecord::class,
    ];

    // Count per category (for dashboard boxes)
    $dataCounts = [];
    foreach ($models as $source => $model) {
        $dataCounts[$source] = $model::count();
    }

    // Filter if specific source is selected
    if ($selectedSource && $selectedSource !== 'All Records') {
        $models = array_filter($models, fn($key) => $key === $selectedSource, ARRAY_FILTER_USE_KEY);
    }

    // Execute queries
    $mergedData = collect();
    foreach ($models as $source => $model) {
        $query = $model::selectRaw('description, SUM(quantity) as count, ? as source, YEAR(date) as year', [$source])
            ->groupBy('description', 'year');

        if ($selectedYear) {
            $query->whereYear('date', $selectedYear);
        }

        $mergedData = $mergedData->concat($query->get());
    }

    // Fetch unique sources for dropdown
    $sources = array_keys($models);

    // Message when no records are found
    $message = $mergedData->isEmpty() ? "No records found for year: $selectedYear and source: $selectedSource" : null;

    return view('dashboard', compact('mergedData', 'selectedYear', 'selectedSource', 'years', 'sources', 'message', 'dataCounts'));
}

}
