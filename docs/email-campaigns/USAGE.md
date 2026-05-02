# Email Campaigns — Usage Guide

Marketing email module for sending bulk campaigns to 66k+ Homey theme clients.

---

## Overview

| What | Detail |
|---|---|
| Client list | 66,107 Homey theme users (role: `homey_client`) |
| Admin UI | `/admin/email-campaigns` |
| Settings | `/admin/settings` → Marketing tab |
| Artisan commands | `import:homey-clients`, `campaign:dispatch` |

---

## Step 1 — Configure Marketing Settings

Before sending, set your sender identity in Voyager.

1. Go to **Admin → Settings → Marketing**
2. Fill in:

| Setting | Description | Example |
|---|---|---|
| From Name | Sender display name | `Rashid \| Webpenter` |
| From Email | Sender address | `sales@webpenter.com` |
| Reply-To Email | Where replies go | `sales@webpenter.com` |
| SMTP Host | Leave blank to use app default | `smtp.sendgrid.net` |
| SMTP Port | Leave blank to use app default | `587` |
| SMTP Username | Leave blank to use app default | — |
| SMTP Password | Leave blank to use app default | — |
| Batch Size | Emails dispatched per queue job | `200` |
| Delay Between Emails (ms) | Throttle to avoid rate limits | `100` |
| Company Name | Footer branding | `Webpenter` |
| Company Address | Footer address | `Rahim Yar Khan, Punjab, Pakistan` |
| Company Website | Footer link | `https://webpenter.com` |
| Unsubscribe Text | Footer opt-out message | `Reply "unsubscribe" to opt out.` |

> **Tip:** For bulk sending to 66k+ recipients use a dedicated SMTP service (SendGrid, Mailgun, AWS SES). Set SMTP credentials here, not in `.env`, so they can be changed without a deployment.

---

## Step 2 — Import Clients (one-time)

The 66,107 Homey clients are already imported. If you need to re-import or add a new file:

```bash
# Dry run first — shows count without writing to DB
php artisan import:homey-clients --dry-run

# Real import
php artisan import:homey-clients

# Custom file
php artisan import:homey-clients --file=client_data/new_list.csv
```

**CSV format expected** (`client_data/homey_clients.csv`):
```
"name","email"
"Jose Daniel Chacon","josedaniel.chacon@gmail.com"
"Kay","elementalcode01@outlook.com"
```

If your source is an HTML-exported XLS (like the Homey customer log), convert it first:
```bash
python client_data/convert.py
```

> **Note:** Duplicate emails are automatically skipped. The Clockify observer is bypassed during import — no API calls are made for imported users.

---

## Step 3 — Create a Campaign

1. Go to **Admin → Email Campaigns → New Campaign**
2. Fill in:
   - **Campaign Name** — internal reference (e.g. `BookHere Launch — May 2026`)
   - **Email Subject** — what recipients see in inbox
   - **From Name / From Email** — pre-filled from Marketing Settings, override per-campaign if needed
   - **Target Role** — `homey_client` for the 66k list
3. Write the email body in the **Quill rich-text editor**

### Personalisation merge tags

Use these anywhere in subject or body:

| Tag | Replaced with |
|---|---|
| `{{name}}` | Full name (`Jose Daniel Chacon`) |
| `{{first_name}}` | First name only (`Jose`) |

### Quick Templates

Click a template button on the left panel to load pre-written content:

- **BookHere Mobile App** — pitch the React Native booking app
- **Zahid — Specialist Services** — Homey/Houzez customisation offer
- **Houzilo Platform** — Laravel/VueJS real estate platform

---

## Step 4 — Preview & Test

1. Open the campaign → click **Preview Email** (opens rendered HTML in new tab)
2. Use **Send Test Email** to send to yourself before bulk dispatch
3. Use **Send to One Email** to send to a specific recipient manually

---

## Step 5 — Bulk Send

> Requires the queue worker to be running (see Step 6).

1. Open the campaign
2. Review the **recipient count** shown on the stats panel
3. Click **Queue Bulk Send** → confirm
4. The system splits recipients into batches of 200 (configurable) and queues them
5. Monitor progress in the **Send Log** table — refresh periodically

The dispatcher **skips already-sent addresses**, so it is safe to click multiple times or resume after a failure.

---

## Step 6 — Run the Queue Worker

Bulk emails are processed through Laravel's queue. Start the worker:

```bash
# Process all queued jobs
php artisan queue:work

# With auto-restart on code changes (recommended for long-running sends)
php artisan queue:work --tries=3 --timeout=120 --sleep=3

# Check queued/failed jobs
php artisan queue:monitor
php artisan queue:failed
```

For production, use **Supervisor** to keep the worker running (see `DEPLOYMENT.md`).

---

## Step 7 — Send One-Off Emails via CLI

You can also dispatch from the command line without the UI:

```bash
# Bulk dispatch campaign ID 1 to all homey_client users
php artisan campaign:dispatch 1

# Dry run — shows count without sending
php artisan campaign:dispatch 1 --dry-run

# Limit to first 500 recipients (useful for staged rollout)
php artisan campaign:dispatch 1 --limit=500

# Custom delay between emails
php artisan campaign:dispatch 1 --delay=200
```

---

## Team Roles & Responsibilities

| Person | Role | Responsibility |
|---|---|---|
| Rashid Bukhari | CEO | Final approval on campaigns, monitor results |
| Zahid Khurshid | Founder / Sr. Dev | Campaign 2 (specialist services), technical setup |
| Ayub Khokhar | BD Lead | Campaign strategy, review content, coordinate rollout |
| Rabia Basit | BD Intern | Schedule campaigns, monitor send logs, handle replies |

### Staged rollout recommendation

| Phase | Recipients | Command |
|---|---|---|
| Test | 5 specific emails | Send to One Email (UI) |
| Pilot | 500 | `campaign:dispatch 1 --limit=500` |
| Full | All 66k | Queue Bulk Send (UI) |

---

## Troubleshooting

| Problem | Solution |
|---|---|
| Emails not sending | Check `QUEUE_CONNECTION=database` in `.env` and worker is running |
| `Quill is not defined` | Hard-refresh browser (`Ctrl+Shift+R`) to clear CDN cache |
| SMTP auth failure | Verify credentials in Admin → Settings → Marketing |
| Import slow / timeout | Use `--chunk=1000` flag; ensure `client_data/homey_clients.csv` exists |
| `/admin/users` slow | Run `php artisan migrate` to ensure indexes are applied |
| Campaign stuck at "sending" | Click **Mark as Complete** after queue worker finishes |

---

## File Reference

```
app/
  Console/Commands/
    ImportHomeyClients.php       ← import:homey-clients command
    DispatchEmailCampaign.php    ← campaign:dispatch command
  Http/Controllers/Voyager/
    EmailCampaignController.php  ← all campaign routes
  Jobs/
    SendCampaignBatchJob.php     ← queued batch sender
  Mail/
    MarketingCampaignMail.php    ← mailable (uses Voyager settings)
  Models/
    EmailCampaign.php
    EmailCampaignLog.php

database/
  migrations/
    2026_04_28_000001_create_email_campaigns_table.php
    2026_04_29_000001_add_indexes_to_users_table.php
  seeders/
    RolesTableSeeder.php         ← adds homey_client role
    MarketingSettingsSeeder.php  ← adds Marketing settings group
    EmailCampaignSeeder.php      ← seeds 3 ready templates

resources/views/
  emails/
    marketing-campaign.blade.php       ← HTML email template
    marketing-campaign-text.blade.php  ← plain text fallback
  vendor/voyager/email-campaigns/
    index.blade.php    ← campaign list
    edit-add.blade.php ← create/edit with Quill editor
    show.blade.php     ← stats, test send, bulk dispatch

client_data/
  customer_log_2026-04-20T23-50-27.xls  ← original Homey export
  homey_clients.csv                      ← cleaned CSV (import source)
```
