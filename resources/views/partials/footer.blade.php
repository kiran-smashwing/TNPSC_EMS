    <footer class="pc-footer">
        <div class="footer-wrapper container-fluid">
            <div class="row">
                <div class=" auth-footer mt-2 mb-2 text-center" style="backgroud:#fff">
                    <p class="m-0 w-100">Copyright © {{ date('Y') }} <a
                            href="https://www.tnpsc.gov.in/">TNPSC</a>. Developed By <a href="https://www.smashwing.com/">Smashwing Technologies Pvt Ltd.</a> All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    @push('scripts')
        <script>
            function openMap(lat, long) {
                if (lat && long) {
                    var url = "https://www.google.com/maps/@?api=1&map_action=map&center=" + lat + "," + long + "&zoom=14";
                    window.open(url, '_blank');
                } else {
                    alert('No location available');
                }
            }
        </script>
    @endpush
