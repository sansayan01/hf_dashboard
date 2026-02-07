@php
    $is_pdf = $is_pdf ?? false;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - {{ $user->profile?->full_name ?? 'N/A' }}</title>
    @if (!$is_pdf)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @endif
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
            background-color: {{ $is_pdf ? '#ffffff' : '#e2e8f0' }};
            color: var(--text-main);
            font-family: {{ $is_pdf ? 'DejaVu Sans, sans-serif' : "'Inter', sans-serif" }};
            -webkit-font-smoothing: antialiased;
            @if (!$is_pdf)
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 40px 0;
                min-height: 100vh;
            @endif
        }

        /* Page Container */
        .letter-page {
            width: {{ $is_pdf ? '100%' : '210mm' }};
            height: {{ $is_pdf ? 'auto' : '296.8mm' }}; /* A4 Height */
            background: white;
            position: relative;
            overflow: hidden;
            @if (!$is_pdf)
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            @endif
            padding: 10mm 15mm;
            display: flex;
            flex-direction: column;
        }

        /* Typography Override for Headings */
        h1, h2, h3, h4, h5, h6 {
            font-family: {{ $is_pdf ? 'DejaVu Sans, sans-serif' : "'Outfit', sans-serif" }};
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid var(--brand-green);
            padding-bottom: 12px;
            margin-bottom: 15px;
        }

        .header-logo {
            width: 70px;
            height: auto;
        }

        .header-text {
            text-align: right;
            flex: 1;
            margin-left: 20px;
        }

        .brand-name {
            font-size: 20px;
            font-weight: 800;
            color: var(--brand-green);
            text-transform: uppercase;
            letter-spacing: -0.5px;
            line-height: 1;
            margin-bottom: 4px;
        }

        .brand-sub {
            font-size: 9px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .brand-details {
            font-size: 8px;
            color: var(--text-muted);
            line-height: 1.3;
        }

        /* Recipient Section */
        .recipient-box {
            background: var(--bg-light);
            border-left: 4px solid var(--brand-green);
            padding: 10px 15px;
            border-radius: 0 4px 4px 0;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .recipient-info h3 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 2px;
        }

        .recipient-details {
            font-size: 10px;
            color: var(--text-muted);
        }

        .doc-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--brand-green);
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 1px solid var(--brand-green);
            padding: 4px 12px;
            border-radius: 20px;
        }

        /* Content Body */
        .content-body {
            font-size: 10.5px;
            line-height: 1.5;
            text-align: justify;
            margin-bottom: 15px;
            color: #334155;
            font-weight: 500;
        }

        .content-body p {
            margin-bottom: 8px;
        }

        .highlight {
            font-weight: 700;
            color: var(--brand-dark);
            background-color: #f0fdf4; /* Light green highlight bg */
            padding: 0 2px;
            border-radius: 2px;
        }

        /* Terms Section (The Space Saver) */
        .terms-container {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px;
            background: #fff;
            flex-grow: 1; /* Push footer down */
            display: flex;
            flex-direction: column;
        }

        .terms-title {
            font-size: 10px;
            font-weight: 800;
            color: var(--brand-red);
            text-transform: uppercase;
            margin-bottom: 8px;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 4px;
            letter-spacing: 0.5px;
        }

        .terms-grid {
            column-count: 2;
            column-gap: 20px;
            width: 100%;
        }

        .term-item {
            font-size: 8.5px;
            line-height: 1.35;
            color: var(--text-muted);
            margin-bottom: 4px;
            break-inside: avoid;
            page-break-inside: avoid;
            position: relative;
            padding-left: 10px;
        }

        .term-item::before {
            content: "•";
            position: absolute;
            left: 0;
            color: var(--brand-green);
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: auto;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .footer-left {
            font-size: 8px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        .footer-right {
            text-align: center;
        }

        .signature-img {
            height: 35px;
            margin-bottom: 2px;
        }

        .auth-line {
            width: 120px;
            height: 1px;
            background: var(--text-main);
            margin: 2px auto;
        }

        .auth-title {
            font-size: 9px;
            font-weight: 800;
            color: var(--brand-green);
            text-transform: uppercase;
        }

        .auth-sub {
            font-size: 8px;
            color: var(--text-muted);
        }

        /* Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            width: 350px;
            opacity: 0.04;
            pointer-events: none;
            z-index: 0;
        }

        /* Screen Controls */
        @if (!$is_pdf && !($hide_controls ?? false))
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
                box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            }

            .btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 12px rgba(0,0,0,0.15);
            }

            .btn-primary { background: var(--brand-green); color: white; }
            .btn-secondary { background: white; color: var(--text-main); }
        @endif
    </style>
</head>

<body>
    @if (!$is_pdf && !($hide_controls ?? false))
        <div class="controls">
            <button onclick="window.print()" class="btn btn-primary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Letter
            </button>
            <button onclick="downloadPDF()" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </button>
            <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back
            </a>
        </div>
    @endif

    <div class="letter-page" id="offer-letter">
        @php
            $logoPath = $is_pdf ? public_path('img/logo 1.png') : asset('img/logo 1.png');
            $sigPath = $is_pdf ? public_path('img/signature.png') : asset('img/signature.png');
        @endphp

        <!-- Watermark -->
        <img src="{{ $logoPath }}" class="watermark" alt="Watermark">

        <!-- Modern Header -->
        <div class="header">
            <img src="{{ $logoPath }}" alt="Logo" class="header-logo">
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
                <h3>{{ $user->profile?->full_name ?? 'N/A' }}</h3>
                <div class="recipient-details">
                    <span>Emp ID: <strong>{{ $user->employee_id }}</strong></span>
                    <span style="margin: 0 8px; color: #cbd5e1;">|</span>
                    <span>Aadhar: {{ $user->profile?->aadhaar_number ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="doc-title">Offer Letter</div>
        </div>

        <!-- Dynamic Body Content -->
        <div class="content-body">
            @if ($user->isDM())
                <p>We are pleased to offer you the position of <span class="highlight">District Manager (DM)</span> under
                <strong>{{ $user->profile?->district ?? 'N/A' }}</strong> District. This appointment is effective immediately upon the commencement of your team's performance. 
                We are confident that your leadership will drive our mission forward, and we assure you of our full support for your professional growth.</p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                You will receive a Monthly Honorarium of <span class="highlight">₹90,000</span> + <span class="highlight">₹5,000</span> (Travel Allowance), plus potential spot incentives. 
                Your primary duty is to lead Relationship Managers (RM) to expand HF membership, conduct surveys, and deliver essential services (Health, Banking, Govt Projects). 
                <strong>Target:</strong> Maintain a minimum of 625 active ROs in your team.</p>
            @elseif($user->isBM())
                <p>We are pleased to offer you the position of <span class="highlight">Block Manager (BM)</span> for
                <strong>{{ $user->profile?->block ?? 'N/A' }}</strong> Block, <strong>{{ $user->profile?->district ?? 'N/A' }}</strong> District. 
                This appointment is effective immediately upon the commencement of your team's performance. We look forward to your contributions.</p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                You will receive a Monthly Honorarium of <span class="highlight">₹37,500</span>, plus potential spot incentives. 
                Your primary duty is to lead Relationship Managers (RM) in expanding membership and delivering services.
                <strong>Target:</strong> Maintain a minimum of 125 active ROs in your team.</p>
            @elseif($user->isRM())
                <p>We are pleased to offer you the position of <span class="highlight">Relationship Manager (RM)</span> for
                <strong>{{ $user->profile?->block ?? 'N/A' }}</strong> Block, <strong>{{ $user->profile?->district ?? 'N/A' }}</strong> District. 
                This appointment is effective immediately upon the commencement of your team's performance.</p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                You will receive a Monthly Honorarium of <span class="highlight">₹18,750</span>, plus potential spot incentives. 
                Your role involves leading Relationship Officers (RO) to expand membership and facilitate service delivery.
                <strong>Target:</strong> Maintain a minimum of 25 active ROs in your team.</p>
            @else
                <p>We are pleased to offer you the position of <span class="highlight">Relationship Officer (RO)</span> at
                <strong>{{ $user->profile?->gram_panchayat ?? 'N/A' }}</strong> Gram Panchayat, <strong>{{ $user->profile?->block ?? 'N/A' }}</strong> Block. 
                This appointment is effective from your start date.</p>

                <p><strong>Compensation & Responsibilities:</strong><br>
                Humanity Foundation is a Govt. Registered Trust. You will receive a Monthly Honorarium of <span class="highlight">₹6,000</span> + <span class="highlight">₹1,500</span> (Travel Allowance).
                <strong>Daily Targets:</strong> Conduct 20 family surveys and generate 8 doctor appointments. 
                <strong>Monthly Targets:</strong> 200 appointments + 100 memberships. Daily reporting to your senior is mandatory.</p>
            @endif
            
            <p style="font-size: 9px; color: var(--text-muted); margin-top: 10px;">
                * This offer is contingent upon the verification of documents furnished by you.
            </p>
        </div>

        <!-- Compact Terms Section -->
        <div class="terms-container">
            <div class="terms-title">Terms & Conditions of Engagement</div>
            @php
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
                } else {
                    $terms = [
                        'Organization isn’t liable to pay you the above Honorarium, if you found guilty or non compliance.',
                        'The notice period is one month. The Trust may terminate immediately for non-performance.',
                        'Management reserves the right to switch to POP (Payment On Performance) mode anytime.',
                        'Flexible shift/process reallocation is mandatory as per Trust requirement.',
                        'Incomplete targets will proportionally affect Honorarium volume.',
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
            @endphp
            <div class="terms-grid">
                @foreach ($terms as $term)
                    <div class="term-item">{{ $term }}</div>
                @endforeach
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
                <img src="{{ $sigPath }}" alt="Signature" class="signature-img">
                <div class="auth-line"></div>
                <div class="auth-title">Secretary</div>
                <div class="auth-sub">Humanity Foundation</div>
            </div>
        </div>
    </div>

    @if (!$is_pdf)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function downloadPDF() {
                const element = document.getElementById('offer-letter');
                const opt = {
                    margin: 0,
                    filename: '{{ $user->profile?->full_name ?? "Offer_Letter" }}_{{ $user->employee_id }}.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, useCORS: true, logging: false },
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(element).save();
            }
        </script>
    @endif
</body>
</html>