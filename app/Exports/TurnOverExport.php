<?php

namespace App\Exports;

use App\Models\TurnOver;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TurnOverExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return TurnOver::all()->map(function ($turnover) {
            return [
                'Position' => $turnover->position,
                'Name' => $turnover->name,
                'Date' => $turnover->date,
                'Quantity' => $turnover->quantity,
                'Description' => $turnover->description,
                'Serial No.' => $turnover->ser_no,
                'Status' => $turnover->status === 'Unknown' ? '' : $turnover->status, // Replaces "Unknown" with blank
            ];
        });
    }

    public function headings(): array
    {
        return ["Position", "Name", "Date", "Quantity", "Description", "Serial No.", "Status"];
    }
}
