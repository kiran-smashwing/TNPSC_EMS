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

<script> $('#res-config').DataTable({
        responsive: true,
      dom: '<"container-fluid"<"row"<"col-md-4" B><"col-sm-12 col-md-8 d-flex justify-content-between align-items-center" l f>>>rtip',
buttons: [
    {
      extend: 'copy',
      className: 'btn btn-light-primary mb-1 btn-sm'
    },
    {
      extend: 'csv',
      className: 'btn btn-light-primary mb-1 btn-sm'
    },
    {
      extend: 'excel',
      className: 'btn btn-light-primary mb-1 btn-sm'
    },
    {
      extend: 'print',
      className: 'btn btn-light-primary mb-1 btn-sm'
    }
  ]      });</script>

<script> $('#testing').DataTable({
        responsive: true,
      dom: '<"container-fluid"<"row"<"col-md-4" B><"col-sm-12 col-md-8 d-flex justify-content-between align-items-center" l f>>>rtip',
buttons: [
    {
      extend: 'copy',
      className: 'btn btn-light-primary mb-1 btn-sm'
    },
    {
      extend: 'csv',
      className: 'btn btn-light-primary mb-1 btn-sm'
    },
    {
      extend: 'excel',
      className: 'btn btn-light-primary mb-1 btn-sm'
    },
    {
      extend: 'print',
      className: 'btn btn-light-primary mb-1 btn-sm'
    }
  ]      });</script>
      
