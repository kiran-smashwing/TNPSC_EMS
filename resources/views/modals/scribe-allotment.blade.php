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
                            @if (!empty($scribes_type))
                                <!-- Scribes Data -->
                                @foreach ($scribes_type as $scribeData)
                                    <tr>
                                        <td>
                                            {{ is_array($scribeData['reg_no'])
                                                ? implode(', ', $scribeData['reg_no'])
                                                : $scribeData['reg_no'] ?? 'Not Available' }}
                                        </td>
                                        <td>
                                            {{ $scribeData['scribe_name'] ?? 'N/A' }} -
                                            {{ $scribeData['scribe_phone'] ?? 'N/A' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <!-- No Data Available -->
                                <tr>
                                    <td colspan="2" class="text-center text-muted">No data available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
