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
            background: #3498db;
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
        .contact-details {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            border-radius: 5px;
        }
        .contact-details table {
            width: 100%;
            margin-top: 10px;
        }
        .contact-details td {
            padding: 8px;
            vertical-align: top;
        }
        .contact-details td:first-child {
            font-weight: bold;
            width: 30%;
        }
        .message-box {
            background: white;
            padding: 15px;
            margin: 15px 0;
            border-left: 4px solid #2ecc71;
            border-radius: 5px;
        }
        .message-box h3 {
            margin-top: 0;
            color: #2ecc71;
        }
        .message-content {
            background: #f9f9f9;
            padding: 12px;
            border-radius: 4px;
            white-space: pre-wrap;
            word-wrap: break-word;
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
        <h2>📧 New Contact Message</h2>
        <p>Webpenter (Pakistan)</p>
    </div>

    <div class="content">
        <p>You have received a new contact message from your website.</p>

        <div class="contact-details">
            <h3 style="margin-top: 0; color: #3498db;">Contact Information:</h3>
            <table>
                <tr>
                    <td>Name:</td>
                    <td><strong>{{ $contact->name }}</strong></td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></td>
                </tr>
                @if($contact->company)
                <tr>
                    <td>Company:</td>
                    <td>{{ $contact->company }}</td>
                </tr>
                @endif
                @if($contact->phone)
                <tr>
                    <td>Phone:</td>
                    <td><a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a></td>
                </tr>
                @endif
                <tr>
                    <td>Submitted at:</td>
                    <td>{{ $contact->created_at->format('F d, Y h:i A') }}</td>
                </tr>
            </table>
        </div>

        <div class="message-box">
            <h3>Message:</h3>
            <div class="message-content">{{ $contact->message }}</div>
        </div>

        <p><strong>Please respond to this inquiry as soon as possible.</strong></p>
    </div>

    <div class="website-link">
        <p>🌐 Visit us at: <a href="https://www.webpenter.com" target="_blank">www.webpenter.com</a></p>
    </div>

    <div class="footer">
        <p>&copy; {{ now()->year }} Webpenter. All rights reserved.</p>
        <p>This is an automated email notification.</p>
    </div>
</body>
</html>
