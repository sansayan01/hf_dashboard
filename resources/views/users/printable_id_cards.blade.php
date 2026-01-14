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
        @page {
            size: A4;
            margin: 10mm;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-auto-rows: min-content;
            gap: 15mm 10mm;
            /* Vertical and Horizontal gap */
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
            height: 25mm;
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
            justify-content: center;
            padding-top: 4mm;
        }

        .profile-container {
            width: 28mm;
            height: 28mm;
            border-radius: 50%;
            background: #e0f2fe;
            position: relative;
            z-index: 10;
            margin: 0 auto;
            margin-top: 14mm;
            overflow: hidden;
            border: 1mm solid white;
            box-shadow: 0 2mm 3mm rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
            top: 44mm;
            left: 0;
            z-index: 5;
        }

        .designation-bar {
            background-color: black;
            color: white;
            font-weight: 900;
            text-transform: uppercase;
            width: 100%;
            height: 8mm;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 55mm;
            left: 0;
            font-size: 13px;
            letter-spacing: 0.2mm;
        }

        .contact-section {
            position: absolute;
            top: 64mm;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1mm;
        }

        .footer {
            background-color: black;
            color: white;
            width: 100%;
            height: 12mm;
            position: absolute;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 7.5px;
            line-height: 1.2;
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

    @php $chunks = $users->chunk(9); @endphp

    <div id="print-container">
        @foreach($chunks as $chunk)
            <div class="page">
                @foreach($chunk as $user)
                    <div class="id-card">
                        <div class="header-curve">
                            <h1
                                class="text-yellow-400 font-black text-[14px] tracking-wide uppercase text-center w-2/3 leading-tight">
                                Humanity Foundation</h1>
                            <p class="text-white text-[7px] mt-0.5">Govt. Reg. : IV-190100489</p>
                        </div>

                        <div class="profile-container">
                            @if($user->profile && $user->profile->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="Profile"
                                    class="profile-img">
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
                            <h2 class="text-red-600 font-black text-[16px] uppercase leading-none mb-1"
                                style="font-weight: 900;">
                                {{ $user->profile->full_name }}</h2>
                            <p class="text-[10px] font-bold text-gray-700">Emp. ID : {{ $user->employee_id }}</p>
                        </div>

                        <div class="designation-bar">
                            {{ $user->getDesignationLabel() }}
                        </div>

                        <div class="contact-section">
                            <div class="flex items-center justify-center">
                                <span class="text-[11px] font-bold text-gray-800">{{ $user->email }}</span>
                            </div>
                            <div class="flex items-center justify-center">
                                <span
                                    class="text-[11px] font-bold text-gray-800">{{ $user->profile->phone_number ?? 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="footer">
                            <p class="font-black">www.hrbardwan.in</p>
                            <p class="font-bold opacity-90">Kendriyanagar, Keshabganj, Purba Bardhaman</p>
                            <p class="font-black">Mob : 8167364107</p>
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
                unit: 'mm',
                format: 'a4'
            });

            for (let i = 0; i < pages.length; i++) {
                loadingText.textContent = `Processing Page ${i + 1} of ${pages.length}...`;

                const canvas = await html2canvas(pages[i], {
                    scale: 3, // High quality
                    useCORS: true,
                    logging: false,
                    allowTaint: false,
                    backgroundColor: "#ffffff",
                    width: 210 * 3.78, // Approximate mm to px conversion for capture
                    height: 297 * 3.78
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);

                if (i > 0) pdf.addPage();
                pdf.addImage(imgData, 'JPEG', 0, 0, 210, 297);
            }

            loadingText.textContent = "Finalizing PDF...";
            pdf.save(`Humanity_Foundation_ID_Cards_${new Date().getTime()}.pdf`);

            overlay.classList.add('hidden');
        }
    </script>
</body>

</html>