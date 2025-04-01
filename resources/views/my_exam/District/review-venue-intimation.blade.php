@extends('layouts.app')

@section('title', 'ID Confirm Venues')

@section('content')
    @push('styles')
        <!-- data tables css -->
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/dataTables.bootstrap5.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/rowReorder.bootstrap5.min.css') }}" />
        <style>
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
                justify-content: space-between;
                /* Aligns the button to the right */
            }

            @media (max-width: 421px) {
                .btn-container {
                    justify-content: center;
                }
            }

            .switch-lg {
                font-size: 2em;
            }

            .form-check-label {
                font-size: 1.2rem !important;
                /* Keep the label text size normal */
            }

            .table-responsive {
                overflow-y: auto;
                overflow-x: auto;
                max-width: 100%;
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

                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->

            <div class="row">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- [ basic-table ] start -->
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center justify-content-between">
                                    <h5 class="mb-3 mb-sm-0">Review Confirmed Venues</h5>
                                    <div>
                                        <h5 class="mb-3 mb-sm-0">Required : {{ $confirmedVenuesCapacity }} / {{$accommodation_required}}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Filter options -->
                                <form id="filterForm" class="mb-3" method="GET">
                                    @csrf
                                    <input type="hidden" name="exam_id" value="{{ $exam->exam_main_no }}">

                                    <div class="filter-item">
                                        <select class="form-select" id="centerCodeFilter" name="center_code">
                                            <option value="">Select Center Code</option>
                                            @foreach ($centers as $center)
                                                <option value="{{ $center->center_code }}"
                                                    {{ request('center_code') == $center->center_code ? 'selected' : '' }}>
                                                    {{ $center->center_code }} - {{ $center->center_name }}
                                                </option>
                                            @endforeach
                                            <!-- Centers will be dynamically populated -->
                                        </select>
                                    </div>
                                    <div class="filter-item" style="max-width: 130px;">
                                        <select class="form-select" id="examDateFilter" name="exam_date"
                                            class="form-select">
                                            @foreach ($examDates as $examDate)
                                                <option value="{{ $examDate }}"
                                                    {{ request('exam_date') == $examDate ? 'selected' : '' }}>
                                                    {{ Carbon\Carbon::parse($examDate)->format('d-m-Y') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                   
                                    <div class="btn-container">
                                        <button type="submit" class="btn btn-primary">Apply Filters</button>
                                        <button type="button" id="resetButton"
                                            class="btn btn-secondary d-flex align-items-center"
                                            onclick="window.location.href='{{ route('district-candidates.reviewVenueIntimationForm', $exam->exam_main_no) }}'">
                                            <i class="ti ti-refresh me-2"></i> Reset
                                        </button>
                                    </div>
                                </form>

                                <div class="dt-responsive table-responsive">
                                    <table id="reorder-events" class="table table-striped table-bordered nowrap">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>VENUE NAME</th>
                                                <th>VENUE CODE</th>
                                                <th>E-MAIL</th>
                                                <th>PHONE</th>
                                                <th>ADDRESS</th>
                                                <th>EXAM DATE</th>
                                                <th>CI NAME</th>
                                                <th>CI EMAIL</th>
                                                <th>CI PHONE</th>
                                                <th>CANDIDATES COUNT</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($venuesWithCIs as $key => $item)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $item['venue']->venues->venue_name }}</td>
                                                    <td>{{ $item['venue']->venues->venue_code }}</td>
                                                    <td>{{ $item['venue']->venues->venue_email }}</td>
                                                    <td>{{ $item['venue']->venues->venue_phone }}</td>
                                                    <td>{{ $item['venue']->venues->venue_address }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['ci']->exam_date)->format('d-m-Y') ?? 'No Date' }}</td>
                                                    <td>{{ $item['ci']->chiefInvigilator->ci_name ?? 'No CI Assigned' }}
                                                    </td>
                                                    <td>{{ $item['ci']->chiefInvigilator->ci_email ?? 'N/A' }}</td>
                                                    <td>{{ $item['ci']->chiefInvigilator->ci_phone ?? 'N/A' }}</td>
                                                    <td>{{ $item['candidates_count'] ?? 0 }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>S.No</th>
                                                <th>VENUE NAME</th>
                                                <th>VENUE CODE</th>
                                                <th>E-MAIL</th>
                                                <th>PHONE</th>
                                                <th>ADDRESS</th>
                                                <th>EXAM DATE</th>
                                                <th>CI NAME</th>
                                                <th>CI EMAIL</th>
                                                <th>CI PHONE</th>
                                            </tr>
                                        </tfoot>
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
        <!-- datatable Js -->
        <script src="{{ asset('storage/assets/js/plugins/dataTables.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
        <script>
            // [ Reorder Events ]
            var rowevents = $('#reorder-events').DataTable({
                rowReorder: false,
                paging: false, // Disable pagination
                language: {
                    emptyTable: "Please select a center, or no venues have been confirmed yet.", // Custom message
                }
            });
        </script>
       
    @endpush

    @include('partials.theme')



@endsection
