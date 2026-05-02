# Laravel Queue Setup — Hostinger Server

This guide covers enabling background queues for the email campaign system on Hostinger.
Right now the app uses `dispatchSync()` (emails sent synchronously, no worker needed).
Once queues are set up, switch back to `dispatch()` for background processing.

---

## Current State (No Queue)

File: `app/Http/Controllers/Voyager/EmailCampaignController.php`

```php
// Synchronous — runs in the web request, no queue worker needed
SendCampaignBatchJob::dispatchSync($campaign->id, $batch);
```

Switch to queued by changing one word:

```php
// Queued — requires a running queue worker
SendCampaignBatchJob::dispatch($campaign->id, $batch);
```

---

## Step 1 — Choose a Queue Driver

Edit `.env` on the server. The recommended driver for Hostinger is **database** (works on
both shared hosting and VPS without extra services).

```env
QUEUE_CONNECTION=database
```

> **Why database?** Redis is not available on Hostinger shared hosting. The database driver
> uses a `jobs` table in MySQL — no extra software needed.

---

## Step 2 — Create the Jobs Table

Run once on the server:

```bash
php artisan queue:table
php artisan migrate
```

This creates a `jobs` table and a `failed_jobs` table in your MySQL database.

---

## Step 3 — Set Up the Worker

Pick the option that matches your Hostinger plan.

---

### Option A — Shared Hosting (hPanel Cron Job)

Shared hosting cannot run persistent background processes. Use a cron job that restarts
the worker every minute. The worker processes all queued jobs and then exits.

**In hPanel → Advanced → Cron Jobs**, add:

```
* * * * * cd /home/YOUR_USERNAME/domains/portal.webpenter.com/public_html && php artisan queue:work --stop-when-empty --tries=3 --timeout=60 >> /dev/null 2>&1
```

Replace `/home/YOUR_USERNAME/domains/portal.webpenter.com/public_html` with your actual
project path. To find it, SSH into the server and run `pwd` inside the project folder.

| Flag | Purpose |
|---|---|
| `--stop-when-empty` | Worker exits after processing all current jobs (safe for cron) |
| `--tries=3` | Retry failed jobs 3 times before marking as failed |
| `--timeout=60` | Kill a job that takes longer than 60 seconds |

> **Limitation:** Jobs queued between cron ticks (up to 59 seconds) wait in the `jobs`
> table until the next cron run. Fine for email campaigns — not suitable for real-time tasks.

---

### Option B — VPS (Supervisor — Recommended)

VPS plans support persistent processes. Supervisor keeps the worker running 24/7 and
automatically restarts it if it crashes.

#### 1. Install Supervisor

```bash
sudo apt-get update
sudo apt-get install supervisor
```

#### 2. Create a Supervisor Config File

```bash
sudo nano /etc/supervisor/conf.d/webpenter-worker.conf
```

Paste this — replace the paths with your actual server paths:

```ini
[program:webpenter-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/YOUR_USERNAME/domains/portal.webpenter.com/public_html/artisan queue:work --sleep=3 --tries=3 --timeout=300 --max-jobs=500
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=YOUR_USERNAME
numprocs=1
redirect_stderr=true
stdout_logfile=/home/YOUR_USERNAME/domains/portal.webpenter.com/public_html/storage/logs/worker.log
stopwaitsecs=3600
```

#### 3. Start Supervisor

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start webpenter-worker:*
```

#### 4. Check Status

```bash
sudo supervisorctl status
```

You should see:

```
webpenter-worker:webpenter-worker_00   RUNNING   pid 12345, uptime 0:00:10
```

#### 5. After Every Deployment

After `git pull`, restart the worker so it picks up new code:

```bash
sudo supervisorctl restart webpenter-worker:*
```

Or add this to your deploy script:

```bash
php artisan queue:restart   # graceful restart — finishes current job first
```

---

## Step 4 — Switch the App Back to Queued Mode

Once the worker is confirmed running, update the controller:

**File:** `app/Http/Controllers/Voyager/EmailCampaignController.php` — line ~205

```php
// Change this:
SendCampaignBatchJob::dispatchSync($campaign->id, $batch);

// Back to this:
SendCampaignBatchJob::dispatch($campaign->id, $batch);
```

Then on the server:

```bash
git pull
php artisan optimize:clear
```

---

## Monitoring Failed Jobs

View failed jobs:

```bash
php artisan queue:failed
```

Retry a specific failed job by its ID:

```bash
php artisan queue:retry 5
```

Retry all failed jobs:

```bash
php artisan queue:retry all
```

Clear all failed jobs:

```bash
php artisan queue:flush
```

---

## Testing the Queue is Working

After setup, test with a small campaign using **Send to Selected Clients** (picks
1–2 users). Then check the jobs table directly:

```bash
php artisan tinker
>>> DB::table('jobs')->count();   // should be 0 if worker processed them
>>> DB::table('failed_jobs')->count();   // should be 0 if no failures
```

Or tail the worker log (VPS only):

```bash
tail -f storage/logs/worker.log
```

---

## Environment Summary

| Setting | Shared Hosting | VPS |
|---|---|---|
| `QUEUE_CONNECTION` | `database` | `database` or `redis` |
| Worker method | Cron (`--stop-when-empty`) | Supervisor (persistent) |
| Real-time processing | No (up to 59 s delay) | Yes |
| Auto-restart on crash | No | Yes (Supervisor) |
| Setup complexity | Low | Medium |

---

## Quick Reference — Common Commands

```bash
# Run worker manually (foreground, good for testing)
php artisan queue:work

# Run worker and exit when queue is empty
php artisan queue:work --stop-when-empty

# Check queue size
php artisan tinker --execute="echo DB::table('jobs')->count();"

# Gracefully restart all workers (after deploy)
php artisan queue:restart

# Clear all pending jobs (use with caution)
php artisan queue:clear
```
