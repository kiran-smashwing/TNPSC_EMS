<?php


namespace App\Http\Controllers;

use App\Models\ExamConfirmedHalls;
use App\Models\ExamMaterialRoutes;
use App\Models\ExamMaterialsData;
use Illuminate\Http\Request;
use App\Models\ChartedVehicleRoute;
use App\Models\EscortStaff;
use Illuminate\Support\Facades\Auth;
use App\Models\Currentexam;
class ChartedVehicleRoutesController extends Controller
{
    public function index(Request $request, $examId)
    {
        $user = $request->get('auth_user');
        $role = session('auth_role');
        $district_code = null;

        // Chennai district van duty staff route creation 
        if (($role == 'headquarters' && $user->role->role_department == 'ID') || $user->district_code == '01') {
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

        return view('my_exam.Charted-Vehicle.index', [
            'examId' => $examId,
            'groupedRoutes' => $routeData,
            'centers' => $centers,
            'examDates' => $examDates,
            'user' => $user,
        ]);
    }

    public function createRoute(Request $request, $examId)
    {
        // Get all exams available
        $exams = Currentexam::get();
        // dd($districts);
        return view('my_exam.Charted-Vehicle.create',compact('exams'));
    }

    public function storeRoute(Request $request)
    {
        dd($request->all());
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
            'exam_id' => json_encode($request->exam_id),
            'charted_vehicle_no' => $request->vehicle_no,
            'driver_details' => json_encode([
                'name' => $request->driver_name,
                'licence_no' => $request->driver_licence_no,
                'phone' => $request->phone
            ]),
            'gps_locks' => json_encode($request->gps_lock),
            'pc_details' => json_encode([
                'name' => $request->police_constable,
                'phone' => $request->police_constable_phone,
                'ifhrms_no' => $request->police_constable_ifhrms_no ?? null
            ]),
            'escort_vehicle_details' => json_encode([
                'vehicle_no' => $request->escort_vehicle_no,
                'driver_name' => $request->escort_driver_name,
                'driver_licence_no' => $request->escort_driver_licence_no,
                'driver_phone' => $request->escort_driver_phone
            ])
        ]);

        foreach ($request->subjects as $subject) {
            EscortStaff::create([
                'charted_vehicle_id' => $chartedVehicleRoute->id,
                'district_code' => $subject['district'],
                'tnpsc_staff_id' => $subject['tnpsc_staff'],
                'si_details' => json_encode([
                    'name' => $subject['si_name'],
                    'phone' => $subject['si_phone'],
                    'ifhrms_no' => $subject['si_ifhrms_no'] ?? null
                ]),
                'revenue_staff_details' => json_encode([
                    'name' => $subject['revenue_staff_name'],
                    'phone' => $subject['revenue_phone'],
                    'ifhrms_no' => $subject['revenue_ifhrms_no'] ?? null
                ])
            ]);
        }

        return redirect()->route('exam-materials-route.store')
            ->with('success', 'Charted Vehicle Route created successfully.');
    }

    public function editRoute(Request $request, $Id)
    {

    }
    public function updateRoute(Request $request, $id)
    {

    }
    public function viewRoute(Request $request, $Id)
    {

    }
    public function getDistrictsForExamIDs(Request $request){
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

}