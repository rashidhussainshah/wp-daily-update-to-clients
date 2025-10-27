<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Fine;
use Carbon\Carbon;

echo "=== Creating Test Fines for November 2025 ===" . PHP_EOL . PHP_EOL;

// Clear existing November fines
Fine::where('user_id', 1)
    ->whereYear('date', 2025)
    ->whereMonth('date', 11)
    ->delete();

// Create some fines with different statuses
$fines = [
    [
        'date' => '2025-11-05',
        'reason' => 'Late arrival to office',
        'amount' => 300.00,
        'paid' => 0, // Will be deducted
    ],
    [
        'date' => '2025-11-12',
        'reason' => 'Missing daily standup meeting',
        'amount' => 200.00,
        'paid' => 0, // Will be deducted
    ],
    [
        'date' => '2025-11-18',
        'reason' => 'Equipment damage (keyboard)',
        'amount' => 500.00,
        'paid' => 500.00, // Already paid - should NOT show
    ],
    [
        'date' => '2025-11-22',
        'reason' => 'Incomplete task submission',
        'amount' => 400.00,
        'paid' => 0, // Will be deducted
    ],
    [
        'date' => '2025-11-25',
        'reason' => 'Late project delivery',
        'amount' => 600.00,
        'paid' => 600.00, // Already paid - should NOT show
    ],
];

foreach ($fines as $fineData) {
    $fine = Fine::create([
        'user_id' => 1,
        'date' => $fineData['date'],
        'reason' => $fineData['reason'],
        'amount' => $fineData['amount'],
        'paid' => $fineData['paid'],
        'note' => 'Test fine for November 2025',
    ]);

    $status = $fineData['paid'] > 0 ? '(Already Paid)' : '(Unpaid - will be deducted)';
    echo "✓ Created: PKR {$fineData['amount']} - {$fineData['reason']} {$status}" . PHP_EOL;
}

echo PHP_EOL;
echo "Summary:" . PHP_EOL;
echo "  - Total fines: 5" . PHP_EOL;
echo "  - Already paid: 2 (PKR 1,100.00) - These should NOT appear in invoice" . PHP_EOL;
echo "  - To be deducted: 3 (PKR 900.00) - These SHOULD appear in invoice" . PHP_EOL;
echo PHP_EOL;
echo "Test with: php artisan salary:process --month=2025-11" . PHP_EOL;
