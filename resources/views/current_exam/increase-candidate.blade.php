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
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">

                        <div class="col-md-12">
                            <!-- <div class="page-header-title">
                                      <h2 class="mb-0"></h2>
                                    </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->


            <!-- [ Main Content ] start -->
            <div class="row">

            </div>
            <div class="row">
                <!-- [ basic-table ] start -->
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h5 class="mb-3 mb-sm-0">Increase Candidates Count</h5>

                            </div>
                        </div>
                        <div class="card-body table-border-style">
                            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
                                <!-- Switch with a hidden percentage input -->
                                <div class="d-flex align-items-center mb-2">
                                    <div class="form-check form-switch custom-switch-v1">
                                        <input type="checkbox" class="form-check-input input-success" id="customswitchv2-3">
                                        <label class="form-check-label" for="customswitchv2-3">Auto</label>
                                    </div>
                                    <!-- Percentage input (initially hidden) -->
                                    <input type="number" id="percentageInput" class="form-control form-control-sm ms-2"
                                        style="width: 70px; display: none;" placeholder="%" min="0" max="100">
                                </div>
                                <!-- Buttons container -->
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="#" class="btn btn-sm btn-light-primary">
                                        <i class="feather icon-download mx-1"></i>Expected Candidates Excel
                                    </a>
                                    <ahref="#" data-pc-animate="blur" data-bs-toggle="modal"
                                    data-bs-target="#sendCenterMailModel" class="btn btn-sm btn-light-primary">
                                    <i class="feather icon-send mx-1"></i>Send Email
                                    </a>
                                </div>
                            </div>
                            <table id="res-config" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Center Code</th>
                                        <th>Center Name</th>
                                        <th>Date</th>
                                        <th>Session </th>
                                        <th>Candidate</th>
                                        <th class="editable">Tenative Candidate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr data-row-id="1">
                                        <td>1</td>
                                        <td>0101</td>
                                        <td>Chennai</td>
                                        <td>20-06-2024</td>
                                        <td>FN</td>
                                        <td>10000</td>
                                        <td class="editable">0</td>
                                    </tr>
                                    <tr data-row-id="2">
                                        <td>2</td>
                                        <td>0201</td>
                                        <td>Coimbatore</td>
                                        <td>20-06-2024</td>
                                        <td>FN</td>
                                        <td>5000</td>
                                        <td class="editable">0</td>
                                    </tr>
                                    <tr data-row-id="3">
                                        <td>3</td>
                                        <td>2201</td>
                                        <td>Thiruvannamlai</td>
                                        <td>20-06-2024</td>
                                        <td>FN</td>
                                        <td>7900</td>
                                        <td class="editable">0</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-12 text-end btn-page">
                        <div class="btn btn-outline-secondary">Cancel</div>
                        <div class="btn btn-primary">Save</div>
                    </div>
                </div>
            </div>
            <!-- [ basic-table ] end -->
            @include('modals.send-center-mail')
        </div>
        <!-- [ Main Content ] end -->
        </div>

    </section>

    <!-- [ Main Content ] end -->
    @include('partials.footer')

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
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
