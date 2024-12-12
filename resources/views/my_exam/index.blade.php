@extends('layouts.app')
@section('title', ' My Exams')
@push('styles')
    <link href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/monokai-sublime.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/quill.core.css') }}" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/quill.snow.css') }}" />
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/prism-coy.css') }}" />
    <style>
        .exam-item {
            transition: background-color 0.2s ease;
        }

        .exam-item:hover {
            background-color: #f8f9fa;
        }

        .card-header {
            border-bottom: 0;
        }

        .exam-item:last-child {
            border-bottom: none !important;
        }
    </style>
@endpush
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
                <div class="col-xl-12 col-md-12 help-main md-view">

                    <div class="row">
                        <!-- Current Exams Section -->
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary  text-center py-3">
                                    <h5 class="mb-0 text-white">Current Exams</h5>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @foreach ($exams as $exam)

                                            @if (strtotime($exam->exam_main_startdate) <= strtotime(date('Y-m-d')))
                                                @php
                                                    $key = 1;
                                                @endphp
                                                <li class="list-group-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avtar avtar-s border"> {{ $key }}
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 mx-3"> {{ $exam->exam_main_name }} |
                                                            {{ $exam->exam_main_postname }} |
                                                            {{ $exam->exam_main_notification }} |
                                                            {{ $exam->exam_main_no }} |
                                                            {{-- {{ $exam_main->exam_sess_date }} | 
                                                            {{ $exam_main->exam_sess_time }} --}}
                                                        </div>

                                                        <div class="flex-shrink-0">
                                                            <a href="{{ route('my-exam.task-action', $exam->exam_main_no) }}"
                                                                class="avtar avtar-s btn-link-secondary">
                                                                <i class="ti ti-chevron-right f-20"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                @php
                                                    $key++;
                                                @endphp
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Upcoming Exams Section -->
                        <div class="col-12">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-secondary text-white text-center py-3">
                                    <h5 class="mb-0 text-white">Upcoming Exams</h5>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @php
                                            $key = 1;
                                        @endphp
                                        @foreach ($exams as $exam)
                                        @if (strtotime($exam->exam_main_startdate) > strtotime(date('Y-m-d')))
                                        <li class="list-group-item">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0">
                                                            <div class="avtar avtar-s border"> {{ $key }}
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1 mx-3"> {{ $exam->exam_main_name }} |
                                                            {{ $exam->exam_main_postname }} |
                                                            {{ $exam->exam_main_notification }} |
                                                            {{ $exam->exam_main_no }} |
                                                            {{-- {{ $exam_main->exam_sess_date }} | 
                                                            {{ $exam_main->exam_sess_time }} --}}
                                                        </div>

                                                        <div class="flex-shrink-0">
                                                            <a href="{{route('my-exam.task-action', $exam->exam_main_no) }}"
                                                                class="avtar avtar-s btn-link-secondary">
                                                                <i class="ti ti-chevron-right f-20"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </li>
                                                    @php
                                                        $key++;
                                                    @endphp
                                                @endif
                                        @endforeach
                                    </ul>
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

    @include('partials.theme')

@endsection
