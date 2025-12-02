<style>
    .qr-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    @media (min-width: 768px) {
        .qr-container {
            flex-direction: row;
        }
    }

    .qr-scanner-container {
        flex: 1;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
    }

    .user-info-card {
        flex: 1;
        background: white;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 16px;
    }

    .scanner-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .user-info-title {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .user-info p {
        margin-bottom: 8px;
    }

    #reader {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    #scanned-result {
        margin-top: 10px;
        font-weight: bold;
        min-height: 20px;
    }

    select {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        background-color: #fff;
        color: #333;
        cursor: pointer;
    }

    select:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
    }
</style>
<div class="">
    <div wire:ignore class="qr-container">
        <div class="qr-scanner-container">
            <div id="reader"></div>
            <div hidden id="scanned-result"></div>
            <div id="scanned-invalid-result"></div>
            <br>
            <label for="attendance_type"> Select Attendance Type</label>
            <select name="attendance_type" id="attendance_type">
                <option value="0">Time In</option>
                <option value="1">Time Out</option>
            </select>
            <br>
            <p id="scanned-invalid-result"></p>
        </div>
        <div class="user-info-card">
            <h5 class="user-info-title">Scanned User Info</h5>
            <div class="user-info">
                <p><strong>Name:</strong> <span id="user-name"></span></p>
                <p><strong>Address:</strong> <span id="user-address"></span></p>
                <p><strong>Email:</strong> <span id="user-email"></span></p>
                <p><strong>Attendance At:</strong> <span id="attendance-at"></span></p>
            </div>
        </div>
    </div>
    <br><br>
</div>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>
<script>
    const qrSecret = '{{ env("QR_SECRET", "gms_secret_key_2024") }}';
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const scannedResult = document.getElementById('scanned-result');
        let html5QrCode = null;
        let lastScannedCode = null;

        // ---- SEND TO BACKEND WITH SPECIFIED ACTION ----
        function sendScan(qrCode, actionType) {
            const attendanceType = document.getElementById('attendance_type').value;
            fetch('/qr-scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        qr_code: qrCode,
                        attendance_type: attendanceType
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('user-name').textContent = data.user.name;
                        document.getElementById('user-address').textContent = data.user.address;
                        document.getElementById('user-email').textContent = data.user.email;
                        document.getElementById('attendance-at').textContent = data.attendance_at;
                    }
                });
        }

        // ---- SCANNER SUCCESS ----
        let scanCooldown = false;

        function onScanSuccess(decodedText) {

            if (scanCooldown) return;

            scanCooldown = true;
            setTimeout(() => scanCooldown = false, 1500);

            if (decodedText === "This member has no active subscription") {
                document.getElementById('scanned-invalid-result').textContent = decodedText;
                return;
            }

            try {
                const payload = JSON.parse(atob(decodedText));
                const expectedSignature = CryptoJS.SHA256(payload.data + qrSecret).toString();
                if (payload.signature !== expectedSignature) {
                    return; // invalid signature
                }
                const data = JSON.parse(payload.data);
                sendScan(data.qr_code);
            } catch (e) {
                // invalid QR code
                return;
            }
        }


        // ---- SCANNER FAILURE (ignore) ----
        function onScanFailure(error) {}

        // ---- START SCANNER ----
        async function startScanner() {
            const scannerConfig = {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            };

            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: {
                            ideal: "environment"
                        }
                    }
                });
                stream.getTracks().forEach(track => track.stop()); // release camera after permission

                html5QrCode = new Html5Qrcode("reader");

                try {
                    await html5QrCode.start({
                        facingMode: "environment"
                    }, scannerConfig, onScanSuccess, onScanFailure);
                } catch (envError) {
                    const cameras = await Html5Qrcode.getCameras();

                    if (!cameras.length) {
                        scannedResult.textContent = "No cameras found.";
                        return;
                    }

                    let selectedCamera = cameras.find(camera => /back|rear|environment/i.test(camera.label));
                    if (!selectedCamera) {
                        selectedCamera = cameras[cameras.length - 1]; // fallback to last (often back) camera
                    }

                    try {
                        await html5QrCode.start(
                            selectedCamera.id,
                            scannerConfig,
                            onScanSuccess,
                            onScanFailure
                        );
                    } catch (fallbackError) {
                        scannedResult.textContent = "Unable to start camera: " + fallbackError;
                    }
                }
            } catch (err) {
                scannedResult.textContent = "Camera error: " + err;
            }
        }

        // ---- BUTTON CLICK EVENTS ----


        startScanner();
    });
</script>
