<?php
    $is_pdf = $is_pdf ?? false;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - <?php echo e($user->profile?->full_name ?? 'N/A'); ?></title>
    <?php if(!$is_pdf): ?>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap"
            rel="stylesheet">
    <?php endif; ?>
    <style>
        :root {
            --brand-green: #008037;
            --brand-dark: #004d21;
            --brand-red: #cc0000;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        /* Reset & Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color:
                <?php echo e($is_pdf ? '#ffffff' : '#e2e8f0'); ?>

            ;
            color: var(--text-main);
            font-family:
                <?php echo e($is_pdf ? 'DejaVu Sans, sans-serif' : "'Inter', sans-serif"); ?>

            ;
            -webkit-font-smoothing: antialiased;
            <?php if(!$is_pdf): ?>
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 40px 0;
                min-height: 100vh;
            <?php endif; ?>
        }

        /* Page Container */
        .letter-page {
            width:
                <?php echo e($is_pdf ? '100%' : '210mm'); ?>

            ;
            height:
                <?php echo e($is_pdf ? 'auto' : '296.8mm'); ?>

            ;
            /* A4 Height */
            background: white;
            position: relative;
            overflow: hidden;
            <?php if(!$is_pdf): ?>
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            <?php endif; ?>
            /* Optimized padding for coverage */
            padding: 10mm 15mm;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Typography Override for Headings */
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family:
                <?php echo e($is_pdf ? 'DejaVu Sans, sans-serif' : "'Outfit', sans-serif"); ?>

            ;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid var(--brand-green);
            padding-bottom: 20px;
            margin-bottom: 30px;
            flex-shrink: 0;
            position: relative;
        }

        .header-logo {
            width: 110px;
            /* Significantly larger */
            height: auto;
        }

        .header-text {
            text-align: right;
            flex: 1;
            margin-left: 30px;
        }

        .brand-name {
            font-size: 32px;
            /* Much larger */
            font-weight: 800;
            color: var(--brand-green);
            text-transform: uppercase;
            letter-spacing: -1px;
            line-height: 1;
            margin-bottom: 8px;
        }

        .brand-sub {
            font-size: 13px;
            /* Increased */
            color: var(--text-main);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }

        .brand-details {
            font-size: 11px;
            /* Increased */
            color: var(--text-muted);
            line-height: 1.4;
            font-weight: 500;
        }

        /* Recipient Section */
        .recipient-box {
            background: var(--bg-light);
            border-left: 5px solid var(--brand-green);
            padding: 12px 18px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .recipient-info h3 {
            font-size: 18px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 4px;
        }

        .recipient-details {
            font-size: 12px;
            color: var(--text-muted);
        }

        .doc-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--brand-green);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border: 2px solid var(--brand-green);
            padding: 6px 16px;
            border-radius: 50px;
        }

        /* Content Body */
        .content-body {
            font-size: 14.5px;
            /* Increased for better visibility */
            line-height: 1.65;
            text-align: justify;
            margin-bottom: 25px;
            color: #334155;
            font-weight: 500;
            flex-grow: 0;
        }

        .content-body p {
            margin-bottom: 15px;
        }

        .highlight {
            font-weight: 700;
            color: var(--brand-dark);
            background-color: #f0fdf4;
            padding: 0 4px;
            border-radius: 4px;
        }

        /* Terms Section */
        .terms-container {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 15px;
            background: #fff;
            flex-grow: 1;
            /* Fills remaining space */
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .terms-title {
            font-size: 16px;
            /* Increased */
            font-weight: 800;
            color: var(--brand-red);
            text-transform: uppercase;
            margin-bottom: 15px;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .terms-grid {
            /* Single column layout */
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        .term-item {
            font-size: 12px;
            line-height:
                <?php echo e($user->isRO() ? '1.6' : '1.35'); ?>

            ;
            color: var(--text-muted);
            margin-bottom:
                <?php echo e($user->isRO() ? '8px' : '3px'); ?>

            ;
            padding-left: 0;
            display: flex;
            align-items: flex-start;
        }

        .term-number {
            font-weight: 700;
            color: var(--brand-green);
            min-width: 20px;
            margin-right: 5px;
        }

        /* Footer */
        .footer {
            margin-top: auto;
            border-top: 1px solid #e2e8f0;
            padding-top: 15px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-shrink: 0;
        }

        .footer-left {
            font-size: 10px;
            color: var(--text-muted);
            line-height: 1.5;
        }

        .footer-right {
            text-align: center;
        }

        .signature-img {
            height: 45px;
            margin-bottom: 5px;
        }

        .auth-line {
            width: 140px;
            height: 1px;
            background: var(--text-main);
            margin: 4px auto;
        }

        .auth-title {
            font-size: 11px;
            font-weight: 800;
            color: var(--brand-green);
            text-transform: uppercase;
        }

        .auth-sub {
            font-size: 9px;
            color: var(--text-muted);
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            width: 400px;
            opacity: 0.03;
            pointer-events: none;
            z-index: 0;
        }

        /* Screen Controls */
        <?php if(!$is_pdf && !($hide_controls ?? false)): ?>
            .controls {
                position: fixed;
                top: 30px;
                right: 30px;
                display: flex;
                gap: 10px;
                z-index: 50;
            }

            .btn {
                padding: 10px 20px;
                border: none;
                border-radius: 8px;
                font-weight: 600;
                cursor: pointer;
                font-size: 14px;
                text-decoration: none;
                display: flex;
                align-items: center;
                gap: 6px;
                transition: transform 0.2s, box-shadow 0.2s;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
            }

            .btn-primary {
                background: var(--brand-green);
                color: white;
            }

            .btn-secondary {
                background: white;
                color: var(--text-main);
            }

        <?php endif; ?>
    </style>
</head>

<body>
    <?php if(!$is_pdf && !($hide_controls ?? false)): ?>
        <div class="controls">
            <button onclick="window.print()" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                    </path>
                </svg>
                Print Letter
            </button>
            <button onclick="downloadPDF()" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download PDF
            </button>
            <a href="<?php echo e(route('users.show', $user->id)); ?>" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Back
            </a>
        </div>
    <?php endif; ?>

    <div class="letter-page" id="offer-letter">
        <?php
            $logoPath = $is_pdf ? public_path('img/logo 1.png') : asset('img/logo 1.png');
            $sigPath = $is_pdf ? public_path('img/signature.png') : asset('img/signature.png');
        ?>

        <!-- Watermark -->
        <img src="<?php echo e($logoPath); ?>" class="watermark" alt="Watermark">

        <!-- Modern Header -->
        <div class="header">
            <img src="<?php echo e($logoPath); ?>" alt="Logo" class="header-logo">
            <div class="header-text">
                <div class="brand-name">Humanity Foundation</div>
                <div class="brand-sub">Registered Non-Government Organization (NGO)</div>
                <div class="brand-details">Reg No: IV-190100489 (Sec-60 & Rule 69)</div>
                <div class="brand-details">Kendriyanagar, Keshabganj Chati, Burdwan, West Bengal - 713104</div>
            </div>
        </div>

        <!-- Recipient Bar -->
        <div class="recipient-box">
            <div class="recipient-info">
                <h3><?php echo e($user->profile?->full_name ?? 'N/A'); ?></h3>
                <div class="recipient-details">
                    <span><?php echo e(in_array($user->designation, ['office_in_charge', 'staff', 'camp_organizer']) ? 'Emp ID' : 'Vol ID'); ?>:
                        <strong><?php echo e($user->employee_id); ?></strong></span>
                    <span style="margin: 0 8px; color: #cbd5e1;">|</span>
                    <span>Aadhar: <strong><?php echo e($user->profile?->aadhaar_number ?? 'N/A'); ?></strong></span>
                </div>
            </div>
            <div class="doc-title">Offer Letter</div>
        </div>

        <!-- Dynamic Body Content -->
        <div class="content-body">
            <?php if($user->isDM()): ?>
                <p>We are pleased to offer you the position of <span class="highlight">District Manager (DM)</span> under
                    <strong><?php echo e($user->profile?->district ?? 'N/A'); ?></strong> District. This appointment is effective
                    immediately upon the commencement of your team's performance.
                    We are confident that your leadership will drive our mission forward, and we assure you of our full
                    support for your professional growth.
                </p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                    You will receive a Monthly Honorarium of <span class="highlight">₹90,000</span> + <span
                        class="highlight">₹5,000</span> (Travel Allowance), plus potential spot incentives.
                    Your primary duty is to lead Relationship Managers (RM) to expand HF membership, conduct surveys, and
                    deliver essential services (Health, Banking, Govt Projects).
                    <strong>Task:</strong> Maintain a minimum of 625 active ROs in your team.
                </p>
            <?php elseif($user->isBM()): ?>
                <p>We are pleased to offer you the position of <span class="highlight">Block Manager (BM)</span> for
                    <strong><?php echo e($user->profile?->block ?? 'N/A'); ?></strong> Block,
                    <strong><?php echo e($user->profile?->district ?? 'N/A'); ?></strong> District.
                    This appointment is effective immediately upon the commencement of your team's performance. We look
                    forward to your contributions.
                </p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                    You will receive a Monthly Honorarium of <span class="highlight">₹37,500</span>, plus potential spot
                    incentives.
                    Your primary duty is to lead Relationship Managers (RM) in expanding membership and delivering services.
                    <strong>Task:</strong> Maintain a minimum of 125 active ROs in your team.
                </p>
            <?php elseif($user->isRM()): ?>
                <p>We are pleased to offer you the position of <span class="highlight">Relationship Manager (RM)</span> for
                    <strong><?php echo e($user->profile?->block ?? 'N/A'); ?></strong> Block,
                    <strong><?php echo e($user->profile?->district ?? 'N/A'); ?></strong> District.
                    This appointment is effective immediately upon the commencement of your team's performance.
                </p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                    You will receive a Monthly Honorarium of <span class="highlight">₹18,750</span>, plus potential spot
                    incentives.
                    Your role involves leading Relationship Officers (RO) to expand membership and facilitate service
                    delivery.
                    <strong>Task:</strong> Maintain a minimum of 25 active ROs in your team.
                </p>
            <?php elseif($user->isRO()): ?>
                <p>We are pleased to offer you the position of <span class="highlight">Relationship Officer (RO)</span> at
                    <strong><?php echo e($user->profile?->gram_panchayat ?? 'N/A'); ?></strong> Gram Panchayat,
                    <strong><?php echo e($user->profile?->block ?? 'N/A'); ?></strong> Block.
                    This appointment is effective from your start date.
                </p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                    Humanity Foundation is a Govt. Registered Trust. You will receive a Monthly Honorarium of <span
                        class="highlight">₹6,000</span> + <span class="highlight">₹1,500</span> (Travel Allowance).
                    Conduct 25 surveys and generate 8 doctor appointments a day. <strong>Monthly Tasks:</strong> 200
                    appointments + 130 membership cards. Daily reporting to your senior is mandatory.</p>
            <?php elseif($user->isSuperAdmin()): ?>
                <p>We are pleased to offer you the position of <span
                        class="highlight"><?php echo e($user->post ?? 'Super Admin'); ?></span>.
                    This appointment is effective immediately. We are confident that your leadership and vision will
                    significantly contribute to the Humanity Foundation's mission and growth.</p>

                <p><strong>Role & Responsibilities:</strong><br>
                    As <?php echo e($user->post ?? 'a Super Admin'); ?>, your role involves strategic leadership, organizational
                    oversight, and providing critical guidance to the broader management team. You are responsible for
                    ensuring the foundation's core objectives are met with the highest standards of integrity and
                    operational excellence.</p>
            <?php elseif($user->designation === 'staff'): ?>
                <p>We are pleased to offer you the position of <span class="highlight">Pharmacist</span>.
                    This appointment is effective immediately. Your professional expertise is vital to our healthcare
                    mission.</p>

                <p><strong>Role & Responsibilities:</strong><br>
                    Your role involves the precise management of the medicine registry, overseeing pharmaceutical stock
                    levels, ensuring accurate dispensing to beneficiaries, and maintaining meticulous inventory records to
                    support our health welfare programs.</p>
            <?php elseif($user->designation === 'camp_organizer'): ?>
                <p>We are pleased to offer you the position of <span class="highlight">Camp Organizer</span>.
                    This appointment is effective after you successfully able to organize your first camp, technically
                    which is your operational testing camp. You will lead the administrative operations of your assigned
                    unit. Your salary is fixed to <span class="highlight">₹8,000</span> if you fulfill the roles and
                    responsibilities mentioned on your offer letter. And if you can't able to maintain, it will harm your
                    salary.</p>

                <p><strong>Roles and Responsibilities:</strong><br>
                    a. You have to take care about the camps, maintained or organized by you should be at least 80-100
                    patients per camp should be attend on the camp date.<br>
                    b. The camp organizer has to maintain a daily (camp wise/purpose) medicine sell of atleast
                    7000-9000 rs.<br>
                    c. You have to take care of medicine stock (through pharmacists or by yourself) of every camps
                    which are your undertaken.<br>
                    d. You are responsible for end-to-end camp logistics, including coordinating doctor transportation,
                    setting up pathology services, managing all floor arrangements, etc. to ensure smooth camp
                    operations.</p>
            <?php elseif($user->isOfficeInCharge()): ?>
                <p>We are pleased to offer you the position of <span class="highlight">Office In-Charge</span>.
                    This appointment is effective immediately. You will lead the administrative operations of your assigned
                    unit.</p>

                <p><strong>Role & Responsibilities:</strong><br>
                    You are responsible for branch administration, supervising office staff, managing day-to-day facility
                    operations, and serving as the primary liaison between field personnel and central management.</p>
            <?php else: ?>
                <p>We are pleased to offer you the position of <span
                        class="highlight"><?php echo e($user->getDesignationLabel()); ?></span>.
                    This appointment is effective immediately. We look forward to your contributions to the Humanity
                    Foundation.</p>

                <p><strong>Role & Responsibilities:</strong><br>
                    Your position involves performing duties assigned by the management team to ensure the smooth operation
                    of our social welfare projects and maintaining the foundation's commitment to community service.</p>
            <?php endif; ?>

            <p style="font-size: 11px; color: var(--text-muted); margin-top: 15px;">
                * This offer is contingent upon the verification of documents furnished by you.
            </p>
        </div>

        <!-- Compact Terms Section -->
        <div class="terms-container">
            <div class="terms-title">Terms & Conditions of Engagement</div>
            <?php
                $terms = [];
                // Use standard User model methods 
                if ($user->isDM() || $user->isBM() || $user->isRM()) {
                    $terms = [
                        'Organization isn’t liable to pay you the above Honorarium, if you found guilty or non compliance.',
                        'You should have minimum ' . ($user->isDM() ? '625' : ($user->isBM() ? '125' : '25')) . ' active ROs in your team.',
                        'The notice period is one month. The Trust may terminate immediately for non-performance or integrity issues.',
                        'No payout for days with stopped work or non-performance.',
                        'Management reserves the right to switch to POP (Payment On Performance) mode anytime.',
                        'Flexible shift/process reallocation is mandatory as per Trust requirement.',
                        'Minimum 40 days service required to claim any salary or dues.',
                        'ID card must be carried during duty hours.',
                        'Unauthorized offering of products/services is strictly prohibited.',
                        'Do not instruct RMs for personal work.',
                        'Daily reporting mandatory. 2-day delay = ₹500 deduction. 5-day = Show Cause. 10-day = Termination.',
                        'Authority to warn/act against non-performing RMs is granted.',
                        'Ensure daily deposit of all collected bills/fees.',
                        'Retention of collected fees is strictly prohibited.',
                        'Maintain professional behavior with all stakeholders.',
                        'Honorarium is disbursed by the 10th of the following month.'
                    ];
                } elseif ($user->isSuperAdmin() || $user->isOfficeInCharge() || $user->designation === 'staff') {
                    $terms = [
                        'Organization isn’t liable to pay you the above Honorarium, if you found guilty of non-compliance or breach of trust.',
                        'The notice period is one month. The Trust may terminate immediately for serious misconduct or integrity issues.',
                        'No honorarium for periods of unapproved absence or non-performance of duties.',
                        'Strict adherence to high confidentiality and professional ethics is mandatory.',
                        'Flexible shift/process reallocation is mandatory as per Trust requirement.',
                        'Minimum 40 days service required to claim any salary or dues upon separation.',
                        'Formal dress and ID card are mandatory during duty hours.',
                        'Unauthorized offering of external products/services is strictly prohibited.',
                        'Daily reporting and attendance marking are mandatory.',
                        'Maintain professional behavior with all members and stakeholders.',
                        'Honorarium is disbursed by the 10th of the following month.'
                    ];
                } else {
                    $terms = [
                        'Organization isn’t liable to pay you the above Honorarium, if you found guilty or non compliance.',
                        'The notice period is one month. The Trust may terminate immediately for non-performance.',
                        'Management reserves the right to switch to POP (Payment On Performance) mode anytime.',
                        'Flexible shift/process reallocation is mandatory as per Trust requirement.',
                        'Incomplete tasks will proportionally affect Honorarium volume.',
                        'Minimum 40 days service required to claim any salary or dues.',
                        'Formal dress and ID card are mandatory during duty.',
                        'Unauthorized offering of products/services is strictly prohibited.',
                        'Daily reporting mandatory. 3-day delay = ₹300 deduction. 5-day = Show Cause. 10-day = Termination.',
                        'Daily submission of collected fees/bills is mandatory.',
                        'Retention of collected fees is strictly prohibited.',
                        'Maintain professional behavior with HF Members.',
                        'Honorarium is disbursed by the 10th of the following month.'
                    ];
                }
            ?>
            <div class="terms-grid">
                <?php $__currentLoopData = $terms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $term): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="term-item">
                        <span class="term-number"><?php echo e($index + 1); ?>.</span>
                        <span><?php echo e($term); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-left">
                <div><strong>Contact Us:</strong></div>
                <div>Phone: +91 97355 63157</div>
                <div>Email: info@hfburdwan.in</div>
                <div>Web: www.hfburdwan.in</div>
            </div>
            <div class="footer-right">
                <img src="<?php echo e($sigPath); ?>" alt="Signature" class="signature-img">
                <div class="auth-line"></div>
                <div class="auth-title">Secretary</div>
                <div class="auth-sub">Humanity Foundation</div>
            </div>
        </div>
    </div>

    <?php if(!$is_pdf): ?>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function downloadPDF() {
                const element = document.getElementById('offer-letter');
                const opt = {
                    margin: 0,
                    filename: '<?php echo e($user->profile?->full_name ?? "Offer_Letter"); ?>_<?php echo e($user->employee_id); ?>.pdf',
                    image: { type: 'jpeg', quality: 1.0 },
                    html2canvas: { scale: 5, useCORS: true, logging: false, letterRendering: true },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(element).save();
            }
        </script>
    <?php endif; ?>
</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views/users/joining_letter.blade.php ENDPATH**/ ?>