<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountabilityRecord;
use App\Models\Technician; // Import Technician model

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // Get all unique years from accountability records
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

        // Fetch accountability data (Grouped by description)
        $finalData = AccountabilityRecord::whereYear('date', $selectedYear)
            ->selectRaw('description, SUM(quantity) as count') // Change COUNT(*) to SUM(quantity)
            ->groupBy('description')
            ->orderBy('count', 'desc')
            ->get();

        // Fetch technician data (Grouped by description) - **Fixed query to filter by year**
        $technicianData = Technician::whereYear('date', $selectedYear) // Added filtering by year
            ->selectRaw('description, SUM(quantity) as count') // Fixed duplicate selectRaw
            ->groupBy('description')
            ->orderBy('count', 'desc')
            ->get();

        return view('dashboard', compact('years', 'selectedYear', 'finalData', 'technicianData'));
    }
}
