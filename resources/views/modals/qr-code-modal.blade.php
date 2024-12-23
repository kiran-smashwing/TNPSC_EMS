<div class="modal " data-bs-backdrop="static" id="qrCodeModal" tabindex="-1" aria-hidden="true"
    style="--bs-border-radius:0px;">
    <div class="modal-dialog modal-fullscreen" role="document">
        <div class="modal-content">
            <!-- Close button -->
            <button type="button" class="btn-close modal-close-btn" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-body p-0"> <!-- Removed padding -->
                <div id="qrReaderContainer" class="qr-reader h-100"> <!-- Added h-100 -->
                    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
                    <style>
                        /* Close button styling */
                        .modal-close-btn {
                            position: fixed;
                            top: 20px;
                            right: 20px;
                            z-index: 1051;
                            background-color: rgba(255, 255, 255, 0.9);
                            padding: 10px;
                            border-radius: 50%;
                            width: 40px;
                            height: 40px;
                            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
                            border: none;
                            transition: transform 0.2s ease;
                        }

                        .modal-close-btn:hover {
                            transform: scale(1.1);
                            background-color: #fff;
                        }

                        .modal-close-btn:focus {
                            outline: none;
                            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
                        }

                        /* Reset modal styles */
                        .modal-fullscreen {
                            padding: 0 !important;
                        }

                        .modal-content {
                            border: none;
                            border-radius: 0;
                        }

                        /* Container styles */
                        .qr-reader {
                            position: fixed;
                            top: 0;
                            left: 0;
                            width: 100vw;
                            height: 100vh;
                            overflow: hidden;
                        }

                        .video-wrapper {
                            position: absolute;
                            top: 0;
                            left: 0;
                            width: 100%;
                            height: 100%;
                        }

                        #video {
                            width: 100%;
                            height: 100%;
                            object-fit: cover;
                        }

                        .overlay {
                            position: fixed;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background: rgba(0, 0, 0, 0.5);
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            z-index: 10;
                        }

                        .cutout {
                            width: 45%;
                            aspect-ratio: 1;
                            position: relative;
                            overflow: hidden;
                            background: transparent;
                        }

                        .corner {
                            position: absolute;
                            width: 20px;
                            height: 20px;
                            border: 2px solid #fff;
                        }

                        .corner-top-left {
                            top: 0;
                            left: 0;
                            border-right: none;
                            border-bottom: none;
                            border-top-left-radius: 8px;
                        }

                        .corner-top-right {
                            top: 0;
                            right: 0;
                            border-left: none;
                            border-bottom: none;
                            border-top-right-radius: 8px;
                        }

                        .corner-bottom-left {
                            bottom: 0;
                            left: 0;
                            border-right: none;
                            border-top: none;
                            border-bottom-left-radius: 8px;
                        }

                        .corner-bottom-right {
                            bottom: 0;
                            right: 0;
                            border-left: none;
                            border-top: none;
                            border-bottom-right-radius: 8px;
                        }

                        /* Prevent scrolling on body when modal is open */
                        body.modal-open {
                            overflow: hidden !important;
                        }
                    </style>
                    <div class="video-wrapper">
                        <video id="video" class="rounded"></video>
                        <canvas id="canvas" style="display: none;"></canvas>
                        <div class="overlay">
                            <div class="cutout">
                                <div class="corner corner-top-left"></div>
                                <div class="corner corner-top-right"></div>
                                <div class="corner corner-bottom-left"></div>
                                <div class="corner corner-bottom-right"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        let lastScannedCode = null;
        let scanning = true;
        let videoStream = null;

        // Function to start camera
        function startCamera() {
            scanning = true;
            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: "environment"
                    },
                    audio: false
                })
                .then(function(stream) {
                    videoStream = stream;
                    video.srcObject = stream;
                    video.setAttribute('playsinline', true);
                    video.play();
                    requestAnimationFrame(tick);
                })
                .catch(function(error) {
                    console.error("Error accessing the camera:", error);
                });
        }

        // Function to stop camera
        function stopCamera() {
            scanning = false;
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            if (video.srcObject) {
                video.srcObject = null;
            }
        }

        // Initialize modal events
        document.addEventListener('DOMContentLoaded', function() {
            const qrCodeModal = document.getElementById('qrCodeModal');

            // Start camera when modal opens
            qrCodeModal.addEventListener('shown.bs.modal', function() {
                startCamera();
            });

            // Stop camera when modal closes
            qrCodeModal.addEventListener('hidden.bs.modal', function() {
                stopCamera();
            });
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
                    console.log("QR code:", code);
                    console.log("QR code found:", lastScannedCode);
                    processQrCode(lastScannedCode);
                    stopCamera();
                }
            }

            if (scanning) {
                requestAnimationFrame(tick);
            }
        }
        // Add this to ensure cleanup on page unload
        window.addEventListener('beforeunload', function() {
            stopCamera();
        });
    </script>
@endpush
