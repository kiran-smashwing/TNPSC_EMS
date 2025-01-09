<?php


namespace App\Http\Controllers;

use App\Models\DepartmentOfficial;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialRoutes;
use App\Models\ExamMaterialsData;
use App\Models\ExamTrunkBoxOTLData;
use Illuminate\Http\Request;
use App\Models\ChartedVehicleRoute;
use App\Models\EscortStaff;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Currentexam;
class ChartedVehicleRoutesController extends Controller
{
    public function index(Request $request)
    {

        $user = $request->get('auth_user');
        $routes = ChartedVehicleRoute::with(['escortstaffs'])->get();
        // Fetching exam notifications 
        foreach ($routes as $route) {
            $examIds = $route->exam_id; // Assuming this is how you fetch the exam IDs array 
            $exams = Currentexam::whereIn('exam_main_no', $examIds)->get();
            $route->exam_notifications = $exams->pluck('exam_main_notification')->implode(', ');
        }
        // Fetching district codes 
        foreach ($routes as $route) {
            $districtCodes = $route->escortstaffs->pluck('district_code')->unique()->toArray();
            $route->district_codes = implode(', ', $districtCodes);
        }
        return view('my_exam.Charted-Vehicle.index', compact('routes'));
    }

    public function createRoute(Request $request)
    {
        // Get all exams available
        $exams = Currentexam::get();
        $tnpscStaffs = DepartmentOfficial::get();
        return view('my_exam.Charted-Vehicle.create', compact('exams', 'tnpscStaffs'));
    }

    public function storeRoute(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'route_no' => 'required|string|max:255',
            'exam_id' => 'required|array',
            'driver_name' => 'required|string|max:255',
            'driver_licence_no' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'vehicle_no' => 'required|string|max:255',
            'otl_locks' => 'required|array',
            'gps_lock' => 'required|array',
            'police_constable' => 'required|string|max:255',
            'police_constable_phone' => 'required|string|max:255',
            'escort_vehicle_no' => 'required|string|max:255',
            'escort_driver_name' => 'required|string|max:255',
            'escort_driver_licence_no' => 'required|string|max:255',
            'escort_driver_phone' => 'required|string|max:255',
        ]);

        $chartedVehicleRoute = ChartedVehicleRoute::create([
            'route_no' => $request->route_no,
            'exam_id' => $request->exam_id,
            'charted_vehicle_no' => $request->vehicle_no,
            'driver_details' => [
                'name' => $request->driver_name,
                'licence_no' => $request->driver_licence_no,
                'phone' => $request->phone
            ],
            'otl_locks' => $request->otl_locks,
            'gps_locks' => $request->gps_lock,
            'pc_details' => [
                'name' => $request->police_constable,
                'phone' => $request->police_constable_phone,
                'ifhrms_no' => $request->police_constable_ifhrms_no ?? null
            ],
            'escort_vehicle_details' => [
                'vehicle_no' => $request->escort_vehicle_no,
                'driver_name' => $request->escort_driver_name,
                'driver_licence_no' => $request->escort_driver_licence_no,
                'driver_phone' => $request->escort_driver_phone
            ]
        ]);

        foreach ($request->escortstaffs as $escortstaff) {
            EscortStaff::create([
                'charted_vehicle_id' => $chartedVehicleRoute->id,
                'district_code' => $escortstaff['district'],
                'tnpsc_staff_id' => $escortstaff['tnpsc_staff'],
                'si_details' => [
                    'name' => $escortstaff['si_name'],
                    'phone' => $escortstaff['si_phone'],
                    'ifhrms_no' => $escortstaff['si_ifhrms_no'] ?? null
                ],
                'revenue_staff_details' => [
                    'name' => $escortstaff['revenue_staff_name'],
                    'phone' => $escortstaff['revenue_phone'],
                    'ifhrms_no' => $escortstaff['revenue_ifhrms_no'] ?? null
                ]
            ]);
        }

        return redirect()->route('exam-materials-route.store')
            ->with('success', 'Charted Vehicle Route created successfully.');
    }

    public function editRoute(Request $request, $id)
    {
        $route = ChartedVehicleRoute::with('escortstaffs')->findOrFail($id);
        $tnpscStaffs = DepartmentOfficial::get();
        $exams = Currentexam::get();
        return view('my_exam.Charted-Vehicle.edit', compact('route', 'exams', 'tnpscStaffs'));
    }
    public function updateRoute(Request $request, $id)
    {
        $route = ChartedVehicleRoute::findOrFail($id);

        // Update route details
        $route->update([
            'route_no' => $request->route_no,
            'exam_id' => $request->exam_id,
            'charted_vehicle_no' => $request->vehicle_no,
            'driver_details' => [
                'name' => $request->driver_name,
                'licence_no' => $request->driver_licence_no,
                'phone' => $request->phone
            ],
            'gps_locks' => $request->gps_locks,
            'otl_locks' => $request->otl_locks,
            'pc_details' => [
                'name' => $request->police_constable,
                'phone' => $request->police_constable_phone,
                'ifhrms_no' => $request->police_constable_ifhrms_no ?? null
            ],
            'escort_vehicle_details' => [
                'vehicle_no' => $request->escort_vehicle_no,
                'driver_name' => $request->escort_driver_name,
                'driver_licence_no' => $request->escort_driver_licence_no,
                'driver_phone' => $request->escort_driver_phone
            ]
        ]);

        // Delete existing escort staff
        EscortStaff::where('charted_vehicle_id', $route->id)->delete();

        // Create new escort staff
        foreach ($request->escortstaffs as $staff) {
            EscortStaff::create([
                'charted_vehicle_id' => $route->id,
                'district_code' => $staff['district'],
                'tnpsc_staff_id' => $staff['tnpsc_staff'],
                'si_details' => [
                    'name' => $staff['si_name'],
                    'phone' => $staff['si_phone'],
                    'ifhrms_no' => $staff['si_ifhrms_no'] ?? null
                ],
                'revenue_staff_details' => [
                    'name' => $staff['revenue_staff_name'],
                    'phone' => $staff['revenue_phone'],
                    'ifhrms_no' => $staff['revenue_ifhrms_no'] ?? null
                ]
            ]);
        }

        return redirect()->back()
            ->with('success', 'Charted Vehicle Route updated successfully.');
    }

    public function viewRoute(Request $request, $id)
    {
        $route = ChartedVehicleRoute::with('escortstaffs')->findOrFail($id);
        $exams = Currentexam::get();
        $tnpscStaffs = DepartmentOfficial::get();
        return view('my_exam.Charted-Vehicle.view', compact('route', 'exams', 'tnpscStaffs'));
    }
    public function getDistrictsForExamIDs(Request $request)
    {
        $examIds = $request->exam_ids;
        $districts = ExamConfirmedHalls::whereIn('exam_id', $examIds)
            ->with('district')
            ->get()
            ->unique('district_id');

        // Prepare the response data
        $response = $districts->map(function ($hall) {
            return [
                'district_code' => $hall->district->district_code,
                'district_name' => $hall->district->district_name,
            ];
        });

        return response()->json($response);
    }

    public function downwardJourneyRoutes(Request $request)
    {
        $user = $request->get('auth_user');

        // $routes = ChartedVehicleRoute::with(['escortstaffs'])->get();

        $routes = ChartedVehicleRoute::with([
            'escortstaffs' => function ($query) use ($user) {
                $query->where('tnpsc_staff_id', $user->dept_off_id);
            }
        ])
            ->whereHas('escortstaffs', function ($query) use ($user) {
                $query->where('tnpsc_staff_id', $user->dept_off_id);
            })
            ->get();

        // Fetching exam notifications 
        foreach ($routes as $route) {
            $examIds = $route->exam_id; // Assuming this is how you fetch the exam IDs array 
            $exams = Currentexam::whereIn('exam_main_no', $examIds)->get();
            $route->exam_notifications = $exams->pluck('exam_main_notification')->implode(', ');
        }
        // Fetching district codes 
        foreach ($routes as $route) {
            $districtCodes = $route->escortstaffs->pluck('district_code')->unique()->toArray();
            $route->district_codes = implode(', ', $districtCodes);
        }

        return view('my_exam.Charted-Vehicle.downward-journey-routes', compact('routes'));
    }
    public function scanTrunkboxes(Request $request, $id)
    {
        $user = $request->get('auth_user');

        // Fetch the route with all escort staff for the given charted vehicle ID and user
        $routes = DB::table('charted_vehicle_routes as cvr')
            ->leftJoin('escort_staffs as es', 'cvr.id', '=', 'es.charted_vehicle_id')
            ->select('cvr.*', 'es.district_code') // Select only required fields
            ->where('cvr.id', $id) // Filter by charted vehicle route ID
            ->where('es.tnpsc_staff_id', $user->dept_off_id) // Filter escortstaffs by user
            ->get(); // Get all matching records

        // Extract unique districts from the routes
        $districtCodes = $routes->pluck('district_code')->unique();

        // Decode exam IDs (assuming they are consistent across all routes)
        $examIds = isset($routes[0]) ? json_decode($routes[0]->exam_id, true) : [];

        // Ensure exam IDs are valid
        if (!is_array($examIds)) {
            $examIds = [];
        }

        // Fetch trunk boxes for all exam IDs and districts
        $trunkBoxes = DB::table('exam_trunkbox_otl_data')
            ->whereIn('exam_id', $examIds) // Match exam IDs
            ->whereIn('district_code', $districtCodes) // Match district codes
            ->get(); // Get all matching trunk boxes
        // dd($trunkBoxes);



        $exams = Currentexam::get();
        $tnpscStaffs = DepartmentOfficial::get();
        return view('my_exam.Charted-Vehicle.scan-trunkbox', compact('trunkBoxes', 'exams', 'tnpscStaffs'));
    }
    public function generateTrunkboxOrder($id)
    {
        // Fetch the route with all escort staff for the given charted vehicle ID and user
        $routes = DB::table('charted_vehicle_routes as cvr')
            ->leftJoin('escort_staffs as es', 'cvr.id', '=', 'es.charted_vehicle_id')
            ->select('cvr.*', 'es.district_code') // Select only required fields
            ->where('cvr.id', $id) // Filter by charted vehicle route ID
            ->get(); // Get all matching records

        // Extract unique districts from the routes
        $districtCodes = $routes->pluck('district_code')->unique();

        // Decode exam IDs (assuming they are consistent across all routes)
        $examIds = isset($routes[0]) ? json_decode($routes[0]->exam_id, true) : [];

        // Ensure exam IDs are valid
        if (!is_array($examIds)) {
            $examIds = [];
        }

        // Fetch trunk boxes for all exam IDs and districts
        $trunkBoxes = DB::table('exam_trunkbox_otl_data')
            ->whereIn('exam_id', $examIds) // Match exam IDs
            ->whereIn('district_code', $districtCodes) // Match district codes
            ->get(); // Get all matching trunk boxes

        // Group trunk boxes by center_id
        $groupedByCenter = $trunkBoxes->groupBy('center_code');

        // Sort centers numerically
        $sortedCenters = $groupedByCenter->sortKeysUsing(function ($keyA, $keyB) {
            return (int) $keyA <=> (int) $keyB;
        });

        // Initialize variables
        $orderedTrunkBoxes = [];
        $orderCounter = 1; // Start ordering from 1

        foreach ($sortedCenters as $center => $trunkBoxes) {
            // Randomize the order of trunk boxes within this center
            $shuffledBoxes = $trunkBoxes->shuffle();

            // Assign new order and append to orderedTrunkBoxes
            foreach ($shuffledBoxes as $trunkBox) {
                $trunkBox->load_order = $orderCounter++;
                $orderedTrunkBoxes[] = (array) $trunkBox; // Convert to array for bulk insert/update
            }
        }

        // Update the trunk boxes in the database
        DB::table('exam_trunkbox_otl_data')
            ->upsert($orderedTrunkBoxes, ['id'], ['load_order']);

        // Prepare CSV data
        $csvData = [];
        $csvData[] = ['Center Code', 'District Code', 'Hall Code', 'Trunk Box Code', 'Load Order'];

        foreach ($orderedTrunkBoxes as $box) {
            $csvData[] = [
                $box['center_code'] ?? '',
                $box['district_code'] ?? '',
                $box['hall_code'] ?? '',
                $box['trunk_box_code'] ?? '',
                $box['load_order'] ?? '',
            ];
        }

        // File storage setup
        $fileName = 'trunkbox_order_' . date('Y_m_d_H_i_s') . '.csv';
        $filePath = storage_path("app/public/{$fileName}");

        // Open the file for writing
        $handle = fopen($filePath, 'w');

        // Add column headers
        fputcsv($handle, ['Load Order' ,'Center Code', 'District Code', 'Hall Code', 'Trunk Box Code' ]);

        // Loop through the trunk boxes and write rows
        foreach ($orderedTrunkBoxes as $box) {
            // Prepare the row with tab prefixes to preserve formatting
            $row = array_map(function ($value) {
                return "\t" . $value; // Adding a tab to force Excel to treat as text
            }, [
                $box['load_order'],
                $box['center_code'],
                $box['district_code'],
                $box['hall_code'],
                $box['trunkbox_qr_code']
            ]);

            // Write the row to the CSV file
            fputcsv($handle, $row);
        }

        // Close the file
        fclose($handle);

        // Cleanup old files in the public storage directory
        $files = glob(storage_path('app/public/trunkbox_order_*.csv'));
        $now = time();
        foreach ($files as $file) {
            if (filemtime($file) < $now - 3600) { // Files older than 1 hour
                unlink($file);
            }
        }

        // Return session with the link to the file
        return redirect()->back()->with('success', 'Trunk boxes have been ordered successfully. <a href="' . asset("storage/{$fileName}") . '" target="_blank">Download CSV</a>');
    }

}