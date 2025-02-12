@extends('layouts.app')

@section('title', 'Increase Candidates Count')

@section('content')
    @push('styles')
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/buttons.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/responsive.bootstrap5.min.css') }}" />

        <style>
            .editable input {
                width: 100%;
                padding: 5px;
                box-sizing: border-box;
            }
        </style>
    @endpush

    <!-- [ Pre-loader ] start -->
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->

    @include('partials.sidebar')
    @include('partials.header')

    <section class="pc-container">
        <div class="pc-content">
            <div class="row text-center mb-4">
                <div class="">
                    {{-- <a href="#" class="">
                        <i class="feather icon-aperture mx-1"></i>Scan OR Code
                    </a> --}}
                    <a href="#" class="me-2 btn btn-light-primary" data-pc-animate="just-me" data-bs-toggle="modal"
                        data-bs-target="#qrCodeModal">
                        <i class="feather icon-aperture mx-1"></i>Scan OR Code
                    </a>
                </div>
            </div>

            <div class="row justify-content-center">
                @foreach ($examMaterials as $session => $materials)
                    <div class="col-xl-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h5 class="mb-3 mb-sm-0">{{ $exam_date }} - {{$session}}</h5>
                                </div>
                            </div>
                            <div class="card-body table-border-style">
                                @if ($exam_type->exam_sess_type === 'Objective')
                                    <div class="table-responsive">
                                        <table class="table table-dark">
                                            <thead>
                                                <tr>
                                                    <th>QP Box</th>
                                                    <th>Timestamp</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php $counter = 1; @endphp
                                                @foreach ($materials as $material)
                                                    @if ($material->category == 'D1')
                                                        <tr>
                                                            <td>{{ $counter++ }}</td>
                                                            <td>
                                                                @if ($material->examMaterialsScan && !is_null($material->examMaterialsScan->ci_scanned_at))
                                                                    {{ \Carbon\Carbon::parse($material->examMaterialsScan->ci_scanned_at)->format('d-m-Y h:i:s') }}
                                                                @else
                                                                    No Scans
                                                                @endif
                                                            </td>
                                                        </tr>
                                                        {{-- @php dd($material->examMaterialsScan->ci_scanned_at); @endphp <!-- Debugging the material object --> --}}
                                                    @endif
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-dark">
                                        <thead>
                                            <tr>
                                                <th>
                                                    @if ($exam_type->exam_sess_type === 'Objective')
                                                        OMR Packet
                                                    @elseif ($exam_type->exam_sess_type === 'Descriptive')
                                                        Answer Booklet
                                                    @endif
                                                </th>
                                                <th>Timestamp</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $counter = 1;
                                                $allowedCategory =
                                                    $exam_type->exam_sess_type === 'Objective' ? 'D2' : 'D1';
                                            @endphp
                                            @foreach ($materials as $material)
                                                @if ($material->category === $allowedCategory)
                                                    <tr>
                                                        <td>{{ $counter++ }}</td>
                                                        <td>
                                                            @if ($material->examMaterialsScan && $material->examMaterialsScan->ci_scanned_at)
                                                                {{ \Carbon\Carbon::parse($material->examMaterialsScan->ci_scanned_at)->format('d-m-Y h:i:s') }}
                                                            @else
                                                                No Scans
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('modals.qr-code-modal')

    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/sweetalert2.all.min.js') }}"></script>

        <script>
            function processQrCode(data) {
                // Hide the modal using Bootstrap's modal method
                const qrCodeModal = document.getElementById('qrCodeModal');
                const modalInstance = bootstrap.Modal.getInstance(qrCodeModal);
                modalInstance.hide();
                fetch("{{ route('receive-exam-materials.scan-ci-exam-materials', $examId) }}", {
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
