<?php

namespace App\Imports;

use App\Models\AwolRecord;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AwolImport implements ToModel, WithHeadingRow
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
        Log::info('Normalized Row: ', $normalizedRow);

        return new AwolRecord([
            'position'   => $normalizedRow['position'] ?? 'Unknown',
            'name'       => $normalizedRow['name'] ?? 'Unknown',
            'date'       => isset($normalizedRow['date']) ? $this->transformDate($normalizedRow['date']) : now(),
            'quantity'   => is_numeric($normalizedRow['quantity']) ? intval($normalizedRow['quantity']) : 0,
            'description'=> $normalizedRow['description'] ?? 'N/A',
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
            return null;
        }

        // Check if the date is a numeric serial (Excel format)
        if (is_numeric($date)) {
            try {
                return Carbon::instance(Date::excelToDateTimeObject($date))->format('Y-m-d');
            } catch (\Exception $e) {
                return null;
            }
        }

        // Remove day name if present (e.g., "Wednesday, 21 February 2024")
        $cleaned = preg_replace('/^[A-Za-z]+,\s*/', '', $date);

        // Try different date formats
        $formats = ['d F Y', 'Y-m-d', 'm/d/Y', 'd/m/Y'];

        foreach ($formats as $format) {
            try {
                return Carbon::createFromFormat($format, $cleaned)->format('Y-m-d');
            } catch (\Exception $e) {
                continue;
            }
        }

        return null;
    }
}
