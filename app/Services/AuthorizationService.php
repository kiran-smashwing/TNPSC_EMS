<?php

namespace App\Services;

class AuthorizationService
{
    private $rolePermissions = [
        'headquarters' => [
            'RND' => [
                'current-exam.create',
                'current-exam.store',
                'current-exam.edit',
            ],
            'APD' => [
                'upload-candidates-csv',
                'finalize-csv',
            ],
            'ID' => [
                'update-percentage',
            ],
            'ED' => [
                'upload-exam-materials-csv',
            ],
            'ADMIN' => [
                'ci-meetings.*',
                'users.*'
            ]
        ],
        'ci' => [
            'ci-meetings.ind',
            'ci-meetings.attendance-QRcode-scan'
        ],
        'district' => [
            'receive-exam-materials',
            'scan-exam-materials',
            'showVenueIntimationForm',
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
            return collect($this->rolePermissions[$role][$department] ?? [])
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