<?php

namespace App\Exports;

use App\Models\Cdo;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CdoExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Cdo::all()->map(function ($cdo) { // ✅ Use lowercase $cdo
            return [
                'Position'     => $cdo->position,
                'Name'         => $cdo->name,
                'Date'         => $cdo->date ?? '', // ✅ Ensure correct field
                'Quantity'     => is_numeric($cdo->quantity) ? (int) $cdo->quantity : 0, // ✅ Force numeric
                'Description'  => $cdo->description ?? '',
                'Serial No.'   => !in_array($cdo->ser_no, ['Unknown', 'N/A']) ? $cdo->ser_no : '', // ✅ Blank if 'Unknown' or 'N/A'
                'Status'       => !in_array($cdo->status, ['Unknown', 'N/A']) ? $cdo->status : '', // ✅ Blank if 'Unknown' or 'N/A'
            ];
        });
    }

    public function headings(): array
    {
        return ["Position", "Name", "Date", "Quantity", "Description", "Serial No.", "Status"];
    }
}
