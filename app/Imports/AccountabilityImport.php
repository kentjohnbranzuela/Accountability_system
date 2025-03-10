<?php

namespace App\Imports;

use App\Models\AccountabilityRecord;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Carbon\Carbon;

class AccountabilityImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    

    return new AccountabilityRecord([
        'id_number'   => $row['id_number'] ?? null,  
        'name'        => $row['name'] ?? 'Unknown',
        'date'        => isset($row['date']) ? $this->transformDate($row['date']) : now(),
        'quantity'    => is_numeric($row['quantity']) ? intval($row['quantity']) : 0,
        'description'  => empty($row['description']) ? null : $row['description'], // Allow null // Allow NULL
       'ser_no' => empty($row['serial_no']) || $row['serial_no'] === 'N/A' ? null : $row['serial_no'],
        'status'      => $row['status'] ?? 'Unknown', 
    ]);
}

    /**
     * Convert Excel serial number or text date to a valid MySQL date format.
     */
    public function transformDate($date)
    {
        if (!$date) {
            return now()->format('Y-m-d'); // Default to today if null
        }

        // Check if the date is a numeric serial (Excel format)
        if (is_numeric($date)) {
            return Carbon::instance(Date::excelToDateTimeObject($date))->format('Y-m-d');
        }

        // Remove day name if present (e.g., "Wednesday, 21 February 2024")
        $cleaned = preg_replace('/^[A-Za-z]+,\s*/', '', $date);

        // Convert to standard format
        try {
            return Carbon::createFromFormat('d F Y', $cleaned)->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->format('Y-m-d'); // Default to today if error
        }
    }
}

