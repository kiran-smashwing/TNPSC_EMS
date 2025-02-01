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
                <div class="col-md-6">

                    <div class="row">
                        <!-- First Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 22C12 22 4 14.36 4 9.5C4 5.36 7.36 2 11.5 2C15.64 2 19 5.36 19 9.5C19 14.36 12 22 12 22Z"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <circle cx="12" cy="9.5" r="2.5" stroke="#4680FF"
                                                        stroke-width="1.5" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Districts</p>
                                            <h6 class="mb-0">38</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12 22C12 22 4 14.36 4 9.5C4 5.36 7.36 2 11.5 2C15.64 2 19 5.36 19 9.5C19 14.36 12 22 12 22Z"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <circle cx="12" cy="9.5" r="2.5" stroke="#4680FF"
                                                        stroke-width="1.5" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Centers</p>
                                            <h6 class="mb-0">317</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12 2L3 7L12 12L21 7L12 2Z" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M5 7V19H19V7" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M9 19V12H15V19" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M3 19H21" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Venues</p>
                                            <h6 class="mb-0">10,000</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="7" r="4" stroke="#4680FF"
                                                        stroke-width="1.5" />
                                                    <path d="M4 21C4 17.13 7.13 14 11 14H13C16.87 14 20 17.13 20 21"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Chief Invigilators</p>
                                            <h6 class="mb-0">20,000</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Five Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="7" r="4" stroke="#4680FF"
                                                        stroke-width="1.5" />
                                                    <path d="M4 21C4 17.13 7.13 14 11 14H13C16.87 14 20 17.13 20 21"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Scribe</p>
                                            <h6 class="mb-0">1000</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="7" r="4" stroke="#4680FF"
                                                        stroke-width="1.5" />
                                                    <path d="M4 21C4 17.13 7.13 14 11 14H13C16.87 14 20 17.13 20 21"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Treasury Officers</p>
                                            <h6 class="mb-0">50</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <circle cx="12" cy="7" r="4" stroke="#4680FF"
                                                        stroke-width="1.5" />
                                                    <path d="M4 21C4 17.13 7.13 14 11 14H13C16.87 14 20 17.13 20 21"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Mobile Team </p>
                                            <h6 class="mb-0">50</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M4 4C4 2.89543 4.89543 2 6 2H18C19.1046 2 20 2.89543 20 4V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4Z"
                                                        stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M8 2V22" stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M12 6H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 10H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 14H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>

                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Exam Services</p>
                                            <h6 class="mb-0">70</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <!-- Bell shape -->
                                                    <path
                                                        d="M12 4C8.68629 4 6 6.68629 6 10V16L4 18H20L18 16V10C18 6.68629 15.3137 4 12 4Z"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <!-- Bell clapper -->
                                                    <path d="M12 4V3" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <!-- Bottom curve -->
                                                    <path d="M9 18C9 19.6569 10.3431 21 12 21C13.6569 21 15 19.6569 15 18"
                                                        stroke="#4680FF" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Notifications </p>
                                            <h6 class="mb-0">100</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M4 4C4 2.89543 4.89543 2 6 2H18C19.1046 2 20 2.89543 20 4V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4Z"
                                                        stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M8 2V22" stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M12 6H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 10H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 14H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>

                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Current Exams</p>
                                            <h6 class="mb-0">15</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M4 4C4 2.89543 4.89543 2 6 2H18C19.1046 2 20 2.89543 20 4V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4Z"
                                                        stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M8 2V22" stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M12 6H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 10H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 14H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Completed Exams </p>
                                            <h6 class="mb-0">85</h6>
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
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M4 4C4 2.89543 4.89543 2 6 2H18C19.1046 2 20 2.89543 20 4V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4Z"
                                                        stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M8 2V22" stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M12 6H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 10H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 14H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>

                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Major Exams</p>
                                            <h6 class="mb-0">30</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Thirteen Card -->
                        <div class="col-md-6">
                            <div class="card mb-2">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <div class="avtar avtar-s bg-light-secondary">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M4 4C4 2.89543 4.89543 2 6 2H18C19.1046 2 20 2.89543 20 4V20C20 21.1046 19.1046 22 18 22H6C4.89543 22 4 21.1046 4 20V4Z"
                                                        stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M8 2V22" stroke="#4680FF" stroke-width="1.5" />
                                                    <path d="M12 6H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 10H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                    <path d="M12 14H16" stroke="#4680FF" stroke-width="1.5"
                                                        stroke-linecap="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 mx-2">
                                            <p class="mb-1">Total Minor Exams </p>
                                            <h6 class="mb-0">70</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <h5 class="mb-0">Exams</h5>
                            </div>

                            <div class="my-3">
                                <div id="overview-product-graph"></div> <!-- Chart Container -->
                            </div>

                            <div class="row g-3 text-center">
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <span>Total Objective Exams</span>
                                        <h6 class="mb-0">20</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <span>Total Descriptive Exams</span>
                                        <h6 class="mb-0">30</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <span>Total Objective & Descriptive Exams</span>
                                        <h6 class="mb-0">25</h6>
                                    </div>
                                </div>
                                <div class="col-6 col-lg-4 col-xxl-4">
                                    <div class="overview-product-legends">
                                        <span>Total CBT Exams</span>
                                        <h6 class="mb-0">25</h6>
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
        {{-- <script src="../assets/js/pages/dashboard-default.js"></script> --}}
        <script src="{{ asset('storage/assets/js/pages/dashboard-default.js') }}"></script>
        <!-- JavaScript for ApexCharts -->
        <script>
            var options8 = {
                chart: {
                    height: 350,
                    type: 'pie'
                },
                labels: ['Total CBT Exams', 'Total Objective & Descriptive Exams', 'Total Descriptive Exams', 'Total Objective Exams'],
                series: [25, 25, 30, 20],
                colors: ['#4680FF', '#4680FF', '#212529', '#212529', '#212529', '#212529'],
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
