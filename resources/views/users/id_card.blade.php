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
        }

        /* ID Card Dimensions (Standard CR80 size ratio approx) */
        #id-card {
            width: 320px;
            /* Scaled up for screen, standard is 85.6mm */
            height: 480px;
            /* Scaled aspect ratio vertical */
            background-color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            border-radius: 8px;
            /* Optional rounded corners for the card itself */
        }

        /* Curved Header */
        .header-curve {
            background-color: black;
            height: 140px;
            width: 150%;
            margin-left: -25%;
            border-bottom-left-radius: 50%;
            border-bottom-right-radius: 50%;
            position: absolute;
            top: -35px;
            left: 0;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding-top: 20px;
        }

        /* Profile Image Container */
        .profile-container {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: #e0f2fe;
            /* Sky blue */
            position: relative;
            z-index: 10;
            margin: 0 auto;
            margin-top: 80px;
            /* Push down relative to header */
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
            /* Double safety for circularity */
        }

        /* Fallback Landscape (Hills & Clouds) */
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
            /* Lime green */
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
            /* Lime 600 */
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
            top: 245px;
            left: 0;
            z-index: 5;
        }

        .designation-bar {
            background-color: white;
            color: black;
            font-weight: 900;
            text-transform: uppercase;
            width: 100%;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: 300px;
            left: 0;
            font-size: 20px;
            letter-spacing: 0.5px;
        }

        .contact-section {
            position: absolute;
            top: 350px;
            height: 60px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .footer {
            background-color: black;
            color: white;
            width: 100%;
            height: 60px;
            position: absolute;
            bottom: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            line-height: 1.4;
        }
    </style>
</head>

<body>

    <div class="mb-6 flex space-x-4">
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
            <h1 class="text-yellow-400 font-black text-xl tracking-wide uppercase text-center w-2/3 leading-tight">
                Humanity Foundation</h1>
            <p class="text-white text-[10px] mt-1">Govt. Reg. : IV-190100489</p>
        </div>

        <!-- Profile Picture -->
        <div class="profile-container">
            @if($user->profile && $user->profile->profile_picture)
                <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="Profile" class="profile-img">
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
            <h2 class="text-red-600 font-extrabold text-2xl uppercase leading-none mb-1" style="font-weight: 900;">
                {{ $user->profile->full_name }}
            </h2>
            <p class="text-xs font-bold text-gray-700">Emp. ID : {{ $user->employee_id }}</p>
        </div>

        <!-- Designation Bar -->
        <div class="designation-bar">
            {{ $user->getDesignationLabel() }}
        </div>

        <!-- Contact Info manually placed between bars -->
        <div class="contact-section">
            <div class="flex items-center justify-center">
                <span class="text-sm font-bold text-gray-800">{{ $user->email }}</span>
            </div>
            <div class="flex items-center justify-center">
                <span class="text-sm font-bold text-gray-800">{{ $user->profile->phone_number ?? 'N/A' }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="font-black">www.hrbardwan.in</p>
            <p class="font-bold opacity-90">Kendriyanagar, Keshabganj, Purba Bardhaman</p>
            <p class="font-black">Mob : 8167364107</p>
        </div>
    </div>

    <script>
        function downloadID() {
            // Set scale to 1 for calculation, then scale up in options
            const card = document.getElementById('id-card');

            // Add a small delay to ensure rendering is settled
            setTimeout(() => {
                html2canvas(card, {
                    scale: 4, // Even higher resolution
                    useCORS: true,
                    logging: false,
                    allowTaint: false,
                    backgroundColor: "#ffffff",
                    windowWidth: card.offsetWidth,
                    windowHeight: card.offsetHeight,
                    onclone: function (clonedDoc) {
                        // Ensure cloned element is visible for capture
                        const clonedCard = clonedDoc.getElementById('id-card');
                        clonedCard.style.boxShadow = "none"; // Remove shadow for clean extraction
                    }
                }).then(canvas => {
                    const link = document.createElement('a');
                    link.download = "ID_Card_{{ $user->employee_id }}.png";
                    link.href = canvas.toDataURL("image/png", 1.0);
                    link.click();
                });
            }, 100);
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