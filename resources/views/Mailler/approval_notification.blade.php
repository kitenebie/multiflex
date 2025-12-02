<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Approval Notification</title>
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
            <p>Hi {{ $user->name }},</p>
            <p>Congratulations! Your {{ ucfirst($role) }} account has been approved and is now active.</p>

            <div class="details">
                <p><strong>Name:</strong> {{ $user->name }}</p>
                <p><strong>Email:</strong> {{ $user->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst($role) }}</p>
                <p><strong>Status:</strong>
                    <span class="status-badge">
                        Active
                    </span>
                </p>
                @if($user->address)
                    <p><strong>Address:</strong> {{ $user->address }}</p>
                @endif
                @if($user->age)
                    <p><strong>Age:</strong> {{ $user->age }}</p>
                @endif
                @if($user->gender)
                    <p><strong>Gender:</strong> {{ ucfirst($user->gender) }}</p>
                @endif
            </div>
        </div>

        <div class="footer-text">
            <p>Welcome to our fitness community! You can now access all {{ $role }} features.</p>
        </div>

        <div class="header">
            <img src="{{ asset('mail-footer.png') }}" alt="Footer Image">
        </div>
    </div>
</body>

</html>