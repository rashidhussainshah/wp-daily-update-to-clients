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
            background: #e74c3c;
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
        .fine-details {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #e74c3c;
            border-radius: 5px;
        }
        .fine-details table {
            width: 100%;
            margin-top: 10px;
        }
        .fine-details td {
            padding: 8px;
        }
        .fine-details td:first-child {
            font-weight: bold;
            width: 40%;
        }
        .amount {
            background: #e74c3c;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-radius: 5px;
            margin: 20px 0;
        }
        .policy-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .policy-box p {
            margin: 10px 0;
            color: #856404;
        }
        .motivation-box {
            background: #d4edda;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            font-style: italic;
            color: #155724;
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
        <h2>⚠️ Fine Notice</h2>
        <p>Webpenter (Pakistan)</p>
    </div>

    <div class="content">
        <p>Dear {{ $user->name }},</p>

        <p>This is to inform you that a fine has been recorded against your account.</p>

        <div class="fine-details">
            <h3 style="margin-top: 0; color: #e74c3c;">Fine Details:</h3>
            <table>
                <tr>
                    <td>Date:</td>
                    <td>{{ \Carbon\Carbon::parse($fine->date)->format('F d, Y') }}</td>
                </tr>
                <tr>
                    <td>Reason:</td>
                    <td>{{ $fine->reason }}</td>
                </tr>
                <tr>
                    <td>Amount:</td>
                    <td><strong>PKR {{ number_format($fine->amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td>Status:</td>
                    <td>{{ $fine->paid > 0 ? 'Partially Paid' : 'Unpaid' }}</td>
                </tr>
                @if($fine->paid > 0)
                <tr>
                    <td>Paid:</td>
                    <td>PKR {{ number_format($fine->paid, 2) }}</td>
                </tr>
                <tr>
                    <td>Outstanding:</td>
                    <td style="color: #e74c3c;"><strong>PKR {{ number_format($fine->amount - $fine->paid, 2) }}</strong></td>
                </tr>
                @endif
            </table>
        </div>

        <div class="amount">
            Amount to be Deducted: PKR {{ number_format($fine->amount - $fine->paid, 2) }}
        </div>

        <div class="policy-box">
            <p><strong>📋 Company Policy Reminder:</strong></p>
            <p>Following company policies is <strong>compulsory for all of us</strong>. These policies are designed to maintain a professional, safe, and productive work environment for everyone.</p>
            <p>This fine will be deducted from your upcoming salary. If you have already paid this fine, please contact the HR department with proof of payment.</p>
        </div>

        <div class="motivation-box">
            <p><strong>💪 Remember:</strong></p>
            <p>"Excellence is not a skill, it's an attitude. By adhering to our policies, we create a culture of respect, accountability, and success together."</p>
            <p>We believe in your commitment to our shared values and look forward to your continued dedication to excellence.</p>
        </div>

        <p>If you have any questions or concerns regarding this fine, please contact the HR department immediately.</p>

        <p>Thank you for your understanding and cooperation.</p>

        <p>Best regards,<br>
        <strong>Webpenter HR Team</strong></p>
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
