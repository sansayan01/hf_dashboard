<!DOCTYPE html>
<html>

<head>
    <title>New Member Registration</title>
</head>

<body>
    <h1>Hello, {{ $upline->profile->full_name }}!</h1>
    <p>A new member has registered under your team/downline.</p>
    <p><strong>Member Name:</strong> {{ $newbie->profile->full_name }}</p>
    <p><strong>Employee ID:</strong> {{ $newbie->employee_id }}</p>
    <p><strong>Phone:</strong> {{ $newbie->profile->phone_number }}</p>

    <p>Please find the unsigned Offer Letter for this member attached to this email.</p>
    <p><strong>Action Required:</strong></p>
    <ol>
        <li>Download the attached offer letter.</li>
        <li>Get it printed and signed by the new member.</li>
        <li>Upload the signed copy from your portal for final admin verification.</li>
    </ol>

    <p>Thank you,</p>
    <p>Humanity Foundation Team</p>
</body>

</html>