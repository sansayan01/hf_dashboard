<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Humanity Foundation</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">

    <h2>Dear <?php echo e($user->profile?->full_name ?? 'User'); ?>,</h2>

    <p>Thank you for registering with Humanity Foundation!</p>

    <p>Your application has been received and is currently under review. Our team will verify your details and get back
        to you shortly.</p>

    <p>In the meantime, you can keep your <strong>Volunteer ID: <?php echo e($user->employee_id); ?></strong> for future reference.
    </p>

    <p>Please note that your <strong>Official Offer Letter</strong> has been sent to your <strong>Upline
            Manager</strong>. You are requested to collect your offer letter from them and complete the necessary
        signature formalities.</p>

    <p>Once your signed document is uploaded and approved by our team, you will receive another email with instructions
        on how to access your dashboard.</p>

    <br>
    <p>Warm regards,</p>
    <p><strong>Humanity Foundation Team</strong></p>
</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views\emails\newbie_invitation.blade.php ENDPATH**/ ?>