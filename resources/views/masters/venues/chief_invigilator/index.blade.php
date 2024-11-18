@extends('layouts.app') 

@section('title', 'Chief Invigilator')

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
            /* Full width on small screens */
            .dataTables_wrapper .col-sm-12 {
                flex: 0 0 100%;
            }
            /* Column adjustments for medium and large screens */
            .dataTables_wrapper .col-md-6 {
                flex: 0 0 50%;
            }
            /* Align buttons and controls */
            .dataTables_wrapper .d-flex {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
            /* Styling for DataTables controls */
            .dataTables_wrapper .dt-buttons, .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
                margin: 0;
                padding: 0;
                display: flex;
                align-items: center;
            }
            /* Responsive adjustments */
            @media (max-width: 768px) {
                .dataTables_wrapper .col-md-6 {
                    flex: 0 0 100%;
                    margin-bottom: 1rem;
                }
                .dataTables_wrapper .d-flex {
                    justify-content: space-between;
                }
                .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
                    flex-direction: column;
                    align-items: flex-start;
                }
            }
        </style>
    @endpush

    <div class="page-loader">
        <div class="bar"></div>
    </div>

    @include('partials.sidebar')
    @include('partials.header')

    <section class="pc-container">
        <div class="pc-content">
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <h5 class="mb-3 mb-sm-0">Chief Invigilator List</h5>
                        </div>
                    </div>
                </div>
            </div>

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
                
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header d-sm-flex align-items-center justify-content-between">
                            <h5>Chief Invigilator List</h5>
                            <a href="{{ route('chief-invigilator.create') }}" class="btn btn-outline-success">Add Chief Invigilator</a>
                        </div>
                        <div class="card-body table-border-style">
                            <table id="res-config" class="display table table-striped table-hover dt-responsive nowrap" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        {{-- <th>Invigilator Code</th> --}}
                                        <th>E-mail</th>
                                        <th>Phone</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($chiefInvigilator as $key => $invigilator)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        @if ($invigilator->ci_image)
                                                            <img src="{{ asset('storage/' . $invigilator->ci_image) }}"
                                                                alt="cropped_image" class="img-radius wid-40">
                                                        @else
                                                            <img src="{{ asset('storage/assets/images/user/avatar-4.jpg') }}"
                                                                alt="default image" class="img-radius wid-40">
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $invigilator->ci_name }}</td>
                                            {{-- <td>{{ $invigilator->ci_code }}</td> --}}
                                            <td>{{ $invigilator->ci_email }}</td>
                                            <td>{{ $invigilator->ci_phone }}</td>
                                            <td class="text-center">
                                                <i class="ti ti-circle-check text-success f-18"></i>
                                            </td>
                                            <td>
                                                <a href="{{ route('chief-invigilator.show', $invigilator->ci_id) }}" class="avtar avtar-xs btn-light-success">
                                                    <i class="ti ti-eye f-20"></i>
                                                </a>
                                                <a href="{{ route('chief-invigilator.edit', $invigilator->ci_id) }}" class="avtar avtar-xs btn-light-success">
                                                    <i class="ti ti-edit f-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No invigilators found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    @push('scripts')
        @include('partials.datatable-export-js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Additional custom JavaScript if needed
            });
        </script>
    @endpush

    @include('partials.theme')
@endsection
