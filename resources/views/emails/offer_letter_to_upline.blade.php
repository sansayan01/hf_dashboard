<!DOCTYPE html>
<html>

<head>
    <title>New Member Registration Notification</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Hello {{ $upline->profile?->full_name ?? 'Manager' }},</h2>

    <p>A new member has registered under your team/downline:</p>

    <ul>
        <li><strong>Name:</strong> {{ $newbie->profile?->full_name ?? 'N/A' }}</li>
        <li><strong>Employee ID:</strong> {{ $newbie->employee_id }}</li>
        <li><strong>Phone:</strong> {{ $newbie->profile?->phone_number ?? 'N/A' }}</li>
        <li><strong>Designation:</strong> {{ ucwords(str_replace('_', ' ', $newbie->designation)) }}</li>
    </ul>

    <p>Please find the attached <strong>Unsigned Offer Letter</strong> for this new member.</p>

    <p><strong>Next Steps:</strong></p>
    <ol>
        <li>Download the attached Offer Letter.</li>
        <li>Get it printed and signed/verified by the new member.</li>
        <li>Upload the signed copy back to your portal for final admin verification.</li>
    </ol>

    <p>You can manage this and other pending approvals in your dashboard.</p>

    <p style="margin-top: 30px;">
        <a href="{{ route('login') }}"
            style="background-color: #3C50E0; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login
            to Dashboard</a>
    </p>

    <br>
    <p>Best regards,</p>
    <p><strong>Humanity Foundation Administration</strong></p>
</body>

</html>