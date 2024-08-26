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
        content="Able Pro is trending dashboard template made using Bootstrap 5 design framework. Able Pro is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
    <meta name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
    <meta name="author" content="Phoenixcoded" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="{{ asset('storage/assets/images/favicon.svg') }}" type="image/x-icon" />
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
            layout_change(savedTheme);
        })();
    </script>
</head>
</head>
<!-- [Head] end -->
<!-- [Body] Start -->

<body data-pc-preset="{{ env('THEME_PRESET', 'preset-8') }}" data-pc-sidebar-caption="true" data-pc-layout="vertical"
    data-pc-direction="ltr" data-pc-theme_contrast="" data-pc-theme=""  data-app-url="{{ url('/') }}">

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

    <!-- [Body] end -->
</body>


</html>
