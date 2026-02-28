<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Membership Card - <?php echo e($patient->patient_id); ?></title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            /* Landscape PVC Dimensions: 85.6mm x 53.98mm = 242.646pt x 153.018pt */
            size: 242.646pt 153.018pt landscape;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            background-color: #ffffff;
            /* Safe default */
            color: #ffffff;
        }

        /* --- THE CARD CANVAS --- */
        .card-container {
            width: 242.646pt;
            height: 153.018pt;
            position: relative;
            background-color: #11141a;
            /* Very dark navy/charcoal for premium feel */
            border: 0.5pt solid #c5a059;
            /* Very thin metallic edge */
            box-sizing: border-box;
            overflow: hidden;
            display: block;
        }

        /* --- BACKGROUND WATERMARK --- */
        .bg-watermark {
            position: absolute;
            top: 20pt;
            right: 10pt;
            width: 140pt;
            height: 140pt;
            opacity: 0.05;
            /* Extremely faint */
            z-index: 0;
        }

        /* --- HIGH-END TOP METALLIC LINE --- */
        .top-edge {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 1.5pt;
            background-color: #E5E4E2;
            z-index: 10;
        }

        /* --- HEADER & ORGANIZATION INFO --- */
        .logo-box {
            position: absolute;
            top: 15pt;
            left: 15pt;
            width: 24pt;
            height: 24pt;
            z-index: 10;
        }

        .org-title {
            position: absolute;
            top: 18pt;
            left: 46pt;
            font-family: 'Times New Roman', serif;
            font-size: 8.5pt;
            font-weight: normal;
            letter-spacing: 2.5pt;
            /* High tracking for luxury feel */
            color: #E5E4E2;
            /* Platinum */
            text-transform: uppercase;
            z-index: 10;
        }

        .org-subtitle {
            position: absolute;
            top: 30pt;
            left: 47pt;
            font-size: 4.5pt;
            font-weight: bold;
            letter-spacing: 4pt;
            color: #C5A059;
            /* Muted Gold/Bronze */
            text-transform: uppercase;
            z-index: 10;
        }

        .card-type {
            position: absolute;
            top: 18pt;
            right: 15pt;
            font-size: 5pt;
            font-weight: 600;
            letter-spacing: 2pt;
            color: #64748b;
            /* Slate gray */
            text-transform: uppercase;
            text-align: right;
            border-bottom: 0.5pt solid #c5a059;
            padding-bottom: 2pt;
            z-index: 10;
        }

        /* --- MEMBER HIGHLIGHT (HERO SECTION) --- */
        .hero-name {
            position: absolute;
            top: 60pt;
            left: 15pt;
            font-size: 13pt;
            font-weight: 300;
            /* Thin, elegant weight */
            letter-spacing: 1.5pt;
            color: #ffffff;
            text-transform: uppercase;
            z-index: 10;
        }

        .hero-relative {
            position: absolute;
            top: 76pt;
            left: 16pt;
            font-size: 5pt;
            font-weight: 400;
            letter-spacing: 1pt;
            color: #94a3b8;
            text-transform: uppercase;
            z-index: 10;
        }

        .hero-id {
            position: absolute;
            top: 86pt;
            left: 15pt;
            font-size: 10.5pt;
            font-weight: bold;
            letter-spacing: 2pt;
            color: #C5A059;
            /* Metallic contrast */
            font-family: 'Courier New', Courier, monospace;
            z-index: 10;
        }

        /* --- SECONDARY DETAILS GRID --- */
        .grid-line {
            position: absolute;
            top: 102pt;
            left: 15pt;
            width: 212.646pt;
            height: 0.5pt;
            background-color: #334155;
            /* Solid fallback for border */
            z-index: 10;
        }

        .detail-block {
            position: absolute;
            z-index: 10;
        }

        /* 3-Column Strict Grid */
        .col-1 {
            left: 15pt;
            width: 62pt;
        }

        .col-2 {
            left: 80pt;
            width: 66pt;
        }

        .col-3 {
            left: 151pt;
            width: 78pt;
        }

        /* Fits Address */

        /* 3-Row Vertical Alignment */
        .row-1 {
            top: 106pt;
        }

        .row-2 {
            top: 121pt;
        }

        .row-3 {
            top: 136pt;
        }

        .label {
            font-size: 4pt;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8pt;
            margin-bottom: 1.5pt;
        }

        .val {
            font-size: 5.5pt;
            font-weight: normal;
            color: #ffffff;
            letter-spacing: 0.5pt;
        }

        .val-address {
            font-size: 5pt;
            font-weight: normal;
            color: #cbd5e1;
            line-height: 1.2;
            /* Allow basic wrapping */
            word-wrap: break-word;
        }

        .blood-group {
            color: #ef4444;
            /* Subtle red for medical context */
            font-weight: bold;
        }

        /* --- FOOTER --- */
        .footer-text {
            position: absolute;
            bottom: 4pt;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 3.5pt;
            color: #64748b;
            letter-spacing: 0.5pt;
            text-transform: uppercase;
            z-index: 10;
        }
    </style>
</head>

<body>
    <div class="card-container">

        <!-- Subtle Top Metallic Edge -->
        <div class="top-edge"></div>

        <!-- Asset Setup -->
        <?php
            $logoPath = public_path('img/hf_gold_logo.png');
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            // Format ID with spaces: HFPM000001 -> HFPM 0000 01
            $idChunks = str_split($patient->patient_id, 4);
            $formattedId = implode(' ', $idChunks);

            // Format Aadhar logically if it exists
            $aadharFormatted = $patient->aadhar_number ? implode(' ', str_split($patient->aadhar_number, 4)) : 'N/A';
        ?>

        <!-- Large Ghosted Background Logo -->
        <img src="<?php echo e($logoSrc); ?>" class="bg-watermark">

        <!-- Header: Logo, Name, Type -->
        <img src="<?php echo e($logoSrc); ?>" class="logo-box">
        <div class="org-title">HUMANITY FOUNDATION</div>
        <div class="org-subtitle">EXECUTIVE MEMBERSHIP</div>

        <div class="card-type">
            PREMIUM<br>
            <span style="font-size: 3.5pt; color: #475569; letter-spacing: 1pt;">VALIDATED</span>
        </div>

        <!-- Hero Section: Member Info -->
        <div class="hero-name"><?php echo e($patient->full_name); ?></div>
        <?php if($patient->relative_name): ?>
            <div class="hero-relative">C/O <?php echo e($patient->relative_name); ?></div>
        <?php endif; ?>
        <div class="hero-id"><?php echo e($formattedId); ?></div>

        <!-- Divider Line -->
        <div class="grid-line"></div>

        <!-- Precise Data Grid (3x3 Matrix) -->

        <!-- GRID ROW 1 -->
        <div class="detail-block col-1 row-1">
            <div class="label">AGE / GENDER</div>
            <div class="val"><?php echo e($patient->age); ?> YRS / <?php echo e(strtoupper(substr($patient->gender ?? 'N', 0, 1))); ?></div>
        </div>
        <div class="detail-block col-2 row-1">
            <div class="label">BLOOD GRP</div>
            <div class="val blood-group"><?php echo e($patient->blood_group ?? 'N/A'); ?></div>
        </div>
        <div class="detail-block col-3 row-1">
            <div class="label">VALID TILL</div>
            <div class="val"><?php echo e(\Carbon\Carbon::parse($patient->created_at)->addYear()->format('M d, Y')); ?></div>
        </div>

        <!-- GRID ROW 2 -->
        <div class="detail-block col-1 row-2">
            <div class="label">CONTACT NO.</div>
            <div class="val">+91 <?php echo e($patient->phone_number); ?></div>
        </div>
        <div class="detail-block col-2 row-2">
            <div class="label">AADHAAR NO.</div>
            <div class="val"><?php echo e($aadharFormatted); ?></div>
        </div>

        <!-- Address Span across Row 2 & 3 in Col 3 -->
        <div class="detail-block col-3 row-2">
            <div class="label">REGISTERED ADDRESS</div>
            <div class="val-address">
                <?php echo e($patient->address); ?>, <?php echo e($patient->gp); ?>, <?php echo e($patient->block); ?> - <?php echo e($patient->pin); ?>

            </div>
        </div>

        <!-- Security / Ownership Footer -->
        <div class="footer-text">
            PROPERTY OF HUMANITY FOUNDATION &nbsp;&bull;&nbsp; SECURE ID &nbsp;&bull;&nbsp; NON-TRANSFERABLE
        </div>

    </div>
</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views/membership/cards/pvc.blade.php ENDPATH**/ ?>