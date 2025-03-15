<?php
namespace App\Exports;

use App\Models\Gingoog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class GingoogExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Gingoog::select([
            'position', 
            'name', 
            'date', 
            'quantity', 
            'description', 
            'ser_no', 
            'status'
        ])->get()->map(function ($gingoogs) {
            return [
                'Position' => $gingoogs->position,
                'Name' => $gingoogs->name,
                'Date' => $gingoogs->date,
                'Quantity' => $gingoogs->quantity,
                'Description' => $gingoogs->description,
                'Serial No' => $gingoogs->ser_no,
                'Status' => $gingoogs->status === 'Unknown' ? '' : $gingoogs->status, // Similar fix to your technicians export
            ];
        });
    }

    public function headings(): array
    {
        return ['Position', 'Name', 'Date', 'Quantity', 'Description', 'Serial No', 'Status'];
    }
}
