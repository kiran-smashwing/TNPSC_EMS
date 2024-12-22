@extends('layouts.app')

@section('title', 'Materials From Printer to Disitrct Treasury')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/buttons.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/responsive.bootstrap5.min.css') }}" />

        <style>
            /* Container and row adjustments */
            .dataTables_wrapper .container-fluid {
                padding: 0;
            }

            .dataTables_wrapper .row {
                display: flex;
                flex-wrap: wrap;
            }

            /* Ensure full width on small screens */
            .dataTables_wrapper .col-sm-12 {
                flex: 0 0 100%;
            }

            /* Adjust columns for medium and larger screens */
            .dataTables_wrapper .col-md-6 {
                flex: 0 0 50%;
            }

            /* Align buttons and controls */
            .dataTables_wrapper .d-flex {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }

            /* Adjust for specific DataTables controls */
            .dataTables_wrapper .dt-buttons {
                margin: 0;
                padding: 0;
            }

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: auto;
                /* Adjust width as needed */
            }

            /* Responsive adjustments for small screens */
            @media (max-width: 768px) {
                .dataTables_wrapper .col-md-6 {
                    flex: 0 0 100%;
                    margin-bottom: 1rem;
                }

                [data-pc-direction="ltr"] .flex-wrap {
                    flex-wrap: nowrap !important;
                }

                div.dt-container div.dt-length label {
                    display: none;
                }

                .dataTables_wrapper .d-flex {
                    justify-content: space-between;
                }

                .dataTables_wrapper .dataTables_length,
                .dataTables_wrapper .dataTables_filter {
                    flex-direction: column;
                    align-items: flex-start;
                }

            }

            @media (max-width: 421px) {
                div.dt-container div.dt-search {
                    margin-bottom: 18px;
                }
            }

            /* Flexbox container for the form */
            #filterForm {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                /* Adds space between items */
                align-items: center;
            }

            /* Flexbox item for filters */
            .filter-item {
                flex: 1 1 200px;
                /* Adjusts basis to a minimum width, grows and shrinks as needed */
            }

            /* Align button to the end */
            .btn-container {
                flex: 1 1 200px;
                /* Ensures button is on the same row */
                display: flex;
                justify-content: flex-end;
                /* Aligns the button to the right */
            }

            @media (max-width: 421px) {
                .btn-container {
                    justify-content: center;
                }
            }
        </style>
    @endpush
    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    @include('partials.sidebar')
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('partials.header')
    <!-- [ Main Content ] start -->
    <section class="pc-container">
        <div class="pc-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">

                        <div class="col-md-12">
                            <!-- <div class="page-header-title">
                                  <h2 class="mb-0"></h2>
                                </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ basic-table ] start -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">Printer to Disitrct Treasury</h5>
                                <ul class="list-inline ms-auto  mb-0">

                                    <li class="list-inline-item"><a href="#" class="badge bg-dark f-14">Received 40 /
                                            100</a></li>
                                    {{-- <li class="list-inline-item"> <a href="{{route('collectorate.create')}}" class="btn btn-outline-success">Scan Now</a></li> --}}
                                </ul>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <!-- Filter options -->
                            <form id="filterForm" class="mb-3">
                                <div class="filter-item">
                                    <select class="form-select" id="districtFilter" name="district">
                                        <option value="">Select District</option>
                                        <option value="Vellore">Vellore</option>
                                        <option value="Chennai">Chennai</option>
                                        <option value="Coimbatore">Coimbatore</option>
                                    </select>
                                </div>
                                <div class="filter-item">
                                    <select class="form-select" id="centerCodeFilter" name="centerCode">
                                        <option value="">Select Center Code</option>
                                        <option value="00101">00101</option>
                                        <option value="00102">00102</option>
                                        <option value="00103">00103</option>
                                    </select>
                                </div>
                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                                <div class="btn-container">
                                    <a href="#" class="me-2 btn btn-sm btn-light-primary " data-pc-animate="just-me"
                                        data-bs-toggle="modal" data-bs-target="#qrCodeModal"><i
                                            class="feather icon-eye mx-1 "></i>Scan </a>
                                </div>
                            </form>

                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Center</th>
                                        <th>Hall no</th>
                                        <th>Exam Date</th>
                                        <th>Exam Session</th>
                                        <th>QP / OMR No</th>
                                        <th>Total Count</th>
                                        <th>Time Stamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $counter = 1; @endphp
                                    @foreach ($examMaterials as $centerGroups)
                                        @foreach ($centerGroups as $hallGroups)
                                            @foreach ($hallGroups as $dateGroups)
                                                @foreach ($dateGroups as $group)
                                                    <tr>
                                                        <td>{{ $counter++ }}</td>
                                                        <td>{{ $group['center']->center_code }} -
                                                            {{ $group['center']->center_name }}</td>
                                                        <td>{{ $group['hall_code'] }}</td>
                                                        <td>{{ date('d-m-Y', strtotime($group['exam_date'])) }}</td>
                                                        <td>{{ $group['exam_session'] }}</td>
                                                        <td>{{ $group['d1_count'] }} / {{ $group['d2_count'] }}</td>
                                                        <td>{{ $group['total_count'] }}</td>
                                                        <td>{{ date('d-m-Y h:i A', strtotime($group['created_at'])) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ basic-table ] end -->
        </div>
        <!-- [ Main Content ] end -->
        </div>
        @include('modals.qr-code-modal')
    </section>
    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');

            let lastScannedCode = null;
            let scanning = true;
            let videoStream = null;

            // Function to start camera
            function startCamera() {
                scanning = true;
                navigator.mediaDevices.getUserMedia({
                        video: {
                            facingMode: "environment"
                        },
                        audio: false
                    })
                    .then(function(stream) {
                        videoStream = stream;
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.play();
                        requestAnimationFrame(tick);
                    })
                    .catch(function(error) {
                        console.error("Error accessing the camera:", error);
                    });
            }

            // Function to stop camera
            function stopCamera() {
                scanning = false;
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null;
                }
                if (video.srcObject) {
                    video.srcObject = null;
                }
            }

            // Initialize modal events
            document.addEventListener('DOMContentLoaded', function() {
                const qrCodeModal = document.getElementById('qrCodeModal');

                // Start camera when modal opens
                qrCodeModal.addEventListener('shown.bs.modal', function() {
                    startCamera();
                });

                // Stop camera when modal closes
                qrCodeModal.addEventListener('hidden.bs.modal', function() {
                    stopCamera();
                });
            });

            function tick() {
                if (video.readyState === video.HAVE_ENOUGH_DATA && scanning) {
                    canvas.height = video.videoHeight;
                    canvas.width = video.videoWidth;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    var code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code) {
                        scanning = false;
                        lastScannedCode = code.data;
                        console.log("QR code found:", lastScannedCode);
                        processQrCode(lastScannedCode);
                        stopCamera();
                    }
                }

                if (scanning) {
                    requestAnimationFrame(tick);
                }
            }

            function processQrCode(data) {
                fetch("{{ route('ci-meetings.attendance-QRcode-scan') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            qr_code: data
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        localStorage.setItem('qrCodeResult', JSON.stringify({
                            type: data.status == 'success' ? 'success' : 'error',
                            message: data.message
                        }));
                        $('#qrCodeModal').modal('hide'); // Close modal after successful scan
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                        localStorage.setItem('qrCodeResult', JSON.stringify({
                            type: 'danger',
                            message: error
                        }));
                        $('#qrCodeModal').modal('hide');
                    });
            }
            // Add this to ensure cleanup on page unload
            window.addEventListener('beforeunload', function() {
                stopCamera();
            });
        </script>
    @endpush

    @include('partials.theme')

@endsection
