<!doctype html>
<html lang="en">
<!-- [Head] start -->

<head>
    <title>@yield('title', 'TNPSC EMS') | Tamil Nadu Public Service Commission </title>

    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description"
        content="TNPSC EMS is a comprehensive examination management system designed to efficiently manage and streamline TNPSC-related processes and functionalities. Developed and maintained by Smashwing Technologies." />
    <meta name="keywords"
        content="Tamil Nadu, தமிழ்நாடு, TNPSC, TNPSC EMS, Examination Management System, Tamil Nadu Public Service Commission, தமிழ்நாடு அரசுப் பணியாளர் தேர்வாணையம், Smashwing Technologies, Exam Management, TNPSC Software Solutions" />
    <meta name="author" content="Smashwing Technologies" />
    <!-- SEO and Indexing -->
    <meta name="robots" content="index, follow, max-snippet:-1, max-video-preview:-1, max-image-preview:large" />
    <meta name="googlebot" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1" />
    <!-- Open Graph / Social Media -->
    <meta property="og:title" content="TNPSC EMS - Examination Management System" />
    <meta property="og:description"
        content="Innovative digital platform for efficient TNPSC examination managment processes" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:site_name" content="TNPSC EMS - Examination Management System" />
    <meta property="og:locale" content="en_IN" />
    <!-- Geo Tags -->
    <meta name="geo.region" content="IN-TN" />
    <meta name="geo.placename" content="Tamil Nadu" />
    <!-- Language and Localization -->
    <meta http-equiv="content-language" content="en, ta" />
    <!-- [Favicon] icon -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('storage/assets/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/assets/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/assets/images/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('storage/assets/site.webmanifest') }}">
    <!-- [Font] Family -->
    <link rel="stylesheet" href="{{ asset('storage/assets/fonts/inter/inter.css') }}" id="main-font-link" />

    <link href="{{ asset('storage/assets/css/plugins/animate.min.css') }}" rel="stylesheet" type="text/css">
    <!-- [Phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="{{ asset('storage/assets/fonts/phosphor/duotone/style.css') }}" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="{{ asset('storage/assets/fonts/tabler-icons.min.css') }}" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="{{ asset('storage/assets/fonts/feather.css') }}" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="{{ asset('storage/assets/fonts/fontawesome.css') }}" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="{{ asset('storage/assets/fonts/material.css') }}" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="{{ asset('storage/assets/css/style.css') }}" id="main-style-link" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/style-preset.css') }}" />
    <!-- [Page Specific CSS] end -->
    @stack('styles')
    <!-- [Page Specific CSS] end -->
    <script>
        (function() {
            var savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-pc-theme', savedTheme);
            document.addEventListener('DOMContentLoaded', function() {
                layout_change(savedTheme);
            });
        })();
    </script>
    <style>
        /* Loader styles */
        .loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color:  rgba(var(--bs-body-bg-rgb),1) !important;
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loader img {
            width: 196px;
            height: 100px;
            margin-bottom: 20px;
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border-left-color: #09f;
            animation: spin 1s ease infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="{{ env('THEME_PRESET', 'preset-8') }}" data-pc-sidebar-caption="true" data-pc-layout="vertical"
    data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="" data-app-url="{{ url('/') }}">
    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <!-- Toasts will be dynamically inserted here -->
    </div>
    <!-- Loader -->
    <div class="loader" id="loader">
        <img src="{{ asset('storage/assets/images/loader-image.png') }}" alt="Logo">
        <div class="spinner"></div>
    </div>
    <!--Loader End -->
    @yield('content')
    <script>
        // Get the base URL from the data attribute
        const baseUrl = document.querySelector('body').getAttribute('data-app-url');
    </script>
    <!-- Required Js -->
    <script src="{{ asset('storage/assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('storage/assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('storage/assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('storage/assets/js/fonts/custom-font.js') }}"></script>
    <script src="{{ asset('storage/assets/js/pcoded.js') }}"></script>
    <script src="{{ asset('storage/assets/js/plugins/feather.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script>
        var savedTheme = localStorage.getItem('theme') || 'light';
        layout_change(savedTheme);
    </script>

    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        const preset = @json(env('THEME_PRESET', 'preset-8'));
        preset_change(preset);
    </script>

    <script>
        main_layout_change('vertical');
    </script>
    <!-- [Page Specific JS] start -->
    @stack('scripts')
    <!-- [Page Specific JS] end -->
    <script>
        // Toast notification helper function
        function showNotification(title, message, type = 'success') {
            // Create unique ID for the toast
            const toastId = 'toast-' + Date.now();

            // Get style classes based on type
            let headerClass = 'bg-light';
            let icon = '../assets/images/favicon.svg';

            switch (type) {
                case 'success':
                    headerClass = 'bg-success text-white';
                    break;
                case 'error':
                    headerClass = 'bg-danger text-white';
                    break;
                case 'warning':
                    headerClass = 'bg-warning';
                    break;
                case 'info':
                    headerClass = 'bg-info text-white';
                    break;
            }

            // Create toast HTML
            const toastHtml = `
        <div class="bg-body p-2 mb-2">
            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}">
                <div class="toast-header ${headerClass}">
                    <img src="${icon}" class="img-fluid m-r-5" alt="icon" style="width: 17px">
                    <strong class="me-auto">${title}</strong>
                    <small>Just now</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        </div>
    `;

            // Add toast to container
            const container = document.getElementById('toast-container');
            container.insertAdjacentHTML('beforeend', toastHtml);

            // Initialize the Bootstrap toast
            const toastElement = document.getElementById(toastId);
            const toast = new bootstrap.Toast(toastElement, {
                delay: 3000,
                animation: true
            });

            // Remove toast element after it's hidden
            toastElement.addEventListener('hidden.bs.toast', function() {
                this.parentElement.remove();
            });
        }
    </script>
    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('loader');
            if (loader) {
                loader.style.display = 'none';
            }
        });
    </script>
    <!-- [Body] end -->
</body>


</html>
