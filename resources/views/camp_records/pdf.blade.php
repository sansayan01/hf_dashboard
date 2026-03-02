<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Camp Record - {{ $campRecord->camp_name }}</title>
    <style>
        * {
            font-family: "DejaVu Sans", sans-serif !important;
            letter-spacing: normal !important;
        }
        body {
            color: #333;
            margin: 0;
            padding: 10px 20px;
            font-size: 11px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #4f46e5;
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            color: #666;
            margin: 2px 0 0 0;
            font-size: 12px;
        }
        
        /* Grid Layout */
        .row {
            width: 100%;
            margin-bottom: 15px;
            clear: both;
        }
        .col-6 {
            width: 48%;
            float: left;
        }
        .col-right {
            float: right;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        .section-title {
            background-color: #f3f4f6;
            color: #1f2937;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 11px;
            border-left: 3px solid #4f46e5;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            padding: 6px 8px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        th {
            color: #6b7280;
            font-weight: 600;
            width: 40%;
            font-size: 10px;
            text-transform: uppercase;
        }
        td {
            color: #111827;
        }

        /* Financial Summary Boxes - More Compact */
        .fin-summary-container {
            margin-bottom: 15px;
        }
        .fin-box {
            width: 31%;
            float: left;
            padding: 12px 5px;
            text-align: center;
            border-radius: 6px;
            margin-right: 2%;
        }
        .fin-box-last {
            margin-right: 0;
        }
        .fin-box-profit { background-color: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; }
        .fin-box-expense { background-color: #fef2f2; border: 1px solid #fecaca; color: #991b1b; }
        .fin-box-net { background-color: #eff6ff; border: 1px solid #bfdbfe; color: #1e40af; }
        .fin-label { font-size: 9px; text-transform: uppercase; font-weight: bold; margin-bottom: 2px; }
        .fin-value { font-size: 16px; font-weight: bold; }
        
        /* Expense Table */
        .expense-table th { background-color: #f9fafb; border: 1px solid #e5e7eb; }
        .expense-table td { border: 1px solid #e5e7eb; padding: 4px 8px; }
        .expense-table .text-right { text-align: right; }
        .expense-table .total-row td { font-weight: bold; background-color: #f3f4f6; }
        
        .footer {
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: auto;
            opacity: 0.08;
            z-index: -1000;
        }

        /* Official Stamp */
        .stamp {
            position: absolute;
            bottom: 40px;
            right: 40px;
            width: 160px;
            height: auto;
            opacity: 0.85;
            transform: rotate(-10deg);
        }
    </style>
</head>
<body>
    <img src="{{ public_path('img/hf_gold_logo.png') }}" class="watermark">
    <img src="{{ public_path('img/foundation_stamp.png') }}" class="stamp">

    <div class="header">
        <h1>Humanity Foundation</h1>
        <p>Health Camp Summary Report</p>
    </div>

    <!-- Row 1: Camp Info & Medicine Stats Side-by-Side -->
    <div class="row clearfix">
        <div class="col-6">
            <div class="section-title">Camp Information</div>
            <table>
                <tr>
                    <th>Camp Name</th>
                    <td>{{ $campRecord->camp_name }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ \Carbon\Carbon::parse($campRecord->date)->format('M d, Y') }}</td>
                </tr>
                <tr>
                    <th>Location</th>
                    <td>{{ $campRecord->location ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Patients</th>
                    <td>{{ $campRecord->patients_count ?: 0 }}</td>
                </tr>
                <tr>
                    <th>RM</th>
                    <td>{{ $campRecord->rm ?: 'N/A' }}</td>
                </tr>
            </table>
        </div>
        <div class="col-6 col-right">
            <div class="section-title">Personnel</div>
            <table>
                <tr>
                    <th>Doctor</th>
                    <td>{{ $campRecord->doctor_name ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Pathologist</th>
                    <td>{{ $campRecord->pathologist ?: 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Pharmacist</th>
                    <td>{{ $campRecord->pharmacists_name ?: 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    @php 
        $actualNetPL = ($campRecord->profit ?? 0) - ($campRecord->expenses ?? 0); 
    @endphp

    <!-- Row 2: Financial Overview -->
    <div class="row">
        <div class="section-title">Financial Performance</div>
        
        <div class="fin-summary-container clearfix">
            <div class="fin-box fin-box-profit">
                <div class="fin-label">Gross Profit</div>
                <div class="fin-value">&#8377;{{ number_format($campRecord->profit ?? 0, 2) }}</div>
            </div>
            <div class="fin-box fin-box-expense">
                <div class="fin-label">Total Expenses</div>
                <div class="fin-value">&#8377;{{ number_format($campRecord->expenses ?? 0, 2) }}</div>
            </div>
            <div class="fin-box fin-box-net fin-box-last" style="{{ $actualNetPL < 0 ? 'background-color: #fef2f2; border-color: #fecaca; color: #991b1b;' : 'background-color: #ecfdf5; border-color: #a7f3d0; color: #065f46;' }}">
                <div class="fin-label">{{ $actualNetPL >= 0 ? 'Net Profit' : 'Net Loss' }}</div>
                <div class="fin-value">
                    {{ $actualNetPL >= 0 ? '+' : '' }}&#8377;{{ number_format($actualNetPL, 2) }}
                </div>
            </div>
        </div>

        <div class="clearfix">
            <div class="col-6">
                <table>
                    <tr style="background-color: #f8fafc;">
                        <th style="width: 50%; color: #475569; border: 1px solid #e2e8f0;">Medicine MRP Base</th>
                        <td style="font-weight: bold; color: #1e293b; border: 1px solid #e2e8f0;">&#8377;{{ number_format($campRecord->medicine_mrp ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-6 col-right">
                <table>
                    <tr style="background-color: #f0fdf4;">
                        <th style="width: 50%; color: #166534; border: 1px solid #dcfce7;">Discounted Price</th>
                        <td style="font-weight: bold; color: #14532d; border: 1px solid #dcfce7;">&#8377;{{ number_format($campRecord->medicine_discount ?? 0, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    @if(!empty($campRecord->expense_details) && count($campRecord->expense_details) > 0)
    <div class="section no-split">
        <div class="section-title">Expense Breakdown</div>
        <table class="expense-table">
            <thead>
                <tr>
                    <th style="width: 8%; text-align: center;">#</th>
                    <th style="width: 62%;">Category / Note</th>
                    <th style="width: 30%; text-align: right;">Amount &#40;&#8377;&#41;</th>
                </tr>
            </thead>
            <tbody>
                @foreach($campRecord->expense_details as $index => $expense)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $expense['category'] ?? 'General' }}</strong>
                        @if(!empty($expense['note']))
                            - <span style="color: #6b7280; font-size: 9px;">{{ $expense['note'] }}</span>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($expense['amount'] ?? 0, 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Total Sum</td>
                    <td class="text-right">&#8377;{{ number_format($campRecord->expenses ?? 0, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer">
        Generated: {{ \Carbon\Carbon::now()->format('d M Y, h:i A') }} • ID: HF-CR-{{ str_pad($campRecord->id, 5, '0', STR_PAD_LEFT) }}
    </div>

</body>
</html>
