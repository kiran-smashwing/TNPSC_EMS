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
                    'create-exam-materails-route',
                    'view-all-districts',
                    'view-all-chief-invigilators',
                    'district-masters',
                    'venues-masters',
                    'view-all-venue',
                    'center-filters',
                    'treasury-officers-filter',
                    'mobile-team-staffs-filter',
                    'departments-masters',
                    'confirmExamVenueHalls',
                    'downlaodConfirmedExamHalls',
                    'heading',
                    'create-exam-materials-route',
                    'view-all-examination-services',
                    'view-all-department',
                    'exam-heading',
                    'current-exam',
                    'exam-completed',
                    'report-heading',
                    'candidate-attendance',
                    'cv-down-updates',
                    'bundle-collection',
                    'omr-qca-delivered',
                    'chv-routes',
                    'replacement-omr-qca',
                    'emergency-alrm',
                    'exam-discrepancy',
                    'candidate-remarks',
                    'candidate-statement',
                    'expenditure-statment',
                    'ci-meeting',

                ],
            ],
            'ED' => [
                'Section Officer' => [
                    'verify-bundle-recevied-at-hq',
                    'exam-heading',
                    'current-exam',
                    'cv-down-updates',
                    'exam-completed',
                    'bundle-collection'
                ],
            ],
            'VMD' => [
                'Section Officer' => [
                    'verify-materials-handovered',
                    'exam-heading',
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
                    'omr-qca-delivered'
                ],
            ],
            'ADMIN' => [
                'ci-meetings.*',
                'users.*'
            ]
        ],
        'ci' => [
            'ci-meetings.ind',
            'ci-meetings.attendance-QRcode-scan',
            'venues-masters',
            'heading',
            'exam-heading',
            'current-exam',
        ],
        'venue' => [
            'venues-masters',
            'heading',
            'view-all-chief-invigilators',
            'exam-heading',
            'current-exam',
        ],
        'district' => [
            'receive-exam-materials',
            'scan-exam-materials',
            'showVenueIntimationForm',
            'receive-exam-materials-from-printer',
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
        ],
        'treasury' => [
            'receive-exam-materials-from-printer',
            'receive-bundle-from-mobile-team',
            'exam-heading',
            'current-exam',
        ],
        'center' => [
            'download-meeting-qr',
            'exam-heading',
            'current-exam',
        ],
        'mobile_team_staffs' => [
            'current-exam',
        ],
        // Add other roles and their permissions
    ];

    public function hasPermission($role, $permission)
    {
        // Check if user is sw-admin first
        if ($role === 'sw-admin') {
            return true; // sw-admin has access to everything
        }

        // Check if user is logged in through any guard
        if (!isset($this->rolePermissions[$role])) {
            return false;
        }

        if ($role === 'headquarters') {
            $user = auth()->guard('headquarters')->user();

            // Check if the user has a role
            if ($user && $user->role) {
                $department = $user->role->role_department;
                $dept_role = $user->role->role_name;

                return collect($this->rolePermissions[$role][$department][$dept_role] ?? [])
                    ->contains(function ($allowedPermission) use ($permission) {
                        return \Illuminate\Support\Str::is($allowedPermission, $permission);
                    });
            }

            // If the user doesn't have a role, return false or handle accordingly
            return false;
        }

        return collect($this->rolePermissions[$role])
            ->contains(function ($allowedPermission) use ($permission) {
                return \Illuminate\Support\Str::is($allowedPermission, $permission);
            });
    }
}