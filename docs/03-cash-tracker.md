# Cash Tracker

**URL:** `/admin/financials/cash`  
**Who can access:** Rashid & Zahid only

---

## Adding a cash entry (5 seconds)

1. Type the amount
2. Pick **Out** (spent) or **In** (received)
3. Pick category
4. Optional: short description
5. Click **Save Entry**

**Example — buying cold drinks:**
```
Amount:      500
Type:        ▼ Out (Spent)
Category:    Cold Drinks / Refreshments
Description: 5 cold drinks for team
Date:        2026-06-09
→ Save
```

**Example — weekly lunch:**
```
Amount:      3500
Type:        ▼ Out
Category:    Weekly Lunch
Description: Friday lunch - 7 people
Date:        2026-06-06
→ Save
```

**Example — cash received (someone gives you cash):**
```
Amount:      10000
Type:        ▲ In (Received)
Category:    Other
Description: Cash from Rashid for office expenses
Date:        2026-06-01
→ Save
```

---

## Categories

| Category | Use for |
|---|---|
| Weekly Lunch | Friday team lunch, food orders |
| Cold Drinks / Refreshments | Drinks for team or guests |
| Guest Expenses | Client visits, meeting refreshments |
| Office Supplies / Cleaning | Cleaning items, stationery, small tools |
| Travel / Petty Cash | Rickshaw, fuel, small travel |
| Other | Anything else |

---

## What you see

```
Cash Balance — June 2026
Rs 2,500                    ← In minus Out

▲ In: Rs 10,000   ▼ Out: Rs 7,500

Spending by Category:
  Weekly Lunch          Rs 3,500   47%
  Cold Drinks           Rs 2,000   27%
  Guest Expenses        Rs 1,500   20%
  Office Supplies       Rs   500    7%
```

Transaction log shows every entry with date and running total.

---

## Recording bank balance snapshot

Same page — bottom section. Use this when you want to record the exact balance on a specific date (not just month-end).

**Example — June 8 snapshot:**
```
Date:              2026-06-08
Rashid Al Habib:   1850000
Rashid Meezan:     320000
Zahid Allied:      420000
Cash (Office):     3500
Note:              After paying salaries
→ Save Snapshot
```

Appears immediately in the history table below the form.

---

## Switching months

Use the **Month** picker at the top to view any previous month.

---

## Deleting a wrong entry

Click the red trash icon next to any entry. Asks for confirmation before deleting.

---

## Tips

- Add cash entries **the same day** — easier than remembering later
- Use **Description** for guest names: `"Client Ahmed — tea & drinks"`
- Lunch paid from cash? Enter here. Lunch paid by bank transfer? Add in P&L expenses instead
