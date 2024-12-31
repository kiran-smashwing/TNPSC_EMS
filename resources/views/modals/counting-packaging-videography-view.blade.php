<div class="modal fade modal-animate anim-blur" data-bs-backdrop="static" id="countingpackagingvideographyview"
    tabindex="-1" aria-labelledby="countingpackagingvideographyviewModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-primary" id="countingpackagingvideographyviewModelLabel">
                    <i class="feather icon-video me-2"></i>Videography
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Checklist Item</th>
                                <th>Checked</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($checklist_videography_data as $index => $checklistItem)
                                <tr>
                                    <td>
                                        @if ($index == 0)
                                            Whether the entire counting and packing activities of all the Bundles A
                                            (Covers
                                            A1 & A2) & B (Covers B1, B2, B3, B4 & B5) have been completely videographed
                                            without any break?
                                        @elseif ($index == 1)
                                            Whether the Videographer has video graphed all the exam rooms during the
                                            time
                                            of examination covering the entrance and the blackboard in the classroom,
                                            where the Register Numbers and the seating arrangement are displayed?
                                        @else
                                            Static Text for Checklist Item {{ $index + 1 }}
                                        @endif
                                    </td>
                                    <td>
                                        {{ $checklistItem['description'] == 1 ? 'Yes' : 'No' }}
                                    </td>
                                    <td>
                                        {{ $checklistItem['inspection_staff'] ?? 'No remarks' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
