<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salary Invoice - {{ $invoiceNumber }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f4f4f4;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #2c3e50;
            padding-bottom: 20px;
        }
        .company-info h1 {
            color: #2c3e50;
            font-size: 32px;
            margin-bottom: 5px;
        }
        .company-info p {
            color: #666;
            font-size: 14px;
        }
        .invoice-info {
            text-align: right;
        }
        .invoice-info h2 {
            color: #e74c3c;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .invoice-info p {
            font-size: 14px;
            color: #666;
        }
        .details-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .details-box {
            flex: 1;
        }
        .details-box h3 {
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 10px;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 5px;
        }
        .details-box p {
            font-size: 14px;
            margin-bottom: 5px;
        }
        .details-box strong {
            color: #2c3e50;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background: #2c3e50;
            color: white;
        }
        table th, table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }
        table th {
            font-weight: bold;
        }
        table tbody tr:hover {
            background: #f8f9fa;
        }
        .amount-cell {
            text-align: right;
        }
        .summary {
            margin-top: 30px;
            border-top: 2px solid #2c3e50;
            padding-top: 20px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 16px;
        }
        .summary-row.total {
            font-size: 20px;
            font-weight: bold;
            color: #27ae60;
            border-top: 2px solid #ecf0f1;
            margin-top: 10px;
            padding-top: 15px;
        }
        .summary-row.deduction {
            color: #e74c3c;
        }
        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            max-width: 120px;
            max-height: 50px;
            height: auto;
            width: auto;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
        }
        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .warning-box p {
            margin: 5px 0;
            color: #856404;
        }
        .quote-box {
            background: #e8f5e9;
            border-left: 4px solid #27ae60;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
            color: #2c3e50;
        }
        .website-link {
            text-align: center;
            margin: 20px 0;
            font-size: 14px;
        }
        .website-link a {
            color: #2c3e50;
            font-weight: bold;
            text-decoration: none;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Logo -->
        @php
            $logoPath = public_path('images/logo/webpenter_logo.png');
            $logoExists = file_exists($logoPath);
            $logoBase64 = null;

            if ($logoExists) {
                $imageData = file_get_contents($logoPath);
                $mimeType = mime_content_type($logoPath);
                $logoBase64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
            }
        @endphp

        @if($logoBase64)
        <div class="logo-container">
            <img src="{{ $logoBase64 }}" alt="Webpenter Logo" class="logo">
        </div>
        @endif

        <!-- Header -->
        <div class="header">
            <div class="company-info">
                <h1>{{ $companyName }}</h1>
                @if($companyAddress)
                <p>{{ $companyAddress }}</p>
                @endif
                @if($companyPhone)
                <p>Phone: {{ $companyPhone }}</p>
                @endif
                @if($companyEmail)
                <p>Email: {{ $companyEmail }}</p>
                @endif
            </div>
            <div class="invoice-info">
                <h2>SALARY INVOICE</h2>
                <p><strong>Invoice #:</strong> {{ $invoiceNumber }}</p>
                <p><strong>Date:</strong> {{ $invoiceDate }}</p>
                <p><strong>Period:</strong> {{ \Carbon\Carbon::parse($data['month'] . '-01')->format('F Y') }}</p>
            </div>
        </div>

        <!-- Employee Details -->
        <div class="details-section">
            <div class="details-box">
                <h3>Employee Information</h3>
                <p><strong>Name:</strong> {{ $data['user']['name'] }}</p>
                <p><strong>Email:</strong> {{ $data['user']['email'] }}</p>
{{--                <p><strong>Employee ID:</strong> {{ $data['user']['id'] }}</p>--}}
            </div>
{{--            <div class="details-box">--}}
{{--                <h3>Contract Information</h3>--}}
{{--                <p><strong>Contract:</strong> {{ $data['contract']['title'] }}</p>--}}
{{--                <p><strong>Monthly Salary:</strong> {{ $data['contract']['currency'] }} {{ number_format($data['contract']['monthly_salary'], 2) }}</p>--}}
{{--                <p><strong>Daily Salary:</strong> {{ $data['contract']['currency'] }} {{ number_format($data['contract']['daily_salary'], 2) }}</p>--}}
{{--            </div>--}}
        </div>

        <!-- Salary Breakdown -->
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th class="amount-cell">Amount ({{ $data['contract']['currency'] }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Gross Salary</strong></td>
                    <td class="amount-cell"><strong>{{ number_format($data['contract']['monthly_salary'], 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Leave Details -->
        @if($data['leaves']['total_leave_days'] > 0)
        <h3 style="color: #2c3e50; margin-top: 20px; margin-bottom: 10px;">Leave Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reason</th>
                    <th class="amount-cell">Days</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['leaves']['details'] as $leave)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($leave['start_date'])->format('M d, Y') }}
                        @if($leave['end_date'] && $leave['start_date'] != $leave['end_date'])
                            - {{ \Carbon\Carbon::parse($leave['end_date'])->format('M d, Y') }}
                        @endif
                    </td>
                    <td>{{ $leave['reason'] }}</td>
                    <td class="amount-cell">{{ $leave['days_in_month'] }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="2"><strong>Total Leave Days</strong></td>
                    <td class="amount-cell"><strong>{{ $data['leaves']['total_leave_days'] }}</strong></td>
                </tr>
                <tr>
                    <td colspan="2">Allowed Monthly Leaves</td>
                    <td class="amount-cell">{{ $data['leaves']['allowed_monthly_leaves'] }}</td>
                </tr>
                @if($data['leaves']['exceeded_leave_days'] > 0)
                <tr style="background: #ffe6e6;">
                    <td colspan="2"><strong>Exceeded Leave Days (Deductible)</strong></td>
                    <td class="amount-cell"><strong>{{ $data['leaves']['exceeded_leave_days'] }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Exceeded Leave Breakdown (Saturday half-day details) -->
        @if($data['leaves']['exceeded_leave_days'] > 0 && !empty($data['leaves']['exceeded_details']))
        <h4 style="color: #e74c3c; margin-top: 15px; margin-bottom: 10px;">Exceeded Leave Deduction Breakdown</h4>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Reason</th>
                    <th class="amount-cell">Deduction Days</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['leaves']['exceeded_details'] as $exceededLeave)
                <tr @if($exceededLeave['is_saturday']) style="background: #fff3cd;" @endif>
                    <td>{{ \Carbon\Carbon::parse($exceededLeave['date'])->format('M d, Y') }}</td>
                    <td>
                        {{ $exceededLeave['day_name'] }}
                        @if($exceededLeave['is_saturday'])
                            <span style="color: #856404; font-weight: bold;">(Half Day)</span>
                        @endif
                    </td>
                    <td>{{ $exceededLeave['reason'] }}</td>
                    <td class="amount-cell">
                        @if($exceededLeave['is_saturday'])
                            <span style="color: #856404; font-weight: bold;">0.5</span>
                        @else
                            1
                        @endif
                    </td>
                </tr>
                @endforeach
                <tr style="background: #f8f9fa; font-weight: bold;">
                    <td colspan="3"><strong>Total Deduction Days</strong></td>
                    <td class="amount-cell"><strong>{{ $data['leaves']['exceeded_leave_deduction_days'] }}</strong></td>
                </tr>
            </tbody>
        </table>
        @endif
        @endif

        <!-- Fine Details -->
        @if($data['fines']['fines_for_deduction'] > 0)
        <h3 style="color: #2c3e50; margin-top: 20px; margin-bottom: 10px;">Fines Deducted This Month</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reason</th>
                    <th class="amount-cell">Amount Deducted ({{ $data['contract']['currency'] }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['fines']['details'] as $fine)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($fine['date'])->format('M d, Y') }}</td>
                    <td>{{ $fine['reason'] }}</td>
                    <td class="amount-cell" style="color: #e74c3c; font-weight: bold;">
                        {{ number_format($fine['amount'], 2) }}
                    </td>
                </tr>
                @endforeach
                <tr style="background: #f8f9fa; font-weight: bold; border-top: 2px solid #333;">
                    <td colspan="2"><strong>Total Fines Deducted:</strong></td>
                    <td class="amount-cell" style="color: #e74c3c;"><strong>{{ number_format($data['fines']['fines_for_deduction'], 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Advance Salary Details -->
        @if($data['advances']['total_advance'] > 0)
        <h3 style="color: #2c3e50; margin-top: 20px; margin-bottom: 10px;">Advance Salary Details</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th class="amount-cell">Amount ({{ $data['contract']['currency'] }})</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['advances']['details'] as $advance)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($advance['created_at'])->format('M d, Y') }}</td>
                    <td>{{ $advance['reason'] }}</td>
                    <td>{{ ucfirst($advance['status']) }}</td>
                    <td class="amount-cell">{{ number_format($advance['amount'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="3"><strong>Total Advance Salary</strong></td>
                    <td class="amount-cell"><strong>{{ number_format($data['advances']['total_advance'], 2) }}</strong></td>
                </tr>
            </tbody>
        </table>
        @endif

        <!-- Warning if deductions exist -->
        @if($data['leaves']['exceeded_leave_days'] > 0 || $data['fines']['fines_for_deduction'] > 0)
        <div class="warning-box">
            @if($data['leaves']['exceeded_leave_days'] > 0)
            <p><strong>Extra Leave Days:</strong> You have taken {{ $data['leaves']['exceeded_leave_days'] }} extra leave day(s) beyond your monthly quota of {{ $data['leaves']['allowed_monthly_leaves'] }} days.</p>
            @if($data['leaves']['exceeded_leave_deduction_days'] != $data['leaves']['exceeded_leave_days'])
            <p style="color: #856404;"><strong>Note:</strong> Saturday leaves are counted as half-day (0.5) for deduction purposes. Total deduction days: {{ $data['leaves']['exceeded_leave_deduction_days'] }}</p>
            @endif
            @endif
            @if($data['fines']['fines_for_deduction'] > 0)
            <p><strong>Fines Deducted:</strong> Total fines deducted this month amount to {{ $data['contract']['currency'] }} {{ number_format($data['fines']['fines_for_deduction'], 2) }}.</p>
            @endif
        </div>
        @endif

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>Gross Salary:</span>
                <span>{{ $data['contract']['currency'] }} {{ number_format($data['summary']['gross_salary'], 2) }}</span>
            </div>

            @if($data['fines']['fines_for_deduction'] > 0)
            <div class="summary-row deduction">
                <span>Fines Deduction:</span>
                <span>- {{ $data['contract']['currency'] }} {{ number_format($data['fines']['fines_for_deduction'], 2) }}</span>
            </div>
            <div class="summary-row" style="background: #f0f0f0; padding: 8px; margin: 5px 0;">
                <span><strong>Salary After Fines:</strong></span>
                <span><strong>{{ $data['contract']['currency'] }} {{ number_format($data['summary']['salary_after_fines'], 2) }}</strong></span>
            </div>
            @endif

            @if($data['leaves']['leave_deduction'] > 0)
            <div class="summary-row deduction">
                <span>Extra Leave Deduction ({{ $data['leaves']['exceeded_leave_deduction_days'] }} days × {{ $data['contract']['currency'] }} {{ number_format($data['contract']['daily_salary'], 2) }}):</span>
                <span>- {{ $data['contract']['currency'] }} {{ number_format($data['leaves']['leave_deduction'], 2) }}</span>
            </div>
            <div class="summary-row" style="background: #f0f0f0; padding: 8px; margin: 5px 0;">
                <span><strong>Salary After Leave Deduction:</strong></span>
                <span><strong>{{ $data['contract']['currency'] }} {{ number_format($data['summary']['salary_after_leaves'], 2) }}</strong></span>
            </div>
            @endif

            @if($data['advances']['total_advance'] > 0)
            <div class="summary-row deduction">
                <span>Advance Salary Deduction:</span>
                <span>- {{ $data['contract']['currency'] }} {{ number_format($data['advances']['total_advance'], 2) }}</span>
            </div>
            @endif

            <div class="summary-row" style="border-top: 1px dashed #ccc; padding-top: 10px; margin-top: 10px;">
                <span>Total Deductions:</span>
                <span>- {{ $data['contract']['currency'] }} {{ number_format($data['summary']['total_deductions'], 2) }}</span>
            </div>

            <div class="summary-row total">
                <span>NET SALARY:</span>
                <span>{{ $data['contract']['currency'] }} {{ number_format($data['summary']['net_salary'], 2) }}</span>
            </div>
        </div>

        <!-- Motivational Quote -->
        <div class="quote-box">
             {{ \App\Helpers\MotivationalQuotes::random() }}
        </div>

        <!-- Website Link -->
        <div class="website-link">
             Visit us at: <a href="https://www.webpenter.com">www.webpenter.com</a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is a computer-generated invoice and does not require a signature.</p>
            <p>Generated on {{ now()->format('F d, Y \a\t h:i A') }}</p>
            <p>&copy; {{ now()->year }} {{ $companyName }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
