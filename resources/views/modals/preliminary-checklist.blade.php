<div class="modal fade modal-animate anim-blur" id="preliminaryCheckListModel" tabindex="-1" aria-labelledby="venueDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header bg-primary ">
          <h5 class="modal-title text-white" id="venueDetailsModalLabel">
            <i class="feather icon-map-pin me-2"></i>Preliminary CheckList
          </h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <div class="scroll-block chat-message">
                <div class="card-body">
                    <div class="row">
                        <!-- Table for Venue Allocation with Editable Columns -->
                        <div class="mb-4">
                            <table class="table table-bordered" id="responsiveTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Description</th>
                                     
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <input class="form-check-input input-success"
                                                type="checkbox" id="customCheckc3" checked="checked">
                                        </td>
                                        <!-- Editable Cell for Venue Name -->
                                        <td>
                                            premises are clean and well maintained
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input input-success"
                                                type="checkbox" id="customCheckc3" checked="checked">
                                        </td>
                                        <!-- Editable Cell for Venue Name -->
                                        <td>
                                            Adequate seating arrangements are available
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input input-success"
                                                type="checkbox" id="customCheckc3" checked="checked">
                                        </td>
                                        <!-- Editable Cell for Venue Name -->
                                        <td>
                                            Adequate lighting and ventilation are available
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input input-success"
                                                type="checkbox" id="customCheckc3" checked="checked">
                                        </td>
                                        <!-- Editable Cell for Venue Name -->
                                        <td>
                                            Drinking water and toilet facilities are available
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input class="form-check-input input-success"
                                                type="checkbox" id="customCheckc3" checked="checked">
                                        </td>
                                        <!-- Editable Cell for Venue Name -->
                                        <td>
                                            Adequate security arrangements are available
                                        </td>
                                    </tr>
                                    

                                    <!-- You can add more rows as needed -->
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary d-flex align-items-center" onclick="saveVenueDetails()">
            <i class="feather icon-save me-2"></i>Save Changes
          </button>
        </div>
      </div>
    </div>
  </div>
  @push('scripts')
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
        <!-- [Page Specific JS] end -->
    @endpush