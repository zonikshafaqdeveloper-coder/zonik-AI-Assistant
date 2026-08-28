<?php

namespace App\Exports;

use App\Models\Pincode;
use App\Models\ZoneProcessing; // Add the ZoneProcessing model
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class PincodeExport implements FromCollection
{
    public function collection()
    {
        $pincodeData = Pincode::all();
        $collection = new Collection([
            [
                'zone_name' => 'Zone Name',
                'pincode' => 'Pincode',
                'ID' => 'ID',
            ]
        ]);

        foreach ($pincodeData as $pincode) {
            $zoneName = ZoneProcessing::where('id', $pincode->zone_id)->value('zone_name');
            $collection->push([
                'zone_name' => $zoneName,
                'pincode' => $pincode->pincode,
                'ID' => $pincode->id,
            ]);
        }

        return $collection;
    }
}
