<?php

namespace App\Exports;

use App\Models\ToolsRequest;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ToolsRequestExport implements FromCollection, WithHeadings
{

    public function collection()
    {
        return ToolsRequest::all()->map(function ($toolsrequest) {
            return [
                'Position' => $toolsrequest->position,
                'Name' => $toolsrequest->name,
                'Date' => $toolsrequest->date,
                'Quantity' => $toolsrequest->quantity,
                'Description' => $toolsrequest->description,
                'Serial No.' => $toolsrequest->ser_no,
                'Status' => $toolsrequest->status === 'Unknown' ? '' : $toolsrequest->status, // Replaces "Unknown" with blank
            ];
        });
    }

    public function headings(): array
    {
        return ["Position", "Name", "Date", "Quantity", "Description", "Serial No.", "Status"];
    }
}
