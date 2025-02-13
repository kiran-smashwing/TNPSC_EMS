<!-- Scribe Attendance and Allotment Modal -->
<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="scribeAllotmentModal" tabindex="-1"
    aria-labelledby="scribeAllotmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title text-primary" id="scribeAllotmentModalLabel">
                    <i class="feather icon-users me-2"></i>Scribe Allotment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Scribe List Table -->
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Reg No:</th>
                                <th>Scribe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                // Decode the session_answer JSON string
                                $selectedScribe = $selectedScribe
                                    ? json_decode($selectedScribe->selected_scribes, true)
                                    : null;

                                // Initialize scribeAssignments to an empty array if selectedScribe or scribe_assignments is null
                                $scribeAssignments =
                                    $selectedScribe && isset($selectedScribe['scribe_assignments'])
                                        ? $selectedScribe['scribe_assignments']
                                        : [];
                                // Collect all unique scribe IDs from the assignments
                                $scribeIds = array_unique(array_column($scribeAssignments, 'scribe_id'));

                                // Fetch scribe details from the database
                                $scribes = \App\Models\Scribe::whereIn('scribe_id', $scribeIds)
                                    ->get()
                                    ->keyBy('scribe_id');
                            @endphp
                            @if (!empty($scribeAssignments))
                                <!-- Scribes Data -->
                                @foreach ($scribeAssignments as $assignment)
                                    @php
                                        $scribe = $scribes->get($assignment['scribe_id']);
                                    @endphp
                                    <tr>
                                        <td>
                                            {{ $assignment['reg_no'] ?? 'Not Available' }}
                                        </td>
                                        <td>
                                            @if ($scribe)
                                                {{ $scribe->scribe_name ?? 'N/A' }} -
                                                {{ $scribe->scribe_phone ?? 'N/A' }}
                                            @else
                                                Scribe details not found
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2">No scribe assignments found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
