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
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"> --}}
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
            background-color: rgba(var(--bs-body-bg-rgb), 1) !important;
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
    <!-- Pusher JS (compatible with Reverb) -->
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
    <!-- Laravel Echo -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.3/echo.iife.js"></script>

    @php
        $user = current_user();
    @endphp

</head>

<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="{{ env('THEME_PRESET', 'preset-8') }}" data-pc-sidebar-caption="true" data-pc-layout="vertical"
    data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme="" data-app-url="{{ asset('/') }}">
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
    <!-- This is the install button for Android or supported browsers -->
    <button hidden id="install-button"
        style=" background-color: #258F6C; color: white; padding: 10px; border: none; cursor: pointer; position: fixed; bottom: 20px; right: 20px; z-index: 10000;">
        Install App
    </button>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
        integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script src="https://openfpcdn.io/fingerprintjs/v4"></script>
    <script>
        const fpPromise = import('https://openfpcdn.io/fingerprintjs/v4')
            .then(FingerprintJS => FingerprintJS.load());

        fpPromise.then(fp => fp.get()).then(result => {
            const fingerprint = result.visitorId;
            // Attach the fingerprint to all fetch() requests
            document.cookie = "device_fingerprint=" + fingerprint +
                "; path=/; SameSite=Lax; Secure";
        });
    </script>

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
        function getRelativeTime(timestamp) {
            if (!timestamp) return 'Just now';

            // Convert "YYYY-MM-DD HH:mm:ss" to a format recognized by the Date constructor (ISO 8601)
            let timeString = timestamp.indexOf(' ') !== -1 ?
                timestamp.replace(' ', 'T') :
                timestamp;
            const time = new Date(timeString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - time) / 1000);

            if (diffInSeconds < 5) {
                return 'Just now';
            }
            if (diffInSeconds < 60) {
                return diffInSeconds + ' seconds ago';
            }

            const diffInMinutes = Math.floor(diffInSeconds / 60);
            if (diffInMinutes < 60) {
                return diffInMinutes + ' minutes ago';
            }

            const diffInHours = Math.floor(diffInMinutes / 60);
            if (diffInHours < 24) {
                return diffInHours + ' hours ago';
            }

            const diffInDays = Math.floor(diffInHours / 24);
            return diffInDays + ' days ago';
        }

        // Toast notification helper function
        function showNotification(title, message, type = 'success', timestamp = null, route = '#') {
            // Play notification sound
            const notificationSound = new Audio("{{ asset('storage/assets/sounds/notification.wav') }}");
            // notificationSound.play().catch((error) => {
            //     // Handle the error gracefully (e.g., log it)
            //     console.warn("Audio playback prevented:", error);
            // });
            var promise = notificationSound.play();

            if (promise !== undefined) {
                promise.then(_ => {
                    // Autoplay started!
                }).catch(error => {
                    // Autoplay was prevented.
                    console.warn("Audio playback prevented:", error);
                });
            }

            // Create unique ID for the toast
            const toastId = 'toast-' + Date.now();

            // Get style classes based on type
            let headerClass = 'bg-light';
            let icon = '{{ asset('storage/assets/images/favicon-32x32.png') }}';

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
            // Get relative time string (if timestamp is provided)
            const relativeTime = getRelativeTime(timestamp);
            // Create toast HTML
            const toastHtml = `
        <div class="bg-body p-2 mb-2">
            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true" id="${toastId}">
                <div class="toast-header ${headerClass}">
                    <img src="${icon}" class="img-fluid m-r-5" alt="icon" style="width: 17px">
                    <strong class="me-auto">${title}</strong>
                        <small>${relativeTime}</small>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                 <a href="${route}" class="text-decoration-none text-dark" style="display: block;">
                <div class="toast-body">
                    ${message}
                </div>
            </a>
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
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker
                .register('{{ asset('storage/assets/js/service-worker.js') }}')
                .then((registration) => {
                    console.log('Service Worker Registered:', registration);
                })
                .catch((error) => {
                    console.error('Service Worker Registration Failed:', error);
                });
        }
    </script>
    <script>
        let installPrompt = null;
        const installButton = document.querySelector("#install-button");

        window.addEventListener("beforeinstallprompt", (event) => {
            event.preventDefault();
            installPrompt = event;
            installButton.removeAttribute("hidden");
        });

        installButton.addEventListener("click", async () => {
            if (!installPrompt) {
                return;
            }
            const result = await installPrompt.prompt();
            console.log(`Install prompt was: ${result.outcome}`);
            installPrompt = null;
            installButton.setAttribute("hidden", "");
        });
    </script>

    <script>
        var user = @json($user);

        if (user && user.role && user.role.role_department === 'MCD') {
            // Initialize Echo using Reverb settings from your environment
            // Modified Echo initialization with detailed logging
            window.Echo = new Echo({
                broadcaster: 'pusher',
                key: '{{ env('REVERB_APP_KEY') }}',
                wsHost: '{{ env('REVERB_HOST') }}',
                wsPort: '{{ env('REVERB_PORT', 8080) }}',
                forceTLS: ('{{ env('REVERB_SCHEME', 'http') }}' === 'https'),
                encrypted: true,
                enabledTransports: ['ws', 'wss'],
                auth: {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                }
            });


            // Connection status logging
            window.Echo.connector.pusher.connection.bind('connecting', () => {
                console.log('Attempting to connect to Reverb...');
            });

            window.Echo.connector.pusher.connection.bind('connected', () => {
                console.log('Successfully connected to Reverb');
            });

            window.Echo.connector.pusher.connection.bind('disconnected', () => {
                console.log('Disconnected from Reverb');
            });

            window.Echo.connector.pusher.connection.bind('error', (error) => {
                console.log('Connection error:', error);
            });

            Echo.channel('alerts')
                .listen('.EmergencyAlertEvent', (e) => {
                    console.log('Emergency Alert Received:', e);
                    showNotification(
                        'Emergency Alert: ' + (e.alertData.details || 'No details provided'),
                        `<b>District</b>: ${e.alertData.district} <b>Center:</b> ${e.alertData.center}
                 </br> <b>Venue:</b> ${e.alertData.venue}</br><b>Remarks:</b> ${e.alertData.remarks}`,
                        'error',
                        e.alertData.timestamp, // Timestamp provided from the server
                        '{{ route('emergency-alarm-notification.report') }}'
                    );
                })
                .listen('.AdequacyCheckEvent', (e) => {
                    console.log('Adequacy Check Received:', e);
                    showNotification(
                        'Adequacy Check: ' + (e.alertData.details || 'No details provided'),
                        `<b>District</b>: ${e.alertData.district} <b>Center:</b> ${e.alertData.center}
                 </br> <b>Venue:</b> ${e.alertData.venue}</br><b>Remarks:</b> ${e.alertData.remarks}`,
                        'error',
                        e.alertData.timestamp,
                        '{{ route('emergency-alarm-notification.report') }}'
                    );
                })

            window.Echo.connector.pusher.connection.bind('error', function(error) {
                console.error('Pusher Error:', error);
            });
        }
    </script>
    @if (session('show_verification_alert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <form id="resendVerificationForm" action="{{ route('user.resend-verification-link') }}" method="POST"
            style="display: none;">
            @csrf
        </form>

        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Email Not Verified!',
                html: 'Please verify your email by clicking on the link sent to your inbox.<br><br>' +
                    '<button id="resendBtn" class="swal2-confirm swal2-styled" style="background-color:#3085d6;">Click here to resend</button>',
                showConfirmButton: false
            });

            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('resendBtn')?.addEventListener('click', function() {
                    document.getElementById('resendVerificationForm').submit();
                });
            });
        </script>
    @endif

    @php
        session()->forget('show_verification_alert');
    @endphp

    <!-- [Body] end -->
</body>

</html>
