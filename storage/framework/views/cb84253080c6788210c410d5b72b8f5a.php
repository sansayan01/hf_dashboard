<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - <?php echo e($user->profile?->full_name ?? $user->employee_id); ?></title>
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
            padding-top: 7mm;
        }

        .logo-img {
            height: 12mm;
            margin-top: 5mm;
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
            margin-top: 21.7mm;
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
            top: 45.5mm;
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
            bottom: 12.5mm;
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

    <div class="controls flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4">
        <div class="flex items-center space-x-3">
            <label for="format-select" class="text-sm font-bold text-gray-700">Export Format:</label>
            <select id="format-select"
                class="px-4 py-2 border-2 border-gray-300 rounded-lg font-bold text-sm focus:border-indigo-600 focus:ring-2 focus:ring-indigo-200 outline-none transition">
                <option value="png" <?php echo e(($format ?? 'png') == 'png' ? 'selected' : ''); ?>>PNG Image</option>
                <option value="pdf" <?php echo e(($format ?? 'png') == 'pdf' ? 'selected' : ''); ?>>PDF Document</option>
                <option value="jpg" <?php echo e(($format ?? 'png') == 'jpg' ? 'selected' : ''); ?>>JPG (Canva Compatible)</option>
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
            <img src="<?php echo e(asset('img/hf_gold_logo.png')); ?>" alt="Logo" class="logo-img" style="mix-blend-mode: screen;">
            <h1
                class="text-yellow-400 font-black text-[13px] tracking-wide uppercase text-center w-2/3 leading-tight mt-[-2px]">
                Humanity Foundation</h1>
            <p class="text-white text-[8px] font-bold opacity-100 mt-[1px]">Govt. Reg. : IV-190100489</p>
        </div>

        <div class="profile-container">
            <?php if($user->profile && $user->profile->profile_picture): ?>
                <img src="<?php echo e($user->profile->getProfilePictureUrl()); ?>" alt="Profile" class="profile-img">
            <?php else: ?>
                <!-- CSS Landscape Fallback -->
                <div class="landscape">
                    <div class="cloud cloud-1"></div>
                    <div class="cloud cloud-2"></div>
                    <div class="hill-1"></div>
                    <div class="hill-2"></div>
                </div>
            <?php endif; ?>
        </div>

        <div class="details">
            <?php
                $name = $user->profile ? $user->profile->full_name : 'N/A';
                $nameFontSize = '11pt';
                if (strlen($name) > 18)
                    $nameFontSize = '9pt';
                if (strlen($name) > 24)
                    $nameFontSize = '8pt';

                $id_fontSize = '7pt';
                if (strlen($user->employee_id) > 20)
                    $id_fontSize = '6pt';
            ?>
            <h2 class="text-red-600 uppercase"
                style="font-weight: 900; font-size: <?php echo e($nameFontSize); ?>; white-space: nowrap; line-height: 1.1;">
                <?php echo e($name); ?>

            </h2>
            <?php if(!$user->isSuperAdmin()): ?>
                <p class="font-bold text-gray-800" style="font-size: <?php echo e($id_fontSize); ?>; margin-top: 0.5mm;">
                    <?php echo e(in_array($user->designation, ['office_in_charge', 'staff', 'camp_organizer']) ? 'Emp. ID' : 'Vol. ID'); ?>

                    : <span class="text-red-500 font-black"><?php echo e($user->employee_id); ?></span>
                </p>
            <?php endif; ?>

            <div class="designation-text text-center">
                <?php echo e($user->getDesignationLabel()); ?>

            </div>

            <div class="geo-details">
                <span>G.P : <?php echo e($user->profile->gram_panchayat ?? 'N/A'); ?></span>
                <span>Block : <?php echo e($user->profile->block ?? 'N/A'); ?></span>
                <span>District : <?php echo e($user->profile->district ?? 'N/A'); ?></span>
            </div>
        </div>

        <div class="phone-section">
            <span class="phone-number"><?php echo e($user->profile->phone_number ?? 'N/A'); ?></span>
        </div>

        <div class="signature-section">
            <img src="<?php echo e(asset('img/signature.png')); ?>" class="signature-img">
            <div class="signature-line"></div>
            <div class="signature-text">Sig. of Authority</div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="font-black text-[7.5px]">www.hfburdwan.in</p>
            <p class="font-bold opacity-100">Kendriyanagar, Keshabganj, Purba Bardhaman</p>
            <p class="font-black">Mob : 9735563157</p>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function downloadID() {
            const card = document.getElementById('id-card');
            const format = document.getElementById('format-select').value;
            const employeeId = "<?php echo e($user->employee_id); ?>";

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

</html><?php /**PATH C:\xampp\htdocs\HF\resources\views/users/id_card.blade.php ENDPATH**/ ?>