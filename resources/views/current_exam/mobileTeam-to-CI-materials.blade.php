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
                <div class="">
                    <a href="{{ route('qr-code-reader') }}" class="me-2 btn btn-light-primary"><i
                            class="feather icon-aperture mx-1"></i>Scan QR Code</a>
                </div>
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

    </section>

    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage//assets/js/plugins/sweetalert2.all.min.js')}}"></script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const qrCodeResult = localStorage.getItem('qrCodeResult');
                if (qrCodeResult) {
                    const result = JSON.parse(qrCodeResult);
                    showAlert(result.type, result.message);
                    localStorage.removeItem('qrCodeResult'); // Clear the result after showing
                }
            });
        
            function showAlert(type, message) {
                // Map the type to SweetAlert2's icon options
                let iconType;
                switch(type) {
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
                });
            }
        </script>
        
    @endpush

    @include('partials.theme')



@endsection
