<?php

namespace App\Services;

class AuthorizationService
{
    private $rolePermissions = [
        'headquarters' => [
            'RND' => [
                'Section Officer' => [
                    'current-exam.create',
                    'current-exam.store',
                    'current-exam.edit',
                    'departments-masters',
                    'view-all-examination-services',
                    'exam-heading',
                    'current-exam',
                    'exam-completed',
                ],
            ],
            'APD' => [
                'Section Officer' => [
                    'upload-candidates-csv',
                    'download-expected-candidates',
                    'finalize-csv',
                    'downlaodConfirmedExamHalls',
                    'download-finalized-halls-csv',
                    'exam-heading',
                    'current-exam',
                    'exam-completed',
                    'report-heading',
                    'candidate-attendance'
                ],
            ],
            'ID' => [
                'Section Officer' => [
                    'download-expected-candidates',
                    'update-percentage',
                    'download-candidates-count-updated',
                    'create-charted-vehicle-route',
                    'edit-charted-vehicle-route',
                    'create-escort-staff',
                    'view-all-districts',
                    'view-all-chief-invigilators',
                    'district-masters',
                    'view-all-other-list',
                    'venues-masters',
                    'view-all-venue',
                    'center-filters',
                    'ci-filter',
                    'ci-district-filter',
                    'treasury-officers-filter',
                    'mobile-team-staffs-filter',
                    'departments-masters',
                    'confirmExamVenueHalls',
                    'downlaodConfirmedExamHalls',
                    'heading',
                    'view-all-examination-services',
                    'district-filter',
                    'view-all-department',
                    'exam-heading',
                    'current-exam',
                    'exam-completed',
                    'report-heading',
                    'candidate-attendance',
                    'cv-down-updates',
                    'bundle-collection',
                    'omr-qca-delivered',
                    'replacement-omr-qca',
                    'emergency-alrm',
                    'exam-discrepancy',
                    'candidate-remarks',
                    'candidate-statement',
                    'expenditure-statment',
                    'ci-meeting',
                    'chv-routes',
                    'email-template',
                    'route-edit',
                    'cv-routes-report',
                    'download-meeting-qr',
                    'create-ci-meetings',
                    'create-vechicle-route'

                ],
            ],
            'ED' => [
                'Section Officer' => [
                    'verify-bundle-recevied-at-hq',
                    'exam-heading',
                    'current-exam',
                    'cv-down-updates',
                    'exam-completed',
                    'bundle-collection',
                    'annexure-1-b.download',
                    'route-checkbox'
                    
                ],
            ],
            'VMD' => [
                'Section Officer' => [
                    'verify-materials-handovered',
                    'exam-heading',
                    'current-exam',
                    'exam-completed',
                    'cv-down-updates',
                ],
            ],
            'VSD' => [
                'Section Officer' => [
                    'report-heading',
                    'replacement-omr-qca',
                ],
            ],
            'MCD' => [
                'Section Officer' => [
                    'report-heading',
                    'emergency-alrm',
                    'exam-discrepancy',
                ],
            ],
            'QD' => [
                'Section Officer' => [
                    'upload-exam-materials-csv',
                    'download-exam-materials-uploaded',
                    'receive-materials-printer-to-hq',
                    'upload-trunk-box-otl-csv',
                    'download-trunk-box-otl-csv',
                    'exam-heading',
                    'current-exam',
                    'exam-completed',
                    'omr-qca-delivered',
                    'create-exam-materails-route',
                    'create-exam-materials-route',
                    // 'chv-routes',
                ],
            ],
            'ADMIN' => [
                'ci-meetings.*',
                'users.*'
            ],
            'VDS' => [
                'VDS' => [
                    'exam-heading',
                    'current-exam',
                    'exam-completed',
                ]
                ],
            'ESCORT' => [
                'ESCORT' => [
                    'cv-down-updates',
                    'route-edit',
                    'otl-lock',
                ]
                ],
        ],
        'ci' => [
            'ci-meetings.ind',
            'ci-meetings.attendance-QRcode-scan',
            'venues-masters',
            'view-all-other-list',
            'heading',
            'exam-heading',
            'current-exam',
            'exam-completed',
        ],
        'venue' => [
            'venues-masters',
            'heading',
            'view-all-other-list',
            'view-all-chief-invigilators',
            'ci-district-filter',
            'exam-heading',
            'current-exam',
            'exam-completed',
        ],
        'district' => [
            'receive-exam-materials',
            'scan-exam-materials',
            'showVenueIntimationForm',
            'receive-exam-materials-from-printer',
            'venues-masters',
            'ci-filter',
            'view-all-chief-invigilators',
            'view-all-venue',
            'create-ci-meetings',
            'download-meeting-qr',
            'create-exam-materails-route',
            'receive-bundle-from-mobile-team',
            'district-masters',
            'heading',
            'view-all-center',
            'create-exam-materials-route',
            'exam-heading',
            'current-exam',
            'exam-completed',
        ],
        'treasury' => [
            'receive-exam-materials-from-printer',
            'receive-bundle-from-mobile-team',
            'exam-heading',
            'current-exam',
            'exam-completed',
            'scan-receive-disitrct-exam-materials',
            'scan-bundle-packaging',
        ],
        'center' => [
            'download-meeting-qr',
            'exam-heading',
            'current-exam',
            'exam-completed',
        ],
        'mobile_team_staffs' => [
            'current-exam',
            'exam-completed',
            'exam-heading',
        ],
        // Add other roles and their permissions
    ];

    public function hasPermission($role, $permission)
    {
        // Check if user is sw-admin first
        if ($role === 'sw-admin') {
            return true; // sw-admin has access to everything
        }
        // if ($permission === 'cv-down-updates' || $permission === 'route-edit' || $permission === 'otl-lock') {
        //     $user = auth()->guard('headquarters')->user();

        //     // Check if the user's dept_off_id exists in EscortStaff
        //     if ($role === 'headquarters' && \App\Models\EscortStaff::where('tnpsc_staff_id', $user->dept_off_id)->exists()) {
        //         return true;
        //     }
        // }
        if ($permission === 'receive-exam-materials-from-printer' || $permission === 'receive-bundle-from-mobile-team') {
            $user = current_user();
            // Check if the user's dept_off_id exists in EscortStaff
            if ($user->district_code === '01') {
                return false; // Re-enabling the return statement
            }
        } 
        // Check if user is logged in through any guard
        if (!isset($this->rolePermissions[$role])) {
            return false;
        }

        if ($role === 'headquarters') {
            $user = auth()->guard('headquarters')->user();
            // If user has 'VDS' as a custom role, set fixed values

            if ($user->custom_role == 'VDS') {
                $department = 'VDS';
                $dept_role = 'VDS';
            }
            elseif ($user->custom_role == 'ESCORT') {
                $department = 'ESCORT';
                $dept_role = 'ESCORT';
            }
            // Check if the user has a role
            else if ($user && $user->role) {
                $department = $user->role->role_department;
                $dept_role = $user->role->role_name;
            } else {
                // If no valid user role exists, return false
                return false;
            }
            return collect($this->rolePermissions[$role][$department][$dept_role] ?? [])
                ->contains(function ($allowedPermission) use ($permission) {
                    return \Illuminate\Support\Str::is($allowedPermission, $permission);
                });
        }

        return collect($this->rolePermissions[$role])
            ->contains(function ($allowedPermission) use ($permission) {
                return \Illuminate\Support\Str::is($allowedPermission, $permission);
            });
    }
}
