<?php

namespace App\Exports;

use App\Models\AwolRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AwolExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return AwolRecord::all()->map(function ($awol) { // ✅ Use lowercase $cdo
            return [
                'Position'     => $awol->position,
                'Name'         => $awol->name,
                'Date'         => $awol->date ?? '', // ✅ Ensure correct field
                'Quantity'     => is_numeric($awol->quantity) ? (int) $awol->quantity : 0, // ✅ Force numeric
                'Description'  => $awol->description ?? '',
                'Serial No.'   => !in_array($awol->ser_no, ['Unknown', 'N/A']) ? $awol->ser_no : '', // ✅ Blank if 'Unknown' or 'N/A'
                'Status'       => !in_array($awol->status, ['Unknown', 'N/A']) ? $awol->status : '', // ✅ Blank if 'Unknown' or 'N/A'
            ];
        });
    }

    public function headings(): array
    {
        return ["Position", "Name", "Date", "Quantity", "Description", "Serial No.", "Status"];
    }
}
