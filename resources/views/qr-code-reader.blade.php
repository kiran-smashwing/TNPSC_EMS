<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        .video-wrapper {
            position: relative;
            width: 100%;
            height: 100%;
        }

        #video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay {
            position: absolute;
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
            /* Ensure background is transparent */
        }

        .cutout::before {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            border-radius: 20px;
        }

        .cutout::after {
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            border-radius: 22px;
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
    </style>
</head>

<body>
    <div class="video-wrapper mx-auto mb-3">
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

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

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
                    console.log("QR code found:", lastScannedCode);
                    processQrCode(lastScannedCode);

                    // Freeze the video by stopping the stream
                    video.pause();
                    video.srcObject.getTracks().forEach(track => track.stop());
                } else {
                    console.log("No QR code found.");
                }
            }

            if (scanning) {
                requestAnimationFrame(tick);
            }
        }

        function processQrCode(data) {
            fetch("{{route('ci-meetings.attendance-QRcode-scan')}}", {
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
                    localStorage.setItem('qrCodeResult', JSON.stringify({
                        type: data.status == 'success' ? 'success' : 'error',
                        message: data.message
                    }));
                     window.history.back(); // Redirect to the previous page
                })
                .catch((error) => {
                    console.error('Error:', error);
                    localStorage.setItem('qrCodeResult', JSON.stringify({
                        type: 'danger',
                        message: error
                    }));
                     window.history.back(); // Redirect to the previous page
                });

        }
    </script>
</body>

</html>
