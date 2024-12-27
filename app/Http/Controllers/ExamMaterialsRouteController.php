<?php

namespace App\Http\Controllers;

use App\Models\ExamMaterialRoutes;
use App\Models\ExamMaterialsData;
use App\Models\MobileTeamStaffs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Currentexam;
class ExamMaterialsRouteController extends Controller
{
    public function index(Request $request, $examId)
    {
        $user = $request->get('auth_user');
        $district_code = $user->district_code;

        // Fetch routes with related data
        $routes = ExamMaterialRoutes::where('exam_id', $examId)
            ->where('district_code', $district_code)
            ->with('mobileteam')
            ->get();

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
                'mobileteam' => $route->mobileteam,
                'halls' => $uniqueVenues,
                'center_code' => $route->center_code,
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
        ]);
    }


    public function createRoute(Request $request, $examId)
    {
        // Get authenticated user
        $user = $request->get('auth_user');
        $district_code = $user->district_code;
        $mobileTeam = MobileTeamStaffs::where('mobile_district_id', $district_code)->get();
        //get center code for the user 
        $centers = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $user->district_code)
            ->join('centers', 'exam_materials_data.center_code', '=', 'centers.center_code')
            ->groupBy('centers.center_code', 'centers.center_name')
            ->select('centers.center_name', 'centers.center_code')
            ->get();
        // Get all hall codes grouped by center code within the user's district
        $halls = ExamMaterialsData::where('exam_id', $examId)
            ->where('district_code', $user->district_code)
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


        return view('my_exam.District.materials-route.create', compact('examId', 'mobileTeam', 'centers', 'halls', 'examDates'));
    }
    public function editRoute()
    {
        return view('my_exam.District.materials-route.edit');
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
            'exam-date' => 'required',
            'route_no' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    $exists = ExamMaterialRoutes::where('exam_id', $request->exam_id)
                        ->where('route_no', $value)
                        ->exists();
                    if ($exists) {
                        $fail('The route number must be unique for the selected exam and center.');
                    }
                },
            ],
            'driver_name' => 'required',
            'driver_licence_no' => 'required',
            'phone' => 'required',
            'vehicle_no' => 'required',
            'mobile_staff' => 'required',
            'center-select' => 'required|array',
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
        foreach ($validated['center-select'] as $centerCode) {

            $route = new ExamMaterialRoutes();
            $route->exam_id = $validated['exam_id'];
            $route->exam_date = $validated['exam-date'];
            $route->route_no = $validated['route_no'];
            $route->driver_name = $validated['driver_name'];
            $route->driver_license = $validated['driver_licence_no'];
            $route->driver_phone = $validated['phone'];
            $route->vehicle_no = $validated['vehicle_no'];
            $route->mobile_team_staff = $validated['mobile_staff'];
            $route->center_code = $centerCode;
            // Convert array to JSON before saving
            $route->hall_code = json_encode($centerHalls[$centerCode] ?? []);
            $route->district_code = $user->district_code;
            $route->save();
        }

        return redirect()->route('exam-materials-route.index', ['examId' => $validated['exam_id']]);
    }


}