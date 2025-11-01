# Net Salary Calculation Update

## Change Summary
Updated the salary calculation and display to show **Net Salary** as **Gross Salary - Fines Only**, with advances and leaves shown separately as additional deductions leading to **Final Payable Amount**.

## Old Calculation Structure
```
Gross Salary:        50,000.00
Leaves:              -3,333.34
Fines:               -2,900.00
Advances:            -12,500.00
Total Adjustments:   -18,733.34
─────────────────────────────
NET SALARY:          31,266.66
```

**Problem:** Net Salary included all deductions (leaves, fines, advances), which was confusing.

## New Calculation Structure

### Example 1: With Fines and Advances
```
Gross Salary:        PKR 50,000.00
Fines:               - PKR 2,900.00
─────────────────────────────────────
NET SALARY:          PKR 47,100.00

Advance Salary:      - PKR 12,500.00
─────────────────────────────────────
FINAL PAYABLE:       PKR 34,600.00
```

### Example 2: With Leaves Only (No Fines)
```
Gross Salary:        PKR 50,000.00
─────────────────────────────────────
NET SALARY:          PKR 50,000.00

Extra Leave Days (2 days): - PKR 3,333.34
─────────────────────────────────────
FINAL PAYABLE:       PKR 46,666.66
```

### Example 3: With All Deductions
```
Gross Salary:        PKR 50,000.00
Fines:               - PKR 1,300.00
─────────────────────────────────────
NET SALARY:          PKR 48,700.00

Extra Leave Days (2 days): - PKR 3,333.34
Advance Salary:            - PKR 5,500.00
─────────────────────────────────────
FINAL PAYABLE:       PKR 39,866.66
```

## Files Modified

### 1. SalaryCalculationService.php (Lines 135-184)

**Changes:**
- Added `net_salary` calculation: `Gross - Fines only`
- Added `final_payable` calculation: `Net - Leaves - Advances`
- Updated return array structure

**New Calculation Logic:**
```php
// Calculate net salary (Gross - Fines only)
$netSalary = round($contract->monthly_salary - $unpaidFines, 2);

// Calculate total deductions (for reference)
$totalDeductions = $leaveDeduction + $unpaidFines + $totalAdvance;

// Calculate final payable amount (Net Salary - Leaves - Advances)
$finalPayable = round($netSalary - $leaveDeduction - $totalAdvance, 2);
```

**Return Structure:**
```php
'summary' => [
    'gross_salary' => $contract->monthly_salary,
    'net_salary' => $netSalary,           // Gross - Fines only
    'total_deductions' => $totalDeductions,
    'final_payable' => $finalPayable,     // Net - Leaves - Advances
]
```

### 2. ProcessMonthlySalaries.php - displayFinalSummary() (Lines 672-701)

**Changes:**
- Show Gross and Fines first
- Display **NET SALARY** after fines
- Show leaves and advances as additional deductions
- Display **FINAL PAYABLE** at the end

**Display Order:**
1. Gross Salary
2. Fines (if any)
3. **NET SALARY** (highlighted in green)
4. Extra Leave Days (if any)
5. Advance Salary (if any)
6. **FINAL PAYABLE** (highlighted in cyan, only if additional deductions exist)

### 3. salary-invoice.blade.php (Lines 118-165)

**Changes:**
- Restructured email template to match command display
- Shows Net Salary prominently after fines
- Separates additional deductions into their own section
- Adds "Final Payable Amount" when additional deductions exist

**Email Structure:**
```html
<h3>Salary Summary:</h3>
- Gross Salary
- Fines (if any)
- Net Salary (bold)

[NET SALARY Box - Large Green]

<h3>Additional Deductions:</h3> (only if leaves or advances exist)
- Extra Leave Days (if any)
- Advance Salary (if any)
- Final Payable Amount (bold, green)
```

## Salary Invoice Log

The `salary_invoice_logs` table now stores:
- `gross_salary`: Monthly salary from contract
- `net_salary`: Gross - Fines (NEW meaning)
- `total_deductions`: Leaves + Fines + Advances (for reference)

## Benefits

### 1. Clarity
- **Net Salary** is now a clear, meaningful number (salary after fines)
- Employees can see their base earned salary before other deductions

### 2. Transparency
- Fines are shown as direct impact on net salary
- Advances and leaves are clearly shown as additional deductions
- Final payable is the actual amount to be paid

### 3. Consistency
- Command output, email, and PDF all use the same structure
- Easy to understand salary breakdown

## Test Results

### November 2025 (Fines + Advances)
```
Gross: 50,000.00
Fines: -2,900.00
─────────────────
Net: 47,100.00

Advances: -12,500.00
─────────────────────
Final: 34,600.00
```
✅ Correct

### October 2025 (Leaves only)
```
Gross: 50,000.00
─────────────────
Net: 50,000.00

Leaves: -3,333.34
─────────────────
Final: 46,666.66
```
✅ Correct

### October 2025 (Fines + Advances + Leaves)
```
Gross: 50,000.00
Fines: -1,300.00
─────────────────
Net: 48,700.00

Leaves: -3,333.34
Advances: -5,500.00
─────────────────────
Final: 39,866.66
```
✅ Correct

## Mathematical Verification

**Formula:**
```
Net Salary = Gross Salary - Fines
Final Payable = Net Salary - Leaves - Advances
```

**Or combined:**
```
Final Payable = Gross - Fines - Leaves - Advances
```

**Example:**
```
50,000 - 2,900 = 47,100 (Net)
47,100 - 12,500 = 34,600 (Final)

Or: 50,000 - 2,900 - 12,500 = 34,600 ✅
```

---
**Last Updated:** November 1, 2025
**Files Modified:** 3
**Backward Compatible:** Yes (all existing data still works)
