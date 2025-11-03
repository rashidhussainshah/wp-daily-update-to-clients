<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .logo-container {
            text-align: center;
            padding: 10px 0 15px 0;
        }
        .logo {
            max-width: 120px;
            max-height: 50px;
            height: auto;
            width: auto;
        }
        .header {
            background: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }
        .content {
            background: #f4f4f4;
            padding: 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .summary-table {
            width: 100%;
            margin: 15px 0;
        }
        .summary-table td {
            padding: 8px;
        }
        .summary-table td:first-child {
            font-weight: bold;
        }
        .total {
            background: #27ae60;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            margin: 20px 0;
        }
        .quote-box {
            background: #e8f5e9;
            border-left: 4px solid #27ae60;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
            color: #2c3e50;
            border-radius: 5px;
        }
        .website-link {
            text-align: center;
            margin: 20px 0;
        }
        .website-link a {
            color: #2c3e50;
            font-weight: bold;
            text-decoration: none;
            font-size: 16px;
        }
        .website-link a:hover {
            color: #27ae60;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
    </style>
</head>
<body>
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

    <div class="header">
        <h2>Salary Invoice - {{ \Carbon\Carbon::createFromFormat('Y-m', $invoiceData['month'])->format('F Y') }}</h2>
        <p>Webpenter (Pakistan)</p>
    </div>

    <div class="content">
        <p>Dear {{ $invoiceData['user']['name'] }},</p>

        <p>Please find your salary invoice for <strong>{{ \Carbon\Carbon::createFromFormat('Y-m', $invoiceData['month'])->format('F Y') }}</strong> attached to this email.</p>

        <h3>Salary Summary:</h3>

        <table class="summary-table">
            <tr>
                <td>Gross Salary:</td>
                <td>{{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['summary']['gross_salary'], 2) }}</td>
            </tr>

            @if($invoiceData['fines']['fines_for_deduction'] > 0)
            <tr>
                <td>Fines:</td>
                <td style="color: #e74c3c;">- {{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['fines']['fines_for_deduction'], 2) }}</td>
            </tr>
            @endif

            <tr style="border-top: 2px solid #333;">
                <td style="font-weight: bold;">Net Salary:</td>
                <td style="font-weight: bold;">{{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['summary']['net_salary'], 2) }}</td>
            </tr>
        </table>

        <div class="total">
            NET SALARY: {{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['summary']['net_salary'], 2) }}
        </div>

        @if($invoiceData['leaves']['exceeded_leave_days'] > 0 || ($invoiceData['advances']['total_advance'] ?? 0) > 0)
        <h3 style="margin-top: 20px;">Additional Deductions:</h3>
        <table class="summary-table">
            @if($invoiceData['leaves']['exceeded_leave_days'] > 0)
            <tr>
                <td>Extra Leave Days ({{ $invoiceData['leaves']['exceeded_leave_days'] }} days):</td>
                <td style="color: #e74c3c;">- {{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['leaves']['leave_deduction'], 2) }}</td>
            </tr>
            @endif

            @if($invoiceData['advances']['total_advance'] > 0)
            <tr>
                <td>Advance Salary:</td>
                <td style="color: #e74c3c;">- {{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['advances']['total_advance'], 2) }}</td>
            </tr>
            @endif

            <tr style="border-top: 2px solid #333; background: #f0f0f0;">
                <td style="font-weight: bold;">Final Payable Amount:</td>
                <td style="font-weight: bold; color: #27ae60;">{{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['summary']['final_payable'], 2) }}</td>
            </tr>
        </table>
        @endif

        @if(($invoiceData['advances']['total_advance'] ?? 0) > 0)
        <h3 style="color: #2c3e50; margin-top: 20px;">Advance Salary Details:</h3>
        <table class="summary-table" style="background: #fff;">
            <thead style="background: #8e44ad; color: white;">
                <tr>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Reason</th>
                    <th style="padding: 10px;">Status</th>
                    <th style="padding: 10px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceData['advances']['details'] as $advance)
                <tr>
                    <td style="padding: 8px;">{{ \Carbon\Carbon::parse($advance['created_at'])->format('M d, Y') }}</td>
                    <td style="padding: 8px;">{{ $advance['reason'] }}</td>
                    <td style="padding: 8px;">{{ ucfirst($advance['status']) }}</td>
                    <td style="padding: 8px; text-align: right;">{{ $invoiceData['contract']['currency'] }} {{ number_format($advance['amount'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        @if($invoiceData['fines']['fines_for_deduction'] > 0)
        <h3 style="color: #2c3e50; margin-top: 20px;">Fines Deducted This Month:</h3>
        <table class="summary-table" style="background: #fff;">
            <thead style="background: #e74c3c; color: white;">
                <tr>
                    <th style="padding: 10px;">Date</th>
                    <th style="padding: 10px;">Reason</th>
                    <th style="padding: 10px; text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoiceData['fines']['details'] as $fine)
                <tr>
                    <td style="padding: 8px;">{{ \Carbon\Carbon::parse($fine['date'])->format('M d, Y') }}</td>
                    <td style="padding: 8px;">{{ $fine['reason'] }}</td>
                    <td style="padding: 8px; text-align: right; color: #e74c3c; font-weight: bold;">
                        {{ $invoiceData['contract']['currency'] }} {{ number_format($fine['amount'], 2) }}
                    </td>
                </tr>
                @endforeach
                <tr style="background: #f8f9fa; font-weight: bold; border-top: 2px solid #333;">
                    <td colspan="2" style="padding: 10px;">Total Fines Deducted:</td>
                    <td style="padding: 10px; text-align: right; color: #e74c3c;">{{ $invoiceData['contract']['currency'] }} {{ number_format($invoiceData['fines']['fines_for_deduction'], 2) }}</td>
                </tr>
            </tbody>
        </table>
        @endif

        <p>A detailed PDF invoice is attached to this email for your records.</p>

        @if($invoiceData['leaves']['exceeded_leave_days'] > 0 || $invoiceData['fines']['fines_for_deduction'] > 0 || ($invoiceData['advances']['total_advance'] ?? 0) > 0)
        <p style="font-size: 13px; color: #666;">
            <strong>Note:</strong> Adjustments have been made for
            @php
                $adjustments = [];
                if ($invoiceData['leaves']['exceeded_leave_days'] > 0) $adjustments[] = 'extra leaves';
                if ($invoiceData['fines']['fines_for_deduction'] > 0) $adjustments[] = 'fines';
                if (($invoiceData['advances']['total_advance'] ?? 0) > 0) $adjustments[] = 'advance salary';

                if (count($adjustments) == 1) {
                    echo $adjustments[0];
                } elseif (count($adjustments) == 2) {
                    echo $adjustments[0] . ' and ' . $adjustments[1];
                } else {
                    echo implode(', ', array_slice($adjustments, 0, -1)) . ', and ' . end($adjustments);
                }
            @endphp.
        </p>
        @endif

        <p>If you have any questions regarding this invoice, please contact the HR department.</p>

        <p>Best regards,<br>
        <strong>Webpenter HR Team</strong></p>
    </div>

    <div class="quote-box">
        💡 {{ \App\Helpers\MotivationalQuotes::random() }}
    </div>

    <div class="website-link">
        <p>🌐 Visit us at: <a href="https://www.webpenter.com" target="_blank">www.webpenter.com</a></p>
    </div>

    <div class="footer">
        <p>&copy; {{ now()->year }} Webpenter. All rights reserved.</p>
        <p>This is an automated email. Please do not reply.</p>
    </div>
</body>
</html>
