<?php

namespace App\Imports;

use App\Models\Cdo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Carbon\Carbon;

class CdoImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        \Log::info('Importing row: ' . json_encode($row));
    
        return new Cdo([
            'position'      => $this->sanitizePosition($row),
            'name'          => $row['name'] ?? 'UNKNOWN',
            'date'          => $this->formatDate($row['date'] ?? null),
            'quantity'      => is_numeric($row['quantity']) ? (int) $row['quantity'] : 0,
            'description'   => $row['description'] ?? 'No Description',
            'ser_no'        => $this->sanitizeSerialNo($row['serial_no'] ?? null),
            'status'        => $row['status'] ?? 'N/A',
        ]);
    }

    /**
     * ✅ Handle multiple possible column names for 'position'.
     */
    private function sanitizePosition($row)
    {
        $possibleKeys = ['id_number', 'position', 'ID', 'ID Number'];
        foreach ($possibleKeys as $key) {
            if (!empty($row[$key])) {
                return trim(strtoupper($row[$key])); // Convert to uppercase and trim spaces
            }
        }
        return 'UNKNOWN';
    }

    /**
     * ✅ Convert different date formats to 'Y-m-d'
     */
    private function formatDate($value)
    {
        if (!$value) {
            return null;
        }

        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Return null if invalid
        }
    }

    /**
     * ✅ Convert 'N/A' or empty serial_no to null
     */
    private function sanitizeSerialNo($value)
    {
        return (empty($value) || strtoupper($value) === 'N/A') ? null : trim($value);
    }
}
