@extends('layouts.app')

@section('title', 'Headquarters to Van Duty Staff')

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
                flex: 1 1 120px;
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
                                <h5 class="mb-3 mb-sm-0">Place Trunk Box & Scan By Order</h5>
                                <ul class="list-inline ms-auto  mb-0">

                                    <li class="list-inline-item"><a href="#" class="badge bg-dark f-14">Received
                                            {{ $totalScanned }} /
                                            {{ $totalTrunkBoxes }}</a></li>
                                    {{-- <li class="list-inline-item"> <a href="{{route('collectorate.create')}}" class="btn btn-outline-success">Scan Now</a></li> --}}
                                </ul>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <!-- Filter options -->
                            <form id="filterForm" class="mb-3" method="GET" action="">
                                <div class="filter-item">
                                    <select class="form-select" id="centerCodeFilter" name="centerCode">
                                        <option value="">Select Center</option>
                                    </select>
                                </div>
                                <div class="filter-item">
                                    <select class="form-select" id="examSessionFilter" name="examSession">
                                        <option value="">Select Exam Session</option>
                                        <option value="FN" {{ request('examSession') == 'FN' ? 'selected' : '' }}>FN
                                        </option>
                                        <option value="AN" {{ request('examSession') == 'AN' ? 'selected' : '' }}>AN
                                        </option>
                                    </select>
                                </div>
                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                                <div class="btn-container">
                                    <button type="button" id="resetButton"
                                        class="btn btn-secondary d-flex align-items-center"
                                        onclick="window.location.href=''">
                                        <i class="ti ti-refresh me-2"></i> Reset
                                    </button>
                                </div>
                                <div class="btn-container">
                                    <a href="#" class="btn btn-light-primary d-flex align-items-center"
                                        data-pc-animate="just-me" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                                        <i class="feather icon-aperture mx-1"></i>Scan
                                    </a>
                                </div>
                            </form>

                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap"
                                width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>District</th>
                                        <th>Center</th>
                                        <th>Hall no</th>
                                        <th>Exam Date</th>
                                        <th>Trunk Box</th>
                                        <th>Time Stamp</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($trunkBoxes as $trunkbox)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $trunkbox->district_code }}</td>
                                            <td>{{ $trunkbox->center_code }}</td>
                                            <td>{{ $trunkbox->hall_code }}</td>
                                            <td>{{ $trunkbox->exam_date }}</td>
                                            <td>{{ $trunkbox->trunkbox_qr_code }}</td>
                                          <td>
   											 @if ($trunkbox && $user->role && ($scanTime = ($user->role->role_department === 'ED' ? $trunkbox->hq_scanned_at : 													$trunkbox->dept_off_scanned_at)))
  												      {{ \Carbon\Carbon::parse($scanTime)->format('d-m-Y h:i:s') }}
   											 @else
      												  No Scans
  											 @endif
											</td>
                                            <td>
                                                {{ $trunkbox &&
                                                ($scanTime = $user->role->role_department === 'ED' ? $trunkbox->hq_scanned_at : $trunkbox->dept_off_scanned_at)
                                                    ? \Carbon\Carbon::parse($scanTime)->format('d-m-Y h:i:s')
                                                    : 'No Scans' }}
                                            </td>
                                        </tr>
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
@php
    $scanRoute = ($user->role?->role_department == 'ED') 
        ? route('bundle-packaging.scan-hq-exam-materials') 
        : route('scanTrunkboxOrder');
@endphp
    @push('scripts')
        @include('partials.datatable-export-js')
        <script src="{{ asset('storage//assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script>
            function processQrCode(data) {
                // Hide the modal using Bootstrap's modal method
                const qrCodeModal = document.getElementById('qrCodeModal');
                const modalInstance = bootstrap.Modal.getInstance(qrCodeModal);
                modalInstance.hide();
                let scanRoute = @json($scanRoute);
                fetch(scanRoute, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            qr_code: data,
                            exam_id: '{{ json_encode($myroute->exam_id) }}', // Replace with the actual exam_id value from $myroute->exam_id}}'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        showAlert(data.status, data.message);
                        $('#qrCodeModal').modal('hide'); // Close modal after successful scan
                        // Update the total scanned and total exam materials count
                    })
                    .catch((error) => {
                        showAlert(data.status, data.message);
                        $('#qrCodeModal').modal('hide');
                    });
            }

            function showAlert(type, message) {
                // Map the type to SweetAlert2's icon options
                let iconType;
                switch (type) {
                    case 'success':
                        iconType = 'success';
                        break;
                    case 'error':
                        iconType = 'error';
                        break;
                    case 'info':
                        iconType = 'info';
                        break;
                    case 'warning':
                        iconType = 'warning';
                        break;
                    default:
                        iconType = 'info'; // Default to 'info' if type is unknown
                }

                // Use SweetAlert2 to display the alert
                Swal.fire({
                    icon: iconType,
                    title: type.charAt(0).toUpperCase() + type.slice(1),
                    text: message,
                    timer: 5000, // Hide after 5 seconds
                    didOpen: () => {
                        setTimeout(() => {
                            Swal.close(); // Automatically close alert after 5 seconds
                        }, 5000);
                    }
                }).then((result) => {
                    window.location.reload(); // Reload the page when "OK" is clicked
                });
            }
        </script>
    @endpush

    @include('partials.theme')

@endsection
