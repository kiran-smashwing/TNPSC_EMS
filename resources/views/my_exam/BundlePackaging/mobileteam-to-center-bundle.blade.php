@extends('layouts.app')

@section('title', 'Mobile Team to Center Treasury')

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

            #swal2-html-container {
                z-index: 9999 !important;
                overflow: visible !important;
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
                                <h5 class="mb-3 mb-sm-0">Mobile Team to Center Treasury
                                </h5>
                                <ul class="list-inline ms-auto  mb-0">
                                    {{-- <li class="list-inline-item"><a href="#" class="badge bg-dark f-14">Received
                                            {{ $totalScanned }} /
                                            {{ $totalExamMaterials }}</a></li> --}}
                                    {{-- <li class="list-inline-item"> <a href="{{route('collectorate.create')}}" class="btn btn-outline-success">Scan Now</a></li> --}}
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <!-- Filter options -->
                            <form id="filterForm" class="mb-3" method="GET"
                                action="{{ route('bundle-packaging.mobileteam-to-center', ['examId' => $examId]) }}">

                                <div class="filter-item">
                                    <select class="form-select" id="examDateFilter" name="examDate" class="form-control">
                                        <option value="">Select Exam Date</option>
                                        @foreach ($examDates as $examDate)
                                            <option value="{{ $examDate }}"
                                                {{ request('examDate') == $examDate ? 'selected' : '' }}>
                                                {{ Carbon\Carbon::parse($examDate)->format('d-m-Y') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="btn-container">
                                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                                </div>
                                <div class="btn-container">
                                    <button type="button" id="resetButton"
                                        class="btn btn-secondary d-flex align-items-center"
                                        onclick="window.location.href='{{ route('bundle-packaging.mobileteam-to-center', ['examId' => $examId]) }}'">
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
                                        <th>Route No</th>
                                        <th>Center Code</th>
                                        <th>Hall No</th>
                                        <th>Trunkbox Code</th>
                                        <th>OTL Locks</th>
                                        <th>Materials Count</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedExamMaterials as $data)
                                        <tr>
                                            <td>{{ $data['route_no'] }}</td>
                                            <td>{{ $data['center_code'] }}</td>
                                            <td>{{ $data['hall_code'] }}</td>
                                            <td>{{ $data['trunkbox_qr_code'] }}</td>
                                            <td>{{ implode(', ', $data['otl_codes']) }}</td>
                                            <td>{{ $data['scanned_count'] }} / {{ $data['materials_count'] }}</td>
                                            @php
                                                $usedOtl = json_decode($data['used_otl_code'], true); // Decode to array
                                                $hasUsedOtl = is_array($usedOtl) && count($usedOtl) > 0;
                                            @endphp

                                            <td>
                                                <a class="avtar avtar-xs {{ $hasUsedOtl ? 'btn-light-danger' : 'btn-light-success' }} bs-ajex-req"
                                                    data-trunkbox="{{ $data['trunkbox_qr_code'] }}"
                                                    data-otl="{{ json_encode($data['otl_codes']) }}"
                                                    title="{{ $hasUsedOtl ? 'OTL already used' : 'Assign OTL' }}">
                                                    <i
                                                        class="ti {{ $hasUsedOtl ? 'ti-alert-triangle' : 'ti-checkbox' }} f-20"></i>
                                                </a>
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
        @include('modals.qr-code-bulk-modal')
    </section>
    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
        <script src="{{ asset('storage/assets/js/plugins/choices.min.js') }}"></script>

        <script src="{{ asset('storage/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script>
            // Submit all scanned QR codes
            document.getElementById("submitScannedCodesBtn").addEventListener("click", function() {
                if (scannedCodes.length === 0) {
                    alert("No exam materials scanned yet!");
                    return;
                }
                // Hide the modal using Bootstrap's modal method
                const qrCodeModal = document.getElementById('qrCodeModal');
                const modalInstance = bootstrap.Modal.getInstance(qrCodeModal);
                modalInstance.hide();
                // Show loader before sending the request
                const loader = document.getElementById('loader');
                if (loader) {
                    loader.style.removeProperty('display');
                }
                fetch("{{ route('bundle-packaging.scan-center-exam-materials', $examId) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            qr_codes: scannedCodes
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Process the returned results array to build a detailed message
                        let successCount = 0;
                        let errorCount = 0;
                        let successMessages = [];
                        let errorMessages = [];
                        data.results.forEach(result => {
                            if (result.status === 'success') {
                                successCount++;
                                successMessages.push(`QR: ${result.qr_code} - ${result.message}`);
                            } else {
                                errorCount++;
                                errorMessages.push(`QR: ${result.qr_code} - ${result.message}`);
                            }
                        });
                        let overallMessage = "";
                        if (successCount > 0) {
                            overallMessage += `${successCount} QR code(s) scanned successfully.\n` + successMessages
                                .join("\n");
                        }
                        if (errorCount > 0) {
                            overallMessage += `\n${errorCount} QR code(s) encountered errors:\n` + errorMessages
                                .join("\n");
                        }
                        // Hide loader once the request completes
                        if (loader) {
                            loader.style.display = 'none';
                        }
                        // Show a detailed alert with the aggregated results
                        showAlert(data.status, overallMessage);
                        // Optionally clear the list after submission
                        scannedCodes = [];
                        updateScannedListUI();
                    })
                    .catch((error) => {
                        // Hide loader once the request completes
                        if (loader) {
                            loader.style.display = 'none';
                        }
                        console.error("Submission error:", error);
                        showAlert("error", "Submission failed");
                    });
            });

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

                }).then((result) => {
                    window.location.reload(); // Reload the page when "OK" is clicked
                });
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Use event delegation to handle clicks on .bs-ajex-req buttons
                document.querySelector('#res-config tbody').addEventListener('click', function(event) {
                    const button = event.target.closest('.bs-ajex-req');
                    if (!button) return; // Exit if the click is not on a .bs-ajex-req button

                    let trunkboxQrCode = button.getAttribute('data-trunkbox');
                    let otlCodes = JSON.parse(button.getAttribute('data-otl'));

                    // Create multi-select dropdown options
                    let optionsHtml = otlCodes.map(code =>
                        `<option value="${code}">${code}</option>`
                    ).join('');

                    Swal.fire({
                        title: 'Select Used OTL Codes',
                        html: `
                        <div class="form-group">
                            <select class="form-select" 
                                id="otlSelect" name="otlSelect[]" multiple>
                                ${optionsHtml}
                            </select>
                        </div>
                    `,
                        showCancelButton: true,
                        confirmButtonText: 'Submit',
                        didOpen: () => {
                            // Initialize Choices.js on the select element after the modal is opened
                            const choicesInstance = new Choices('#otlSelect', {
                                removeItemButton: true,
                                placeholderValue: 'Select OTL Codes',
                                position: 'bottom',
                                searchEnabled: true,
                                searchChoices: true,
                                multiple: true,
                                itemSelectText: ''
                            });
                        },
                        preConfirm: () => {
                            let selectEl = document.getElementById('otlSelect');
                            let selectedOptions = Array.from(selectEl.selectedOptions)
                                .map(option => option.value);

                            if (selectedOptions.length === 0) {
                                Swal.showValidationMessage('Please select at least one OTL code');
                                return false;
                            }

                            return {
                                trunkboxQrCode,
                                otlCodes: selectedOptions
                            };
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch('{{ route('bundle-packaging.save-center-used-otl-codes') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        ...result.value,
                                        examId: '{{ $examId }}'
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Swal.fire('Success!', 'OTL Codes updated successfully.',
                                            'success')
                                        .then(() => {
                                            window.location
                                                .reload(); // Reload after alert is closed
                                        });

                                })
                                .catch(error => {
                                    Swal.fire('Error!', 'Something went wrong.', 'error');
                                });
                        }
                    });
                });
            });
        </script>
    @endpush

    @include('partials.theme')

@endsection
