@extends('layouts.app')

@section('title', 'Exam Service')
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
    <div class="page-loader">
        <div class="bar"></div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    @include('partials.sidebar')
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    @include('partials.header')




    <section class="pc-container">
        <div class="pc-content"><!-- [ breadcrumb ] start -->
            <div class="row"><!-- Config table start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>Exam Process - Centre</h5>
                        </div>
                        <div class="card-body">
                            <table id="datatbale" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Center Name</th>
                                        <th>Taluk Name</th>
                                        <th>Venue Name</th>
                                        <th>Address</th>
                                        <th>Venue Email</th>
                                        <th>Candidate Count</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>CHENNAI</td>
                                        <td>AYANAVARAM</td>
                                        <td>W. P. A. SOUNDARAPANDIAN HR. SEC. SCHOOL</td>
                                        <td>66 & 76, N.M.K.STREET,</td>
                                        <td>wpas_hrsec@yahoo.com</td>
                                        <td>200</td>
                                        <td><button type="button" class="btn btn-primary btn-sm">Accepted</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- Config table end --><!-- `New` Constructor table start -->
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                        </div>
                        <div class="card-body">
                            <table id="datatbales" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Center/Taluk Code</th>
                                        <th>Center/Taluk Name</th>
                                        <th>Date</th>
                                        <th>Candidate/Tentative Count</th>
                                        <th>No of Insitution(s) Selected</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>0101</td>
                                        <td>Chennai</td>
                                        <td>15/08/2024</td>
                                        <td>500</td>
                                        <td>0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- Config table end --><!-- `New` Constructor table start -->
            </div><!-- [ Main Content ] end -->
    
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Venue List</h5>
                        </div>
                        <div class="card-body">
                            <table id="datatbaless" class="display table table-striped table-hover dt-responsive nowrap" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Taluk Name</th>
                                        <th>Venue Name</th>
                                        <th>Address</th>
                                        <th>Type</th>
                                        <th>Phone</th>
                                        <th>No of Rooms</th>
                                        <th>ID</th>
                                        <th>Center Code</th>
                                        <th>P.Center Code</th>
                                        <th>Venue Email</th>
                                        <th>Sensitive</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>ADYAR</td>
                                        <td>JAIGOPAL GARODIA GOVERNMENT GIRLS HIGHER SECONDARY SCHOOL</td>
                                        <td>THODHUNTER NAGAR</td>
                                        <td>GOVT</td>
                                        <td>04424333128</td>
                                        <td>0</td>
                                        <td>284</td>
                                        <td>0111</td>
                                        <td>0101</td>
                                        <td>pennathur1905@yahoo.co.in</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div><!-- Venue List end -->
    
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Venue Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h6>Total No. of Candidates to be allotted (Taluk)</h6>
                                </div>
                                <div>
                                    <p class="mb-0">500</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h6>No. of Institutions Selected</h6>
                                </div>
                                <div>
                                    <p class="mb-0">0</p>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <h6>Total No. of Candidates Received</h6>
                                </div>
                                <div>
                                    <p class="mb-0">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div><!-- Venue Details end -->
                
                
            </div><!-- New row end -->
        </div><!-- [ breadcrumb ] end -->
    </section>
    

    </div>
    </div>
    </div><!-- [Page Specific JS] start --><!-- datatable Js -->


    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
    @endpush

    @include('partials.theme')



@endsection
