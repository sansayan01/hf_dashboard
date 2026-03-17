<!DOCTYPE html>
<html>

<head>
    <title>New Member Registration Notification</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Hello <?php echo e($upline->profile?->full_name ?? 'Manager'); ?>,</h2>

    <p>A new member has registered under your team/downline:</p>

    <ul>
        <li><strong>Name:</strong> <?php echo e($newbie->profile?->full_name ?? 'N/A'); ?></li>
        <li><strong>Employee ID:</strong> <?php echo e($newbie->employee_id); ?></li>
        <li><strong>Phone:</strong> <?php echo e($newbie->profile?->phone_number ?? 'N/A'); ?></li>
        <li><strong>Designation:</strong> <?php echo e(ucwords(str_replace('_', ' ', $newbie->designation))); ?></li>
    </ul>

    <p>Please click the button below to view and download the <strong>Unsigned Offer Letter</strong> for this new
        member.</p>

    <p style="margin: 20px 0;">
        <a href="<?php echo e(route('users.joining-letter', $newbie->id)); ?>"
            style="background-color: #10B981; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">
            View Offer Letter
        </a>
    </p>

    <p><strong>Next Steps:</strong></p>
    <ol>
        <li>Download the Offer Letter using the link above.</li>
        <li>Get it printed and signed/verified by the new member.</li>
        <li>Upload the signed copy back to your portal for final admin verification.</li>
    </ol>

    <p>You can manage this and other pending approvals in your dashboard.</p>

    <p style="margin-top: 30px;">
        <a href="<?php echo e(route('login')); ?>"
            style="background-color: #3C50E0; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; font-weight: bold;">Login
            to Dashboard</a>
    </p>

    <br>
    <p>Best regards,</p>
    <p><strong>Humanity Foundation Administration</strong></p>
</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views\emails\offer_letter_to_upline.blade.php ENDPATH**/ ?>