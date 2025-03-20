<?php

namespace App\Imports;

use App\Models\TurnOver;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TurnOverImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Normalize column names (convert to lowercase and remove spaces/underscores)
        $normalizedRow = [];
        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(str_replace([' ', '_'], '', trim($key)));
            $normalizedRow[$normalizedKey] = $value;
        }

        // Log column names for debugging
        \Log::info('Normalized Row: ', $normalizedRow);

        return new TurnOver([
            'position'   => $normalizedRow['idnumber'] ?? $normalizedRow['position'] ?? null,
            'name'       => $normalizedRow['name'] ?? 'Unknown',
            'date'       => isset($normalizedRow['date']) ? $this->transformDate($normalizedRow['date']) : now(),
            'quantity'   => is_numeric($normalizedRow['quantity']) ? intval($normalizedRow['quantity']) : 0,
            'description'=> empty($normalizedRow['description']) ? 'N/A' : $normalizedRow['description'],
            'ser_no'     => $normalizedRow['serno'] ?? $normalizedRow['serialno'] ?? null,
            'status'     => $normalizedRow['status'] ?? 'Unknown',
        ]);
    }

    /**
     * Convert Excel serial number or text date to a valid MySQL date format.
     */
    public function transformDate($date)
    {
        if (!$date) {
            return null; // Return null if empty
        }

        // Check if the date is a numeric serial (Excel format)
        if (is_numeric($date)) {
            try {
                return Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date))->format('Y-m-d');
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
