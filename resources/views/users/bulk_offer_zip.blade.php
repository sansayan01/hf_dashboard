<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bulk Offer Letters ZIP Generator - Humanity Foundation</title>
    <!-- Dependencies -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f8fafc;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        /* Professional PDF Styles (Hidden from view) */
        #render-target {
            position: absolute;
            left: -9999px;
            top: -9999px;
        }

        .letter-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            background: white;
            position: relative;
            color: #334155;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-100">
        <div class="p-8 sm:p-12">
            <!-- Header -->
            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-blue-50 text-blue-600 rounded-3xl mb-6">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-black text-slate-900 mb-2">Generating Offer Letters</h1>
                <p class="text-slate-500 font-medium">Please stay on this page while we process {{ count($users) }}
                    documents...</p>
            </div>

            <!-- Progress Section -->
            <div class="space-y-6">
                <div id="progress-container" class="relative pt-1">
                    <div class="flex mb-4 items-center justify-between">
                        <div>
                            <span id="status-label"
                                class="text-xs font-black inline-block py-1 px-3 uppercase rounded-full text-blue-600 bg-blue-100 ring-4 ring-blue-50">
                                Starting...
                            </span>
                        </div>
                        <div class="text-right">
                            <span id="progress-percent" class="text-sm font-bold inline-block text-blue-600">0%</span>
                        </div>
                    </div>
                    <div class="overflow-hidden h-4 mb-4 text-xs flex rounded-full bg-slate-100 shadow-inner">
                        <div id="progress-bar" style="width:0%"
                            class="progress-bar shadow-lg flex flex-col text-center whitespace-nowrap text-white justify-center bg-gradient-to-r from-blue-500 to-blue-600">
                        </div>
                    </div>
                    <p id="status-text" class="text-sm text-slate-400 font-medium text-center italic">Initializing PDF
                        engine...</p>
                </div>

                <!-- Complete Section (Hidden) -->
                <div id="complete-actions" class="hidden text-center animate-bounce mt-8">
                    <div class="p-6 bg-emerald-50 rounded-2xl border border-emerald-100 mb-6">
                        <div class="flex items-center space-x-3 justify-center text-emerald-600 font-bold mb-2">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span>Generation Complete!</span>
                        </div>
                        <p class="text-emerald-600/70 text-sm">Your ZIP file has been downloaded successfully.</p>
                    </div>
                    <button onclick="window.close()"
                        class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-slate-800 transition shadow-lg">Close
                        Tab</button>
                    <button onclick="location.reload()"
                        class="ml-3 px-8 py-3 bg-white text-slate-600 border border-slate-200 rounded-xl font-bold hover:bg-slate-50 transition">Restart</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Rendering Container -->
    <div id="render-target"></div>

    <!-- User Data Templates -->
    @foreach($users as $user)
        <template id="user-{{ $user->id }}">
            @include('users.joining_letter', ['user' => $user])
        </template>
    @endforeach

    <script>
        const { jsPDF } = window.jspdf;
        const users = @json($users);
        const zip = new JSZip();

        const progressBar = document.getElementById('progress-bar');
        const progressPercent = document.getElementById('progress-percent');
        const statusLabel = document.getElementById('status-label');
        const statusText = document.getElementById('status-text');
        const completeActions = document.getElementById('complete-actions');
        const renderTarget = document.getElementById('render-target');

        function updateProgress(processed, total) {
            const percent = Math.round((processed / total) * 100);
            progressBar.style.width = percent + '%';
            progressPercent.textContent = percent + '%';
        }

        async function processAll() {
            let processed = 0;
            const total = users.length;

            for (const user of users) {
                processed++;
                statusLabel.textContent = `Processing ${processed}/${total}`;
                statusText.textContent = `Rendering: ${user.name}...`;
                updateProgress(processed, total);

                // 1. Setup template
                const template = document.getElementById(`user-${user.id}`);
                renderTarget.innerHTML = '';
                renderTarget.appendChild(template.content.cloneNode(true));

                // Wait a tiny bit for any layout calculations
                await new Promise(r => setTimeout(r, 100));

                try {
                    // 2. Capture to Canvas
                    const letterPage = renderTarget.querySelector('.letter-page');
                    if (!letterPage) throw new Error("Template logic error");

                    const canvas = await html2canvas(letterPage, {
                        scale: 2,
                        useCORS: true,
                        backgroundColor: "#ffffff",
                        windowWidth: 794, // A4 width at 96 DPI
                        logging: false
                    });

                    // 3. Generate PDF
                    const pdf = new jsPDF({
                        orientation: 'p',
                        unit: 'mm',
                        format: 'a4'
                    });

                    const imgData = canvas.toDataURL('image/jpeg', 0.95);
                    pdf.addImage(imgData, 'JPEG', 0, 0, 210, 297, undefined, 'FAST');

                    // 4. Add to ZIP
                    const fullName = user.profile?.full_name || 'User';
                    const employeeId = user.employee_id || user.id;
                    const safeName = `${fullName}_${employeeId}`.replace(/[/\\?%*:|"<>]/g, '-');
                    const pdfBlob = pdf.output('blob');
                    zip.file(`${safeName}.pdf`, pdfBlob);

                } catch (err) {
                    console.error(`Error processing user ${user.id}:`, err);
                }
            }

            statusText.textContent = "Finalizing ZIP archive...";
            statusLabel.textContent = "Packing...";

            try {
                const content = await zip.generateAsync({ type: "blob" });
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
                saveAs(content, `Bulk_Offer_Letters_${timestamp}.zip`);

                statusText.textContent = "Download complete!";
                statusLabel.textContent = "Success";
                statusLabel.className = "text-xs font-black inline-block py-1 px-3 uppercase rounded-full text-emerald-600 bg-emerald-100 ring-4 ring-emerald-50";
                completeActions.classList.remove('hidden');
            } catch (err) {
                statusText.textContent = "Error generating ZIP file.";
                console.error(err);
            }
        }

        // Start processing when page loads
        window.onload = () => {
            setTimeout(processAll, 1000);
        };
    </script>
</body>

</html>