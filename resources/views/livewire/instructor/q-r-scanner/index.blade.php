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

    /* Default (light mode) styles can stay the same as your original CSS */

    .dark {
        background-color: #121212;
        color: #e0e0e0;
    }

    .dark .qr-container {
        gap: 20px;
    }

    .dark .qr-scanner-container,
    .dark .user-info-card {
        background-color: #1e1e1e;
        border: 1px solid #333;
        border-radius: 8px;
        padding: 16px;
        color: #e0e0e0;
    }

    .dark .scanner-title,
    .dark .user-info-title {
        color: #fff;
    }

    .dark .user-info p {
        color: #e0e0e0;
    }

    .dark #reader {
        background-color: #000;
        border-radius: 8px;
    }

    .dark #scanned-result,
    .dark #scanned-invalid-result {
        color: #ff6b6b;
    }

    .dark select {
        background-color: #2c2c2c;
        color: #fff;
        border: 1px solid #555;
    }

    .dark select:focus {
        border-color: #1a73e8;
        box-shadow: 0 0 5px rgba(26, 115, 232, 0.5);
    }

    .dark option {
        background-color: #2c2c2c;
        color: #fff;
    }

    .dark label {
        color: #fff;
    }
</style>
<div class="qr-container">
    <div class="qr-scanner-container">
        <div id="reader"></div>
        <p id="scanned-invalid-result" style="color:red;"></p>

        <br>
        <label>Select Attendance Type</label>
        <select id="attendance_type">
            <option value="0">Time In</option>
            <option value="1">Time Out</option>
        </select>
    </div>

    <div class="user-info-card">
        <h5>Scanned User Info</h5>
        <p><strong>Name:</strong> <span id="user-name"></span></p>
        <p><strong>Address:</strong> <span id="user-address"></span></p>
        <p><strong>Email:</strong> <span id="user-email"></span></p>
        <p><strong>Attendance At:</strong> <span id="attendance-at"></span></p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

<script>
    const qrSecret = "{{ env('QR_SECRET', 'gms_secret_key_2024') }}";

    document.addEventListener('DOMContentLoaded', function() {
        let scanCooldown = false;

        function sendScan(qrCode) {
            const attendanceType = document.getElementById("attendance_type").value;

            fetch("/qr-scan", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        qr_code: qrCode,
                        attendance_type: attendanceType
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (!data.success) return;

                    document.getElementById("user-name").textContent = data.user.name;
                    document.getElementById("user-address").textContent = data.user.address;
                    document.getElementById("user-email").textContent = data.user.email;
                    document.getElementById("attendance-at").textContent = data.attendance_at;
                });
        }

        function onScanSuccess(decodedText) {
            if (scanCooldown) return;

            scanCooldown = true;
            setTimeout(() => scanCooldown = false, 1500);

            const invalidBox = document.getElementById("scanned-invalid-result");
            invalidBox.textContent = "";

            if (decodedText === "This member has no active subscription") {
                invalidBox.textContent = decodedText;
                return;
            }

            try {
                // Step 1: Base64 decode
                const decoded = JSON.parse(atob(decodedText));

                if (!decoded.data || !decoded.signature) {
                    invalidBox.textContent = "Invalid QR Format";
                    return;
                }

                // Step 2: Validate signature
                const expectedSignature = CryptoJS.SHA256(decoded.data + qrSecret).toString();
                if (decoded.signature !== expectedSignature) {
                    invalidBox.textContent = "Unauthorized QR Code";
                    return;
                }

                // Step 3: Decode inner JSON
                const innerData = JSON.parse(decoded.data);

                // Step 4: Expiration check (5 minutes)
                const now = Math.floor(Date.now() / 1000);
                if (now - innerData.timestamp > 300) {
                    invalidBox.textContent = "QR Code Expired";
                    return;
                }

                // Step 5: Send to backend
                sendScan(innerData.qr_code);

            } catch (err) {
                invalidBox.textContent = "Invalid QR Code";
            }
        }

        const html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start({
                facingMode: "environment"
            }, {
                fps: 10,
                qrbox: {
                    width: 250,
                    height: 250
                }
            },
            onScanSuccess,
            () => {}
        );
    });
</script>
