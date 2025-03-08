<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Charts\EmployeeAccountabilityChart;
use App\Models\AccountabilityRecord;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get all unique years
        $years = AccountabilityRecord::selectRaw('YEAR(date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Ensure 2023 appears in the dropdown
        if (!$years->contains(2023)) {
            $years->push(2023);
        }

        // Get selected year (default to current year)
        $selectedYear = $request->input('year', now()->year);

        // Fetch data for selected year
        $finalData = AccountabilityRecord::whereYear('date', $selectedYear)
            ->selectRaw('description, COUNT(*) as count')
            ->groupBy('description')
            ->orderBy('count', 'desc')
            ->get();

        return view('dashboard', compact('years', 'selectedYear', 'finalData'));
    }
}


