<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - {{ $user->profile->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-green: #008037;
            --dark-green: #004d21;
            --accent-red: #cc0000;
            --slate-800: #1e293b;
            --slate-600: #475569;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: #f1f5f9;
            color: var(--slate-800);
            display: flex;
            justify-content: center;
            padding: 40px 0;
            margin: 0;
        }

        .letter-page {
            width: 210mm;
            height: 296.8mm;
            /* Slightly less than 297mm to avoid 2nd page overflow */
            background: white;
            padding: 10mm 15mm;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Removed corner decorations for cleaner look/print */
        .corner-decoration {
            display: none;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2px;
            position: relative;
            z-index: 1;
        }

        .logo-section img {
            height: 100px;
            /* Increased from 85px */
            width: auto;
        }

        .title-section {
            text-align: center;
            flex-grow: 1;
        }

        .title-section h1 {
            color: var(--primary-green);
            font-size: 38px;
            /* Significantly increased from 30px */
            font-weight: 900;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            text-transform: uppercase;
        }

        .title-section p {
            font-size: 13.5px;
            /* Increased from 11px */
            font-weight: 700;
            color: var(--slate-800);
            margin-bottom: 2px;
        }

        .address-bar {
            background-color: var(--dark-green);
            color: white;
            text-align: center;
            padding: 6px;
            font-size: 14px;
            /* Increased from 11px */
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
            font-size: 26px;
            /* Increased from 20px */
            font-weight: 800;
            text-decoration: underline;
            text-underline-offset: 7px;
            text-transform: uppercase;
            letter-spacing: 4px;
        }

        .recipient-section {
            margin-bottom: 8px;
            /* Reduced from 15px */
            font-size: 15px;
            /* Slightly reduced */
            line-height: 1.4;
            font-weight: 600;
        }

        .recipient-section .to-label {
            font-size: 17px;
            /* Slightly reduced */
            font-weight: 800;
            margin-bottom: 1px;
        }

        .recipient-section .dynamic-data {
            color: var(--slate-800);
        }

        .body-content {
            font-size: 13px;
            /* Slightly reduced from 13.5px */
            line-height: 1.4;
            /* Slightly reduced from 1.5 */
            text-align: justify;
            margin-bottom: 10px;
            /* Reduced from 15px */
        }

        .body-content p {
            margin-bottom: 12px;
        }

        .tc-heading {
            color: var(--accent-red);
            font-weight: 900;
            font-size: 13.5px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .tc-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 18px;
        }

        .tc-list li {
            position: relative;
            padding-left: 22px;
            margin-bottom: 2px;
            font-size: 11px;
            /* Increased from 10px */
            line-height: 1.3;
            font-weight: 600;
        }

        .tc-list li::before {
            content: attr(data-index) ".";
            position: absolute;
            left: 0;
            font-weight: 800;
        }

        .footer {
            margin-top: 15mm;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            padding-bottom: 75mm;
            /* Moved very high from the edge */
            position: relative;
            z-index: 1;
        }

        .contact-info {
            font-size: 10px;
            font-weight: 600;
            color: var(--slate-600);
        }

        .contact-item {
            display: flex;
            align-items: center;
            margin-bottom: 2px;
            gap: 5px;
        }

        .contact-icon {
            width: 16px;
            height: 16px;
            background: var(--slate-600);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
        }

        .seal-section {
            text-align: center;
            position: relative;
        }

        .seal-img {
            height: 70px;
            opacity: 0.12;
            transform: rotate(-10deg);
        }

        .signature-section {
            text-align: center;
            min-width: 200px;
        }

        .signature-img {
            height: 40px;
            width: auto;
            margin-bottom: 2px;
        }

        .signature-label {
            font-weight: 800;
            font-size: 11px;
            margin-bottom: 1px;
        }

        .signature-org {
            font-size: 10px;
            font-weight: 600;
            color: var(--primary-green);
        }

        /* Central Watermark */
        .watermark {
            position: absolute;
            top: 55%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-15deg);
            width: 700px;
            height: auto;
            opacity: 0.06;
            pointer-events: none;
            z-index: 0;
        }

        /* Controls */
        .controls {
            position: fixed;
            top: 40px;
            right: 40px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            z-index: 100;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            transition: all 0.3s;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: var(--primary-green);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 12px rgba(0, 128, 55, 0.3);
        }

        .btn-secondary {
            background: white;
            color: var(--slate-800);
            border: 1px solid #e2e8f0;
        }

        @media print {
            body {
                padding: 0;
                background: white;
            }

            .letter-page {
                box-shadow: none;
                margin: 0;
                width: 100%;
                height: 100%;
            }

            .controls {
                display: none;
            }

            .letter-page {
                border: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>
    <div class="controls">
        <button onclick="window.print()" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            Print Letter
        </button>
        <button onclick="downloadPDF()" class="btn btn-secondary"
            style="background: #1e293b; color: white; border: none;">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Download PDF
        </button>
        <a href="{{ route('users.show', $user->id) }}" class="btn btn-secondary">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Back
        </a>
    </div>

    <div class="letter-page" id="offer-letter">
        <img src="{{ asset('img/logo 1.png') }}" class="watermark" alt="Watermark">
        <div class="corner-decoration top-left-decor"></div>
        <div class="corner-decoration bottom-right-decor"></div>

        <div class="header">
            <div class="logo-section">
                <img src="{{ asset('img/logo 1.png') }}" alt="Humanity Foundation Logo">
            </div>
            <div class="title-section">
                <h1>HUMANITY FOUNDATION</h1>
                <p>Registered of Non Government Organization (NGO)</p>
                <p>Registered Under Sec - 60 & Rule 69, Registration No. - IV-190100489</p>
            </div>
        </div>

        <div class="address-bar">
            Head Office : Kendriyanagar Keshabganj Chati , Burdwan West Bengal-713104
        </div>

        <div class="document-title">
            <h2>OFFER LETTER</h2>
        </div>

        <div class="recipient-section">
            <div class="to-label">To:</div>
            <div class="dynamic-data">{{ $user->profile->full_name }}</div>
            <div class="dynamic-data">Emp ID : {{ $user->employee_id }}</div>
            <div class="dynamic-data">Aadhar Number : {{ $user->profile->aadhaar_number }}</div>
        </div>

        <div class="body-content">
            @if($user->isRM())
                {{-- RM Content --}}
                <p>We are pleased to offer you this Joining letter as designation of Relationship Manager (RM),
                    “{{ $user->profile->block }}” Block, Under “{{ $user->profile->district }}” District , Which will effect
                    from the date of your team performance starts.
                    We congratulate you and wish you a long and successful career with us. We are confident that your
                    contribution will take us further in our journey towards becoming world leaders. We assure you of our
                    support for your professional development and growth.</p>

                <p>You will get Monthly Honorarium of Rupees 18,750 (Eighteen thousand Seven hundred fifty ) inr . Also you
                    may earn Spot incentives . Your first duty is to lead all Relationship Officers (RO) to increase HF
                    Members, doing surveys, Delivering Banking & Financial services, Health services (Doctor Consultation,
                    Medicine, Pathology services), governments projects, Nutrition food to every family etc..you should have
                    minimum 25 ROs in your team.</p>
            @else
                {{-- Default RO Content --}}
                <p>We are pleased to offer you an Offer letter as designation of Relationship Officer (RO) at
                    “{{ $user->profile->gram_panchayat }}” Gram Panchayat, “{{ $user->profile->block }}” Block ,
                    “{{ $user->profile->district }}” District, Which will effect from the date of your performance starts.
                    We congratulate you and wish you a long and successful career with us. We are confident that your
                    contribution will take us further in our journey towards becoming world leaders. We assure you of our
                    support for your professional development and growth.</p>

                <p>Humanity Foundation is a Government Registered Trust and you are going to be a part of this Organization.
                    You will get Monthly Honorarium of Rupees 6,000(six thousands)inr+ 1500 (One Thousand Five Hundred) as
                    travelling allowance. Your responsibility/task is to doing survey minimum 20 families every day, through
                    which has to generate minimum 8 doctors appointments, in a month has to provide at least 200 Doctor
                    appointments+100 Memberships & other banking services. It is Mandatory to give your work report to your
                    senior at the end of every day and organize Health camps. Apart from this it is your duty to supply
                    medicines/Glasses/test reports to Members/Patients. Also you can regularly get spot incentives, loan
                    Commissions etc by providing various services.</p>
            @endif

            <p>Your offer has been made based on information furnished by you. However, if there is a discrepancy in the
                copies of documents or certificates given by you as a proof of above, we retain the right to review our
                offer of employment.</p>
        </div>

        <div class="tc-heading">
            DETAILS OF THE TERMS AND CONDITIONS OF THE OFFER ARE AS UNDER:
        </div>

        <ul class="tc-list">
            @if($user->isRM())
                {{-- RM Terms --}}
                <li data-index="1">Organization isn’t liable to pay you the above Honorarium, if you found guilty or non
                    compliance.</li>
                <li data-index="2">You should have minimum 25 RO’s active in your team.</li>
                <li data-index="3">The notice period is one month from the effective date of acceptance of resignation.
                    However the Trust at its sole discretion is liable to terminate with immediate effect due to
                    nonperformance or integrity issues.</li>
                <li data-index="4">If for any reason the work is stopped or you do not perform your duty, That day’s payout
                    will not be counted.</li>
                <li data-index="5">The Trust holds all right to switch over to POP (payment on performance) mode without any
                    further notice or information.</li>
                <li data-index="6">The Trust can reallocate your shift & process as per requirement and the employee should
                    be flexible enough to reallocate as per Trust’s requirement, failing which service come to an end.</li>
                <li data-index="7">To claim any salary or dues an employee has to be there in the Trust floor minimum 40
                    days.</li>
                <li data-index="8">Always carry your ID card along with you during duty hours.</li>
                <li data-index="9">You may not offer any product or services to any member or do not on your behalf without
                    the Organization’s permission.</li>
                <li data-index="10">Do not give instructions to any RMs for your personal use or to do any work other than
                    work for the Organization.</li>
                <li data-index="11">You have to update your team performance report to your senior. If you do not deliver
                    your daily report till 2 days then 500 inr will deduct from your base Honorarium and you would be warned
                    but if you will do continue the same then within 5 days you may get a “Show Cause notice”. After 10 days
                    of your inactive response we will take it as your resignation. You will lose your job.</li>
                <li data-index="12">You do have the authority to give warning and take action on those RMs who are not
                    working.</li>
                <li data-index="13">It is one of your important responsibility to see if the RM’s are collecting and
                    depositing bills of Medicine or pathology or other payments or membership fees on a daily basis..</li>
                <li data-index="14">You cannot keep Membership fees/ Medicine billed amount/ Pathology billed amount etc..
                </li>
                <li data-index="15">Please do not miss behave with any RMs or any HF Members.</li>
                <li data-index="16">Within the 10th of every month, you will receive the Honorarium of the previous month.
                </li>
            @else
                {{-- RO Terms --}}
                <li data-index="1">Trust isn’t liable to pay you the above Honorarium, if you found guilty or non compliance
                    in the Trust.</li>
                <li data-index="2">The notice period is one month from the effective date of acceptance of resignation.
                    However the Trust at its sole discretion is liable to terminate with immediate effect due to
                    nonperformance or integrity issues.</li>
                <li data-index="3">The Trust holds all right to switch over to POP (payment on performance) mode without any
                    further notice or information.</li>
                <li data-index="4">The Trust can reallocate your shift & process as per requirement and the employee should
                    be flexible enough to reallocate as per Trust’s requirement, failing which service come to an end.</li>
                <li data-index="5">If you cannot Complete the stipulated task, the amount of your Monthly Honorarium will
                    depend on the volume of your work.</li>
                <li data-index="6">To claim any salary or dues an employee has to be there in the Trust floor minimum 40
                    days.</li>
                <li data-index="7">You will get HF identity cards. always wear formal dress & carry your ID card along with
                    you during duty hours.</li>
                <li data-index="8">You may not offer any product or services to any member on your behalf without the
                    Organization’s permission.</li>
                <li data-index="9">You have to send your daily performance report to your senior. If you do not deliver your
                    daily report till 3 days then 300 inr will deduct from your base Honorarium and you would be warned but
                    if you will do continue the same then within 5 days you may get a “Show Cause notice”. After 10 days of
                    your inactive response we will take it as your resignation. You will lose your job.</li>
                <li data-index="10">Medicine or pathology due or membership fees Daily collection and submit to HF Official
                    is an important work in your daily schedule.</li>
                <li data-index="11">You cannot keep Membership fees/ Medicine billed amount/ Pathology billed amount etc..
                </li>
                <li data-index="12">Please do not miss behave with any HF Members.</li>
                <li data-index="13">Within the 10th of every month, you will receive the Honorarium of the previous month.
                </li>
            @endif
        </ul>

        <div class="footer">
            <div class="contact-info">
                <div class="contact-item">
                    <div class="contact-icon">📞</div>
                    <span>9735563157</span>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">📧</div>
                    <span>info@hfburdwan.in</span>
                </div>
                <div class="contact-item">
                    <div class="contact-icon">🌐</div>
                    <span>www.hfburdwan.in</span>
                </div>
            </div>

            <div class="signature-section">
                <img src="{{ asset('img/signature.png') }}" alt="Director Signature" class="signature-img">
                <div class="signature-label">Secretary</div>
                <div class="signature-org">Humanity Foundation</div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.getElementById('offer-letter');
            const opt = {
                margin: 0,
                filename: 'Offer_Letter_{{ $user->employee_id }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    logging: false,
                    letterRendering: true,
                    scrollY: 0,
                    scrollX: 0
                },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: 'avoid-all' }
            };

            // Capture the letter
            html2pdf().set(opt).from(element).toPdf().get('pdf').then(function (pdf) {
                // Double check if page count is > 1 and it's blank (safety measure if library still adds it)
                // but avoid-all and the height adjustment should handle it.
            }).save();
        }
    </script>
</body>

</html>