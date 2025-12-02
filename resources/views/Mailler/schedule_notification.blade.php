<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Notification</title>
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
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-ongoing {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-completed {
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
            @if($action === 'created')
                <p>Hi {{ $schedule->member->name ?? 'Member' }},</p>
                <p>A new workout schedule has been created for you. Here are the details:</p>
            @elseif($action === 'assigned')
                <p>Hi {{ $schedule->coach->name ?? 'Coach' }},</p>
                <p>You have been assigned to a new workout schedule. Here are the details:</p>
            @elseif($action === 'updated')
                <p>Hi {{ $schedule->member->name ?? $schedule->coach->name ?? 'User' }},</p>
                <p>Your workout schedule has been updated. Here are the latest details:</p>
            @elseif($action === 'status_changed')
                <p>Hi {{ $schedule->member->name ?? $schedule->coach->name ?? 'User' }},</p>
                <p>The status of your workout schedule has changed from <strong>{{ ucfirst($oldStatus) }}</strong> to <strong>{{ ucfirst($schedule->status) }}</strong>.</p>
                <p>Here are the current details:</p>
            @elseif($action === 'reminder')
                <p>Hi {{ $schedule->member->name ?? 'Member' }},</p>
                <p>This is a reminder for your upcoming workout schedule. Here are the details:</p>
            @endif

            <div class="details">
                <p><strong>Coach:</strong> {{ $schedule->coach->name ?? 'Not assigned' }}</p>
                <p><strong>Member:</strong> {{ $schedule->member->name ?? 'Not assigned' }}</p>
                <p><strong>Date:</strong> {{ $schedule->date->format('F j, Y') }}</p>
                <p><strong>Time:</strong> {{ $schedule->time->format('g:i A') }}</p>
                <p><strong>Status:</strong>
                    <span class="status-badge status-{{ $schedule->status }}">
                        {{ ucfirst($schedule->status) }}
                    </span>
                </p>
                <p><strong>Workout Plan:</strong></p>
                <p>{{ $schedule->workout_plan }}</p>
                @if($schedule->notes)
                    <p><strong>Notes:</strong></p>
                    <p>{{ $schedule->notes }}</p>
                @endif
            </div>
        </div>

        <div class="footer-text">
            @if($action === 'reminder')
                <p>Please arrive on time for your session. See you soon!</p>
            @else
                <p>Thank you for using our fitness services!</p>
            @endif
        </div>

        <div class="header">
            <img src="{{ asset('mail-footer.png') }}" alt="Footer Image">
        </div>
    </div>
</body>

</html>