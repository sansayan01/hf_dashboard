@php
    $is_pdf = $is_pdf ?? false;
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter - {{ $user->profile->full_name }}</title>
    @if (!$is_pdf)
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
            rel="stylesheet">
    @endif
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
            font-family:
                {{ $is_pdf ? 'DejaVu Sans, sans-serif' : "'Outfit', sans-serif" }}
            ;
        }

        body {
            background-color:
                {{ $is_pdf ? '#ffffff' : '#f1f5f9' }}
            ;
            color: var(--slate-800);
            @if (!$is_pdf)
                display: flex;
                justify-content: center;
                padding: 40px 0;
            @endif margin: 0;
        }

        .letter-page {
            width:
                {{ $is_pdf ? '100%' : '210mm' }}
            ;
            height:
                {{ $is_pdf ? 'auto' : '296.8mm' }}
            ;
            background: white;
            padding:
                {{ $is_pdf ? '5mm' : '10mm 15mm' }}
            ;
            @if (!$is_pdf)
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            @endif position: relative;
            overflow: hidden;
            display: block;
        }

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

        .tc-list {
            list-style: none;
            padding-left: 0;
            margin-bottom: 10px;
        }

        .tc-list li {
            position: relative;
            padding-left: 22px;
            margin-bottom: 2px;
            font-size: 10px;
            line-height: 1.3;
            font-weight: 600;
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

        @if (!$is_pdf)
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

            .btn-secondary {
                background: white;
                color: var(--slate-800);
                border: 1px solid #e2e8f0;
            }

        @endif
    </style>
</head>

<body>
    @if (!$is_pdf)
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
    @endif

    <div class="letter-page" id="offer-letter">
        @php
            $logoPath = $is_pdf ? public_path('img/logo 1.png') : asset('img/logo 1.png');
            $sigPath = $is_pdf ? public_path('img/signature.png') : asset('img/signature.png');
        @endphp
        <img src="{{ $logoPath }}" class="watermark" alt="Watermark">

        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="logo-section">
                        <img src="{{ $logoPath }}" alt="Humanity Foundation Logo">
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
            <div class="dynamic-data">{{ $user->profile->full_name }}</div>
            <div class="dynamic-data">Emp ID : {{ $user->employee_id }}</div>
            <div class="dynamic-data">Aadhar Number : {{ $user->profile->aadhaar_number }}</div>
        </div>

        <div class="body-content">
            @if ($user->isDM())
                <p>We are pleased to offer you this Joining letter as designation of District Manager (DM) Under
                    <strong>“{{ $user->profile->district }}”</strong> District, Which will effect from the date of your
                    team performance starts. We congratulate you and wish you a long and successful career with us. We are
                    confident that your contribution will take us further in our journey towards becoming world leaders. We
                    assure you of our support for your professional development and growth.
                </p>

                <p>You will get Monthly Honorarium of Rupees 90,000 (Ninety thousand) inr + Travelling allowance 5,000 (Five
                    Thousands) inr. Also you may earn Spot incentives. Your first duty is to lead all Relationship Manager
                    (RM) to increase HF Members, doing surveys, Delivering Banking & Financial services, Health services
                    (Doctor Consultation, Medicine, Pathology services), governments projects, Nutrition food to every
                    family etc..you should have minimum 625 ROs in your team.</p>
            @elseif($user->isBM())
                <p>We are pleased to offer you this Joining letter as designation of Block Manager (BM) Under
                    <strong>“{{ $user->profile->block }}”</strong> block of
                    <strong>“{{ $user->profile->district }}”</strong> District, Which will effect from the date of your
                    team performance starts. We congratulate you and wish you a long and successful career with us. We are
                    confident that your contribution will take us further in our journey towards becoming world leaders. We
                    assure you of our support for your professional development and growth.
                </p>

                <p>You will get Monthly Honorarium of Rupees 37,500 (Thirty seven thousand five hundred) inr. Also you may
                    earn Spot incentives. Your first duty is to lead all Relationship Manager (RM) to increase HF Members,
                    doing surveys, Delivering Banking & Financial services, Health services (Doctor Consultation, Medicine,
                    Pathology services), governments projects, Nutrition food to every family etc. You should have minimum
                    125 ROs in your team.</p>
            @elseif($user->isRM())
                <p>We are pleased to offer you this Joining letter as designation of Relationship Manager (RM),
                    <strong>“{{ $user->profile->block }}”</strong> Block, Under
                    <strong>“{{ $user->profile->district }}”</strong> District , Which will effect
                    from the date of your team performance starts.
                    We congratulate you and wish you a long and successful career with us. We are confident that your
                    contribution will take us further in our journey towards becoming world leaders. We assure you of our
                    support for your professional development and growth.
                </p>

                <p>You will get Monthly Honorarium of Rupees 18,750 (Eighteen thousand Seven hundred fifty ) inr . Also you
                    may earn Spot incentives . Your first duty is to lead all Relationship Officers (RO) to increase HF
                    Members, doing surveys, Delivering Banking & Financial services, Health services (Doctor Consultation,
                    Medicine, Pathology services), governments projects, Nutrition food to every family etc..you should have
                    minimum 25 ROs in your team. </p>
            @else
                <p>We are pleased to offer you an Offer letter as designation of Relationship Officer (RO) at
                    <strong>“{{ $user->profile->gram_panchayat }}”</strong> Gram Panchayat,
                    <strong>“{{ $user->profile->block }}”</strong> Block ,
                    <strong>“{{ $user->profile->district }}”</strong> District, Which will effect from the date of your
                    performance starts.
                    We congratulate you and wish you a long and successful career with us. We are confident that your
                    contribution will take us further in our journey towards becoming world leaders. We assure you of our
                    support for your professional development and growth.
                </p>

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
                        <img src="{{ $sigPath }}" alt="Director Signature" class="signature-img">
                        <div class="signature-label">Secretary</div>
                        <div class="signature-org">Humanity Foundation</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    @if (!$is_pdf)
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
        <script>
            function downloadPDF() {
                const element = document.getElementById('offer-letter');
                const opt = {
                    margin: 0,
                    filename: 'Offer_Letter_{{ $user->employee_id }}.pdf',
                    image: {
                        type: 'jpeg',
                        quality: 0.98
                    },
                    html2canvas: {
                        scale: 2,
                        useCORS: true,
                        logging: false,
                        letterRendering: true,
                        scrollY: 0,
                        scrollX: 0
                    },
                    jsPDF: {
                        unit: 'mm',
                        format: 'a4',
                        orientation: 'portrait'
                    },
                    pagebreak: {
                        mode: 'avoid-all'
                    }
                };

                html2pdf().set(opt).from(element).save();
            }
        </script>
    @endif
</body>

</html>