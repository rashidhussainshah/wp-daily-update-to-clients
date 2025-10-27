# 🧪 Tinker Testing Guide

All commands can be run in Laravel Tinker for quick testing.

---

## 🚀 Start Tinker

```bash
php artisan tinker
```

---

## 1️⃣ TEST FINE NOTIFICATION EMAIL

### Create a test fine (auto-sends email):

```php
$user = App\Models\User::find(1);
$fine = App\Models\Fine::create([
    'user_id' => 1,
    'date' => now(),
    'reason' => 'Late to meeting - Policy violation',
    'amount' => 500.00,
    'paid' => 0,
    'note' => 'Test fine created via tinker'
]);
```

**Result**: Email sent automatically to employee with:
- ✅ Fine details
- ✅ Policy reminder: "Following company policies is compulsory for all of us"
- ✅ Motivational message
- ✅ Company logo

**Check**: Mailtrap inbox for "Important: Fine Notice"

---

## 2️⃣ TEST ADVANCE SALARY

### Create a test advance:

```php
$advance = App\Models\AdvanceSalary::create([
    'user_id' => 1,
    'amount' => 5000.00,
    'month' => '2025-10',
    'reason' => 'Medical emergency expenses',
    'status' => 'approved',
    'request_date' => now(),
    'approved_date' => now(),
    'approved_by' => 1,
]);
```

### View advance:

```php
$advance = App\Models\AdvanceSalary::where('user_id', 1)
    ->where('month', '2025-10')
    ->first();
echo "Amount: PKR {$advance->amount}\n";
echo "Reason: {$advance->reason}\n";
echo "Status: {$advance->status}\n";
```

---

## 3️⃣ TEST SALARY CALCULATION

### Calculate salary with all deductions:

```php
$service = new App\Services\SalaryCalculationService();
$data = $service->calculateMonthlySalary(1, '2025-10');

echo "Gross Salary: PKR " . number_format($data['summary']['gross_salary'], 2) . "\n";
echo "Leave Deduction: PKR " . number_format($data['leaves']['leave_deduction'], 2) . "\n";
echo "Fines: PKR " . number_format($data['fines']['unpaid_fines'], 2) . "\n";
echo "Advance: PKR " . number_format($data['advances']['total_advance'], 2) . "\n";
echo "Total Deductions: PKR " . number_format($data['summary']['total_deductions'], 2) . "\n";
echo "NET SALARY: PKR " . number_format($data['summary']['net_salary'], 2) . "\n";
```

---

## 4️⃣ TEST SALARY EMAIL WITH PDF

### Send salary email manually:

```php
$user = App\Models\User::find(1);
$service = new App\Services\SalaryCalculationService();
$invoiceService = new App\Services\SalaryInvoiceService($service);

// Calculate salary
$data = $service->calculateMonthlySalary(1, '2025-10');

// Generate HTML
$html = $invoiceService->generateInvoiceHtml(1, '2025-10');

// Generate PDF
$pdf = Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
$pdfPath = storage_path('app/invoices/2025-10/salary_invoice_1_2025-10.pdf');

// Create directory
$dir = dirname($pdfPath);
if (!file_exists($dir)) mkdir($dir, 0755, true);

// Save PDF
$pdf->save($pdfPath);

// Send email
Mail::to($user->email)->send(new App\Mail\SalaryInvoiceMail($data, $pdfPath));

echo "✅ Email sent to {$user->email}\n";
echo "✅ PDF saved to {$pdfPath}\n";
```

---

## 5️⃣ VIEW ADVANCE DETAILS IN CALCULATION

### Check if advance shows in salary data:

```php
$service = new App\Services\SalaryCalculationService();
$data = $service->calculateMonthlySalary(1, '2025-10');

if (!empty($data['advances']['details'])) {
    echo "Advance Salary Details:\n";
    foreach ($data['advances']['details'] as $advance) {
        echo "  - Date: {$advance['request_date']}\n";
        echo "    Amount: PKR {$advance['amount']}\n";
        echo "    Reason: {$advance['reason']}\n";
        echo "    Status: {$advance['status']}\n";
    }
    echo "\nTotal Advance: PKR {$data['advances']['total_advance']}\n";
} else {
    echo "No advance salary found\n";
}
```

---

## 6️⃣ VIEW FINE DETAILS

### Check unpaid fines:

```php
$fines = App\Models\Fine::where('user_id', 1)
    ->whereColumn('paid', '<', 'amount')
    ->get();

foreach ($fines as $fine) {
    $unpaid = $fine->amount - $fine->paid;
    echo "Date: {$fine->date}\n";
    echo "Reason: {$fine->reason}\n";
    echo "Amount: PKR {$fine->amount}\n";
    echo "Paid: PKR {$fine->paid}\n";
    echo "Unpaid: PKR {$unpaid}\n";
    echo "---\n";
}
```

---

## 7️⃣ TEST EMAIL CONFIGURATION

### Quick email test:

```php
Mail::raw('Test email from tinker', function($message) {
    $message->to('test@example.com')->subject('Test');
});

echo "✅ Test email sent to Mailtrap\n";
```

---

## 8️⃣ VIEW USER DATA

### Get complete user info:

```php
$user = App\Models\User::with(['contract', 'leaves', 'fines', 'advances'])->find(1);

echo "Name: {$user->name}\n";
echo "Email: {$user->email}\n";
echo "Monthly Salary: PKR {$user->contract->monthly_salary}\n";
echo "Leaves: {$user->leaves->count()}\n";
echo "Fines: {$user->fines->count()}\n";
echo "Advances: {$user->advances->count()}\n";
```

---

## 9️⃣ CLEAN UP TEST DATA

### Delete test fine:

```php
$fine = App\Models\Fine::latest()->first();
echo "Deleting fine ID: {$fine->id}\n";
$fine->delete();
```

### Delete test advance:

```php
$advance = App\Models\AdvanceSalary::latest()->first();
echo "Deleting advance ID: {$advance->id}\n";
$advance->delete();
```

### Delete all test fines for user:

```php
App\Models\Fine::where('user_id', 1)
    ->where('reason', 'LIKE', '%test%')
    ->delete();
echo "✅ Test fines deleted\n";
```

---

## 🔟 TEST LOGO DISPLAY

### Check if logo exists:

```php
$logoPath = public_path('images/logo/webpenter_logo.png');
echo "Logo exists: " . (file_exists($logoPath) ? 'YES' : 'NO') . "\n";
if (file_exists($logoPath)) {
    echo "File size: " . number_format(filesize($logoPath)) . " bytes\n";
}
```

---

## 1️⃣1️⃣ TEST MOTIVATIONAL QUOTES

### Get random quote:

```php
echo App\Helpers\MotivationalQuotes::random() . "\n";
```

### Get all quotes:

```php
$quotes = App\Helpers\MotivationalQuotes::all();
echo "Total quotes: " . count($quotes) . "\n";
echo "First quote: {$quotes[0]}\n";
```

---

## 1️⃣2️⃣ COMPLETE WORKFLOW TEST

### Test entire salary processing workflow:

```php
// 1. Create advance
$advance = App\Models\AdvanceSalary::create([
    'user_id' => 1,
    'amount' => 3000.00,
    'month' => '2025-10',
    'reason' => 'Personal emergency',
    'status' => 'approved',
    'request_date' => now(),
    'approved_date' => now(),
    'approved_by' => 1,
]);
echo "✅ Advance created: PKR 3,000\n";

// 2. Create fine (auto-sends email)
$fine = App\Models\Fine::create([
    'user_id' => 1,
    'date' => now(),
    'reason' => 'Late arrival',
    'amount' => 200.00,
    'paid' => 0,
    'note' => 'Test fine'
]);
echo "✅ Fine created: PKR 200 (Email sent automatically)\n";

// 3. Calculate salary
$service = new App\Services\SalaryCalculationService();
$data = $service->calculateMonthlySalary(1, '2025-10');
echo "\n📊 Salary Calculation:\n";
echo "Gross: PKR " . number_format($data['summary']['gross_salary'], 2) . "\n";
echo "Deductions: PKR " . number_format($data['summary']['total_deductions'], 2) . "\n";
echo "NET: PKR " . number_format($data['summary']['net_salary'], 2) . "\n";

// 4. Send salary email
$user = App\Models\User::find(1);
$invoiceService = new App\Services\SalaryInvoiceService($service);
$html = $invoiceService->generateInvoiceHtml(1, '2025-10');
$pdf = Barryvdh\DomPDF\Facade\Pdf::loadHTML($html)->setPaper('a4');
$pdfPath = storage_path('app/invoices/2025-10/salary_invoice_1_2025-10.pdf');
if (!file_exists(dirname($pdfPath))) mkdir(dirname($pdfPath), 0755, true);
$pdf->save($pdfPath);
Mail::to($user->email)->send(new App\Mail\SalaryInvoiceMail($data, $pdfPath));
echo "\n✅ Salary email sent with:\n";
echo "   - Advance details table\n";
echo "   - Fine details table\n";
echo "   - PDF attachment\n";
echo "   - Motivational quote\n";

echo "\n📬 Check Mailtrap for 2 emails:\n";
echo "   1. Fine Notice\n";
echo "   2. Salary Invoice\n";
```

---

## ⚡ QUICK TESTS

### One-liner: Create fine and check email sent:

```php
App\Models\Fine::create(['user_id'=>1,'date'=>now(),'reason'=>'Test','amount'=>100,'paid'=>0,'note'=>'Test']); echo "✅ Fine created, email sent!\n";
```

### One-liner: Send test salary email:

```php
$u=App\Models\User::find(1);$s=new App\Services\SalaryCalculationService();$d=$s->calculateMonthlySalary(1,'2025-10');Mail::to($u->email)->send(new App\Mail\SalaryInvoiceMail($d));echo"✅ Sent!\n";
```

---

## 📋 CHECKLIST FOR TESTING

Use these tinker commands to verify:

- [ ] **Fine Email**: Create fine → Check Mailtrap
- [ ] **Advance Table**: Create advance → View in salary email
- [ ] **Logo**: Check logo exists and displays
- [ ] **Quotes**: Get random quote
- [ ] **PDF**: Generate PDF with all features
- [ ] **Conditional Display**: Verify fines only show if unpaid
- [ ] **Email Config**: Test email delivery

---

## 💡 TIPS

1. **Copy-paste** commands directly into tinker
2. **Check Mailtrap** after each email command
3. **Clean up** test data when done
4. **Use arrow keys** to repeat previous commands
5. **Exit tinker**: `exit` or `Ctrl+C`

---

## 🎯 PRODUCTION USAGE

For production, use artisan commands:

```bash
# Process salaries
php artisan salary:process --month=2025-10

# View summary
php artisan salary:summary --user_id=1 --month=2025-10
```

Fines will auto-send emails when created via Voyager admin panel!

---

All testing can be done with tinker - no PHP files needed! 🚀
