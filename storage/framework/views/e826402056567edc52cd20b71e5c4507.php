<!DOCTYPE html>
<html>

<head>
    <title>Welcome to the Family</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Dear <?php echo e($user->profile?->full_name ?? 'User'); ?>,</h2>

    <p>Welcome to the Humanity Foundation family!<br>
        We are truly grateful to have you join us in our mission to create positive change and serve humanity.</p>

    <p>Your support means a lot to us, and together we can make a real difference in the lives of those who need it
        most.</p>

    <p>Please ensure you have collected, signed, and submitted your official **Offer Letter** from your **Upline
        Manager** for our records.</p>

    <p>Thank you for being part of this journey.</p>

    <h3>Your Official Details:</h3>
    <ul>
        <li><strong><?php echo e(in_array($user->designation, ['office_in_charge', 'staff', 'camp_organizer']) ? 'Employee ID' : 'Volunteer ID'); ?>:</strong>
            <?php echo e($user->employee_id); ?></li>
        <li><strong>Designation:</strong> <?php echo e(ucwords(str_replace('_', ' ', $user->designation))); ?></li>
        <li><strong>Password:</strong> <?php echo e($user->password_plain); ?></li>
    </ul>

    <p>You may now access your dashboard using your registered email or
        <?php echo e(in_array($user->designation, ['office_in_charge', 'staff', 'camp_organizer']) ? 'employee ID' : 'volunteer ID'); ?>

        as the user name and with the
        password.
    </p>

    <p style="margin-top: 30px;">
        <a href="https://dashboard.hfburdwan.in/"
            style="background-color: #3C50E0; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login
            to Dashboard</a>
    </p>

    <br>
    <p>Warm regards,</p>
    <p><strong><?php echo e($approver->profile->full_name ?? 'The Administration'); ?></strong><br>
        Humanity Foundation</p>

</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views\emails\user_approved.blade.php ENDPATH**/ ?>