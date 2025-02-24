<?php


namespace App\Http\Controllers;

use App\Models\DepartmentOfficial;
use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialRoutes;
use App\Models\ExamMaterialsData;
use App\Models\ExamTrunkBoxOTLData;
use App\Models\ExamTrunkBoxScan;
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

        return redirect()->route('charted-vehicle-routes.index')
            ->with('success', 'Charted Vehicle Route created successfully.');
    }

    public function editRoute(Request $request, $id)
    {
        $user = $request->get('auth_user');

        if ($user->role && $user->role->role_department == 'ID') {
            $route = ChartedVehicleRoute::with('escortstaffs')->findOrFail($id);
        } else {
            $route = ChartedVehicleRoute::with([
                'escortstaffs' => function ($query) use ($user) {
                    $query->where('tnpsc_staff_id', $user->dept_off_id); // Filter escortstaffs where tnpsc_staff_id matches the user's ID
                }
            ])->where('id', $id)->first();
        }

        $tnpscStaffs = DepartmentOfficial::get();
        $exams = Currentexam::get();
        return view('my_exam.Charted-Vehicle.edit', compact('route', 'exams', 'tnpscStaffs'));
    }

    public function updateRoute(Request $request, $id)
    {
        $route = ChartedVehicleRoute::findOrFail($id);
        $user = $request->get('auth_user');

        if ($user->role && $user->role->role_department == 'ID') {

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
        } else {

            // Update escort staff details only 
            foreach ($request->escortstaffs as $staff) {
                $escortstaff = EscortStaff::where('charted_vehicle_id', $route->id)
                    ->where('tnpsc_staff_id', $user->dept_off_id) // Filter escortstaffs where tnpsc_staff_id matches the user's ID
                    ->first();

                if ($escortstaff) {
                    $escortstaff->update([
                        'district_code' => $staff['district'],
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
            }

        }
        return redirect()->route('charted-vehicle-routes.index')
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
        ->groupBy('district.district_code') // Ensure unique districts
        ->map(function ($group) {
            $hall = $group->first(); // Get first record in each group
            return [
                'district_code' => $hall->district->district_code,
                'district_name' => $hall->district->district_name,
            ];
        })
        ->values(); // Reset array keys to numeric

    return response()->json($districts);
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

    public function viewTrunkboxes(Request $request, $id)
    {
        $user = $request->get('auth_user');
        $query = DB::table('charted_vehicle_routes as cvr')
            ->leftJoin('escort_staffs as es', 'cvr.id', '=', 'es.charted_vehicle_id')
            ->select('cvr.*', 'es.district_code')
            ->where('cvr.id', $id);
        //TODO: update the user department to required 
        if ($user->role && !in_array($user->role->role_department, ['ED', 'QD'])) {
    	// Apply additional condition only if the user's department is not 'ID'
   		 $query->where('es.tnpsc_staff_id', $user->dept_off_id);
		}

        // Execute the query
        $routes = $query->get();

        // Extract unique districts from the routes
        $districtCodes = $routes->pluck('district_code')->unique();
        // Decode exam IDs (assuming they are consistent across all routes)
        $examIds = isset($routes[0]) ? json_decode($routes[0]->exam_id, true) : [];

        // Ensure exam IDs are valid
        if (!is_array($examIds)) {
            $examIds = [];
        }

        // Determine the order direction based on the role
      $orderDirection = ($user->role && in_array($user->role->role_department, ['ED', 'QD'])) ? 'desc' : 'asc';

        // Fetch trunk boxes for all exam IDs and districts, with conditional ordering
        $trunkBoxes = DB::table('exam_trunkbox_otl_data as e')
            ->leftJoin('exam_trunkbox_scans as s', 'e.id', '=', 's.exam_trunkbox_id') // Join with scans table
            ->whereIn('e.exam_id', $examIds) // Match exam IDs
            ->whereIn('e.district_code', $districtCodes) // Match district codes
            ->orderByRaw('e.load_order::INTEGER ' . $orderDirection) // PostgreSQL integer sorting
            ->get(); // Get all matching trunk boxes
        //total number of trunk boxes found for this user
        $totalTrunkBoxes = $trunkBoxes->count();
        // Total number of trunk boxes scanned by the user
        $totalScanned = $trunkBoxes->filter(
            fn($examMaterial) => !is_null(
                value: $examMaterial->{
                     $user->role && in_array($user->role->role_department, ['ED', 'QD'])
                    ? 'hq_scanned_at'
                    : 'dept_off_scanned_at'
                    }
            )
        )->count();
        $myroute = ChartedVehicleRoute::where('id', $id)->first();

        return view('my_exam.Charted-Vehicle.scan-trunkbox', compact('trunkBoxes', 'user', 'myroute', 'totalTrunkBoxes', 'totalScanned'));
    }

    public function scanTrunkboxOrder(Request $request)
    {
        // Validate request
        $request->validate([
            'qr_code' => 'required|string',
            'exam_id' => 'required|string',
        ]);

        // Decode exam_id from JSON string
        $decodedExamIds = json_decode(htmlspecialchars_decode($request->exam_id), true);

        // Ensure the decoded value is an array
        if (!is_array($decodedExamIds)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid exam_id format'
            ], 400); // 400 for bad request
        }

        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Check authorization
        if ($role !== 'headquarters' || !$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found or not authorized'
            ], 403); // 403 is for authorization errors
        }

        // Find exam materials matching qr_code and any of the exam_id values
        $examMaterials = ExamTrunkBoxOTLData::where('trunkbox_qr_code', $request->qr_code)
            ->whereIn('exam_id', $decodedExamIds)
            ->first();

        if (!$examMaterials) {
            // Try to find the trunk box with the original exam_id and qr_code
            $examMaterials = ExamTrunkBoxOTLData::where('trunkbox_qr_code', $request->qr_code)
                ->whereIn('exam_id', $decodedExamIds)
                ->with('center')
                ->with('district')
                ->first();

            $msg = "This QR Code belongs to the following District: " .
                $examMaterials->district->district_name .
                ", Center: " . $examMaterials->center->center_name .
                ", Hall Code: " . $examMaterials->hall_code;

            return response()->json([
                'status' => 'error',
                'message' => $msg
            ], 404);
        }

        // Get the previous trunk box in the load order
           $previousTrunkBox = ExamTrunkBoxOTLData::where('exam_id', $examMaterials->exam_id)
        ->where('district_code', $examMaterials->district_code)
        ->where('center_code', $examMaterials->center_code)
        ->where('load_order', $examMaterials->load_order - 1) // Always check the immediate previous load_order
        ->first();
    

        // Check if the previous trunk box was scanned
        if ($previousTrunkBox && !ExamTrunkBoxScan::where('exam_trunkbox_id', $previousTrunkBox->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please scan trunk boxes in the correct order. The previous trunk box has not been scanned yet.',
            ], 400);
        }

        // Check if the current trunk box is already scanned
        if (ExamTrunkBoxScan::where('exam_trunkbox_id', $examMaterials->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR code has already been scanned.',
            ], 409);
        }

        // Create scan record
        ExamTrunkBoxScan::create([
            'exam_trunkbox_id' => $examMaterials->id,
            'dept_off_scanned_at' => now()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'QR code scanned successfully.',
        ], 200);
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
        fputcsv($handle, ['Load Order', 'Center Code', 'District Code', 'Hall Code', 'Trunk Box Code']);

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
