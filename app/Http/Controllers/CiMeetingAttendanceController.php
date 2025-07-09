<?php

namespace App\Http\Controllers;

use App\Models\ExamConfirmedHalls;
use Spatie\Browsershot\Browsershot;
use App\Models\Currentexam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Center;
use App\Models\CIMeetingQrcode;
use App\Models\CIMeetingAttendance;
use Illuminate\Support\Facades\Auth;
use App\Models\District;

class CiMeetingAttendanceController extends Controller
{
    public function __construct()
    {
        //apply the auth middleware to the entire controller
        $this->middleware('auth.multi');
    }
    public function index()
    {
        $districts = District::all(); // Fetch all districts
        // Fetch unique center values from the same table
        $centers = center::all(); // Fetch all venues
        return view('view_report.ci_meeting_report.index', compact('districts', 'centers')); // Path matches the file created
    }
    public function getDropdownData(Request $request)
    {
        $notificationNo = $request->query('notification_no');

        // Check if notification exists
        $examDetails = Currentexam::where('exam_main_notification', $notificationNo)->first();

        if (!$examDetails) {
            // return back()->with('error', 'Invalid Notification No.');
            return response()->json(['error' => 'Invalid Notification No'], 404);
        }

        // Retrieve the role and guard from session
        $role = session('auth_role');
        $guard = $role ? Auth::guard($role) : null;

        // Get the user based on the guard
        $user = $guard ? $guard->user() : null;
        $districtCodeFromSession = $user ? $user->district_code : null; // Assuming district_code is in the user table
        $centerCodeFromSession = $user ? $user->center_code : null; // Assuming center_code is in the user table or retrieved through a relationship

        // Fetch districts - shown for headquarters and district roles
        $districts = [];
        if ($role === 'headquarters' || $role === 'district') {
            $districts = DB::table('district')
                ->select('district_code as id', 'district_name as name')
                ->get();
        }

        // Fetch centers
        $centers = [];
        if ($role === 'headquarters') {
            // Fetch all centers for headquarters
            $centers = DB::table('centers')
                ->select('center_code as id', 'center_name as name')
                ->get();
        } elseif ($role === 'district') {
            // Fetch centers for the specific district
            if ($districtCodeFromSession) {
                $centers = DB::table('centers')
                    ->where('center_district_id', $districtCodeFromSession)
                    ->select('center_code as id', 'center_name as name')
                    ->get();
            }
        }

        // Exam Dates and Sessions - applicable to all roles
        $examDates = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_date')
            ->distinct()
            ->pluck('exam_sess_date');

        $sessions = DB::table('exam_session')
            ->where('exam_sess_mainid', $examDetails->exam_main_no)
            ->select('exam_sess_session')
            ->distinct()
            ->pluck('exam_sess_session');
        // Return data with logic applied based on roles
        return response()->json([
            'user' => $user,
            'districts' => $districts,
            'centers' => $centers,
            'examDates' => $examDates,
            'sessions' => $sessions,
            'centerCodeFromSession' => $centerCodeFromSession,
        ]);
    }

    public function generatecategorysender(Request $request)
    {
        $category = $request->query('category'); // category can be district, center, or overall

        switch ($category) {
            case 'district':
                return $this->generateCImeetingDistrictReport($request);
                break;

            case 'center':
                return $this->generateCImeetingcenterReport($request);
                break;

            case 'all':
                return $this->generateCIMeetingReport($request);
                break;

            default:
                return back()->with('error', 'Invalid category specified.');
        }
    }
    public function generateCImeetingDistrictReport(Request $request)
    {
        $notification_no = $request->input('notification_no');
        $districtId = $request->input('district');
        $centerId = $request->input('center');

        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notification_no)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $exam_id = $exam_data->exam_main_no;

        try {
            $examconfirmed_halls = ExamConfirmedHalls::where('exam_id', $exam_id)
                ->with([
                    'district',
                    'center' => function ($query) {
                        $query->with('district');
                    },
                    'venue',
                    'chiefInvigilator' => function ($query) {
                        $query->with('venue');
                    },
                    'ciCandidateLogs'
                ])
                ->get();

            if ($examconfirmed_halls->isEmpty()) {
                return back()->with('error', 'No confirmed halls found for this exam.');
            }

            $examconfirmed_halls = $examconfirmed_halls->unique('ci_id');

            if ($districtId) {
                $district_analysis = $examconfirmed_halls->map(function ($hall) {
                    return [
                        'hall_id' => $hall->id,
                        'direct_district_id' => $hall->district_id ?? null,
                        'center_district_id' => $hall->center ? $hall->center->district_id ?? null : null,
                        'district_relation_id' => ($hall->center && $hall->center->district) ? $hall->center->district->id : null,
                        'district_name' => ($hall->center && $hall->center->district) ? $hall->center->district->district_name : null,
                        'center_name' => $hall->center ? $hall->center->center_name : null,
                    ];
                })->groupBy('district_relation_id')->map(function ($group) {
                    return [
                        'count' => $group->count(),
                        'sample' => $group->first()
                    ];
                })->toArray();

                $filtered_halls = collect();

                $strategy1 = $examconfirmed_halls->filter(function ($hall) use ($districtId) {
                    return isset($hall->district_id) && $hall->district_id == $districtId;
                });

                $strategy2 = $examconfirmed_halls->filter(function ($hall) use ($districtId) {
                    return $hall->center && isset($hall->center->district_id) && $hall->center->district_id == $districtId;
                });

                $strategy3 = $examconfirmed_halls->filter(function ($hall) use ($districtId) {
                    return $hall->center && $hall->center->district && $hall->center->district->id == $districtId;
                });

                $strategy4 = $examconfirmed_halls->filter(function ($hall) use ($districtId) {
                    return $hall->center && $hall->center->district && (string)$hall->center->district->id === (string)$districtId;
                });

                $strategy5 = $examconfirmed_halls->filter(function ($hall) use ($districtId) {
                    return $hall->center && $hall->center->district && $hall->center->district->district_code == $districtId;
                });

                if ($strategy1->count() > 0) {
                    $filtered_halls = $strategy1;
                } elseif ($strategy2->count() > 0) {
                    $filtered_halls = $strategy2;
                } elseif ($strategy3->count() > 0) {
                    $filtered_halls = $strategy3;
                } elseif ($strategy4->count() > 0) {
                    $filtered_halls = $strategy4;
                } elseif ($strategy5->count() > 0) {
                    $filtered_halls = $strategy5;
                } else {
                    $available_districts = $examconfirmed_halls->map(function ($hall) {
                        if ($hall->center && $hall->center->district) {
                            return [
                                'id' => $hall->center->district->id,
                                'name' => $hall->center->district->district_name,
                                'code' => $hall->center->district->district_code ?? 'No code'
                            ];
                        }
                        return null;
                    })->filter()->unique('id')->values()->toArray();

                    return back()->with('error', 'No confirmed halls found for district ID: ' . $districtId . '. Check logs for available districts.');
                }

                $examconfirmed_halls = $filtered_halls;
            }

            if ($centerId) {
                $examconfirmed_halls = $examconfirmed_halls->filter(function ($hall) use ($centerId) {
                    return isset($hall->center_id) && $hall->center_id == $centerId;
                });
            }

            if ($examconfirmed_halls->isEmpty()) {
                return back()->with('error', 'No confirmed halls found for the selected criteria.');
            }

            $ci_meeting_attendance = CIMeetingAttendance::where('exam_id', $exam_id)
                ->with([
                    'ci',
                    'ci.venue',
                    'center' => function ($query) {
                        $query->with('district');
                    }
                ])
                ->get()
                ->keyBy('ci_id');

            $districtCodes = collect();
            foreach ($examconfirmed_halls as $hall) {
                if ($hall->center && $hall->center->district && $hall->center->district->district_code) {
                    $districtCodes->push($hall->center->district->district_code);
                }
            }
            $districtCodes = $districtCodes->unique()->filter();

            $ci_meeting_times = collect();
            if ($districtCodes->isNotEmpty()) {
                $ci_meeting_times = CIMeetingQrcode::where('exam_id', $exam_id)
                    ->whereIn('district_code', $districtCodes)
                    ->get()
                    ->keyBy('district_code');
            }

            $merged_records = collect();

            foreach ($examconfirmed_halls as $hall) {
                if (isset($ci_meeting_attendance[$hall->ci_id])) {
                    $record = $ci_meeting_attendance[$hall->ci_id];
                    $record->attendance_status = 'Present';
                    $record->scan_time = $record->created_at;
                } else {
                    $record = (object) [
                        'ci_id' => $hall->ci_id,
                        'hall_code' => $hall->hall_code,
                        'center' => $hall->center,
                        'ci' => $hall->chiefInvigilator,
                        'created_at' => null,
                        'updated_at' => null,
                        'attendance_status' => 'Absent',
                        'scan_time' => null,
                    ];
                }

                $merged_records->push($record);
            }

            $grouped_data = $merged_records->groupBy(function ($item) {
                if ($item->center && $item->center->district && $item->center->district->district_name) {
                    return $item->center->district->district_name;
                }
                return 'Unknown District';
            })->map(function ($items) use ($ci_meeting_times) {
                $districtCode = null;
                $firstItem = $items->first();

                if (
                    $firstItem &&
                    $firstItem->center &&
                    $firstItem->center->district &&
                    $firstItem->center->district->district_code
                ) {
                    $districtCode = $firstItem->center->district->district_code;
                }

                $total_cis = $items->count();
                $present_cis = $items->where('attendance_status', 'Present')->count();
                $absent_cis = $items->where('attendance_status', 'Absent')->count();
                $attendance_percentage = $total_cis > 0 ? round(($present_cis / $total_cis) * 100, 2) : 0;

                return [
                    'ci_meeting_records' => $items->sortBy(function ($item) {
                        return $item->center && $item->center->center_code ? $item->center->center_code : 'zzz';
                    }),
                    'meeting_time' => $districtCode && isset($ci_meeting_times[$districtCode])
                        ? [
                            'meeting_date' => $ci_meeting_times[$districtCode]->meeting_date_time
                                ? date('d-m-Y', strtotime($ci_meeting_times[$districtCode]->meeting_date_time))
                                : 'N/A',
                            'meeting_time' => $ci_meeting_times[$districtCode]->meeting_date_time
                                ? date('h:i A', strtotime($ci_meeting_times[$districtCode]->meeting_date_time))
                                : 'N/A'
                        ]
                        : ['meeting_date' => 'N/A', 'meeting_time' => 'N/A'],
                    'statistics' => [
                        'total_cis' => $total_cis,
                        'present_cis' => $present_cis,
                        'absent_cis' => $absent_cis,
                        'attendance_percentage' => $attendance_percentage
                    ]
                ];
            });

            $exam_name = $exam_data->exam_main_name ?? 'N/A';
            $exam_services = $exam_data->examservice->examservice_name ?? 'N/A';

            $html = view('view_report.ci_meeting_report.ci-meeting-report-district', compact(
                'exam_services',
                'exam_name',
                'notification_no',
                'grouped_data'
            ))->render();

            $pdf = Browsershot::html($html)
                ->setOption('landscape', true)
                ->setOption('margin', [
                    'top' => '4mm',
                    'right' => '4mm',
                    'bottom' => '8mm',
                    'left' => '4mm'
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

            $filename = 'ci-meeting-attendance-report-' . time() . '.pdf';

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } catch (\Exception $e) {
            \Log::error('CI Meeting Report Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return back()->with('error', 'An error occurred while generating the report. Please check the logs.');
        }
    }
    public function generateCImeetingcenterReport(Request $request)
    {
        $notification_no = $request->input('notification_no');
        $districtId = $request->input('district');
        $centerId = $request->input('center');

        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notification_no)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $exam_id = $exam_data->exam_main_no;

        try {
            // Build the query with proper filtering
            $query = ExamConfirmedHalls::where('exam_id', $exam_id)
                ->with([
                    'district',
                    'center' => function ($query) {
                        $query->with('district');
                    },
                    'venue',
                    'chiefInvigilator' => function ($query) {
                        $query->with('venue');
                    },
                    'ciCandidateLogs'
                ]);

            // Apply district filter at database level
            if ($districtId) {
                $query->whereHas('center.district', function ($q) use ($districtId) {
                    $q->where('district_code', $districtId);
                });
            }

            // Apply center filter at database level
            if ($centerId) {
                $query->where('center_code', $centerId);
            }

            // Get distinct records by ci_id
            $examconfirmed_halls = $query->get()->unique('ci_id');

            if ($examconfirmed_halls->isEmpty()) {
                return back()->with('error', 'No confirmed halls found for the selected criteria.');
            }

            $ci_meeting_attendance = CIMeetingAttendance::where('exam_id', $exam_id)
                ->with([
                    'ci',
                    'ci.venue',
                    'center' => function ($query) {
                        $query->with('district');
                    }
                ])
                ->get()
                ->keyBy('ci_id');

            $districtCodes = collect();
            foreach ($examconfirmed_halls as $hall) {
                if ($hall->center && $hall->center->district && $hall->center->district->district_code) {
                    $districtCodes->push($hall->center->district->district_code);
                }
            }
            $districtCodes = $districtCodes->unique()->filter();

            $ci_meeting_times = collect();
            if ($districtCodes->isNotEmpty()) {
                $ci_meeting_times = CIMeetingQrcode::where('exam_id', $exam_id)
                    ->whereIn('district_code', $districtCodes)
                    ->get()
                    ->keyBy('district_code');
            }

            $merged_records = collect();

            foreach ($examconfirmed_halls as $hall) {
                if (isset($ci_meeting_attendance[$hall->ci_id])) {
                    $record = $ci_meeting_attendance[$hall->ci_id];
                    $record->attendance_status = 'Present';
                    $record->scan_time = $record->created_at;
                    $record->hall_code = $hall->hall_code; // Add hall_code from confirmed halls
                } else {
                    $record = (object) [
                        'ci_id' => $hall->ci_id,
                        'hall_code' => $hall->hall_code,
                        'center' => $hall->center,
                        'ci' => $hall->chiefInvigilator,
                        'created_at' => null,
                        'updated_at' => null,
                        'attendance_status' => 'Absent',
                        'scan_time' => null,
                    ];
                }
                $merged_records->push($record);
            }

            $grouped_data = $merged_records->groupBy(function ($item) {
                return $item->center->district->district_name ?? 'Unknown District';
            })->map(function ($items) use ($ci_meeting_times) {
                $districtCode = $items->first()->center->district->district_code ?? null;
                $meeting = $districtCode && isset($ci_meeting_times[$districtCode])
                    ? $ci_meeting_times[$districtCode]
                    : null;

                return [
                    'ci_meeting_records' => $items,
                    'meeting_time' => $meeting && $meeting->meeting_date_time
                        ? [
                            'meeting_date' => date('d-m-Y', strtotime($meeting->meeting_date_time)),
                            'meeting_time' => date('h:i A', strtotime($meeting->meeting_date_time))
                        ]
                        : ['meeting_date' => 'N/A', 'meeting_time' => 'N/A'],
                ];
            });
            $centerName = null;

            if ($centerId) {
                $centerRecord = \App\Models\Center::where('center_code', $centerId)->first();
                $centerName = $centerRecord ? $centerRecord->center_name : 'N/A';
            }
            // dd($centerName);
            $exam_name = $exam_data->exam_main_name ?? 'N/A';
            $exam_services = $exam_data->examservice->examservice_name ?? 'N/A';

            $html = view('view_report.ci_meeting_report.ci-meeting-report-center', compact(
                'exam_services',
                'exam_name',
                'notification_no',
                'grouped_data',
                'centerName',
            ))->render();

            $pdf = Browsershot::html($html)
                ->setOption('landscape', true)
                ->setOption('margin', [
                    'top' => '4mm',
                    'right' => '4mm',
                    'bottom' => '8mm',
                    'left' => '4mm'
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

            $filename = 'ci-meeting-attendance-centerwise-' . time() . '.pdf';

            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
        } catch (\Exception $e) {
            \Log::error('CI Meeting Center Report Error', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'exam_id' => $exam_id,
                'district_id' => $districtId,
                'center_id' => $centerId
            ]);

            return back()->with('error', 'An error occurred while generating the report. Please check the logs.');
        }
    }


    public function generateCIMeetingReport(Request $request)
    {
        $notification_no = $request->input('notification_no');
        $districtId = $request->input('district');
        $centerId = $request->input('center');

        // Retrieve exam data
        $exam_data = Currentexam::with('examservice')
            ->where('exam_main_notification', $notification_no)
            ->first();

        if (!$exam_data) {
            return back()->with('error', 'Exam data not found.');
        }

        $exam_id = $exam_data->exam_main_no;

        // Fetch all confirmed halls (similar to attendance report pattern)
        $examconfirmed_halls = ExamConfirmedHalls::with([
            'district',
            'center',
            'venue',
            'chiefInvigilator.venue',
            'ciCandidateLogs'
        ])
            ->where('exam_id', $exam_id)
            ->when($districtId, function ($query) use ($districtId) {
                return $query->whereHas('center.district', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            })
            ->when($centerId, function ($query) use ($centerId) {
                return $query->whereHas('center', function ($q) use ($centerId) {
                    $q->where('id', $centerId);
                });
            })
            ->get()
            ->unique('ci_id')
            ->sortBy('center.center_code');  // Keep only one entry per CI

        // Retrieve CI Meeting Attendance data and key by ci_id for quick lookup
        $ci_meeting_attendance = CIMeetingAttendance::where('exam_id', $exam_data->exam_main_no)
            ->when($districtId, function ($query) use ($districtId) {
                return $query->whereHas('center.district', function ($q) use ($districtId) {
                    $q->where('id', $districtId);
                });
            })
            ->when($centerId, function ($query) use ($centerId) {
                return $query->whereHas('center', function ($q) use ($centerId) {
                    $q->where('id', $centerId);
                });
            })
            ->with([
                'ci',
                'ci.venue',
                'center.district',
            ])
            ->get()
            ->keyBy('ci_id'); // Key by ci_id for quick lookup

        // Extract district codes from confirmed halls  
        $districtCodes = $examconfirmed_halls->pluck('center.district.district_code')->unique()->filter();

        // Retrieve CI Meeting times for each district
        $ci_meeting_times = CIMeetingQrcode::where('exam_id', $exam_data->exam_main_no)
            ->whereIn('district_code', $districtCodes)
            ->get()
            ->keyBy('district_code');

        // Create merged records for all confirmed halls
        $merged_records = collect();

        foreach ($examconfirmed_halls as $hall) {
            // Check if CI meeting attendance exists for this CI
            if (isset($ci_meeting_attendance[$hall->ci_id])) {
                // Use existing attendance record
                $record = $ci_meeting_attendance[$hall->ci_id];
            } else {
                // Create a fake record with the same structure for CIs who didn't attend
                $record = (object) [
                    'ci_id' => $hall->ci_id,
                    'hall_code' => $hall->hall_code,
                    'center' => $hall->center,
                    'ci' => $hall->chiefInvigilator,
                    'created_at' => null, // No attendance
                    'updated_at' => null, // No attendance
                ];
            }

            $merged_records->push($record);
        }

        if ($merged_records->isEmpty()) {
            return back()->with('error', 'No CI data found.');
        }

        // Group merged records by district (maintaining your existing structure)
        $grouped_data = $merged_records->groupBy(function ($item) {
            return $item->center->district->district_name ?? 'Unknown District';
        })->map(function ($items) use ($ci_meeting_times) {
            $districtCode = $items->first()->center->district->district_code ?? null;
            return [
                'ci_meeting_records' => $items,
                'meeting_time' => isset($districtCode) && isset($ci_meeting_times[$districtCode])
                    ? [
                        'meeting_date' => optional($ci_meeting_times[$districtCode])->meeting_date_time
                            ? date('d-m-Y', strtotime($ci_meeting_times[$districtCode]->meeting_date_time))
                            : 'N/A',
                        'meeting_time' => optional($ci_meeting_times[$districtCode])->meeting_date_time
                            ? date('h:i A', strtotime($ci_meeting_times[$districtCode]->meeting_date_time))
                            : 'N/A'
                    ]
                    : null,
            ];
        });

        $exam_name = $exam_data->exam_main_name ?? 'N/A';
        $exam_services = $exam_data->examservice->examservice_name ?? 'N/A';

        // Render the view (keeping your existing structure)
        $html = view('view_report.ci_meeting_report.ci-meeting-report', compact('exam_services', 'exam_name', 'notification_no', 'grouped_data'))->render();

        // Generate PDF using Browsershot
        $pdf = Browsershot::html($html)
            ->setOption('landscape', true)
            ->setOption('margin', [
                'top' => '4mm',
                'right' => '4mm',
                'bottom' => '8mm',
                'left' => '4mm'
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

        $filename = 'ci-meeting-attendance-report-' . time() . '.pdf';

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }
}
