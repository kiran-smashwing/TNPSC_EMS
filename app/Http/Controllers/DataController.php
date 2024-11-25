<?php
namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\District;
use Hash;

class DataController extends Controller
{
    public function addData()
    {
        $jsonData = file_get_contents(public_path('centers.json'));
        $centers = json_decode($jsonData, true);

        foreach ($centers as $center) {
            if ($center['code'] == $center['parent_code']) {
                District::create([
                    'district_name' => $center['name'],
                    'district_code' => $center['code'],
                    'district_email' => $center['contact'],
                    'district_phone' => "0000000000",
                    'district_alternate_phone' => "0000000000",
                    'district_password' => Hash::make('password'),
                    'district_website' => "www." . $center['name'] . ".com",
                    'district_address' => $center['address'],
                    'district_longitude' => "78.0000",
                    'district_latitude' => "10.0000",
                    'district_image' => null
                ]);
            } else {
                Center::create([
                    'center_district_id' => $center['parent_code'],
                    'center_name' => $center['name'],
                    'center_code' => $center['code'],
                    'center_email' => $center['contact'],
                    'center_phone' => "0000000000",
                    'center_alternate_phone' => "1234567890",
                    'center_password' => Hash::make('password'),
                    'center_address' => $center['address'],
                    'center_longitude' => "78.0000",
                    'center_latitude' => "10.0000",
                    'center_image' => null
                ]);
            }
        }

    }

}
