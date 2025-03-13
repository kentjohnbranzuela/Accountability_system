<?php

namespace App\Exports;

use App\Models\AccountabilityRecord;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


class BROExport implements FromCollection, WithHeadings
    {
        public function collection()
        {
            return AccountabilityRecord::all()->map(function ($AccountabilityRecord) {
                return [
                    'Position' => $AccountabilityRecord->id_number,
                    'Name' => $AccountabilityRecord->name,
                    'Date' => $AccountabilityRecord->date,
                    'Quantity' => $AccountabilityRecord->quantity,
                    'Description' => $AccountabilityRecord->description,
                    'Serial No.' => $AccountabilityRecord->ser_no,
                    'Status' => $AccountabilityRecord->status === 'Unknown' ? '' : $AccountabilityRecord->status, // Replaces "Unknown" with blank
                ];
            });
        }
        public function headings(): array
    {
        return ["Position","name","Date", "Quantity", "Description", "Serial No.", "Status"];
    }
}

