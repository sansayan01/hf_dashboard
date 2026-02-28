<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Membership Card - {{ $patient->patient_id }}</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
            /* Landscape PVC Dimensions: 85.6mm x 53.98mm = 242.646pt x 153.018pt */
            size: 242.646pt 153.018pt landscape;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 242pt;
            height: 152pt;
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            background-color: #ffffff;
            color: #ffffff;
            overflow: hidden;
        }

        /* --- THE CARD CANVAS --- */
        .card-container {
            width: 242.646pt;
            height: 152.5pt;
            position: absolute;
            top: 0;
            left: 0;
            background-color: #11141a;
            border-top: 4pt solid #c5a059;
            border-left: 4pt solid #c5a059;
            border-right: 1pt solid #c5a059;
            border-bottom: 1pt solid #c5a059;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* --- BACKGROUND WATERMARK --- */
        .bg-watermark {
            position: absolute;
            top: 6.5pt;
            left: 51.3pt;
            width: 140pt;
            height: 140pt;
            opacity: 0.05;
            /* Extremely faint */
            z-index: 0;
        }

        /* --- HEADER & ORGANIZATION INFO --- */
        .logo-box {
            position: absolute;
            top: 13pt;
            left: 15pt;
            width: 33pt;
            height: 35pt;
            z-index: 10;
        }

        .org-title {
            position: absolute;
            top: 20pt;
            left: 53pt;
            font-family: 'Times New Roman', serif;
            font-size: 9pt;
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
            top: 32pt;
            left: 54pt;
            font-size: 4.8pt;
            font-weight: bold;
            letter-spacing: 4pt;
            color: #C5A059;
            /* Muted Gold/Bronze */
            text-transform: uppercase;
            z-index: 10;
        }


        /* --- MEMBER HIGHLIGHT (HERO SECTION) --- */
        .hero-name {
            position: absolute;
            top: 53pt;
            left: 15pt;
            font-size: 13pt;
            font-weight: 300;
            /* Thin, elegant weight */
            letter-spacing: 1.5pt;
            color: #ffffff;
            text-transform: uppercase;
            z-index: 10;
        }

        .hero-id {
            position: absolute;
            top: 72pt;
            left: 15pt;
            font-size: 6pt;
            font-weight: 800;
            letter-spacing: 3pt;
            color: #C5A059;
            /* Premium Serif look */
            font-family: 'Times New Roman', Times, serif;
            z-index: 10;
        }

        /* --- SECONDARY DETAILS GRID --- */
        .grid-line {
            position: absolute;
            top: 90pt;
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
        }

        .col-2 {
            left: 78pt;
        }

        .col-3 {
            left: 140pt;
        }

        /* --- QR CODE --- */
        .qr-code {
            position: absolute;
            top: 52pt;
            right: 15pt;
            width: 33pt;
            height: 33pt;
            background-color: #ffffff;
            padding: 1.5pt;
            border-radius: 2pt;
            z-index: 10;
        }

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
            top: 92pt;
        }

        .row-2 {
            top: 106pt;
        }

        .row-3 {
            top: 134pt;
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
            font-family: 'Times New Roman', Times, serif;
        }

        .val-address {
            font-weight: normal;
            color: #cbd5e1;
            white-space: nowrap;
            overflow: hidden;
            font-family: 'Times New Roman', Times, serif;
        }

        .blood-group {
            color: #ef4444;
            /* Subtle red for medical context */
            font-weight: bold;
        }

        /* --- FOOTER --- */
        .footer-text {
            position: absolute;
            top: 131pt;
            left: 0;
            width: 100%;
            text-align: center;
            font-size: 3.5pt;
            color: #64748b;
            letter-spacing: 0.3pt;
            line-height: 1.4;
            z-index: 10;
        }

        .footer-text span {
            color: #E5E4E2;
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="card-container">

        <!-- Asset Setup -->
        @php
            $logoPath = public_path('img/hf_gold_logo.png');
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoSrc = 'data:image/png;base64,' . $logoData;

            // Merged ID
            $formattedId = $patient->patient_id;

            // Format Aadhar logically if it exists
            $aadharFormatted = $patient->aadhar_number ? implode(' ', str_split($patient->aadhar_number, 4)) : 'N/A';

            // SVG Icons for Footer (Color Matched to Text)
            $phoneSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#64748b" d="M164.9 24.6c-7.7-18.6-28-28.5-47.4-23.2l-88 24C12.1 30.2 0 46 0 64C0 311.4 200.6 512 448 512c18 0 33.8-12.1 38.6-29.5l24-88c5.3-19.4-4.6-39.7-23.2-47.4l-96-40c-16.3-6.8-35.2-2.1-46.3 11.6L304.7 368C234.3 334.7 177.3 277.7 144 207.3L193.3 167c13.7-11.2 18.4-30 11.6-46.3l-40-96z"/></svg>';
            $mailSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#64748b" d="M48 64C21.5 64 0 85.5 0 112c0 15.1 7.1 29.3 19.2 38.4L236.8 313.6c11.4 8.5 27 8.5 38.4 0L492.8 150.4c12.1-9.1 19.2-23.3 19.2-38.4c0-26.5-21.5-48-48-48H48zM0 176V384c0 35.3 28.7 64 64 64H448c35.3 0 64-28.7 64-64V176L294.4 339.2c-22.8 17.1-54 17.1-76.8 0L0 176z"/></svg>';
            $webSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="#64748b" d="M352 256c0 22.2-1.2 43.6-3.3 64H163.3c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64H348.7c2.2 20.4 3.3 41.8 3.3 64zm28.8-64H503.9c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64H380.8c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32H376.7c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0H167.7c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0H18.6C48.6 85.9 112.4 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192H131.2c-2.1 20.6-3.2 42-3.2 64s1.1 43.4 3.2 64H8.1C2.8 299.5 0 278.1 0 256s2.8-43.5 8.1-64zM194.7 446.6c-11.6-26-20.9-58.2-27-94.6H344.3c-6.1 36.4-15.5 68.6-27 94.6c-10.5 23.6-22.2 40.7-33.5 51.5C272.6 508.8 263.3 512 256 512s-16.6-3.2-27.8-13.8c-11.3-10.8-23-27.9-33.5-51.5zM135.3 352c10 63.9 29.8 117.4 55.3 151.6C112.4 482.9 48.6 426.1 18.6 352H135.3zm358.1 0c-29.9 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6H493.4z"/></svg>';
            $hqSvg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="#64748b" d="M215.7 499.2C267 435 384 279.4 384 192C384 86 298 0 192 0S0 86 0 192c0 87.4 117 243 168.3 307.2c12.3 15.3 35.1 15.3 47.4 0zM192 128a64 64 0 1 1 0 128 64 64 0 1 1 0-128z"/></svg>';

            $iconPhone = 'data:image/svg+xml;base64,' . base64_encode($phoneSvg);
            $iconMail = 'data:image/svg+xml;base64,' . base64_encode($mailSvg);
            $iconWeb = 'data:image/svg+xml;base64,' . base64_encode($webSvg);
            $iconHq = 'data:image/svg+xml;base64,' . base64_encode($hqSvg);

                        // QR code ($qrBase64) is now generated in the controller using local PHP - no API calls needed

            // Dynamic Font Scaling for Address
            $fullAddress = ($patient->address ?: '') . ', ' . ($patient->gp ?: '') . ', ' . ($patient->block ?: '') . ' - ' . ($patient->pin ?: '');
            $addrLen = strlen($fullAddress);

            // Base font: 5.2pt for up to 55 chars. Drop down for longer addresses.
            if ($addrLen > 80)
                $addrFontSize = '3.8pt';
            elseif ($addrLen > 70)
                $addrFontSize = '4.2pt';
            elseif ($addrLen > 60)
                $addrFontSize = '4.6pt';
            elseif ($addrLen > 50)
                $addrFontSize = '5.0pt';
            else
                $addrFontSize = '5.2pt';
        @endphp

        <!-- Large Ghosted Background Logo -->
        <img src="{{ $logoSrc }}" class="bg-watermark">

        <!-- Header: Logo, Name, Type -->
        <img src="{{ $logoSrc }}" class="logo-box">
        <div class="org-title">HUMANITY FOUNDATION</div>
        <div class="org-subtitle">EXECUTIVE MEMBERSHIP</div>

        <!-- Member Hero Highlight -->
        <div class="hero-name">{{ $patient->full_name }}</div>
        <div class="hero-id">{{ $formattedId }}</div>

        @if($qrBase64)
            <img src="{{ $qrBase64 }}" class="qr-code" alt="Verify Member">
        @endif

        <!-- Grid Divider -->
        <div class="grid-line"></div>

        <!-- Precise Data Grid (2x3 Matrix style) -->

        <!-- GRID ROW 1 -->
        <div class="detail-block col-1 row-1">
            <div class="label">AGE / GENDER</div>
            <div class="val">{{ $patient->age }} YRS / {{ strtoupper(substr($patient->gender ?? 'N', 0, 1)) }}</div>
        </div>

        <!-- Address Span across Row 1 in Col 2 & 3 -->
        <div class="detail-block col-2 row-1" style="width: 144pt;">
            <div class="label">REGISTERED ADDRESS</div>
            <div class="val-address" style="font-size: {{ $addrFontSize }};">
                {{ $fullAddress }}
            </div>
        </div>

        <!-- GRID ROW 2 -->
        <div class="detail-block col-1 row-2">
            <div class="label">AADHAAR NO.</div>
            <div class="val">{{ $aadharFormatted }}</div>
        </div>
        <div class="detail-block col-2 row-2">
            <div class="label">BLOOD GRP</div>
            <div class="val blood-group">{{ $patient->blood_group ?? 'N/A' }}</div>
        </div>
        <div class="detail-block col-3 row-2">
            <div class="label">VALID TILL</div>
            <div class="val">{{ \Carbon\Carbon::parse($patient->created_at)->addYear()->format('M d, Y') }}</div>
        </div>

        <!-- Foundation Contact Footer -->
        <div class="footer-text">
            <span style="display:inline-block; margin-right: 1pt;">
                <img src="{{ $iconPhone }}" style="width: 4.5pt; height: 4.5pt; transform: translateY(0.5pt);">
            </span> +91 9339677678 &nbsp;&nbsp;|&nbsp;&nbsp;

            <span style="display:inline-block; margin-right: 1pt;">
                <img src="{{ $iconMail }}" style="width: 4.5pt; height: 4.5pt; transform: translateY(0.5pt);">
            </span> info@hfburdwan.in &nbsp;&nbsp;|&nbsp;&nbsp;

            <span style="display:inline-block; margin-right: 1pt;">
                <img src="{{ $iconWeb }}" style="width: 4.5pt; height: 4.5pt; transform: translateY(0.5pt);">
            </span> www.hfburdwan.in<br>

            <span style="color: #ffffff; font-weight: bold; display:inline-block; margin-right: 1pt; padding-top: 1pt;">
                <img src="{{ $iconHq }}" style="width: 4.5pt; height: 4.5pt; transform: translateY(0.5pt);">
            </span> Kendriyanagar, Keshabganj Chati, Purba Bardhaman, 713104, West Bengal
        </div>

    </div>
</body>

</html>