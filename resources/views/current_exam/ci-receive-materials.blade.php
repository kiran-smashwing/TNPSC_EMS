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
    <!-- [ Sidebar Menu ] start -->
    @include('partials.sidebar')
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('partials.header')
    <!-- [ Main Content ] start -->
    <section class="pc-container">
        <div class="pc-content">

            <!-- [ Main Content ] start -->
            <div class="row text-center mb-4">
                <a href="#" class="btn btn-light-primary d-flex align-items-center mx-auto" style="width: 100px;"
                    data-pc-animate="just-me" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                    <i class="feather icon-aperture mx-1"></i>Scan
                </a>
            </div>
            <div class="row justify-content-center">
                <!-- [ basic-table ] start -->

                <div class="col-xl-4 ">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">24-07-2024 - FN</h5>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>QP Box</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>OMR Packet</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">24-07-2024 - AN</h5>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>QP Box</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-dark">
                                    <thead>
                                        <tr>
                                            <th>OMR Packet</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>24-07-2024 9:30 AM</td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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
        <script src="{{ asset('storage//assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script>
            function processQrCode(data) {
                // Hide the modal using Bootstrap's modal method
                const qrCodeModal = document.getElementById('qrCodeModal');
                const modalInstance = bootstrap.Modal.getInstance(qrCodeModal);
                modalInstance.hide();
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
