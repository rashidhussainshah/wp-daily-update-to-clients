# P&L Dashboard

**URL:** `/admin/financials`  
**Who can access:** Rashid & Zahid only

---

## What it shows

Everything pulls automatically — no manual entry needed for this page.

| Section | Where data comes from |
|---|---|
| Income | `incomes` table (you enter once when payment received) |
| Partner share (37.5%) | `user_payments` — auto when partner submits & Rashid approves |
| BD commissions | `user_payments` — auto-created when partner payment approved |
| Fixed salaries | `salary_invoice_logs` — auto after `php artisan salary:process` |
| Expenses | You enter once a month (see below) |

---

## Monthly routine (5 minutes total)

### Step 1 — Auto-fill fixed expenses
Click **"Auto-fill Fixed"** button on the right panel.  
This adds: Rent Rs 16,500 · Claude Rs 12,000 · Moon Rs 6,000 · Prime Rs 1,900

### Step 2 — Add variable expenses
Fill in 4 fields and click Add:

| Category | Example amount |
|---|---|
| Electricity Bill | Rs 8,400 |
| Water Bill | Rs 1,200 |
| Lunch / Food | Rs 14,000 |
| Guest Expenses | Rs 3,500 |

**Example entry:**
```
Month:    2026-06
Category: Electricity Bill
Amount:   8400
Paid From: Rashid — Bank Al Habib
Note:     June bill
```

### Step 3 — Set BD targets (optional, once a month)
Bottom of right panel:
```
User:   Ayub Khokhar
Month:  2026-06
Target: 1500    ← USD
```

---

## Period filters

| Button | Shows |
|---|---|
| This Month | Current month only |
| Last Month | Previous month |
| Last 6 Months | Rolling 6-month total |
| This Year | Jan to now |
| Custom | Pick any date range |

---

## Reading the numbers

```
INCOME
  Fiverr          $820  → Rs 228,800
  Upwork          $510  → Rs 142,800
  ──────────────────────────────────
  TOTAL           $1,330  Rs 371,600

OUTGOINGS
  Partner share (37.5%)      Rs 139,350   ← auto
  BD commissions             Rs  18,000   ← auto
  Fixed salaries             Rs  85,000   ← auto (net after fines)
    ✓ Fines deducted (2)    − Rs   4,000  ← saves you money
  Office Rent                Rs  16,500
  Claude accounts            Rs  12,000
  ──────────────────────────────────
  TOTAL OUT                  Rs 270,850

NET SAVING                   Rs 100,750  ✓ Profit
```

> **Tip:** If net saving is red, you're spending more than you earned that month.
