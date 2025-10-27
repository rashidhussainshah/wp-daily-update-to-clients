# Salary System - PDF & Email Setup Guide

## 📧 Email Sending Feature

The system can now send salary invoices via email with PDF attachments!

---

## 🚀 Quick Setup (3 Steps)

### Step 1: Install PDF Library

Choose **ONE** of these options:

#### Option A: DomPDF (Recommended - Easiest)
```bash
composer require barryvdh/laravel-dompdf
```

#### Option B: Snappy (Better quality, requires wkhtmltopdf binary)
```bash
composer require barryvdh/laravel-snappy
# Also install wkhtmltopdf on your system
```

---

### Step 2: Configure Email in `.env`

Add these settings to your `.env` file:

```env
# Gmail Example
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Webpenter HR"

# Or use Mailtrap for testing
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-mailtrap-username
MAIL_PASSWORD=your-mailtrap-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=hr@webpenter.com
MAIL_FROM_NAME="Webpenter HR"
```

**Gmail Users:** Create an App Password:
1. Go to Google Account → Security
2. Enable 2-Factor Authentication
3. Generate App Password
4. Use that password in `.env`

---

### Step 3: Add Email Method to Command

Update `ProcessMonthlySalaries.php` to add email sending:

```php
// Add at top of file
use Illuminate\Support\Facades\Mail;
use App\Mail\SalaryInvoiceMail;
use Barryvdh\DomPDF\Facade\Pdf; // If using DomPDF

// Add this method to the class
protected function sendInvoiceEmail(User $user, int $userId): void
{
    try {
        // Get salary data
        $data = $this->calculateSalary($userId);
        if (!$data) {
            return;
        }

        // Generate HTML
        $html = $this->invoiceService->generateInvoiceHtml($userId, $this->currentMonth);

        // Generate PDF (using DomPDF)
        $pdf = Pdf::loadHTML($html);
        $pdfPath = storage_path("app/invoices/{$this->currentMonth}/salary_invoice_{$userId}_{$this->currentMonth}.pdf");

        // Create directory if needed
        $dir = dirname($pdfPath);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        // Save PDF
        $pdf->save($pdfPath);

        // Send email
        Mail::to($user->email)->send(new SalaryInvoiceMail($data, $pdfPath));

        $this->info("✓ Invoice emailed to {$user->email}");
    } catch (Exception $e) {
        $this->error("Failed to send email: " . $e->getMessage());
    }
}

// Update finalizeSalary method to include email option
protected function finalizeSalary(User $user, array $data): void
{
    // Mark advances as deducted
    if ($data['advances']['total_advance'] > 0) {
        $this->markAdvancesAsDeducted($user->id);
    }

    // Generate invoice
    if ($this->confirm("Do you want to generate and save invoice for {$user->name}?", true)) {
        $this->generateAndSaveInvoice($user->id);
    }

    // Send email
    if ($this->confirm("Do you want to email the invoice to {$user->name}?", true)) {
        $this->sendInvoiceEmail($user, $user->id);
    }

    $this->info("✓ Salary processing completed for {$user->name}");
}
```

---

## 🧪 Testing

### Test Email Configuration
```bash
php artisan tinker
```

```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com')->subject('Test');
});
```

### Test Salary Email
```bash
php artisan salary:process --user_id=1 --month=2025-10
```

When prompted, answer "yes" to send email.

---

## 📁 File Locations

- **PDF Invoices:** `storage/app/invoices/2025-10/salary_invoice_1_2025-10.pdf`
- **Email Template:** `resources/views/emails/salary-invoice.blade.php`
- **Mail Class:** `app/Mail/SalaryInvoiceMail.php`

---

## ✅ Features

✓ Professional email template with salary summary
✓ PDF invoice attached automatically
✓ Both HTML and PDF saved for records
✓ Email includes:
  - Salary summary table
  - Net salary highlighted
  - Professional branding
  - PDF attachment

---

## 🎯 Usage

```bash
# Process salary and send emails
php artisan salary:process --month=2025-10

# The command will ask:
# 1. Do you want to mark fines as paid?
# 2. Do you want to approve advances?
# 3. Do you want to finalize salary?
# 4. Do you want to generate invoice? → Yes
# 5. Do you want to email the invoice? → Yes (NEW!)
```

---

## 🔧 Troubleshooting

### Email not sending?
1. Check `.env` mail settings
2. Test with `php artisan tinker` (see above)
3. Check `storage/logs/laravel.log` for errors
4. Try Mailtrap for testing first

### PDF not generating?
1. Install DomPDF: `composer require barryvdh/laravel-dompdf`
2. Clear config: `php artisan config:clear`
3. Check write permissions on `storage/app/invoices/`

### Gmail "Less secure apps" error?
Use App Password instead of regular password (see Step 2 above)

---

## 📧 Email Preview

**Subject:** Salary Invoice - October 2025 - John Doe

**Body:**
```
Dear John Doe,

Please find your salary invoice for October 2025 attached to this email.

Salary Summary:
Gross Salary: PKR 50,000.00
Extra Leave Days (2 days): - PKR 3,333.33
Fines: - PKR 1,000.00
Total Adjustments: - PKR 4,333.33

NET SALARY: PKR 45,666.67

A detailed PDF invoice is attached to this email for your records.
```

**Attachment:** salary_invoice_2025-10.pdf

---

## 🎨 Customization

### Change Email Template
Edit: `resources/views/emails/salary-invoice.blade.php`

### Change PDF Styling
Edit: `resources/views/invoices/salary.blade.php`

### Change Sender Name
Update `.env`: `MAIL_FROM_NAME="Your Company HR"`

---

All done! Your salary system now supports PDF generation and email delivery. 🎉
