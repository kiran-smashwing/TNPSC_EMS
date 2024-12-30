<?php

namespace App\Http\Controllers;

use App\Models\DepartmentOfficial;
use App\Models\ExamMaterialRoutes;
use App\Models\ExamMaterialsData;
use App\Models\ExamSession;
use App\Models\MobileTeamStaffs;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Currentexam;
use Spatie\Browsershot\Browsershot;
class ExamMaterialsRouteController extends Controller
{
    public function index(Request $request, $examId)
    {
        $user = $request->get('auth_user');
        // Get all mobile team staffs for the user's district br role based
        $role = session('auth_role');
        $district_code = null;
        // chennai disitrict van duty staff route creation 
        if (($role == 'headquarters' && $user->role->role_department == 'ID') || $user->district_code == '01') {
            // Initialize query builder with base query
            $district_code = '01';
            $query = ExamMaterialRoutes::where('exam_id', $examId)
                ->where('district_code', $district_code)
                ->with('department_official');
        } else {
            // Initialize query builder with base query
            $district_code = $user->district_code;
            $query = ExamMaterialRoutes::where('exam_id', $examId)
                ->where('district_code', $district_code)
                ->with('mobileteam');
        }

        // Apply filters conditionally
        if ($request->has('centerCode') && !empty($request->centerCode)) {
            $query->where('center_code', $request->centerCode);
        }
        if ($request->has('examDate') && !empty($request->examDate)) {
            // Convert date from dd-mm-yyyy to yyyy-mm-dd
            $examDate = \DateTime::createFromFormat('d-m-Y', $request->examDate)->format('Y-m-d');
            $query->whereDate('exam_date', $examDate);
        }

        // Execute the query and fetch results
        $routes = $query->get();

        // Prepare data - Each center will be a separate row
        $routeData = [];
        foreach ($routes as $route) {
            // Get halls array from JSON
            $hallCodes = is_array($route->hall_code) ? $route->hall_code : json_decode($route->hall_code, true);

            // Fetch venue data for all halls in this center
            $halls = ExamMaterialsData::select('exam_materials_data.*', 'venue.venue_name as venue_name')
                ->join('venue', 'exam_materials_data.venue_code', '=', 'venue.venue_code')
                ->where('exam_materials_data.exam_id', $examId)
                ->where('exam_materials_data.district_code', $district_code)
                ->where('exam_materials_data.center_code', $route->center_code)
                ->whereIn('exam_materials_data.hall_code', $hallCodes)
                ->get();


            $uniqueVenues = $halls->pluck('venue_name')->unique()->implode(', ');

            // Create a unique key combining route number and center code
            $key = $route->route_no . '-' . $route->center_code;

            $routeData[$key] = [
                'id' => $route->id,
                'route_no' => $route->route_no,
                'driver_name' => $route->driver_name,
                'driver_license' => $route->driver_license,
                'driver_phone' => $route->driver_phone,
                'vehicle_no' => $route->vehicle_no,
                'mobileteam' => (session('auth_role') == 'district' && $user->district_code != '01') ? $route->mobileteam : $route->department_official,
                'halls' => $uniqueVenues,
                'center_code' => $route->center_code,
                'district_code' => $route->district_code,
                'exam_date' => $route->exam_date,
            ];
        }
        //get centers from table grouped and send to index
        $centers = ExamMaterialRoutes::where('exam_id', $examId)
            ->where('district_code', $district_code)
            ->join('centers', 'exam_material_routes.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        //get districts from table grouped and send to index
        // $districts = ExamMaterialRoutes::where('exam_id', $examId)
        //     ->where('district_code', $district_code)
        //     ->join('district', 'exam_material_routes.district_code', '=', 'district.district_code')
        //     ->groupBy('district.district_code', 'district.district_name')
        //     ->select('district.district_name')
        //     ->get();
        // Get current exam session details
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();
        // Group exam sessions by date
        $examDates = $session->examsession->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys(); // Get only the keys (exam dates)
        // dd($centers);


        return view('my_exam.District.materials-route.index', [
            'examId' => $examId,
            'groupedRoutes' => $routeData,
            'centers' => $centers,
            // 'districts' => $districts,
            'examDates' => $examDates,
            'user' => $user,
        ]);
    }

    public function createRoute(Request $request, $examId)
    {
        // Get authenticated user
        $user = $request->get('auth_user');
        // Get all mobile team staffs for the user's district br role based
        $role = session('auth_role');
        $district_code = null;
        // dd($role);
        // chennai disitrict van duty staff route creation 
        if (($role == 'headquarters' && $user->role->role_department == 'ID') || $user->district_code == '01') {
            $role = Role::where('role_name', 'Van Duty Staff')->first();
            $mobileTeam = DepartmentOfficial::where('dept_off_role', $role->role_id)->get();
            $district_code = '01';
        } else {
            // get mobile team staffs for the user's district br role based
            $district_code = $user->district_code;
            $mobileTeam = MobileTeamStaffs::where('mobile_district_id', $user->district_code)->get();
        }

        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        // Get all hall codes grouped by center code within the user's district
        $halls = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('exam_materials_data.center_code', 'centers.center_name', 'exam_materials_data.hall_code', )
            ->select(
                'centers.center_name',
                'exam_materials_data.center_code',
                'exam_materials_data.hall_code'
            )
            ->orderBy('exam_materials_data.center_code') // Optional: Order by center code
            ->get();
        // Get current exam session details
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();

        // Group exam sessions by date
        $examDates = $session->examsession->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys(); // Get only the keys (exam dates)


        return view('my_exam.District.materials-route.create', compact('examId', 'mobileTeam', 'centers', 'halls', 'examDates', 'user'));
    }

    public function storeRoute(Request $request)
    {
        // Get authenticated user
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;
        $user = $guard ? $guard->user() : null;

        // Validate request
        $validated = $request->validate([
            'exam_id' => 'required',
            'exam_date' => 'required',
            'route_no' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $examDate = \DateTime::createFromFormat('d-m-Y', $request->exam_date)->format('Y-m-d');
                    $exists = ExamMaterialRoutes::where('exam_id', $request->exam_id)
                        ->where('route_no', $value)
                        ->whereDate('exam_date', $examDate)
                        ->exists();
                    if ($exists) {
                        $fail('The route number must be unique for the selected exam date and center.');
                    }
                },
            ],
            'driver_name' => 'required',
            'driver_licence_no' => 'required',
            'phone' => 'required',
            'vehicle_no' => 'required',
            'mobile_staff' => 'required',
            'centers' => 'required|array',
            'halls' => 'required|array',
        ]);

        // Group halls by center
        $centerHalls = [];
        foreach ($validated['halls'] as $hall) {
            [$centerCode, $hallCode] = explode(':', $hall);
            if (!isset($centerHalls[$centerCode])) {
                $centerHalls[$centerCode] = [];
            }
            $centerHalls[$centerCode][] = $hallCode;
        }
        // Create one row per center with its associated halls
        foreach ($validated['centers'] as $centerCode) {

            $route = new ExamMaterialRoutes();
            $route->exam_id = $validated['exam_id'];
            $route->exam_date = $validated['exam_date'];
            $route->route_no = $validated['route_no'];
            $route->driver_name = $validated['driver_name'];
            $route->driver_license = $validated['driver_licence_no'];
            $route->driver_phone = $validated['phone'];
            $route->vehicle_no = $validated['vehicle_no'];
            $route->mobile_team_staff = $validated['mobile_staff'];
            $route->center_code = $centerCode;
            // Convert array to JSON before saving
            $route->hall_code = $centerHalls[$centerCode] ?? [];
            $route->district_code = $role == 'department_official' ? '01' : $user->district_code;
            $route->save();
        }

        return redirect()->route('exam-materials-route.index', ['examId' => $validated['exam_id']]);
    }

    public function editRoute(Request $request, $Id)
    {
        // Get authenticated user
        $user = $request->get('auth_user');
        $district_code = null;
        $routes = ExamMaterialRoutes::where('id', $Id)->first();
        $mobileTeam = null;
        $role = session('auth_role');
        // chennai disitrict van duty staff route creation
        if (($role == 'headquarters' && $user->role->role_department == 'ID') || $user->district_code == '01') {
            $role = Role::where('role_name', 'Van Duty Staff')->first();
            $mobileTeam = DepartmentOfficial::where('dept_off_role', $role->role_id)->get();
            $district_code = '01';
        } else {
            // get mobile team staffs for the user's district br role based
            $district_code = $user->district_code;
            $mobileTeam = MobileTeamStaffs::where('mobile_district_id', $user->district_code)->get();
        }
        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $routes->exam_id)
            ->where('district_code', $district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        // Get all hall codes grouped by center code within the user's district
        $halls = ExamMaterialsData::where('exam_id', $routes->exam_id)
            ->where('district_code', $district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('exam_materials_data.center_code', 'centers.center_name', 'exam_materials_data.hall_code', )
            ->select(
                'centers.center_name',
                'exam_materials_data.center_code',
                'exam_materials_data.hall_code'
            )
            ->orderBy('exam_materials_data.center_code') // Optional: Order by center code
            ->get();
        // Get current exam session details
        $session = Currentexam::with('examsession')->where('exam_main_no', $routes->exam_id)->first();

        // Group exam sessions by date
        $examDates = $session->examsession->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys(); // Get only the keys (exam dates)
        // dd($centers);
        return view('my_exam.District.materials-route.edit', compact('routes', 'mobileTeam', 'centers', 'halls', 'examDates', 'user'));

    }
    public function updateRoute(Request $request, $id)
    {
        $route = ExamMaterialRoutes::findOrFail($id);

        // Validate request
        $validated = $request->validate([
            'exam_date' => 'required',
            'route_no' => [
                'required',
                function ($attribute, $value, $fail) use ($request, $route) {
                    $examDate = \DateTime::createFromFormat('d-m-Y', $request->exam_date)->format('Y-m-d');
                    $exists = ExamMaterialRoutes::where('exam_id', $request->exam_id)
                        ->where('route_no', $value)
                        ->whereDate('exam_date', $examDate)
                        ->where('id', '!=', $route->id) // Exclude the current route
                        ->exists();
                    if ($exists) {
                        $fail('The route number must be unique for the selected exam date and center.');
                    }
                },
            ],
            'driver_name' => 'required',
            'driver_licence_no' => 'required',
            'driver_phone' => 'required',
            'vehicle_no' => 'required',
            'mobile_staff' => 'required',
            'center_code' => 'required',
            'halls' => 'required|array',
        ]);
        // update the  database
        $updatedata = [
            'exam_date' => $validated['exam_date'],
            'route_no' => $validated['route_no'],
            'driver_name' => $validated['driver_name'],
            'driver_license' => $validated['driver_licence_no'],
            'driver_phone' => $validated['driver_phone'],
            'vehicle_no' => $validated['vehicle_no'],
            'mobile_team_staff' => $validated['mobile_staff'],
            'center_code' => $validated['center_code'],
            'hall_code' => $validated['halls'],
        ];
        $route->update($updatedata);
        return redirect()->route('exam-materials-route.index', ['examId' => $route->exam_id]);
    }
    public function viewRoute(Request $request, $Id)
    {

        $user = $request->get('auth_user');
        $role = session('auth_role');
        if (($role == 'headquarters' && $user->role->role_department == 'ID') || $user->district_code == '01') {
            // Get authenticated user
            $route = ExamMaterialRoutes::where('id', $Id)->with(['district', 'department_official'])->first();
        } else {
            // Get authenticated user
            $route = ExamMaterialRoutes::where('id', $Id)->with(['district', 'mobileTeam'])->first();
        }
        $routeData = []; // Initialize the array to store individual hall rows

        // Get halls array from JSON
        $hallCodes = is_array($route->hall_code) ? $route->hall_code : json_decode($route->hall_code, true);

        // Fetch venue data for all halls in this center
        $halls = ExamMaterialsData::select('exam_materials_data.*', 'venue.venue_name as venue_name', 'venue.venue_address as venue_address')
            ->join('venue', 'exam_materials_data.venue_code', '=', 'venue.venue_code')
            ->where('exam_materials_data.exam_id', $route->exam_id)
            ->where('exam_materials_data.district_code', $route->district_code)
            ->where('exam_materials_data.center_code', $route->center_code)
            ->whereIn('exam_materials_data.hall_code', $hallCodes)
            ->get();

        foreach ($halls as $key => $hall) {
            // Create a unique key combining route number and hall code
            $key = $hall->hall_code;

            $routeData[$key] = [
                'id' => $route->id,
                'route_no' => $route->route_no,
                'driver_name' => $route->driver_name,
                'driver_license' => $route->driver_license,
                'driver_phone' => $route->driver_phone,
                'vehicle_no' => $route->vehicle_no,
                'mobileteam' => $route->mobileteam,
                'hall_code' => $hall->hall_code,
                'venue_name' => $hall->venue_name,
                'venue_address' => $hall->venue_address,
                'center_code' => $route->center_code,
                'exam_date' => $route->exam_date,
            ];
        }
        // dd($routeData);
        // Get current exam session details
        $session = ExamSession::where('exam_sess_mainid', $route->exam_id)
            ->where('exam_sess_date', \Carbon\Carbon::parse($route->exam_date)->format('d-m-Y'))
            ->with('currentexam')
            ->first();

        $mobileTeam = MobileTeamStaffs::where('mobile_id', $route->mobile_team_staff)->first();

        $html = view('PDF.Route.center-routes', [
            'route' => $route,
            'session' => $session,
            'mobileTeam' => $mobileTeam,
            'routeData' => $routeData,
            'user' => $user,
        ])->render();

        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
            ->setOption('margin', [
                'top' => '10mm',
                'right' => '10mm',
                'bottom' => '10mm',
                'left' => '10mm'
            ])
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

        $filename = 'meeting-qrcode-' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

}