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
                <a target="_blank" href="http://localhost/ems/public/current-exam/ciReceiveMaterials"
                    class="me-2 btn btn-light-primary"><i class="feather icon-aperture mx-1"></i>Scan QR Code</a></div>
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
        <script src="{{ asset('storage/assets/js/plugins/dataTables.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/dataTables.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/buttons.colVis.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/buttons.print.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/pdfmake.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/jszip.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/vfs_fonts.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/buttons.bootstrap5.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('storage/assets/js/plugins/responsive.bootstrap5.min.js') }}"></script>
        <script>
            // jQuery to toggle the visibility of the percentage input
            $('#customswitchv2-3').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#percentageInput').show(); // Show the input when checked
                } else {
                    $('#percentageInput').hide(); // Hide the input when unchecked
                }
            });
        </script>
        <script>
            var table = $('#res-config').DataTable({
                deferRender: true,
                scrollY: 200,
                scrollCollapse: true,
                scroller: true
            });
            // Make the "Actions" column editable
            $('#res-config').on('click', 'td.editable', function() {
                var currentCell = table.cell(this);
                var currentValue = currentCell.data();
                var rowId = $(this).closest('tr').data('row-id');

                var input = $('<input type="text">')
                    .val(currentValue)
                    .on('blur', function() {
                        var newValue = $(this).val();
                        saveChanges(rowId, newValue, currentCell);
                    })
                    .on('keypress', function(e) {
                        if (e.which === 13) { // Enter key
                            var newValue = $(this).val();
                            saveChanges(rowId, newValue, currentCell);
                        }
                    });

                $(this).html(input);
                input.focus();
            });

            function saveChanges(rowId, newValue, cell) {
                // Here you would typically send an AJAX request to save the changes
                // For demonstration, we'll just update the cell and log the change
                cell.data(newValue).draw();
                console.log('Row ID:', rowId, 'New Value:', newValue);

                // Example AJAX call (uncomment and modify as needed):
                /*
                $.ajax({
                    url: '#',
                    method: 'POST',
                    data: {
                        rowId: rowId,
                        newValue: newValue,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        console.log('Update successful', response);
                    },
                    error: function(xhr, status, error) {
                        console.error('Update failed', error);
                        // Optionally revert the cell to its original value
                    }
                });
                */
            }
        </script>
        <script>
            // Listen for the 'show.bs.modal' event on any modal
            document.addEventListener('show.bs.modal', function(event) {
                var modal = event.target; // Get the modal being triggered
                var button = event.relatedTarget; // Button that triggered the modal
                var recipient = button.getAttribute('data-pc-animate'); // Get data attribute for animation type

                // Update the modal title and apply animation class
                var modalTitle = modal.querySelector('.modal-title');
                modalTitle.textContent = 'Animate Modal: ' + recipient;
                modal.classList.add('anim-' + recipient);

                // Optionally, apply animation to the body for specific cases
                if (recipient == 'let-me-in' || recipient == 'make-way' || recipient == 'slip-from-top') {
                    document.body.classList.add('anim-' + recipient);
                }
            });

            // Listen for the 'hidden.bs.modal' event on any modal
            document.addEventListener('hidden.bs.modal', function(event) {
                var modal = event.target; // Get the modal being hidden
                removeClassByPrefix(modal, 'anim-');
                removeClassByPrefix(document.body, 'anim-');
            });

            // Helper function to remove classes by prefix
            function removeClassByPrefix(node, prefix) {
                var classesToRemove = Array.from(node.classList).filter(function(c) {
                    return c.startsWith(prefix);
                });
                classesToRemove.forEach(function(c) {
                    node.classList.remove(c);
                });
            }
        </script>
    @endpush

    @include('partials.theme')



@endsection
