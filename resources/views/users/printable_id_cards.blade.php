<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print ID Cards - Humanity Foundation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        /* A4 Page Settings */
        /* 12x18 Inch Page Settings */
        @page {
            size: 12in 18in;
            margin: 0;
        }

        .page {
            width: 12in;
            height: 18in;
            padding: 0.5in;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 2mm;
            justify-items: center;
            align-items: center;
            page-break-after: always;
        }

        @media print {
            body {
                background: none;
            }

            .no-print {
                display: none;
            }

            .page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                height: 100%;
                padding: 0;
            }
        }

        /* ID Card Base Styles (Standard 86x54mm approx scaled to 320x480 for clarity) */
        .id-card {
            width: 54mm;
            height: 86mm;
            background-color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 0.1mm solid #eee;
            /* Light border for cutting guide */
            margin: 0 auto;
        }

        .header-curve {
            background-color: black;
            height: 40mm;
            width: 150%;
            margin-left: -25%;
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
            position: absolute;
            top: -6mm;
            left: 0;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 7mm;
        }

        .logo-img {
            height: 13mm;
            margin-bottom: 0.1mm;
            z-index: 2;
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            object-fit: contain;
        }

        .profile-container {
            width: 25mm;
            height: 25mm;
            border-radius: 50%;
            background: #e0f2fe;
            position: relative;
            z-index: 10;
            margin: 0 auto;
            margin-top: 22mm;
            overflow: hidden;
            border: 0.8mm solid white;
            box-shadow: 0 1.5mm 2mm rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: fixed;
            object-position: center;
        }

        .landscape {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 5;
        }

        .landscape .hill-1 {
            position: absolute;
            bottom: -2mm;
            left: -4mm;
            width: 22mm;
            height: 11mm;
            background: #a3e635;
            border-radius: 50%;
            opacity: 0.8;
        }

        .landscape .hill-2 {
            position: absolute;
            bottom: -4mm;
            right: -4mm;
            width: 26mm;
            height: 13mm;
            background: #65a30d;
            border-radius: 50%;
        }

        .landscape .cloud {
            position: absolute;
            background: white;
            border-radius: 4mm;
        }

        .cloud-1 {
            top: 4mm;
            left: 6mm;
            width: 8mm;
            height: 3mm;
        }

        .cloud-2 {
            top: 7mm;
            right: 8mm;
            width: 6mm;
            height: 2mm;
        }

        .details {
            text-align: center;
            width: 100%;
            position: absolute;
            top: 46.5mm;
            left: 0;
            z-index: 5;
            display: flex;
            flex-direction: column;
            gap: 0mm;
        }

        .designation-text {
            color: black;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 10pt;
            line-height: 1;
            margin: 0.8mm 0 0.4mm 0;
            padding: 0 2mm;
        }

        .geo-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 6.5pt;
            font-weight: 700;
            color: #1f2937;
            gap: 0mm;
            line-height: 1.1;
        }

        .phone-section {
            position: absolute;
            bottom: 13.5mm;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1mm;
            z-index: 10;
        }

        .phone-circle {
            width: 3.5mm;
            height: 3.5mm;
            background-color: #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .phone-number {
            font-size: 8.5pt;
            font-weight: 900;
            color: black;
        }

        .footer {
            background-color: #000;
            color: white;
            width: 100%;
            height: 10mm;
            position: absolute;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 5pt;
            line-height: 1.1;
        }

        .signature-section {
            position: absolute;
            bottom: 11mm;
            right: 0.5mm;
            width: 12mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 11;
        }

        .signature-img {
            width: 12mm;
            height: 5mm;
            object-fit: contain;
        }

        .signature-line {
            border-top: 0.15mm solid black;
            margin-top: -0.3mm;
        }

        .signature-text {
            font-size: 3.5pt;
            font-weight: 900;
            color: black;
            text-transform: uppercase;
            width: 60%;
            text-align: center;
            margin-top: 0.3mm;
        }
    </style>
</head>

<body>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <!-- UI Overlay for Loading -->
    <div id="loading-overlay"
        class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] flex flex-col items-center justify-center text-white">
        <div class="w-16 h-16 border-4 border-indigo-400 border-t-white rounded-full animate-spin mb-4"></div>
        <p class="text-xl font-bold" id="loading-text">Generating PDF...</p>
        <p class="text-sm opacity-80 mt-2">Please wait while we process the pages.</p>
    </div>

    <!-- Save as PDF Button -->
    <div class="no-print fixed bottom-8 right-8 z-50">
        <button onclick="saveAsPDF()"
            class="bg-indigo-600 text-white px-8 py-4 rounded-full shadow-2xl font-bold hover:bg-indigo-700 transition transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <span>Save as PDF</span>
        </button>
    </div>

    @php $chunks = $users->chunk(25); @endphp

    <div id="print-container">
        @foreach($chunks as $chunk)
            <div class="page">
                @foreach($chunk as $user)
                    <div class="id-card">
                        <div class="header-curve">
                            <img src="{{ asset('img/hf_gold_logo.png') }}" alt="Logo" class="logo-img"
                                style="mix-blend-mode: screen;">
                            <h1
                                class="text-yellow-400 font-black text-[13px] tracking-wide uppercase text-center w-2/3 leading-tight mt-[-2px]">
                                Humanity Foundation</h1>
                            <p class="text-white text-[8px] font-bold opacity-100 mt-[1px]">Govt. Reg. : IV-190100489</p>
                        </div>

                        <div class="profile-container">
                            @if($user->profile && $user->profile->profile_picture)
                                <img src="{{ $user->profile->getProfilePictureUrl() }}" alt="Profile" class="profile-img">
                            @else
                                <div class="landscape">
                                    <div class="cloud cloud-1"></div>
                                    <div class="cloud cloud-2"></div>
                                    <div class="hill-1"></div>
                                    <div class="hill-2"></div>
                                </div>
                            @endif
                        </div>

                        <div class="details">
                            @php
                                $p_name = $user->profile ? $user->profile->full_name : 'N/A';
                                $p_nameFontSize = '11pt';
                                if (strlen($p_name) > 18)
                                    $p_nameFontSize = '9pt';
                                if (strlen($p_name) > 24)
                                    $p_nameFontSize = '8pt';

                                $id_fontSize = '7pt';
                                if (strlen($user->employee_id) > 20)
                                    $id_fontSize = '6pt';
                            @endphp
                            <h2 class="text-red-600 uppercase"
                                style="font-weight: 900; font-size: {{ $p_nameFontSize }}; white-space: nowrap; line-height: 1.1;">
                                {{ $p_name }}
                            </h2>
                            <p class="font-bold text-gray-800" style="font-size: {{ $id_fontSize }}; margin-top: 0.5mm;">
                                Emp. ID : <span class="text-red-500 font-black">{{ $user->employee_id }}</span>
                            </p>

                            <div class="designation-text text-center">
                                {{ $user->getDesignationLabel() }}
                            </div>

                            <div class="geo-details">
                                <span>G.P : {{ $user->profile->gram_panchayat ?? 'N/A' }}</span>
                                <span>Block : {{ $user->profile->block ?? 'N/A' }}</span>
                                <span>District : {{ $user->profile->district ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="phone-section">
                            <span class="phone-number">{{ $user->profile->phone_number ?? 'N/A' }}</span>
                        </div>

                        <div class="signature-section">
                            <img src="{{ asset('img/signature.png') }}" class="signature-img">
                            <div class="signature-line"></div>
                            <div class="signature-text">Sig. of Authority</div>
                        </div>

                        <div class="footer">
                            <p class="font-black text-[7.5px]">www.hfburdwan.in</p>
                            <p class="font-bold opacity-100">Kendriyanagar, Keshabganj, Purba Bardhaman</p>
                            <p class="font-black">Mob : 9735563157</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <script shadow>
        async function saveAsPDF() {
            const { jsPDF } = window.jspdf;
            const overlay = document.getElementById('loading-overlay');
            const loadingText = document.getElementById('loading-text');
            const pages = document.querySelectorAll('.page');

            overlay.classList.remove('hidden');

            const pdf = new jsPDF({
                orientation: 'p',
                unit: 'in',
                format: [12, 18]
            });

            for (let i = 0; i < pages.length; i++) {
                loadingText.textContent = `Processing Page ${i + 1} of ${pages.length}...`;

                const canvas = await html2canvas(pages[i], {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    allowTaint: false,
                    backgroundColor: "#ffffff"
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);

                if (i > 0) pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, 0, 12, 18);
            }

            loadingText.textContent = "Finalizing PDF...";
            pdf.save(`Humanity_Foundation_ID_Cards_${new Date().getTime()}.pdf`);

            overlay.classList.add('hidden');
        }
    </script>
</body>

</html>