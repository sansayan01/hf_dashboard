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
            padding-top: 10mm;
        }

        .logo-img {
            height: 11mm;
            margin-bottom: 0.1mm;
            z-index: 2;
            display: block;
            margin-left: auto;
            margin-right: auto;
            max-width: 100%;
            object-fit: contain;
        }

        .profile-container {
            width: 26mm;
            height: 26mm;
            border-radius: 50%;
            background: #e0f2fe;
            position: relative;
            z-index: 10;
            margin: 0 auto;
            margin-top: 24.5mm;
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
            top: 50mm;
            left: 0;
            z-index: 5;
            display: flex;
            flex-direction: column;
            gap: 0.2mm;
        }

        .designation-bar {
            background-color: white;
            color: black;
            font-weight: 900;
            text-transform: uppercase;
            width: 100%;
            height: 6mm;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 59mm;
            left: 0;
            font-size: 11px;
            letter-spacing: 0.3mm;
        }

        .contact-section {
            position: absolute;
            top: 64mm;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-left: 0;
            gap: 0mm;
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
            font-size: 6px;
            line-height: 1.1;
            gap: 0.8mm;
        }

        .contact-icon {
            width: 3mm;
            margin-right: 1mm;
        }

        .signature-section {
            position: absolute;
            top: 67mm;
            right: 2mm;
            width: 22mm;
            display: flex;
            flex-direction: column;
            align-items: end;
            z-index: 10;
        }

        .signature-img {
            width: 50%;
            height: 6mm;
            object-fit: contain;
            align-items: end;
        }

        .signature-text {
            font-size: 4.3px;
            font-weight: 900;
            color: black;
            text-transform: capitalize;
            border-top: 0.3mm solid black;
            width: 50%;
            text-align: end;
            margin-top: 0mm;
            padding-top: 0mm;
            line-height: 1;
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
                            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
                            <h1
                                class="text-yellow-400 font-black text-[13px] tracking-wide uppercase text-center w-2/3 leading-tight">
                                Humanity Foundation</h1>
                            <p class="text-white text-[6px] font-bold opacity-100 mt-[1px]">Govt. Reg. -IV190100489</p>
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
                                $p_name = $user->profile->full_name;
                                $p_nameFontSize = '14px';
                                if (strlen($p_name) > 18)
                                    $p_nameFontSize = '11px';
                                if (strlen($p_name) > 24)
                                    $p_nameFontSize = '9px';
                            @endphp
                            <h2 class="text-red-600 uppercase"
                                style="font-weight: 900; font-size: {{ $p_nameFontSize }}; white-space: nowrap;">
                                {{ $p_name }}
                            </h2>
                            <p class="text-[9px] font-bold text-gray-700">Emp. ID : <span
                                    class="text-red-600">{{ $user->employee_id }}</span></p>
                        </div>

                        <div class="designation-bar">
                            {{ $user->getDesignationLabel() }}
                        </div>

                        <div class="contact-section">
                            <p class="text-[8px] font-bold text-gray-800">Dist : {{ $user->profile->district ?? 'N/A' }}</p>
                            <div class="flex items-center justify-center">
                                <span
                                    class="text-[10px] font-bold text-gray-800">{{ $user->profile->phone_number ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="signature-section">
                            <img src="{{ asset('img/signature.png') }}" class="signature-img">
                            <div class="signature-text">Sig. of Authority</div>
                        </div>

                        <div class="footer">
                            <p class="font-bold text-[7px]">website : www.hfburdwan.in</p>
                            <p class="font-bold opacity-100">Head Office : Kendriyanagar, Keshabganj, Purba Bardhaman</p>
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