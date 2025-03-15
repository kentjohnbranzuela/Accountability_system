<?php

namespace App\Imports;

use App\Models\Technician;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class TechnicianImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
{
    \Log::info('Imported Row: ', $row); // Debugging line to check imported data

    return new Technician([
       'position'   => $row['position'] ?? null,
        'name'        => $row['name'] ?? 'Unknown',
        'date'        => isset($row['date']) ? $this->transformDate($row['date']) : now(),
        'quantity'    => is_numeric($row['quantity']) ? intval($row['quantity']) : 0,
        'description'  => empty($row['description']) ? 'N/A' : $row['description'], // FIXED: Replace empty/NULL with "N/A"
        'ser_no' => empty($row['ser_no'] ?? $row['Serial No.'] ?? null) ? null : ($row['ser_no'] ?? $row['Serial No.']),
        'status'      => $row['status'] ?? 'Unknown',

    ]);
}
    /**
     * Convert Excel serial number or text date to a valid MySQL date format.
     */
    public function transformDate($date)
    {
        if (!$date) {
            return null; // Return null instead of defaulting to today
        }

        // Check if the date is a numeric serial (Excel format)
        if (is_numeric($date)) {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($date))->format('Y-m-d');
            } catch (\Exception $e) {
                return null; // Return null if conversion fails
            }
        }

        // Remove day name if present (e.g., "Wednesday, 21 February 2024")
        $cleaned = preg_replace('/^[A-Za-z]+,\s*/', '', $date);

        // Try different possible date formats
        $formats = [
            'd F Y', // 21 February 2024
            'Y-m-d', // 2024-02-21
            'm/d/Y', // 02/21/2024 (US format)
            'd/m/Y', // 21/02/2024 (EU format)
        ];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $cleaned)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        return null; // Return null if none of the formats work
    }
}

