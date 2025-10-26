<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\AdvanceSalary;
use Carbon\Carbon;

echo "=== CREATING TEST ADVANCE SALARY ===\n\n";

$userId = 1;
$month = '2025-10';

// Delete existing advances for this month
AdvanceSalary::where('user_id', $userId)
    ->where('month', $month)
    ->delete();

echo "Deleted existing advances for user {$userId}, month {$month}\n";

// Create new test advance
$advance = AdvanceSalary::create([
    'user_id' => $userId,
    'amount' => 5000.00,
    'month' => $month,
    'reason' => 'Medical emergency expenses',
    'status' => 'approved',
    'request_date' => Carbon::parse('2025-10-15'),
    'approved_date' => Carbon::parse('2025-10-16'),
    'approved_by' => 1,
]);

echo "✅ Created test advance salary:\n";
echo "   Amount: PKR 5,000.00\n";
echo "   Reason: Medical emergency expenses\n";
echo "   Status: Approved\n";
echo "   Request Date: 2025-10-15\n";
echo "   Approved Date: 2025-10-16\n";

echo "\n=== DONE ===\n";
