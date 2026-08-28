<?php

namespace App\Imports;

use App\Models\Pincode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\ZoneProcessing;

class PincodeImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $zoneName = $row['zone_name'];
        $pincode = $row['pincode'];

        $zone = ZoneProcessing::where('zone_name', $zoneName)->first();

        if (!$zone) {
            // If the zone doesn't exist, create a new one
            $zone = new ZoneProcessing();
            $zone->zone_name = $zoneName;
            $zone->save();
        }

        // Now we have the zone, let's get the zone_id
        $zoneId = $zone->id;

        // Check if pincode already exists
        $existingPincode = Pincode::where('pincode', $pincode)->first();

        if ($existingPincode) {
            // Update existing pincode with zone_id
            $existingPincode->zone_id = $zoneId;
            $existingPincode->save();
            return $existingPincode;
        }

        // Create a new pincode entry
        $pincodeModel = new Pincode([
            'pincode' => $pincode,
            'zone_id' => $zoneId,
        ]);
        $pincodeModel->save();

        return $pincodeModel;
    }
}
