<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Bulk Offer Letters - Humanity Foundation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

        :root {
            --primary-green: #008037;
            --dark-green: #004d21;
            --accent-red: #cc0000;
            --slate-800: #1e293b;
            --slate-600: #475569;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        @page {
            size: A4;
            margin: 0;
        }

        .letter-page {
            width: 210mm;
            min-height: 297mm;
            padding: 10mm 15mm;
            margin: 30px auto;
            background: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }

        @media print {
            body {
                background: none;
            }

            .no-print {
                display: none;
            }

            .letter-page {
                margin: 0;
                box-shadow: none;
                width: 100%;
                height: 100%;
            }
        }

        /* Copied from joining_letter.blade.php */
        .header {
            width: 100%;
            margin-bottom: 2px;
            position: relative;
            z-index: 1;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-section {
            width: 25%;
            vertical-align: middle;
        }

        .logo-section img {
            height: 80px;
            width: auto;
        }

        .title-section {
            width: 75%;
            text-align: center;
            vertical-align: middle;
        }

        .title-section h1 {
            color: var(--primary-green);
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .title-section p {
            font-size: 11px;
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 2px;
        }

        .address-bar {
            background-color: var(--dark-green);
            color: white;
            text-align: center;
            padding: 6px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 4px;
            margin-bottom: 15px;
            position: relative;
            z-index: 1;
        }

        .document-title {
            text-align: center;
            margin-bottom: 15px;
        }

        .document-title h2 {
            color: var(--accent-red);
            font-size: 22px;
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 7px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .recipient-section {
            margin-bottom: 8px;
            font-size: 14px;
            line-height: 1.4;
            font-weight: 600;
        }

        .recipient-section .to-label {
            font-size: 16px;
            font-weight: 800;
            margin-bottom: 1px;
        }

        .body-content {
            font-size: 12px;
            line-height: 1.4;
            text-align: justify;
            margin-bottom: 10px;
        }

        .body-content p {
            margin-bottom: 8px;
        }

        .tc-heading {
            color: var(--accent-red);
            font-weight: 900;
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .footer {
            margin-top: 10mm;
            width: 100%;
            position: relative;
            z-index: 1;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .contact-info {
            width: 50%;
            font-size: 9px;
            font-weight: 600;
            color: var(--slate-600);
        }

        .signature-section {
            width: 50%;
            text-align: right;
        }

        .signature-img {
            height: 35px;
            width: auto;
            margin-bottom: 2px;
        }

        .signature-label {
            font-weight: 800;
            font-size: 10px;
            margin-bottom: 1px;
        }

        .signature-org {
            font-size: 9px;
            font-weight: 600;
            color: var(--primary-green);
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            width: 400px;
            opacity: 0.06;
            z-index: 0;
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
        <p class="text-sm opacity-80 mt-2">Please wait while we process the offer letters.</p>
    </div>

    <!-- Controls -->
    <div class="no-print fixed bottom-8 right-8 z-50 flex flex-col space-y-4">
        <button onclick="window.print()"
            class="bg-white text-slate-800 px-8 py-4 rounded-full shadow-2xl font-bold hover:bg-slate-100 transition transform hover:scale-105 flex items-center space-x-2 border border-slate-200">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4H5v4a2 2 0 002 2zM12 17h.01M9 16h6" />
            </svg>
            <span>Print Now</span>
        </button>
        <button onclick="saveAsPDF()"
            class="bg-slate-900 text-white px-8 py-4 rounded-full shadow-2xl font-bold hover:bg-black transition transform hover:scale-105 flex items-center space-x-2">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            <span>Save as PDF</span>
        </button>
    </div>

    <div id="print-container">
        @foreach($users as $user)
            <div class="letter-page">
                <img src="{{ asset('img/logo 1.png') }}" class="watermark" alt="Watermark">

                <div class="header">
                    <table class="header-table">
                        <tr>
                            <td class="logo-section">
                                <img src="{{ asset('img/logo 1.png') }}" alt="Humanity Foundation Logo">
                            </td>
                            <td class="title-section">
                                <h1>HUMANITY FOUNDATION</h1>
                                <p>Registered of Non Government Organization (NGO)</p>
                                <p>Registered Under Sec - 60 & Rule 69, Registration No. - IV-190100489</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="address-bar">
                    Head Office : Kendriyanagar Keshabganj Chati , Burdwan West Bengal-713104
                </div>

                <div class="document-title">
                    <h2>OFFER LETTER</h2>
                </div>

                <div class="recipient-section">
                    <div class="to-label">To:</div>
                    <div class="dynamic-data">{{ $user->profile?->full_name ?? 'N/A' }}</div>
                    <div class="dynamic-data">Emp ID : {{ $user->employee_id }}</div>
                    <div class="dynamic-data">Aadhar Number : {{ $user->profile?->aadhaar_number ?? 'N/A' }}</div>
                </div>

                <div class="body-content">
                    @if ($user->isDM())
                        <p>We are pleased to offer you this Joining letter as designation of District Manager (DM) Under
                            <strong>“{{ $user->profile?->district ?? 'N/A' }}”</strong> District, Which will effect from the
                            date of your
                            team performance starts. We congratulate you and wish you a long and successful career with us. We
                            are
                            confident that your contribution will take us further in our journey towards becoming world leaders.
                            We
                            assure you of our support for your professional development and growth.
                        </p>
                        <p>You will get Monthly Honorarium of Rupees 90,000 (Ninety thousand) inr + Travelling allowance 5,000
                            (Five
                            Thousands) inr. Also you may earn Spot incentives. Your first duty is to lead all Relationship
                            Manager
                            (RM) to increase HF Members, doing surveys, Delivering Banking & Financial services, Health services
                            (Doctor Consultation, Medicine, Pathology services), governments projects, Nutrition food to every
                            family etc..you should have minimum 625 ROs in your team.</p>
                    @elseif($user->isBM())
                        <p>We are pleased to offer you this Joining letter as designation of Block Manager (BM) Under
                            <strong>“{{ $user->profile?->block ?? 'N/A' }}”</strong> block of
                            <strong>“{{ $user->profile?->district ?? 'N/A' }}”</strong> District, Which will effect from the
                            date of your
                            team performance starts. We congratulate you and wish you a long and successful career with us. We
                            are
                            confident that your contribution will take us further in our journey towards becoming world leaders.
                            We
                            assure you of our support for your professional development and growth.
                        </p>
                        <p>You will get Monthly Honorarium of Rupees 37,500 (Thirty seven thousand five hundred) inr. Also you
                            may
                            earn Spot incentives. Your first duty is to lead all Relationship Manager (RM) to increase HF
                            Members,
                            doing surveys, Delivering Banking & Financial services, Health services (Doctor Consultation,
                            Medicine,
                            Pathology services), governments projects, Nutrition food to every family etc. You should have
                            minimum
                            125 ROs in your team.</p>
                    @elseif($user->isRM())
                        <p>We are pleased to offer you this Joining letter as designation of Relationship Manager (RM),
                            <strong>“{{ $user->profile?->block ?? 'N/A' }}”</strong> Block, Under
                            <strong>“{{ $user->profile?->district ?? 'N/A' }}”</strong> District , Which will effect
                            from the date of your team performance starts.
                            We congratulate you and wish you a long and successful career with us. We are confident that your
                            contribution will take us further in our journey towards becoming world leaders. We assure you of
                            our
                            support for your professional development and growth.
                        </p>
                        <p>You will get Monthly Honorarium of Rupees 18,750 (Eighteen thousand Seven hundred fifty ) inr . Also
                            you
                            may earn Spot incentives . Your first duty is to lead all Relationship Officers (RO) to increase HF
                            Members, doing surveys, Delivering Banking & Financial services, Health services (Doctor
                            Consultation,
                            Medicine, Pathology services), governments projects, Nutrition food to every family etc..you should
                            have
                            minimum 25 ROs in your team. </p>
                    @else
                        <p>We are pleased to offer you an Offer letter as designation of Relationship Officer (RO) at
                            <strong>“{{ $user->profile?->gram_panchayat ?? 'N/A' }}”</strong> Gram Panchayat,
                            <strong>“{{ $user->profile?->block ?? 'N/A' }}”</strong> Block ,
                            <strong>“{{ $user->profile?->district ?? 'N/A' }}”</strong> District, Which will effect from the
                            date of your
                            performance starts.
                            We congratulate you and wish you a long and successful career with us. We are confident that your
                            contribution will take us further in our journey towards becoming world leaders. We assure you of
                            our
                            support for your professional development and growth.
                        </p>
                        <p>Humanity Foundation is a Government Registered Trust and you are going to be a part of this
                            Organization.
                            You will get Monthly Honorarium of Rupees 6,000(six thousands)inr+ 1500 (One Thousand Five Hundred)
                            as
                            travelling allowance. Your responsibility/task is to doing survey minimum 20 families every day,
                            through
                            which has to generate minimum 8 doctors appointments, in a month has to provide at least 200 Doctor
                            appointments+100 Memberships & other banking services. It is Mandatory to give your work report to
                            your
                            senior at the end of every day and organize Health camps. Apart from this it is your duty to supply
                            medicines/Glasses/test reports to Members/Patients. Also you can regularly get spot incentives, loan
                            Commissions etc by providing various services.</p>
                    @endif
                </div>

                <div class="tc-heading">
                    DETAILS OF THE TERMS AND CONDITIONS OF THE OFFER ARE AS UNDER:
                </div>

                @php
                    $terms = [];
                    if ($user->isDM() || $user->isBM() || $user->isRM()) {
                        $terms = [
                            'Organization isn’t liable to pay you the above Honorarium, if you found guilty or non compliance.',
                            'You should have minimum ' . ($user->isDM() ? '625' : ($user->isBM() ? '125' : '25')) . ' RO’s active in your team.',
                            'The notice period is one month from the effective date of acceptance of resignation. However the Trust at its sole discretion is liable to terminate with immediate effect due to nonperformance or integrity issues.',
                            'If for any reason the work is stopped or you do not perform your duty, That day’s payout will not be counted.',
                            'The Trust holds all right to switch over to POP (payment on performance) mode without any further notice or information.',
                            'The Trust can reallocate your shift & process as per requirement and the employee should be flexible enough to reallocate as per Trust’s requirement, failing which service come to an end.',
                            'To claim any salary or dues an employee has to be there in the Trust floor minimum 40 days.',
                            'Always carry your ID card along with you during duty hours.',
                            'You may not offer any product or services to any member or do not on your behalf without the Organization’s permission.',
                            'Do not give instructions to any RMs for your personal use or to do any work other than work for the Organization.',
                            'You have to update your team performance report to your senior. If you do not deliver your daily report till 2 days then 500 inr will deduct from your base Honorarium and you would be warned but if you will do continue the same then within 5 days you may get a “Show Cause notice”. After 10 days of your inactive response we will take it as your resignation. You will lose your job.',
                            'You do have the authority to give warning and take action on those RMs who are not working.',
                            'It is one of your important responsibility to see if the RM’s are collecting and depositing bills of Medicine or pathology or other payments or membership fees on a daily basis..',
                            'You cannot keep Membership fees/ Medicine billed amount/ Pathology billed amount etc..',
                            'Please do not miss behave with any RMs or any HF Members.',
                            'Within the 10th of every month, you will receive the Honorarium of the previous month.',
                        ];
                    } else {
                        $terms = [
                            'Trust isn’t liable to pay you the above Honorarium, if you found guilty or non compliance in the Trust.',
                            'The notice period is one month from the effective date of acceptance of resignation. However the Trust at its sole discretion is liable to terminate with immediate effect due to nonperformance or integrity issues.',
                            'The Trust holds all right to switch over to POP (payment on performance) mode without any further notice or information.',
                            'The Trust can reallocate your shift & process as per requirement and the employee should be flexible enough to reallocate as per Trust’s requirement, failing which service come to an end.',
                            'If you cannot Complete the stipulated task, the amount of your Monthly Honorarium will depend on the volume of your work.',
                            'To claim any salary or dues an employee has to be there in the Trust floor minimum 40 days.',
                            'You will get HF identity cards. always wear formal dress & carry your ID card along with you during duty hours.',
                            'You may not offer any product or services to any member on your behalf without the Organization’s permission.',
                            'You have to send your daily performance report to your senior. If you do not deliver your daily report till 3 days then 300 inr will deduct from your base Honorarium and you would be warned but if you will do continue the same then within 5 days you may get a “Show Cause notice”. After 10 days of your inactive response we will take it as your resignation. You will lose your job.',
                            'Medicine or pathology due or membership fees Daily collection and submit to HF Official is an important work in your daily schedule.',
                            'You cannot keep Membership fees/ Medicine billed amount/ Pathology billed amount etc..',
                            'Please do not miss behave with any HF Members.',
                            'Within the 10th of every month, you will receive the Honorarium of the previous month.',
                        ];
                    }
                @endphp

                <div class="tc-list">
                    @foreach ($terms as $index => $term)
                        <div style="font-size: 10px; margin-bottom: 2px;">
                            <strong>{{ $index + 1 }}.</strong> {{ $term }}
                        </div>
                    @endforeach
                </div>

                <div class="footer">
                    <table class="footer-table">
                        <tr>
                            <td class="contact-info">
                                <div>📞 9735563157</div>
                                <div>📧 info@hfburdwan.in</div>
                                <div>🌐 www.hfburdwan.in</div>
                            </td>
                            <td class="signature-section">
                                <img src="{{ asset('img/signature.png') }}" alt="Director Signature" class="signature-img">
                                <div class="signature-label">Secretary</div>
                                <div class="signature-org">Humanity Foundation</div>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @endforeach
    </div>

    <script shadow>
        async function saveAsPDF() {
            const { jsPDF } = window.jspdf;
            const overlay = document.getElementById('loading-overlay');
            const loadingText = document.getElementById('loading-text');
            const pages = document.querySelectorAll('.letter-page');

            overlay.classList.remove('hidden');

            const pdf = new jsPDF({
                orientation: 'p',
                unit: 'mm',
                format: 'a4'
            });

            for (let i = 0; i < pages.length; i++) {
                loadingText.textContent = `Processing Letter ${i + 1} of ${pages.length}...`;

                const canvas = await html2canvas(pages[i], {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    allowTaint: false,
                    backgroundColor: "#ffffff"
                });

                const imgData = canvas.toDataURL('image/jpeg', 0.95);

                if (i > 0) pdf.addPage();

                // A4 dimensions are 210 x 297 mm
                pdf.addImage(imgData, 'JPEG', 0, 0, 210, 297);
            }

            loadingText.textContent = "Finalizing PDF...";
            pdf.save(`Bulk_Offer_Letters_${new Date().getTime()}.pdf`);

            overlay.classList.add('hidden');
        }
    </script>
</body>

</html>