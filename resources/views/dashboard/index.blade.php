@extends('layouts.app')
@section('title', ' Dashboard')

@section('content')

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
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ Main Content ] start -->
            <div class="row">
                @if (session('status'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <div class="col-md-6">
                    <div class="row">
                        <!-- First Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <img src="{{ asset('storage/assets/images/user/collectorate.png') }}"
                                                    width="24" height="24">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Districts</p>
                                            <h6 class="mb-0">{{ $districtCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Second Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Treasury Officers</p>
                                            <h6 class="mb-0">{{ $treasuryofficerCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Third Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <img src="{{ asset('storage/assets/images/user/center.png') }}"
                                                    width="24" height="24">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Centers</p>
                                            <h6 class="mb-0">{{ $centerCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fourth Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-users"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Mobile Team Officers</p>
                                            <h6 class="mb-0">{{ $mobileTeamStaffCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Five Card -->
                        {{-- <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <img src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                    width="24" height="24"
                                                    style="border-radius: 50%; object-fit: cover;">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Scribe</p>
                                            <h6 class="mb-0">1000</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <img src="{{ asset('storage/assets/images/user/venue.png') }}"
                                                    width="24" height="24">
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Venues</p>
                                            <h6 class="mb-0">{{ $venueCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Six Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-user-friends"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Chief Invigilators</p>
                                            <h6 class="mb-0">{{ $chiefInvigilatorCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Seven Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-clipboard"></i>

                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Examination Services</p>
                                            <h6 class="mb-0">{{ $examServiceCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Eight Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-bell"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Exam Notifications </p>
                                            <h6 class="mb-0">{{ $currentExamCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Nine Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-paste"></i>

                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Current Exams</p>
                                            <h6 class="mb-0">{{ $currentsExamCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Ten Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-paste"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Completed Exams </p>
                                            <h6 class="mb-0">{{ $completedExamCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Eleven Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-paste"></i>

                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Major Exams</p>
                                            <h6 class="mb-0">{{ $majorExamCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Twelve Card -->

                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-success">
                                                <i class="fas fa-paste"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Minor Exams </p>
                                            <h6 class="mb-0">{{ $minorExamCount }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Thirteen Card -->

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">Category wise exams</h5>
                            </div>

                            <div class="my-3">
                                <div id="overview-product-graph"></div> <!-- Chart Container -->
                            </div>

                            <div class="row g-3 text-center">
                                <div class="col-6 col-lg-6 col-xxl-6">
                                    <div class="overview-product-legends">
                                        <span>Objective Exams</span>
                                        <h6 class="mb-0">{{ $objectiveExamCount }}</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-6 col-xxl-6">
                                    <div class="overview-product-legends">
                                        <span>Descriptive Exams</span>
                                        <h6 class="mb-0">{{ $descriptiveExamCount }}</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-6 col-xxl-6">
                                    <div class="overview-product-legends">
                                        <span>Objective & Descriptive Exams</span>
                                        <h6 class="mb-0">{{ $objDescExamCount }}</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-6 col-xxl-6">
                                    <div class="overview-product-legends">
                                        <span>CBT Exams</span>
                                        <h6 class="mb-0">{{ $cbtExamCount }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    @include('partials.footer')

    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/apexcharts.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/pages/dashboard-analytics.js') }}"></script>
        {{-- <script src="../assets/js/pages/dashboard-default.js"></script> --}}
        <script src="{{ asset('storage/assets/js/pages/dashboard-default.js') }}"></script>
        <!-- JavaScript for ApexCharts -->
        <script>
            var options8 = {
                chart: {
                    height: 350,
                    type: 'pie'
                },
                labels: ['CBT Exams', 'Objective & Descriptive Exams', 'Descriptive Exams', 'Objective Exams'],
                series: [
                    {{ $cbtExamCount }},
                    {{ $objDescExamCount }},
                    {{ $descriptiveExamCount }},
                    {{ $objectiveExamCount }}
                ],
                colors: ['#2ca87f', '#199f73', '#FFC022', '#FF3E3E'],
                fill: {
                    opacity: [0.4, 0.6, 0.8, 1]
                },
                legend: {
                    show: false
                },
                dataLabels: {
                    enabled: true,
                    dropShadow: {
                        enabled: false
                    }
                },
                responsive: [{
                    breakpoint: 575,
                    options: {
                        chart: {
                            height: 250
                        },
                        dataLabels: {
                            enabled: false
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector('#overview-product-graph'), options8);
            chart.render();
        </script>
    @endpush

    @include('partials.theme')

@endsection
