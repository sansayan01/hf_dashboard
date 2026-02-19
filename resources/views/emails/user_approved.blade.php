<!DOCTYPE html>
<html>

<head>
    <title>Welcome to the Family</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Dear {{ $user->profile->full_name }},</h2>

    <p>Welcome to the Humanity Foundation family!<br>
        We are truly grateful to have you join us in our mission to create positive change and serve humanity.</p>

    <p>Your support means a lot to us, and together we can make a real difference in the lives of those who need it
        most.</p>

    <p>Thank you for being part of this journey.</p>

    <h3>Your Official Details:</h3>
    <ul>
        <li><strong>Volunteer ID:</strong> {{ $user->employee_id }}</li>
        <li><strong>Designation:</strong> {{ ucwords(str_replace('_', ' ', $user->designation)) }}</li>
        <li><strong>Password:</strong> <em>(The password set during registration)</em></li>
    </ul>

    <p>You may now access your dashboard using your registered email or volunteer ID as the user name and with the
        password.</p>

    <p style="margin-top: 30px;">
        <a href="{{ route('login') }}"
            style="background-color: #3C50E0; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login
            to Dashboard</a>
    </p>

    <br>
    <p>Warm regards,</p>
    <p><strong>{{ $approver->profile->full_name ?? 'The Administration' }}</strong><br>
        Humanity Foundation</p>

</body>

</html>