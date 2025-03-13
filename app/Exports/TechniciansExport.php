<?php

namespace App\Exports;

use App\Models\Technician;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TechniciansExport implements FromCollection, WithHeadings
{
        public function collection()
        {
            return Technician::all()->map(function ($technician) {
                return [
                    'Position' => $technician->position,
                    'Name' => $technician->name,
                    'Date' => $technician->date,
                    'Quantity' => $technician->quantity,
                    'Description' => $technician->description,
                    'Serial No.' => $technician->ser_no,
                    'Status' => $technician->status === 'Unknown' ? '' : $technician->status, // Replaces "Unknown" with blank
                ];
            });
        }
        public function headings(): array
        {
            return ["Position","name","Date", "Quantity", "Description", "Serial No.", "Status"];
        }
    }

