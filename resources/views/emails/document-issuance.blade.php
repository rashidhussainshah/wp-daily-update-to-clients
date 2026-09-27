<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    body { font-family: Arial, sans-serif; line-height: 1.6; color: #1e293b; max-width: 600px; margin: 0 auto; padding: 20px; }
    .header { background: #16a34a; color: #ffffff; padding: 18px 20px; text-align: center; border-radius: 6px; }
    .content { background: #f8faf9; padding: 20px; margin: 20px 0; border-radius: 6px; }
    .footer { text-align: center; font-size: 12px; color: #64748b; margin-top: 24px; padding-top: 16px; border-top: 1px solid #e2e8f0; }
</style>
</head>
<body>
    <div class="header"><h2 style="margin:0;">WebPenter</h2></div>
    <div class="content">
        <p>Dear {{ $issuance->recipient_name }},</p>
        <p>Please find attached your <strong>{{ $issuance->template->name }}</strong>, issued by WebPenter HR.</p>
        <p>Reference: {{ $issuance->verify_code }}</p>
        <p>If you have any questions about this document, please contact the HR department.</p>
        <p>Best regards,<br>WebPenter HR Team</p>
    </div>
    <div class="footer">
        <p>&copy; {{ now()->year }} WebPenter. All rights reserved.</p>
    </div>
</body>
</html>
