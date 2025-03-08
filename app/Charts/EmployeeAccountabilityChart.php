<?php

namespace App\Charts;

use ConsoleTVs\Charts\Classes\Chartjs\Chart;
use App\Models\AccountabilityRecord;

class EmployeeAccountabilityChart
{
    public function build()
    {
        $chart = new Chart;

        // Fetch accountability count per employee
        $data = AccountabilityRecord::selectRaw('name, COUNT(*) as count')
            ->groupBy('name')
            ->pluck('count', 'name');

        // Configure the chart
        $chart->labels($data->keys());
        $chart->dataset('Employee Accountabilities', 'bar', $data->values())
            ->backgroundColor(['#FF5733', '#33FF57', '#3357FF', '#F3FF33', '#FF33A1']);

        return $chart;
    }
}
