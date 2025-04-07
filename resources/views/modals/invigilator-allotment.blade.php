<!-- Invigilator Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="invigilatorAllotmentModel" tabindex="-1"
    aria-labelledby="invigilatorAllotmentModelLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="invigilatorAllotmentModelLabel">
                    <i class="feather icon-users me-2"></i>Invigilator Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form to Assign Invigilators -->
                <form id="invigilatorAllotmentForm" action="#" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-12">
                            <!-- Hall Allocation Table -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="allocation-table">
                                    <thead>
                                        <tr>
                                            <th>Hall No</th>
                                            <th>Invigilator</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to fetch and display the allotment details
            function fetchAllotmentDetails() {
                fetch("{{ route('staffalloment.view-invigilator-allocate') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            exam_id: '{{ $session->exam_sess_mainid }}',
                            exam_sess_date: '{{ $session->exam_sess_date }}',
                            exam_sess_session: '{{ $session->exam_sess_session }}',
                            exam_session_id: '{{ $session->exam_session_id }}'
                        })
                    })
                    .then(response => response.json())
                    .then(response => {
                        $('#allocation-table tbody').empty();

                        if (response.success && response.invigilators_allotment.hall_allocations.length > 0) {
                            response.invigilators_allotment.hall_allocations.forEach(function(allocation) {
                                var row = `<tr>
                    <td>${allocation.hall_code}</td>
                    <td>${allocation.invigilator_name} - ${allocation.invigilator_phone}</td>
                </tr>`;
                                $('#allocation-table tbody').append(row);
                            });
                        } else {
                            var row = `<tr><td colspan="2">${response.message}</td></tr>`;
                            $('#allocation-table tbody').append(row);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching allotment details:', error);
                        var row = '<tr><td colspan="2">Error fetching allotment details</td></tr>';
                        $('#allocation-table tbody').append(row);
                    });
            }

            // Trigger fetchAllotmentDetails when the modal is shown
            fetchAllotmentDetails();
        });
    </script>
@endpush
