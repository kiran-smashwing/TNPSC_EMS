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
                                        @php
                                            // Shuffle the invigilators_type array to randomize the invigilators
                                            $shuffledInvigilators = collect($invigilators_type)->shuffle();

                                            // Create an array of hall numbers
                                            $hallNumbers = range(1, count($shuffledInvigilators));
                                        @endphp

                                        @foreach ($shuffledInvigilators as $key => $data)
                                            @php
                                                // Get the invigilator ID from the data
                                                $invigilator_id = $data['invigilators'];

                                                // Fetch invigilator details based on invigilator_id
                                                $invigilator = \App\Models\Invigilator::find($invigilator_id);

                                                // Check if the invigilator exists (avoid null reference error)
                                                if (!$invigilator) {
                                                    continue;
                                                }

                                                // Assign hall numbers sequentially
                                                $hallNo = str_pad($hallNumbers[$key], 3, '0', STR_PAD_LEFT);
                                            @endphp
                                            <tr>
                                                <td>{{ $hallNo }}</td>
                                                <td>{{ $invigilator->invigilator_name }} -
                                                    {{ $invigilator->invigilator_phone }}</td>
                                            </tr>
                                        @endforeach
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
        const tableBody = document.querySelector('#allocation-table tbody');
        const localStorageKey = 'shuffledTableRows';

        // Load saved table rows from localStorage
        function loadSavedRows() {
            const savedRows = localStorage.getItem(localStorageKey);
            if (savedRows) {
                const parsedRows = JSON.parse(savedRows);

                // Clear current rows
                tableBody.innerHTML = '';

                // Add saved rows back to the table
                parsedRows.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = row;
                    tableBody.appendChild(tr);
                });
            }
        }

        // Shuffle table rows and save to localStorage
        function shuffleTableRows() {
            const rows = Array.from(tableBody.rows);
            rows.sort(() => Math.random() - 0.5);

            // Clear table and append shuffled rows
            tableBody.innerHTML = '';
            rows.forEach(row => tableBody.appendChild(row));

            // Save shuffled rows to localStorage
            const rowHTML = rows.map(row => row.outerHTML);
            localStorage.setItem(localStorageKey, JSON.stringify(rowHTML));
        }

        // Event listener for "View" button
        document.getElementById('viewBtn').addEventListener('click', function() {
            if (!localStorage.getItem(localStorageKey)) {
                shuffleTableRows(); // Shuffle only if not already shuffled
            }
        });

        // Load saved rows on page load
        document.addEventListener('DOMContentLoaded', loadSavedRows);
    </script>
@endpush
