<?php

namespace App\Http\Controllers;

use App\Models\ChartedVehicleRoute;
use App\Models\Currentexam;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class Vehicle_SecurityController extends Controller
{
    public function generateVehicleReport()
    {
        $vehicle_id = 13;
        $vehicle_no_details = ChartedVehicleRoute::with('escortstaffs.district')  // Load the 'district' relation on 'escortstaffs'
            ->where('id', $vehicle_id)
            ->first();

        // If no data is found, handle it
        if (!$vehicle_no_details) {
            return abort(404, 'Vehicle details not found.');
        }

        // Get exam_id and make sure it's a string before processing
        $exam_id_string = $vehicle_no_details->exam_id;

        // Ensure it's not null and is a string
        if (!empty($exam_id_string)) {
            // Handle the case if the exam_id is stored in a non-standard format
            if (is_string($exam_id_string)) {
                // Case where the exam IDs are in a comma-separated string (like "{1,2,3}")
                $processed_exam_ids = explode(',', trim($exam_id_string, '{}[]'));
            } else {
                // If the string is already an array, directly use it
                $processed_exam_ids = (array) $exam_id_string;
            }
        } else {
            // Handle case where exam_id_string is empty or null
            return abort(404, 'No valid exam IDs found.');
        }

        // Ensure we have valid IDs before querying
        if (empty($processed_exam_ids)) {
            return abort(404, 'No valid exam IDs found.');
        }

        // Fetch exam data matching the IDs
        $exam_data = Currentexam::with('examsession')  // Make sure the relation `examsession` is loaded here
            ->whereIn('exam_main_no', $processed_exam_ids) // Fetch matching records
            ->get(); // Retrieve multiple records

        // Check if no data was found
        if ($exam_data->isEmpty()) {
            return abort(404, 'No exam data found for the given IDs.');
        }

        // Extract `exam_main_name`, `exam_main_startdate`, and related `district_name`
        $exam_names = [];
        $exam_dates = [];
        $district_names = [];

        foreach ($exam_data as $exam) {
            $exam_names[] = $exam->exam_main_name;
            $exam_dates[] = $exam->exam_main_startdate;

            // Check if `escortstaffs` relation exists and is not empty
            if ($vehicle_no_details->escortstaffs && $vehicle_no_details->escortstaffs->isNotEmpty()) {
                foreach ($vehicle_no_details->escortstaffs as $escort) {
                    // Access the district name using the 'district' relation
                    if ($escort->district) {
                        $district_names[] = $escort->district->district_name;
                    }
                }
            }
        }

        // Remove duplicates by using array_unique
        $district_names_string = implode(', ', array_unique($district_names));

        // Create comma-separated strings for names and dates
        $exam_names_string = implode(', ', $exam_names);
        $exam_dates_string = implode(', ', $exam_dates);

        // Debugging - Show both comma-separated strings
        // dd([
        //     'exam_names' => $exam_names_string,
        //     'exam_dates' => $exam_dates_string,
        //     'district_names' => $district_names_string,
        // ]);

        // Pass data to the Blade view
        $html = view('PDF.Vehicle.vehicle-security-checklist', compact('vehicle_no_details', 'exam_data', 'exam_names_string', 'exam_dates_string', 'district_names_string'))->render();

        // Generate PDF
        $pdf = Browsershot::html($html)
            ->setOption('landscape', false)
            ->setOption('margin', ['top' => '10mm', 'right' => '10mm', 'bottom' => '10mm', 'left' => '10mm'])
            ->setOption('displayHeaderFooter', true)
            ->setOption('headerTemplate', '<div></div>')
            ->setOption('footerTemplate', '
    <div style="font-size:10px;width:100%;text-align:center;">
        Page <span class="pageNumber"></span> of <span class="totalPages"></span>
    </div>
    <div style="position: absolute; bottom: 5mm; right: 10px; font-size: 10px;">
        IP: ' . $_SERVER['REMOTE_ADDR'] . ' | Timestamp: ' . date('d-m-Y H:i:s') . ' 
    </div>')
            ->setOption('preferCSSPageSize', true)
            ->setOption('printBackground', true)
            ->scale(1)
            ->format('A4')
            ->pdf();

        // Define filename and return PDF
        $filename = 'vehicle_security_checklist_' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
