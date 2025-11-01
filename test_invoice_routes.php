<?php

require __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Testing Salary Invoice Routes\n";
echo str_repeat("=", 70) . "\n\n";

// Test route generation
try {
    $testPdfPath = 'invoices/2025-10/SAL-1-202510.pdf';
    $testHtmlPath = 'invoices/2025-10/SAL-1-202510.html';

    echo "1. Testing Route Generation:\n";
    echo str_repeat("-", 70) . "\n";

    $viewPdfUrl = route('salary-invoice.pdf', ['path' => $testPdfPath]);
    echo "View PDF URL:\n";
    echo "  {$viewPdfUrl}\n\n";

    $downloadPdfUrl = route('salary-invoice.pdf', ['path' => $testPdfPath, 'download' => 'true']);
    echo "Download PDF URL:\n";
    echo "  {$downloadPdfUrl}\n\n";

    $viewHtmlUrl = route('salary-invoice.html', ['path' => $testHtmlPath]);
    echo "View HTML URL:\n";
    echo "  {$viewHtmlUrl}\n\n";

    echo "\n2. Testing File Existence:\n";
    echo str_repeat("-", 70) . "\n";

    if (Storage::exists($testPdfPath)) {
        echo "✓ PDF file exists: {$testPdfPath}\n";
        $size = Storage::size($testPdfPath);
        echo "  File size: " . number_format($size / 1024, 2) . " KB\n";
    } else {
        echo "✗ PDF file NOT found: {$testPdfPath}\n";
    }

    if (Storage::exists($testHtmlPath)) {
        echo "✓ HTML file exists: {$testHtmlPath}\n";
        $size = Storage::size($testHtmlPath);
        echo "  File size: " . number_format($size / 1024, 2) . " KB\n";
    } else {
        echo "✗ HTML file NOT found: {$testHtmlPath}\n";
    }

    echo "\n3. Testing Database Records:\n";
    echo str_repeat("-", 70) . "\n";

    $logs = DB::table('salary_invoice_logs')
        ->select('id', 'user_id', 'month', 'invoice_number', 'pdf_path', 'invoice_path')
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();

    if ($logs->isEmpty()) {
        echo "No salary invoice logs found in database.\n";
    } else {
        echo "Recent Salary Invoice Logs:\n\n";
        foreach ($logs as $log) {
            echo "  Invoice #{$log->invoice_number}\n";
            echo "  User ID: {$log->user_id} | Month: {$log->month}\n";
            echo "  PDF Path: {$log->pdf_path}\n";
            echo "  HTML Path: {$log->invoice_path}\n";

            // Generate URLs for this log
            $pdfUrl = route('salary-invoice.pdf', ['path' => $log->pdf_path]);
            $htmlUrl = route('salary-invoice.html', ['path' => $log->invoice_path]);

            echo "  View PDF: {$pdfUrl}\n";
            echo "  View HTML: {$htmlUrl}\n";
            echo "\n";
        }
    }

    echo "\n4. Voyager Actions Registered:\n";
    echo str_repeat("-", 70) . "\n";

    $actions = [
        'ViewSalaryInvoicePdfAction',
        'DownloadSalaryInvoicePdfAction',
        'ViewSalaryInvoiceHtmlAction',
    ];

    foreach ($actions as $action) {
        $className = "App\\Actions\\{$action}";
        if (class_exists($className)) {
            echo "✓ {$action} exists\n";
        } else {
            echo "✗ {$action} NOT found\n";
        }
    }

    echo "\n" . str_repeat("=", 70) . "\n";
    echo "Testing Complete!\n\n";

    echo "To use the PDF viewer:\n";
    echo "1. Login to Voyager admin panel\n";
    echo "2. Navigate to Salary Invoice Logs\n";
    echo "3. You will see 3 buttons for each record:\n";
    echo "   - [View PDF] (Green) - Opens PDF in browser\n";
    echo "   - [Download PDF] (Blue) - Downloads PDF file\n";
    echo "   - [View HTML] (Cyan) - Opens HTML invoice\n";

} catch (Exception $e) {
    echo "\nERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
