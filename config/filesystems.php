<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'permissions' => [
                'file' => [
                    'public' => 0644,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0700,
                ],
            ],
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        'private' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'visibility' => 'private',
            'permissions' => [
                'file' => [
                    'public' => 0644,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0755,
                    'private' => 0700,
                ],
            ],
        ],

        'secure' => [
            'driver' => 'local',
            'root' => storage_path('app/secure'),
            'visibility' => 'private',
            'permissions' => [
                'file' => [
                    'public' => 0600,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0700,
                    'private' => 0700,
                ],
            ],
            'throw' => true,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

    // Allowed mime types and their extensions
    'allowed_types' => [
        'image' => [
            'mimes' => ['image/jpeg', 'image/png', 'image/gif'],
            'extensions' => ['jpg', 'jpeg', 'png', 'gif'],
            'max_size' => 5120, // 5MB
        ],
        'document' => [
            'mimes' => [
                'application/pdf',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
            'extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx'],
            'max_size' => 10240, // 10MB
        ],
    ],

    // Security settings
    'security' => [
        'sanitize_filenames' => true,
        'check_mime_type' => true,
        'max_filename_length' => 255,
        'disallowed_chars' => ['/', '\\', '?', '%', '*', ':', '|', '"', '<', '>', '.', ' '],
        'scan_files' => env('SCAN_UPLOADED_FILES', true),
        'encrypt_files' => env('ENCRYPT_UPLOADED_FILES', false),
    ],

];
