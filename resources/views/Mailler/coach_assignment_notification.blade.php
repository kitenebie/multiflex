<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Member Assignment Notification</title>
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
            padding: 12px;
            background-color: #fafafa;
            border: 1px solid #e5e5e5;
        }

        .details p {
            margin: 6px 0;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background-color: #d4edda;
            color: #155724;
        }

        .footer-text {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('mail-logo.png') }}" alt="Header Image">
        </div>

        <div class="message">
            <p>Hi {{ $coach->name }},</p>
            <p>You have been assigned as the coach for a new member.</p>

            <div class="details">
                <p><strong>Member Name:</strong> {{ $member->name }}</p>
                <p><strong>Member Email:</strong> {{ $member->email }}</p>
                <p><strong>Fitness Offer:</strong> {{ $subscription->fitnessOffer->name }}</p>
                <p><strong>Start Date:</strong> {{ $subscription->start_date->format('F j, Y') }}</p>
                <p><strong>End Date:</strong> {{ $subscription->end_date->format('F j, Y') }}</p>
                <p><strong>Status:</strong>
                    <span class="status-badge">
                        Active
                    </span>
                </p>
            </div>
        </div>

        <div class="footer-text">
            <p>Please reach out to your new member to begin their training program.</p>
        </div>

        <div class="header">
            <img src="{{ asset('mail-footer.png') }}" alt="Footer Image">
        </div>
    </div>
</body>

</html>