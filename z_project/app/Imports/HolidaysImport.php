<?php
namespace App\Imports;

use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Holiday;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HolidaysImport implements ToModel
{
    public function model(array $row)
    {
        // Ensure the row has at least 3 columns (ID, holiday_date, holiday_name)
        if (!isset($row[1]) || !isset($row[2])) {
            return null; // Skip if columns are missing
        }

        // Convert Excel numeric date format to 'YYYY-MM-DD'
        if (is_numeric($row[1])) {
            $holidayDate = Carbon::instance(Date::excelToDateTimeObject($row[1]))->format('Y-m-d');
        } else {
            try {
                $holidayDate = Carbon::parse($row[1])->format('Y-m-d');
            } catch (\Exception $e) {
                return null; // Skip invalid dates
            }
        }

        // Check if holiday already exists
        $holiday = Holiday::where('holiday_date', $holidayDate)->first();

        if ($holiday) {
            // Update holiday name if date exists
            $holiday->update([
                'holiday_name' => $row[2] ?? 'Unnamed Holiday',
            ]);
        } else {
            // Insert a new holiday if date doesn't exist
            $holiday = Holiday::create([
                'holiday_date' => $holidayDate,
                'holiday_name' => $row[2] ?? 'Unnamed Holiday',
            ]);
        }

        return $holiday;
    }
}
