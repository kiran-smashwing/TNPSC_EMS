@extends('layouts.app')

@section('title', 'Current Exam')
@push('styles')
    <link rel="stylesheet" href="{{ asset('storage/assets/css/plugins/datepicker-bs5.min.css') }}" />
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
    <!-- [ Header Topbar ] end -->
    <!-- [ Main Content ] start -->
    <div class="pc-container">
        <div class="pc-content">

            <!-- [ Main Content ] start -->
            <div class="row">
                <!-- [ sample-page ] start -->
                <div class="col-sm-12">
                    <div class="chat-wrapper">
                        <div class="offcanvas-xxl offcanvas-start chat-offcanvas" tabindex="-1" id="offcanvas_User_list" style="max-width:100%;">
                            <div class="offcanvas-header">
                                <button class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#offcanvas_User_list"
                                    aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body p-0">
                                <div id="chat-user_list" class="show collapse collapse-horizontal" style="max-width:100%;">
                                    <div class="chat-user_list"style="width: 100%;" >
                                        <div class="card overflow-hidden">
                                            <div class="card-header">
                                                <h5 class="">District Collectorates <span
                                                        class="me-2 btn btn-sm bg-light-primary">{{ $groupedDistrictCount }}/{{ $totalDistricts }}</span>
                                                </h5>
                                            </div>
                                            <div class="scroll-block">
                                                <div class="card-body p-0">
                                                    <div class="list-group list-group-flush">
                                                        @foreach ($districts as $district)
                                                            <a href="#"
                                                                class="list-group-item list-group-item-action p-3">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="chat-avtar">
                                                                        <input
                                                                            class="form-check-input input-success district-checkbox"
                                                                            type="checkbox"
                                                                            checked
                                                                            data-district-id="{{ $district['district_code'] }}"
                                                                            data-district-code="{{ $district['district_code'] }}">
                                                                    </div>
                                                                    <div class="flex-grow-1 mx-2">
                                                                        <h6 class="mb-0">{{ $district['district_name'] }}
                                                                        </h6>
                                                                        <span class="text-sm text-muted">
                                                                            {{ $district['sent_at'] ? \Carbon\Carbon::parse($district['sent_at'])->format('d-m-Y h:i:s A') : 'Email not sent yet' }}
                                                                        </span>
                                                                    </div>
                                                                    <div class="chat-avtar">
                                                                        <span class="chat-badge-status text-warning">
                                                                            {{ $district['total_accommodation_required'] }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body p-0">
                                                <div class="list-group list-group-flush">
                                                    <a href="#" id="send-mail-btn"
                                                        onclick="sendSelectedDistricts({{ $examId }})"
                                                        class="list-group-item list-group-item-action d-flex align-items-center gap-2 bg-light-success">
                                                        <i class="ti ti-mail-forward" style="font-size: 19px;"></i>
                                                        <span>Send Mail (<span
                                                                id="selected-count">{{ count($districts) }}</span>)</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                     
                    </div>
                </div>
                <!-- [ sample-page ] end -->
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- [ Main Content ] end -->

    @include('partials.footer')
    @push('scripts')
        <script src="{{ asset('storage/assets/js/plugins/sweetalert2.all.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/datepicker-full.min.js') }}"></script>

        <!-- [Page Specific JS] start -->
        <script>
            // scroll-block
            var tc = document.querySelectorAll('.scroll-block');
            for (var t = 0; t < tc.length; t++) {
                new SimpleBar(tc[t]);
            }
            setTimeout(function() {
                var element = document.querySelector('.chat-content .scroll-block .simplebar-content-wrapper');
                var elementheight = document.querySelector('.chat-content .scroll-block .simplebar-content');
                var off = elementheight.getBoundingClientRect();
                var h = off.height;
                element.scrollTop += h;
            }, 100);
        </script>
        <script>
            function sendSelectedDistricts(examId) {
                // Retrieve input values
                // const letterNo = document.getElementById('letter_no').value.trim();
                // const letterDate = document.getElementById('letter_date').value.trim();
                // const examController = document.getElementById('exam_controller').value.trim();

                // // Validate that all required fields are filled
                // if (!letterNo || !letterDate || !examController) {
                //     Swal.fire({
                //         icon: 'error',
                //         title: 'Required Fields Missing',
                //         text: 'Please fill in Letter No, Letter Date, and Exam Controller before sending the mail.'
                //     });
                //     return;
                // }

                // Get all checked checkboxes
                const selectedCheckboxes = document.querySelectorAll('.district-checkbox:checked');

                // Collect selected district codes
                const selectedDistrictCodes = Array.from(selectedCheckboxes)
                    .map(checkbox => checkbox.getAttribute('data-district-code'));

                // Prepare data to send
                const payload = {
                    exam_id: examId,
                    district_codes: selectedDistrictCodes,
                    // letter_no: letterNo,
                    // letter_date: letterDate,
                    // exam_controller: examController
                };
                console.log(payload);

                $.ajax({
                    url: '{{ route('id-candidates.send-accommodation-email') }}', // Your route
                    method: 'POST',
                    contentType: 'application/json',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for security
                    },
                    data: JSON.stringify(payload),
                    beforeSend: function() {
                        // Show loader before the request starts
                        const loader = document.getElementById('loader');
                        if (loader) {
                            loader.style.removeProperty('display');
                        }
                    },
                    success: function(response) {
                        // Hide loader once the request completes
                        const loader = document.getElementById('loader');
                        if (loader) {
                            loader.style.display = 'none';
                        }

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: response.message,
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                window.location.reload(); // Reload the page when "OK" is clicked
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error || 'Failed to send emails',
                                confirmButtonText: 'Try Again'
                            }).then((result) => {
                                window.location.reload(); // Reload the page when "OK" is clicked
                            });
                        }
                    },
                    error: function(xhr) {
                        // Hide loader in case of error
                        const loader = document.getElementById('loader');
                        if (loader) {
                            loader.style.display = 'none';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while sending emails. Please try again later.',
                            confirmButtonText: 'Close'
                        }).then((result) => {
                            window.location.reload(); // Reload the page when "OK" is clicked
                        });
                        console.error('Error:', xhr.responseText);
                    }
                });

            }

            document.addEventListener('DOMContentLoaded', function() {
                // Initialize datepickers for existing elements
                // document.querySelectorAll('.input-group.date input').forEach(function(input) {
                //     new Datepicker(input, {
                //         buttonClass: 'btn',
                //         todayBtn: true,
                //         clearBtn: true,
                //         format: 'dd-mm-yyyy'
                //     });
                // });
                // Get all district checkboxes
                const checkboxes = document.querySelectorAll('.district-checkbox');
                const selectedCountSpan = document.getElementById('selected-count');

                // Add event listener to each checkbox
                checkboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', updateSelectedCount);
                });

                function updateSelectedCount() {
                    // Count checked checkboxes
                    const selectedCount = document.querySelectorAll('.district-checkbox:checked').length;

                    // Update the count in the "Send Mail" button
                    selectedCountSpan.textContent = selectedCount;
                }
            });
        </script>
        <!-- [Page Specific JS] end -->
    @endpush
    @include('partials.theme')

@endsection