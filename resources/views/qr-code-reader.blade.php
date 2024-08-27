@extends('layouts.app')

@section('title', 'Scribe')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
@endpush

@section('content')

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
    <!-- [ Header Topbar ] end -->
    <div class="pc-container">
        <div class="pc-content">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="container-fluid">
                    <div class="row justify-content-center mt-4">
                        <div class="col-lg-8 col-md-10">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-center py-3">
                                    <h4 class="mb-0 text-white ">
                                        <i class="feather icon-camera me-2"></i>QR Code Reader
                                    </h4>
                                </div>
                                <div class="card-body text-center">
                                    <video id="video" class="rounded mb-3 img-fluid border"
                                        style="width: 100%;"></video>
                                    <canvas id="canvas" style="display: none;"></canvas>
                                    <div id="output" class="alert alert-info mt-3" role="alert">Awaiting QR Code...
                                    </div>
                                    <button id="scan-again-btn" class="btn btn-primary mt-3" style="display: none;">Scan
                                        Again</button>
                                </div>
                                <div class="card-footer text-center">
                                    <p class="text-muted mb-0">Align the QR code within the camera frame to scan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    @push('scripts')
        <script>
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const ctx = canvas.getContext('2d');
            const output = document.getElementById('output');
            const scanAgainBtn = document.getElementById('scan-again-btn');
            const lastFrame = document.getElementById('last-frame');

            let lastScannedCode = null;
            let scanning = true;
            let videoStream = null;

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment" // Use "user" for the front camera
                    },
                    audio: false // You can include audio constraints if needed
                })
                .then(function(stream) {
                    // Handle the stream (e.g., display it in a video element)
                    videoStream = stream;
                    video.srcObject = stream;
                    video.setAttribute('playsinline', true);
                    video.play();
                    requestAnimationFrame(tick);
                })
                .catch(function(error) {
                    console.error("Error accessing the camera:", error);
                    output.classList.remove('alert-info');
                    output.classList.add('alert-danger');
                    output.innerText = "Error accessing the camera. Please check your browser settings and permissions.";
                });

            scanAgainBtn.addEventListener('click', function() {
                window.location.reload();

                // Restart scanning
                scanning = true;
                output.classList.remove('alert-success', 'alert-danger');
                output.classList.add('alert-info');
                output.innerText = "Awaiting QR Code...";
                scanAgainBtn.style.display = 'none';
                lastFrame.style.display = 'none'; // Hide last frame

                // Restart video stream
                if (videoStream) {
                    video.srcObject = videoStream;
                    video.play();
                    requestAnimationFrame(tick);
                }
            });

            function tick() {
                if (video.readyState === video.HAVE_ENOUGH_DATA && scanning) {
                    canvas.height = video.videoHeight;
                    canvas.width = video.videoWidth;
                    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                    var imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    var code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert",
                    });

                    if (code) {
                        scanning = false;
                        lastScannedCode = code.data;
                        output.classList.remove('alert-info');
                        output.classList.add('alert-success');
                        output.innerText = "QR Code detected: " + code.data;
                        scanAgainBtn.style.display = 'block';

                        // Freeze the video by stopping the stream
                        video.pause();
                        video.srcObject.getTracks().forEach(track => track.stop());

                        // Display the last frame on the image element
                        lastFrame.src = canvas.toDataURL();
                        lastFrame.style.display = 'block';
                    } else {
                        output.innerText = "No QR code detected";
                    }
                }

                if (scanning) {
                    requestAnimationFrame(tick);
                }
            }

            function processQrCode(data) {
                fetch('/process-qr-code', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            qr_code: data
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('Success:', data);
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
            }
        </script>
    @endpush


    @include('partials.theme')

@endsection
