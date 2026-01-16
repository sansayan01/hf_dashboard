<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $user->profile->full_name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        /* Scaled ID Card Dimensions for Screen Display (approx 54mm x 86mm ratio) */
        #id-card {
            width: 54mm;
            height: 86mm;
            background-color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            transform: scale(2);
            /* Make it readable on screen */
            transform-origin: center;
            margin: 100px 0;
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
            height: 10mm;
            margin-top: 5mm;
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
            margin-top: 26mm;
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
            top: 52mm;
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
            top: 61mm;
            left: 0;
            font-size: 11px;
            letter-spacing: 0.3mm;
        }

        .contact-section {
            position: absolute;
            top: 65.5mm;
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

        /* Controls bar stays at top of viewport */
        .controls {
            position: fixed;
            top: 20px;
            z-index: 100;
            background: white;
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .signature-section {
            position: absolute;
            top: 66.5mm;
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
        }

        .signature-text {
            font-size: 4px;
            font-weight: 900;
            color: black;
            text-transform: capitalize;
            border-top: 0.3mm solid black;
            width: 50%;
            text-align: center;
            margin-top: 0mm;
            padding-top: 0mm;
            line-height: 1;
        }
    </style>
</head>

<body>

    <div class="controls flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
        <div class="flex items-center space-x-3">
            <label for="format-select" class="text-sm font-bold text-gray-700">Export Format:</label>
            <select id="format-select"
                class="px-4 py-2 border-2 border-gray-300 rounded-lg font-bold text-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 outline-none transition">
                <option value="png" {{ ($format ?? 'png') == 'png' ? 'selected' : '' }}>PNG Image</option>
                <option value="pdf" {{ ($format ?? 'png') == 'pdf' ? 'selected' : '' }}>PDF Document</option>
                <option value="jpg" {{ ($format ?? 'png') == 'jpg' ? 'selected' : '' }}>JPG (Canva Compatible)</option>
            </select>
        </div>
        <button onclick="downloadID()"
            class="px-6 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 font-bold transition">
            Download ID Card
        </button>
        <button onclick="window.close()"
            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg shadow hover:bg-gray-300 font-bold transition">
            Close
        </button>
    </div>

    <!-- ID Card Container -->
    <div id="id-card">
        <!-- Curved Header -->
        <div class="header-curve">
            <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo-img">
            <h1 class="text-yellow-400 font-black text-[13px] tracking-wide uppercase text-center w-2/3 leading-tight">
                Humanity Foundation</h1>
            <p class="text-white text-[6px] font-bold opacity-90 mt-[-1px]">Govt. Reg. -IV-190100489</p>
        </div>

        <div class="profile-container">
            @if($user->profile && $user->profile->profile_picture)
                <img src="{{ $user->profile->getProfilePictureUrl() }}" alt="Profile" class="profile-img">
            @else
                <!-- CSS Landscape Fallback -->
                <div class="landscape">
                    <div class="cloud cloud-1"></div>
                    <div class="cloud cloud-2"></div>
                    <div class="hill-1"></div>
                    <div class="hill-2"></div>
                </div>
            @endif
        </div>

        <!-- User Details -->
        <div class="details">
            @php
                $name = $user->profile->full_name;
                $nameFontSize = '14px';
                if (strlen($name) > 18)
                    $nameFontSize = '11px';
                if (strlen($name) > 24)
                    $nameFontSize = '9px';
            @endphp
            <h2 class="text-red-600 uppercase"
                style="font-weight: 900; font-size: {{ $nameFontSize }}; white-space: nowrap;">
                {{ $name }}
            </h2>
            <p class="text-[9px] font-bold text-gray-700">Emp. ID : <span
                    class="text-red-600">{{ $user->employee_id }}</span></p>
        </div>

        <!-- Designation Bar -->
        <div class="designation-bar">
            {{ $user->getDesignationLabel() }}
        </div>

        <!-- Contact Info -->
        <div class="contact-section">
            <p class="text-[8px] font-bold text-gray-800">Dist : {{ $user->profile->district ?? 'N/A' }}</p>
            <div class="flex items-center justify-center">
                <span class="text-[10px] font-bold text-gray-800">{{ $user->profile->phone_number ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="signature-section">
            <img src="{{ asset('img/signature.png') }}" class="signature-img">
            <div class="signature-text">Sig. of Authority</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="font-bold text-[7px]">website : www.hfburdwan.in</p>
            <p class="font-bold opacity-100">Head Office : Kendriyanagar, Keshabganj, Purba Bardhaman</p>
            <p class="font-black">Mob : 9735563157</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function downloadID() {
            const card = document.getElementById('id-card');
            const format = document.getElementById('format-select').value;
            const employeeId = "{{ $user->employee_id }}";

            // Add a small delay to ensure rendering is settled
            setTimeout(() => {
                if (format === 'jpg') {
                    downloadAsJPG(card, employeeId);
                } else if (format === 'pdf') {
                    downloadAsPDF(card, employeeId);
                } else {
                    downloadAsPNG(card, employeeId);
                }
            }, 100);
        }

        function downloadAsPNG(card, employeeId) {
            html2canvas(card, {
                scale: 4, // High resolution
                useCORS: true,
                logging: false,
                allowTaint: false,
                backgroundColor: "#ffffff",
                windowWidth: card.offsetWidth,
                windowHeight: card.offsetHeight,
                onclone: function (clonedDoc) {
                    const clonedCard = clonedDoc.getElementById('id-card');
                    clonedCard.style.boxShadow = "none"; // Remove shadow for clean extraction
                    clonedCard.style.transform = "none"; // Reset scale for high res capture
                    clonedCard.style.margin = "0";
                }
            }).then(canvas => {
                const link = document.createElement('a');
                link.download = `ID_Card_${employeeId}.png`;
                link.href = canvas.toDataURL("image/png", 1.0);
                link.click();
            });
        }

        function downloadAsPDF(card, employeeId) {
            html2canvas(card, {
                scale: 4,
                useCORS: true,
                logging: false,
                allowTaint: false,
                backgroundColor: "#ffffff",
                windowWidth: card.offsetWidth,
                windowHeight: card.offsetHeight,
                onclone: function (clonedDoc) {
                    const clonedCard = clonedDoc.getElementById('id-card');
                    clonedCard.style.boxShadow = "none";
                    clonedCard.style.transform = "none";
                    clonedCard.style.margin = "0";
                }
            }).then(canvas => {
                const { jsPDF } = window.jspdf;
                const imgData = canvas.toDataURL('image/png', 1.0);

                // ID card dimensions in mm (standard CR80 size: 85.6mm x 53.98mm, but we're using vertical)
                const cardWidth = 54;
                const cardHeight = 86;

                const pdf = new jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: [cardWidth, cardHeight]
                });

                pdf.addImage(imgData, 'PNG', 0, 0, cardWidth, cardHeight);
                pdf.save(`ID_Card_${employeeId}.pdf`);
            });
        }

        function downloadAsJPG(card, employeeId) {
            html2canvas(card, {
                scale: 4, // High resolution for quality
                useCORS: true,
                logging: false,
                allowTaint: false,
                backgroundColor: "#ffffff",
                windowWidth: card.offsetWidth,
                windowHeight: card.offsetHeight,
                onclone: function (clonedDoc) {
                    const clonedCard = clonedDoc.getElementById('id-card');
                    clonedCard.style.boxShadow = "none";
                    clonedCard.style.transform = "none";
                    clonedCard.style.margin = "0";
                }
            }).then(canvas => {
                // Convert to JPG with high quality (0.95 = 95% quality)
                const link = document.createElement('a');
                link.download = `ID_Card_${employeeId}.jpg`;
                link.href = canvas.toDataURL("image/jpeg", 0.95);
                link.click();
            });
        }

        // Auto-refresh layout on load to ensure CSS rounding is solid
        window.addEventListener('load', () => {
            document.fonts.ready.then(() => {
                console.log('Fonts loaded, ID Card ready for capture.');
            });
        });
    </script>
</body>

</html>