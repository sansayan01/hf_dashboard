<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk ID Card Download</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        /* Same ID Card styles from single card view */
        .id-card {
            width: 320px;
            height: 480px;
            background-color: white;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-radius: 8px;
            margin: 10px;
        }

        .header-curve {
            background-color: black;
            height: 140px;
            width: 150%;
            margin-left: -25%;
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
            position: absolute;
            top: -30px;
            left: 0;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 35px;
        }

        .logo-img {
            height: 50px;
            margin-bottom: 2px;
            z-index: 2;
        }

        .profile-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #e0f2fe;
            position: relative;
            z-index: 10;
            margin: 0 auto;
            margin-top: 75px;
            overflow: hidden;
            border: 6px solid white;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            border-radius: 50%;
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
            bottom: -10px;
            left: -20px;
            width: 120px;
            height: 60px;
            background: #a3e635;
            border-radius: 50%;
            opacity: 0.8;
        }

        .landscape .hill-2 {
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 140px;
            height: 70px;
            background: #65a30d;
            border-radius: 50%;
        }

        .landscape .cloud {
            position: absolute;
            background: white;
            border-radius: 20px;
        }

        .cloud-1 {
            top: 20px;
            left: 30px;
            width: 40px;
            height: 15px;
        }

        .cloud-2 {
            top: 35px;
            right: 40px;
            width: 30px;
            height: 10px;
        }

        .details {
            text-align: center;
            width: 100%;
            position: absolute;
            top: 250px;
            left: 0;
            z-index: 5;
            display: flex;
            flex-direction: column;
            gap: 1px;
        }

        .designation-text {
            color: black;
            font-weight: 900;
            text-transform: uppercase;
            font-size: 22px;
            line-height: 1.1;
            margin-top: 3px;
        }

        .geo-details {
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            font-weight: 700;
            color: #1f2937;
            gap: 1px;
            line-height: 1.1;
            margin-top: 3px;
        }

        .phone-section {
            position: absolute;
            bottom: 62px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            z-index: 10;
            padding-right: 50px;
        }

        .phone-circle {
            width: 22px;
            height: 22px;
            background-color: #f59e0b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .phone-number {
            font-size: 16px;
            font-weight: 900;
            color: black;
        }

        .footer {
            background-color: #000;
            color: white;
            width: 100%;
            height: 55px;
            position: absolute;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            line-height: 1.1;
        }

        .signature-section {
            position: absolute;
            bottom: 62px;
            right: 15px;
            width: 90px;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 11;
        }

        .signature-img {
            width: 70px;
            height: 25px;
            object-fit: contain;
        }

        .signature-line {
            width: 100%;
            border-top: 1px solid black;
            margin-top: -2px;
        }

        .signature-text {
            font-size: 7px;
            font-weight: 900;
            color: black;
            text-transform: uppercase;
            width: 100%;
            text-align: center;
            margin-top: 2px;
        }
    </style>
</head>

<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-2xl font-bold mb-4">Bulk ID Card Download</h1>
            <p class="text-gray-600 mb-4">Total Users: <span class="font-bold">{{ count($users) }}</span></p>

            <button onclick="generateAndDownloadAll()"
                class="px-6 py-3 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 font-bold transition">
                Download All ID Cards as ZIP
            </button>

            <div id="progress" class="hidden mt-4">
                <div class="bg-gray-200 rounded-full h-4 overflow-hidden">
                    <div id="progress-bar" class="bg-indigo-600 h-full transition-all duration-300" style="width: 0%">
                    </div>
                </div>
                <p id="progress-text" class="text-sm text-gray-600 mt-2">Processing...</p>
            </div>
        </div>

        <!-- ID Cards Container (positioned off-screen but visible for capture) -->
        <div id="cards-container" style="position: absolute; left: -9999px; top: 0;">
            @foreach($users as $user)
                <div class="id-card" id="card-{{ $user->id }}" data-filename="ID_Card_{{ $user->employee_id }}.png">
                    <div class="header-curve">
                        <img src="{{ asset('img/hf_gold_logo.png') }}" alt="Logo" class="logo-img"
                            style="mix-blend-mode: screen;">
                        <h1
                            class="text-yellow-400 font-black text-xl tracking-wide uppercase text-center w-2/3 leading-tight">
                            Humanity Foundation</h1>
                        <p class="text-white text-[10px] mt-1">Govt. Reg. : IV-190100489</p>
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
                            $c_name = $user->profile->full_name;
                            $c_nameFontSize = '24px';
                            if (strlen($c_name) > 18)
                                $c_nameFontSize = '20px';
                            if (strlen($c_name) > 24)
                                $c_nameFontSize = '16px';
                        @endphp
                        <h2 class="text-red-600 uppercase leading-none mb-1"
                            style="font-weight: 900; font-size: {{ $c_nameFontSize }}; white-space: nowrap;">{{ $c_name }}
                        </h2>
                        <p class="text-sm font-bold text-gray-800" style="margin-top: 2px;">
                            Emp. ID : <span class="text-red-500 font-black">{{ $user->employee_id }}</span>
                        </p>

                        <div class="designation-text">
                            {{ $user->getDesignationLabel() }}
                        </div>

                        <div class="geo-details">
                            <span>G.P : {{ $user->profile->gram_panchayat ?? 'N/A' }}</span>
                            <span>Block : {{ $user->profile->block ?? 'N/A' }}</span>
                            <span>District : {{ $user->profile->district ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="phone-section">
                        <div class="phone-circle">
                            <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24">
                                <path
                                    d="M18.48 22.926c-1.12.818-2.61.446-3.83.18-2.82-.6-5.83-2.31-8.24-4.72-2.41-2.41-4.12-5.42-4.72-8.24-.266-1.22-.638-2.71.18-3.83l.91-1.25a2.536 2.536 0 0 1 3.51-.7l1.79 1.13c.89.56 1.34 1.51 1.14 2.54l-.45 2.14c-.16.8.12 1.63.75 2.21l1.52 1.52c.58.63 1.41.91 2.21.75l2.14-.45c1.03-.2 1.98.25 2.54 1.14l1.13 1.79c.92 1.47.41 3.2-.7 3.51l-1.25.91z">
                                </path>
                            </svg>
                        </div>
                        <span class="phone-number">{{ $user->profile->phone_number ?? 'N/A' }}</span>
                    </div>

                    <div class="signature-section">
                        <img src="{{ asset('img/signature.png') }}" class="signature-img">
                        <div class="signature-line"></div>
                        <div class="signature-text">Sig. of Authority</div>
                    </div>

                    <div class="footer">
                        <p class="font-black">www.hfburdwan.in</p>
                        <p class="font-bold opacity-90">Kendriyanagar, Keshabganj, Purba Bardhaman</p>
                        <p class="font-black">Mob : 9735563157</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        async function generateAndDownloadAll() {
            const cards = document.querySelectorAll('.id-card');
            const zip = new JSZip();
            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const progressContainer = document.getElementById('progress');

            progressContainer.classList.remove('hidden');

            for (let i = 0; i < cards.length; i++) {
                const card = cards[i];
                const filename = card.dataset.filename;

                progressText.textContent = `Processing ${i + 1} of ${cards.length}...`;
                progressBar.style.width = `${((i + 1) / cards.length) * 100}%`;

                try {
                    // Wait a bit for images to load
                    await new Promise(resolve => setTimeout(resolve, 200));

                    const canvas = await html2canvas(card, {
                        scale: 4,
                        useCORS: true,
                        logging: true,
                        allowTaint: false,
                        backgroundColor: "#ffffff",
                        width: 320,
                        height: 480,
                        onclone: function (clonedDoc) {
                            const clonedCard = clonedDoc.getElementById(card.id);
                            if (clonedCard) {
                                clonedCard.style.position = 'relative';
                                clonedCard.style.left = '0';
                                clonedCard.style.display = 'block';
                            }
                        }
                    });

                    // Convert canvas to blob
                    const blob = await new Promise((resolve, reject) => {
                        canvas.toBlob((b) => {
                            if (b && b.size > 0) {
                                resolve(b);
                            } else {
                                reject(new Error('Empty blob generated'));
                            }
                        }, 'image/png', 1.0);
                    });

                    console.log(`Generated ${filename}: ${blob.size} bytes`);
                    zip.file(filename, blob);

                } catch (error) {
                    console.error(`Error processing card ${filename}:`, error);
                    progressText.textContent = `Error on ${filename}. Continuing...`;
                }
            }

            progressText.textContent = 'Creating ZIP file...';

            try {
                const zipBlob = await zip.generateAsync({ type: 'blob' });
                saveAs(zipBlob, 'All_ID_Cards.zip');

                progressText.textContent = 'Download complete!';
                setTimeout(() => {
                    progressContainer.classList.add('hidden');
                    progressBar.style.width = '0%';
                }, 2000);
            } catch (error) {
                console.error('Error creating ZIP:', error);
                progressText.textContent = 'Error creating ZIP file!';
            }
        }

        // Process logos for transparency
        window.addEventListener('load', () => {
            document.querySelectorAll('.logo-img').forEach(img => {
                if (img.complete) {
                    processLogo(img);
                } else {
                    img.onload = () => processLogo(img);
                }
            });
        });

        function processLogo(img) {
            if (img.dataset.processed) return;
            try {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                ctx.drawImage(img, 0, 0);
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const data = imageData.data;
                for (let i = 0; i < data.length; i += 4) {
                    if (data[i] < 45 && data[i + 1] < 45 && data[i + 2] < 45) {
                        data[i + 3] = 0;
                    }
                }
                ctx.putImageData(imageData, 0, 0);
                img.src = canvas.toDataURL();
                img.style.mixBlendMode = 'normal';
                img.dataset.processed = "true";
            } catch (e) { console.error("Logo processing failed", e); }
        }
    </script>
</body>

</html>