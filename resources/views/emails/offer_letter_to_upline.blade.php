<!DOCTYPE html>
<html>

<head>
    <title>New Registration Notification</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Hello {{ $upline->profile?->full_name ?? 'Upline' }},</h2>

    <p>A new member has registered under your hierarchy:</p>

    <ul>
        <li><strong>Name:</strong> {{ $user->profile?->full_name ?? 'N/A' }}</li>
        <li><strong>Volunteer ID:</strong> {{ $user->employee_id }}</li>
        <li><strong>Designation:</strong> {{ ucwords(str_replace('_', ' ', $user->designation)) }}</li>
    </ul>

    <p>Please find the attached <strong>Unsigned Offer Letter</strong> for this new member.</p>

    <p><strong>Next Steps:</strong></p>
    <ol>
        <li>Download the attached Offer Letter.</li>
        <li>Have it signed/verified according to the standard procedure.</li>
        <li>Upload the signed copy back to your portal for final approval.</li>
    </ol>

    <p>You can manage this and other pending approvals in your dashboard.</p>

    <p style="margin-top: 30px;">
        <a href="{{ route('login') }}"
            style="background-color: #3C50E0; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login
            to Portal</a>
    </p>

    <br>
    <p>Best regards,</p>
    <p><strong>Humanity Foundation Administration</strong></p>

</body>

</html>