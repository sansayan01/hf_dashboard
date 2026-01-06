<!DOCTYPE html>
<html>

<head>
    <title>Account Approved</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Hello {{ $user->profile->full_name }},</h2>

    <p>Congratulations! Your account with <strong>Humanity Foundation</strong> has been approved by the administration.
    </p>

    <p>We are thrilled to welcome you to our team.</p>

    <h3>Your Account Details:</h3>
    <ul>
        <li><strong>Employee ID:</strong> {{ $user->employee_id }}</li>
        <li><strong>Designation:</strong> {{ strtoupper(str_replace('_', ' ', $user->designation)) }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
    </ul>

    <p>You can now login to your dashboard using the password you created during registration.</p>

    <p>
        <a href="{{ route('login') }}"
            style="background-color: #4F46E5; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Login
            to Dashboard</a>
    </p>

    <p>If you have any questions, please contact your reporting manager.</p>

    <br>
    <p>Best Regards,</p>
    <p><strong>Humanity Foundation Team</strong></p>

</body>

</html>