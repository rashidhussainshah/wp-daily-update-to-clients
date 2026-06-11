# Charts & Bank Balances

**URL:** `/admin/financials/charts`  
**Who can access:** Rashid & Zahid only

---

## Charts (fully automatic)

All charts build themselves from existing data — nothing to enter.

| Chart | What it tells you |
|---|---|
| **Monthly P&L bar** | Income vs outgoings — side by side for 6 months |
| **Net saving trend** | Green dot = profit month, red = loss |
| **Bank balance trend** | All 3 accounts plotted over time |
| **Outgoing donut** | This month's breakdown: partners / BD / salaries / expenses |
| **BD performance** | Ayub & Ali — target line vs achieved bars |

---

## Recording bank balances

Do this **once at month-end** — takes 30 seconds.

**Form at the top of the charts page:**
```
Month:              2026-06
Rashid Al Habib:    1850000    ← type current balance
Rashid Meezan:      320000
Zahid Allied:       420000
Note:               After June salaries paid
→ Click "Save Balances"
```

That's it. The chart updates automatically.

### What you see after saving
- Each account card shows current balance + change vs last month
  ```
  Rashid — Bank Al Habib
  Rs 18,50,000
  ▼ Rs 1,50,000 vs last month   ← you paid salaries
  ```
- Combined total across all accounts
- Trend line chart for last 6 months

---

## Balance history table

Bottom of the page — shows all 3 accounts side by side per month:

```
Month    | Al Habib      | Meezan      | Allied      | Total
Jun 2026 | Rs 18,50,000  | Rs 3,20,000 | Rs 4,20,000 | Rs 25,90,000
May 2026 | Rs 20,00,000  | —           | Rs 4,00,000 | Rs 24,00,000
Apr 2026 | —             | —           | —           | —
```

> **Tip:** Dashes (—) mean no snapshot was recorded that month. Record at least once a month.

---

## Domains expiry

Cards at top of page show domains expiring soon:

| Colour | Meaning |
|---|---|
| Green | Safe (30+ days) |
| Orange | Renew soon (15–30 days) |
| Red | Renew now (under 15 days) |

**To renew a domain:**  
Go to P&L page → Domains panel → click **Renew** next to the domain → enter new expiry date + cost.  
The renewal cost automatically appears in that month's expenses.

---

## Your domains

| Domain | Project |
|---|---|
| webpenter.com | Webpenter |
| bookhere.tech | BookHere |
| houzilo.com | Houzilo |
| scriptandtools.com | ScriptAndTools |

> Update the actual expiry dates at `/admin/financials` → Domains → Add Domain.
