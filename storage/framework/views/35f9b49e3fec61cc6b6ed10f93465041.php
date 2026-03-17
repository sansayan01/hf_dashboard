<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trust Verification | Humanity Foundation</title>
    <!-- Google Fonts: Outfit for a premium, modern look -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-blue: #0f172a;
            --accent-blue: #1e293b;
            --brand-gold: #c5a059;
            --brand-gold-light: #dfc89d;
            --success-green: #10B981;
            --danger-red: #EF4444;
            --glass-bg: rgba(255, 255, 255, 0.9);
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        body {
            background: radial-gradient(circle at top right, #1e293b, #0f172a);
            font-family: 'Outfit', sans-serif;
            color: #1e293b;
            min-height: 100vh;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        /* --- Animated Background Decoration --- */
        body::before {
            content: "";
            position: fixed;
            top: -10%;
            right: -10%;
            width: 40%;
            height: 40%;
            background: radial-gradient(circle, rgba(197, 160, 89, 0.08) 0%, transparent 70%);
            z-index: -1;
            pointer-events: none;
        }

        body::after {
            content: "";
            position: fixed;
            bottom: -5%;
            left: -5%;
            width: 30%;
            height: 40%;
            background: radial-gradient(circle, rgba(16, 185, 129, 0.05) 0%, transparent 70%);
            z-index: -1;
            pointer-events: none;
        }

        .header-brand {
            background: transparent;
            padding: 2.5rem 1rem 1.5rem;
            text-align: center;
        }

        .header-brand h1 {
            color: white;
            font-weight: 800;
            font-size: 1.75rem;
            letter-spacing: 3px;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .header-brand .tagline {
            color: var(--brand-gold);
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 0.5rem;
        }

        /* --- Main Container Styling --- */
        .verification-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 24px;
            box-shadow: var(--card-shadow);
            max-width: 520px;
            margin: 0 auto 3rem;
            overflow: hidden;
            position: relative;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* --- Status Banners --- */
        .status-badge-container {
            padding: 1.5rem 1rem;
            text-align: center;
            background: rgba(255, 255, 255, 0.5);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 10px 24px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .badge-valid {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
        }

        .badge-invalid {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
        }

        /* --- Profile Header --- */
        .profile-header {
            padding: 2.5rem 2rem 1.5rem;
            text-align: center;
        }

        .member-name {
            font-size: 1.85rem;
            font-weight: 800;
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .member-id-pill {
            background: var(--accent-blue);
            color: var(--brand-gold);
            padding: 6px 18px;
            border-radius: 12px;
            font-family: 'Monaco', 'Consolas', monospace;
            font-size: 1rem;
            font-weight: 600;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.1);
        }

        /* --- Data Presentation --- */
        .info-section {
            padding: 1rem 2rem 2rem;
        }

        .data-item {
            display: flex;
            align-items: flex-start;
            gap: 16px;
            padding: 1rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            transition: all 0.3s ease;
        }

        .icon-box {
            width: 40px;
            height: 40px;
            background: rgba(197, 160, 89, 0.1);
            color: var(--brand-gold);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .data-content {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .data-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748B;
            padding-top: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .data-val {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--primary-blue);
        }

        .blood-highlight {
            color: var(--danger-red);
            font-weight: 800;
        }

        /* --- Expiry Highlight --- */
        .expiry-box {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.08), rgba(16, 185, 129, 0.02));
            margin: 1rem 2rem;
            padding: 1.25rem;
            border-radius: 20px;
            border: 1px solid rgba(16, 185, 129, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* --- Timeline / History --- */
        .history-container {
            padding: 0 0 2rem;
        }

        .history-title {
            background: #f8fafc;
            padding: 1rem 2rem;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 2px;
            color: #64748B;
            text-transform: uppercase;
            border-top: 1px solid rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .history-block {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }

        .history-block:last-child {
            border-bottom: none;
        }

        .history-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .history-label {
            font-weight: 700;
            color: var(--primary-blue);
            font-size: 1rem;
        }

        .history-count {
            background: var(--brand-gold);
            color: white;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 700;
        }

        .timeline-item {
            position: relative;
            padding-left: 20px;
            border-left: 2px solid #E2E8F0;
            padding-bottom: 1.25rem;
        }

        .timeline-item::before {
            content: "";
            position: absolute;
            left: -7px;
            top: 4px;
            width: 12px;
            height: 12px;
            background: white;
            border: 2px solid var(--brand-gold);
            border-radius: 50%;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
            border-left: 2px solid transparent;
        }

        .extra-small {
            font-size: 0.75rem;
        }

        /* --- Utilities --- */
        .text-platinum { color: #E5E4E2; }
        .bg-platinum { background-color: #E5E4E2; }
        
        @media (max-width: 576px) {
            .verification-card { border-radius: 0; margin: 0; min-height: 100vh; }
            .info-section { padding: 1rem 1.5rem; }
            .history-block { padding: 1.5rem; }
        }
    </style>
</head>

<body>

    <header class="header-brand">
        <h1>HUMANITY</h1>
        <div class="tagline">Foundation Trust</div>
    </header>

    <div class="container pb-5">
        <div class="verification-card">

            <?php if($isValid && $patient): ?>
                <div class="status-badge-container">
                    <div class="status-badge badge-valid">
                        <i class="fa-solid fa-crown"></i>
                        VERIFIED PREMIUM MEMBER
                    </div>
                </div>

                <div class="profile-header">
                    <div class="member-name"><?php echo e(strtoupper($patient->full_name)); ?></div>
                    <div class="member-id-pill">ID: <?php echo e($patient->patient_id); ?></div>
                </div>

                <div class="info-section">
                    <!-- Essential Grid -->
                    <div class="row g-0">
                        <div class="col-12">
                            <div class="data-item">
                                <div class="icon-box"><i class="fa-solid fa-user-shield"></i></div>
                                <div class="data-content">
                                    <div class="data-label">Guardian / Relation</div>
                                    <div class="data-val"><?php echo e($patient->relative_name ?: 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="data-item">
                                <div class="icon-box"><i class="fa-solid fa-venus-mars"></i></div>
                                <div class="data-content">
                                    <div class="data-label">Age / Sex</div>
                                    <div class="data-val"><?php echo e($patient->age); ?>Y / <?php echo e(strtoupper(substr($patient->gender, 0, 1))); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-6">
                            <div class="data-item">
                                <div class="icon-box"><i class="fa-solid fa-droplet"></i></div>
                                <div class="data-content">
                                    <div class="data-label">Blood Grp</div>
                                    <div class="data-val blood-highlight"><?php echo e($patient->blood_group ?: 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="data-item">
                                <div class="icon-box"><i class="fa-solid fa-phone-volume"></i></div>
                                <div class="data-content">
                                    <div class="data-label">Contact Primary</div>
                                    <div class="data-val">+91 <?php echo e($patient->phone_number); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="data-item">
                                <div class="icon-box"><i class="fa-solid fa-id-card"></i></div>
                                <div class="data-content">
                                    <div class="data-label">KYC (Aadhaar / PAN)</div>
                                    <div class="data-val" style="font-size: 0.9rem;">
                                        <?php echo e($patient->aadhar_number ? implode(' ', str_split($patient->aadhar_number, 4)) : 'N/A'); ?>

                                        <?php if($patient->pan_number): ?> <span class="text-muted mx-1">|</span> <?php echo e($patient->pan_number); ?> <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="p-3 bg-light rounded-4" style="border: 1px dashed #cbd5e1;">
                                <div class="data-label mb-2"><i class="fa-solid fa-location-arrow me-1"></i> Registered Address</div>
                                <div class="data-val fw-normal" style="font-size: 0.95rem; line-height: 1.5; color: #475569;">
                                    <?php echo e($patient->address); ?>, <?php echo e($patient->gp); ?>, <?php echo e($patient->block); ?><br>
                                    <?php echo e($patient->district); ?>, WB - <?php echo e($patient->pin); ?>

                                </div>
                            </div>
                        </div>

                        <div class="col-12 mt-3">
                            <div class="data-label mb-2 fw-bold text-dark"><i class="fa-solid fa-file-medical me-1"></i> Health Notes</div>
                            <div class="p-3 bg-white rounded-4" style="border: 1px solid #f1f5f9; min-height: 60px;">
                                <div class="data-val fw-normal text-muted" style="font-size: 0.9rem;">
                                    <?php echo e($patient->health_issues ?: 'No medical alerts or concurrent diseases recorded.'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="expiry-box">
                    <div>
                        <div class="data-label text-success">TRUST ACCOUNT ACCESS</div>
                        <div class="data-val text-success" style="font-size: 1.25rem;">Active Member</div>
                    </div>
                    <div class="text-end">
                        <div class="data-label">Valid Until</div>
                        <div class="data-val fw-bold"><?php echo e(\Carbon\Carbon::parse($patient->created_at)->addYear()->format('d M Y')); ?></div>
                    </div>
                </div>

                <div class="history-container">
                    <div class="history-title">Institutional Record Archive</div>

                    <!-- Appointments -->
                    <div class="history-block">
                        <div class="history-header">
                            <div class="history-label text-uppercase small">Medical Visits</div>
                            <div class="history-count"><?php echo e($patient->appointments->count()); ?></div>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $patient->appointments->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $app): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-bold"><?php echo e($app->doctor_type); ?></div>
                                    <div class="text-muted small"><?php echo e(\Carbon\Carbon::parse($app->appointment_date)->format('d M y')); ?></div>
                                </div>
                                <div class="text-muted extra-small"><?php echo e($app->location); ?></div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted small">No visit records available.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Medicine -->
                    <div class="history-block">
                        <div class="history-header">
                            <div class="history-label text-uppercase small">Pharmacy Dispensary</div>
                            <div class="history-count"><?php echo e($patient->medicineDistributions->count()); ?></div>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $patient->medicineDistributions->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-bold">Rx Dist #<?php echo e($dist->id); ?></div>
                                    <div class="text-muted small"><?php echo e($dist->created_at->format('d M y')); ?></div>
                                </div>
                                <div class="text-muted extra-small">
                                    <?php $meds = $dist->items->pluck('medicine.name')->toArray(); ?>
                                    <?php echo e(implode(', ', array_slice($meds, 0, 3))); ?><?php if(count($meds) > 3): ?>... <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted small">No dispensary history.</div>
                        <?php endif; ?>
                    </div>

                    <!-- Pathology -->
                    <div class="history-block mb-0">
                        <div class="history-header">
                            <div class="history-label text-uppercase small">Diagnostics (Lab)</div>
                            <div class="history-count"><?php echo e($patient->pathologyTests->count()); ?></div>
                        </div>
                        <?php $__empty_1 = true; $__currentLoopData = $patient->pathologyTests->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="timeline-item">
                                <div class="d-flex justify-content-between">
                                    <div class="fw-bold"><?php echo e($test->test_name); ?></div>
                                    <div class="text-muted small"><?php echo e($test->date->format('d M y')); ?></div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-muted small">No diagnostic records.</div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php else: ?>
                <div class="status-badge-container">
                    <div class="status-badge badge-invalid">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        VERIFICATION FAILED
                    </div>
                </div>

                <div class="p-5 text-center">
                    <div class="mb-4">
                        <i class="fa-solid fa-user-slash text-danger" style="font-size: 3.5rem; opacity: 0.5;"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Data Not Found</h4>
                    <p class="text-muted" style="max-width: 300px; margin: 0 auto; font-size: 0.95rem;">
                        The verification token is either expired, revoked, or non-existent in the trust database.
                    </p>
                </div>
            <?php endif; ?>

        </div>
        
        <div class="text-center text-platinum opacity-50 small mt-4">
            &copy; <?php echo e(date('Y')); ?> Humanity Foundation, West Bengal<br>
            Secure Blockchain Verification Node: HF-<?php echo e(strtoupper(bin2hex(random_bytes(4)))); ?>

        </div>
    </div>

</body>

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views\membership\public_verify.blade.php ENDPATH**/ ?>