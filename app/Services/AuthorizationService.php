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
                ],
            ],
            'APD' => [
                'Section Officer' => [
                    'upload-candidates-csv',
                    'download-expected-candidates',
                    'finalize-csv',
                    'downlaodConfirmedExamHalls',
                    'download-finalized-halls-csv',
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
                ],
            ],
            'ED' => [
                'Section Officer' => [
                    'upload-exam-materials-csv',
                    'download-exam-materials-uploaded',
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
        ],
        'venue' => [
            'venues-masters',
            'view-all-chief-invigilators',
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
            'view-all-center',

        ],
        'treasury' => [
            'receive-exam-materials-from-printer',
        ],
        'center' => [
            'download-meeting-qr',
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
            $department = $user->role->role_department;
            $dept_role = $user->role->role_name;
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