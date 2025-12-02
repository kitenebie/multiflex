<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Submitted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #dddddd;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100%;
            height: auto;
        }

        .message {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }

        .details {
            margin-top: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #eeeeee;
        }

        .details p {
            margin: 5px 0;
        }

        .proof-img {
            max-width: 100%;
            height: auto;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('mail-logo.png') }}" alt="Header Image">
        </div>
        <div class="message">
            <p>Dear {{ $full_name ?? 'unknown' }},</p>
            <p>Your payment with proof has been submitted successfully.</p>
            <div class="details">
                <p><strong>Reference No:</strong> {{ $reference_no ?? 'unknown' }}</p>
                <p><strong>Address:</strong> {{ $address ?? 'unknown' }}</p>
                <p><strong>Date Submitted:</strong> {{ $date_submitted ?? 'unknown' }}</p>
                <p><strong>Proof of Payment:</strong></p>
                <img src="{{ $proof_img ?? 'unknown' }}" alt="Proof of Payment" class="proof-img">
            </div>
        </div>
    </div>
    <div class="header">
        <img src="{{ asset('mail-footer.png') }}" alt="Header Image">
    </div>
</body>

</html>
