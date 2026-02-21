<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Humanity Foundation</title>
</head>

<body>
    <h1>Welcome, {{ $user->profile->full_name }}!</h1>
    <p>Congratulations! Your profile has been verified and approved by the administration.</p>
    <p>We are excited to have you as part of the Humanity Foundation team.</p>

    <p><strong>Your Details:</strong></p>
    <ul>
        <li><strong>Employee ID:</strong> {{ $user->employee_id }}</li>
        <li><strong>Designation:</strong> {{ $user->getDesignationLabel() }}</li>
    </ul>

    <p>Please find your official joining letter attached to this email.</p>

    <p>Best Regards,</p>
    <p>Humanity Foundation Administration</p>
</body>

</html>