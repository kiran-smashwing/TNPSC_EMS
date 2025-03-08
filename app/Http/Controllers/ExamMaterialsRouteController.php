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
use App\Services\ExamAuditService;

class ExamMaterialsRouteController extends Controller
{
    protected $auditService;

    public function __construct(ExamAuditService $auditService)
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
        $this->auditService = $auditService;
    }
    public function index(Request $request, $examId)
    {
        $user = $request->get('auth_user');
        $role = session('auth_role');
        $district_code = null;

        // Chennai district van duty staff route creation 
        if (($role == 'headquarters' && $user->role->role_department == 'QD') || $user->district_code == '01') {
            $district_code = '01';
            $query = ExamMaterialRoutes::where('exam_id', $examId)
                ->where('district_code', $district_code)
                ->with('department_official');
        } else {
            $district_code = $user->district_code;
            $query = ExamMaterialRoutes::where('exam_id', $examId)
                ->where('district_code', $district_code)
                ->with('mobileteam');
        }

        // Apply filters conditionally
        if ($request->has('centerCode') && !empty($request->centerCode)) {
            $query->whereJsonContains('center_code', $request->centerCode);
        }
        if ($request->has('examDate') && !empty($request->examDate)) {
            $examDate = \DateTime::createFromFormat('d-m-Y', $request->examDate)->format('Y-m-d');
            $query->whereDate('exam_date', $examDate);
        }

        // Execute the query and fetch results
        $routes = $query->get();

        // Prepare data without center-wise separation
        $routeData = [];
        foreach ($routes as $route) {
            $centerCodes = is_string($route->center_code) ? json_decode($route->center_code, true) : $route->center_code;
            $hallCodes = is_string($route->hall_code) ? json_decode($route->hall_code, true) : $route->hall_code;

            $uniqueVenues = [];
            foreach ($centerCodes as $centerCode) {
                $halls = isset($hallCodes[$centerCode]) ? $hallCodes[$centerCode] : [];
                $venues = ExamMaterialsData::select('venue.venue_name')
                    ->join('venue', 'exam_materials_data.venue_code', '=', 'venue.venue_code')
                    ->where('exam_materials_data.exam_id', $examId)
                    ->where('exam_materials_data.district_code', $district_code)
                    ->where('exam_materials_data.center_code', $centerCode)
                    ->whereIn('exam_materials_data.hall_code', $halls)
                    ->pluck('venue.venue_name')
                    ->toArray();

                $uniqueVenues = array_merge($uniqueVenues, $venues);
            }

            $routeData[] = [
                'id' => $route->id,
                'route_no' => $route->route_no,
                'driver_name' => $route->driver_name,
                'driver_license' => $route->driver_license,
                'driver_phone' => $route->driver_phone,
                'vehicle_no' => $route->vehicle_no,
                'mobileteam' => (session('auth_role') == 'district' && $user->district_code != '01') ? $route->mobileteam : $route->department_official,
                'halls' => implode(', ', array_unique($uniqueVenues)),
                'center_code' => implode(', ', $centerCodes),
                'district_code' => $route->district_code,
                'exam_date' => $route->exam_date,
            ];
        }

        // Get centers from table grouped and send to index
        $centers = ExamMaterialRoutes::where('exam_id', $examId)
            ->where('district_code', $district_code)
            ->join('centers', 'exam_material_routes.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();

        // Get current exam session details
        $session = Currentexam::with('examsession')->where('exam_main_no', $examId)->first();
        $examDates = $session->examsession->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->exam_sess_date)->format('d-m-Y');
        })->keys();

        return view('my_exam.District.materials-route.index', [
            'examId' => $examId,
            'groupedRoutes' => $routeData,
            'centers' => $centers,
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
        if (($role == 'headquarters' && $user->role->role_department == 'QD') || $user->district_code == '01') {
            $role = Role::where('role_name', 'Van Duty Staff')->first();
            $mobileTeam = DepartmentOfficial::where(function ($query) {
                $query->where('dept_off_role', '')
                    ->orWhereNull('dept_off_role');
            })->get();
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
            ->groupBy('exam_materials_data.center_code', 'centers.center_name', 'exam_materials_data.hall_code',)
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
        $lastRoute = ExamMaterialRoutes::where('exam_id', $examId)->where('district_code', $district_code)->max('route_no');
        $lastRouteNumber = $lastRoute ? intval($lastRoute) : 1;
        $newRouteNumber = str_pad(($lastRouteNumber + 1), 3, '0', STR_PAD_LEFT);

        return view('my_exam.District.materials-route.create', compact('examId', 'mobileTeam', 'centers', 'halls', 'examDates', 'user','newRouteNumber'));
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
                function ($attribute, $value, $fail) use ($request, $user) {
                    $examDate = \DateTime::createFromFormat('d-m-Y', $request->exam_date)->format('Y-m-d');
                    $exists = ExamMaterialRoutes::where('exam_id', $request->exam_id)
                        ->where('route_no', $value)
                        ->whereDate('exam_date', $examDate)
                        ->where('district_code', $user->district_code ?? '01')
                        ->exists();
                    if ($exists) {
                        $fail('The route number must be unique for the selected exam date.');
                    }
                },
            ],
            'driver_name' => 'required',
            'driver_licence_no' => 'required',
            'phone' => 'required',
            'vehicle_no' => 'required',
            'mobile_staff' => 'required',
            'centers' => 'required|array',
            'halls' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($request, $user) {
                    $examDate = \DateTime::createFromFormat('d-m-Y', $request->exam_date)->format('Y-m-d');
                    foreach ($value as $hall) {
                        [$centerCode, $hallCode] = explode(':', $hall);
                        $exists = ExamMaterialRoutes::where('exam_id', $request->exam_id)->whereDate('exam_date', $examDate)->where('mobile_team_staff', $request->mobile_staff)->where('district_code', session('auth_role') == 'headquarters' ? '01' : $user->district_code)->whereJsonContains('hall_code->' . $centerCode, $hallCode)->exists();
                        if ($exists) {
                            $fail('The hall ' . $hallCode . ' for center ' . $centerCode . ' is already assigned.');
                        }
                    }
                },
            ],
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
        // foreach ($validated['centers'] as $centerCode) {

        $route = new ExamMaterialRoutes();
        $route->exam_id = $validated['exam_id'];
        $route->exam_date = $validated['exam_date'];
        $route->route_no = $validated['route_no'];
        $route->driver_name = $validated['driver_name'];
        $route->driver_license = $validated['driver_licence_no'];
        $route->driver_phone = $validated['phone'];
        $route->vehicle_no = $validated['vehicle_no'];
        $route->mobile_team_staff = $validated['mobile_staff'];
        $route->center_code = json_encode($validated['centers']);
        // Convert array to JSON before saving
        $route->hall_code = $centerHalls ?? [];
        $route->district_code = $role == 'headquarters' ? '01' : $user->district_code;
        $route->save();
        //update the departmetofficals role to VDS role
        if ($role == 'headquarters') {
            $departmentOfficial = DepartmentOfficial::findOrFail($validated['mobile_staff']);
            $departmentOfficial->custom_role = 'VDS';
            $departmentOfficial->save();
        }
        // }
        //update the mobileteam for the exam materials data for centers and halls which are assigned 
        $validated['centers'] = json_decode($route->center_code);
        $validated['halls'] = $route->hall_code;
        //update the mobileteam for the exam materials data for centers and halls which are assigned 
        $examMaterialsData = ExamMaterialsData::where('exam_id', $validated['exam_id'])
            ->where(function ($query) use ($centerHalls) {
                foreach ($centerHalls as $centerCode => $hallCodes) {
                    $query->orWhere(function ($q) use ($centerCode, $hallCodes) {
                        $q->where('center_code', $centerCode)
                            ->whereIn('hall_code', $hallCodes);
                    });
                }
            })
            ->get();

        foreach ($examMaterialsData as $data) {
            $data->mobile_team_id = $validated['mobile_staff'];
            $data->save();
        }
        // Audit Logging - Creation with consolidation
        $currentUser = current_user();
        $userName = $currentUser ? $currentUser->display_name : 'Unknown';

        // Try to find existing audit log for this exam and user for route creation
        $existingLog = $this->auditService->findLog([
            'exam_id' => $validated['exam_id'],
            'task_type' => 'exam_material_routes_created',
            'user_id' => $role == 'headquarters' && $user->role->role_department == 'ID' ? $user->dept_off_id : $user->district_id,
        ]);
        if ($existingLog) {
            // If log exists, update it by adding new route ID
            $existingRouteIds = $existingLog->afterState['route_ids'] ?? [];
            $updatedRouteIds = array_merge($existingRouteIds, [$route->route_no]);

            $this->auditService->updateLog(
                $existingLog->id,
                afterState: ['route_ids' => $updatedRouteIds],
                description: "Created " . count($updatedRouteIds) . " routes",
                metadata: ['user_name' => $userName]
            );
        } else {
            // If no log exists, create new one
            $this->auditService->log(
                examId: $validated['exam_id'],
                actionType: 'routes_created',
                taskType: 'exam_material_routes_created',
                beforeState: null,
                afterState: ['route_ids' => [$route->route_no]],
                description: "Created new route",
                metadata: ['user_name' => $userName]
            );
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
        if (($role == 'headquarters' && $user->role->role_department == 'QD') || $user->district_code == '01') {
            $role = Role::where('role_name', 'Van Duty Staff')->first();
            $mobileTeam = DepartmentOfficial::where(function ($query) {
                $query->where('dept_off_role', '')
                    ->orWhereNull('dept_off_role');
            })->get();
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
            ->groupBy('exam_materials_data.center_code', 'centers.center_name', 'exam_materials_data.hall_code',)
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

        $user = $request->get('auth_user');
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
            'center_code' => 'required|array',
            'halls' => [
                'required',
                'array',
                function ($attribute, $value, $fail) use ($request, $route, $user) {
                    $examDate = \DateTime::createFromFormat('d-m-Y', $request->exam_date)->format('Y-m-d');
                    foreach ($value as $hall) {
                        [$centerCode, $hallCode] = explode(':', $hall);
                        $exists = ExamMaterialRoutes::where('exam_id', $request->exam_id)->whereDate('exam_date', $examDate)->where('mobile_team_staff', $request->mobile_staff)->where('district_code', session('auth_role') == 'headquarters' ? '01' : $user->district_code)->where('id', '!=', $route->id)
                            // Exclude the current route 
                            ->whereJsonContains('hall_code->' . $centerCode, $hallCode)->exists();
                        if ($exists) {
                            $fail('The hall ' . $hallCode . ' for center ' . $centerCode . ' is already assigned.');
                        }
                    }
                },
            ],
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

        // Prepare data for update
        $updatedData = [
            'exam_date' => \DateTime::createFromFormat('d-m-Y', $validated['exam_date'])->format('Y-m-d'),
            'route_no' => $validated['route_no'],
            'driver_name' => $validated['driver_name'],
            'driver_license' => $validated['driver_licence_no'],
            'driver_phone' => $validated['driver_phone'],
            'vehicle_no' => $validated['vehicle_no'],
            'mobile_team_staff' => $validated['mobile_staff'],
            'center_code' => $validated['center_code'],
            'hall_code' => $centerHalls,
        ];

        // Update the route in the database
        $route->update($updatedData);

        return redirect()->route('exam-materials-route.index', ['examId' => $route->exam_id]);
    }

    public function viewRoute(Request $request, $id)
    {
        $user = $request->get('auth_user');
        $role = session('auth_role');

        // dd($id, $user, $role); // Debugging

        if (($role == 'headquarters' && $user->role->role_department == 'QD') || $user->district_code == '01') {
            $route = ExamMaterialRoutes::where('id', $id)->with(['district', 'department_official'])->first();
        } else {
            $route = ExamMaterialRoutes::where('id', $id)->with(['district', 'mobileTeam'])->first();
        }

        // dd($route); // Debugging

        if (!$route) {
            abort(404, 'Route not found');
        }

        // Get center codes and halls array from JSON
        $centerCodes = is_string($route->center_code) ? json_decode($route->center_code, true) : $route->center_code;
        $hallCodes = is_string($route->hall_code) ? json_decode($route->hall_code, true) : $route->hall_code;

        // Check if $hallCodes is an array before sorting
        if (is_array($hallCodes)) {
            foreach ($hallCodes as $key => &$hallList) {
                if (is_array($hallList)) {
                    sort($hallList, SORT_NATURAL | SORT_FLAG_CASE); // Ensure ascending order
                }
            }
            unset($hallList); // Unset reference to avoid unexpected behavior
        }

        // Debugging
        // dd($hallCodes);


        // print_r($hallCodes); // Check the sorted array



        // dd($centerCodes, $hallCodes); // Debugging

        $routeData = [];

        foreach ($centerCodes as $centerCode) {
            $halls = isset($hallCodes[$centerCode]) ? $hallCodes[$centerCode] : [];

            // Fetch venue data for all halls in this center
            $venueData = ExamMaterialsData::select('exam_materials_data.*', 'venue.venue_name as venue_name', 'venue.venue_address as venue_address')
                ->join('venue', 'exam_materials_data.venue_code', '=', 'venue.venue_code')
                ->where('exam_materials_data.exam_id', $route->exam_id)
                ->where('exam_materials_data.district_code', $route->district_code)
                ->where('exam_materials_data.center_code', $centerCode)
                ->whereIn('exam_materials_data.hall_code', $halls)
                ->orderBy('exam_materials_data.hall_code', 'asc') // Ensure sorting at query level
                ->get();

            // dd($venueData); // Debugging

            foreach ($venueData as $hall) {
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
                    'center_code' => $centerCode,
                    'exam_date' => $route->exam_date,
                ];
            }
        }

        // Get current exam session details
        $session = ExamSession::where('exam_sess_mainid', $route->exam_id)
            ->where('exam_sess_date', \Carbon\Carbon::parse($route->exam_date)->format('d-m-Y'))
            ->with('currentexam')
            ->first();

        // dd($routeData); // Debugging

        $mobileTeam = MobileTeamStaffs::where('mobile_id', $route->mobile_team_staff)->first();

        // dd($mobileTeam); // Debugging

        $html = view('PDF.Route.center-routes', [
            'route' => $route,
            'session' => $session,
            'mobileTeam' => $mobileTeam,
            'routeData' => $routeData,
            'user' => $user,
        ])->render();

        // dd($html); // Debugging

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

        // dd($pdf); // Debugging

        $filename = 'meeting-qrcode-' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
