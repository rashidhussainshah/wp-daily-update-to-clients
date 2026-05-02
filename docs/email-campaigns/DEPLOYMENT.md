# Email Campaigns — Deployment Guide

Steps to deploy the Webpenter PMS (with email campaigns module) to a production Linux server.

**Stack:** PHP 8.2 · Laravel 10 · MySQL · Supervisor · Nginx

---

## Prerequisites

- Ubuntu 20.04 / 22.04 server
- PHP 8.2 with extensions: `mbstring`, `xml`, `curl`, `zip`, `pdo_mysql`, `bcmath`, `dom`
- MySQL 8.0+
- Composer 2
- Nginx
- Supervisor (for queue worker)
- Git access to the repo

---

## 1. Pull the Code

```bash
cd /var/www
git clone <repo-url> portal
cd portal
```

If updating an existing deployment:
```bash
git pull origin master
```

---

## 2. Install PHP Dependencies

```bash
composer install --no-dev --optimize-autoloader
```

---

## 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` for production:

```env
APP_NAME=Webpenter
APP_ENV=production
APP_DEBUG=false
APP_URL=https://portal.webpenter.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=webpenter_portal
DB_USERNAME=portal_user
DB_PASSWORD=<strong-password>

# Queue must NOT be sync — use database driver for bulk email
QUEUE_CONNECTION=database
CACHE_DRIVER=file          # or redis if available

# Base SMTP (used when Marketing Settings SMTP fields are blank)
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_ENCRYPTION=tls
MAIL_USERNAME=apikey
MAIL_PASSWORD=<sendgrid-api-key>
MAIL_FROM_ADDRESS=sales@webpenter.com
MAIL_FROM_NAME="Webpenter"
```

> **Marketing SMTP override:** Bulk campaign credentials are set in  
> **Admin → Settings → Marketing** — no deployment needed to change them.

---

## 4. Database Setup

```bash
# Run all migrations (creates tables + indexes)
php artisan migrate --force

# Seed roles, Marketing settings, and 3 email templates
php artisan db:seed --class=RolesTableSeeder --force
php artisan db:seed --class=MarketingSettingsSeeder --force
php artisan db:seed --class=EmailCampaignSeeder --force
```

---

## 5. Import Client Data

Upload the CSV to the server first:
```bash
scp client_data/homey_clients.csv user@server:/var/www/portal/client_data/
```

Then run the import:
```bash
php artisan import:homey-clients --dry-run   # verify count first
php artisan import:homey-clients             # run import
```

Expected output: `Done. Imported 66107 Homey clients (role_id=58).`

---

## 6. Storage & Permissions

```bash
php artisan storage:link

chown -R www-data:www-data /var/www/portal
chmod -R 755 /var/www/portal
chmod -R 775 /var/www/portal/storage
chmod -R 775 /var/www/portal/bootstrap/cache
```

---

## 7. Optimise for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

To clear all caches after a deployment:
```bash
php artisan optimize:clear
```

---

## 8. Queue Worker — Supervisor Config

The bulk email sender uses Laravel's queue. Supervisor keeps the worker alive.

Install Supervisor:
```bash
sudo apt install supervisor -y
```

Create config file:
```bash
sudo nano /etc/supervisor/conf.d/webpenter-worker.conf
```

Paste:
```ini
[program:webpenter-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/portal/artisan queue:work --sleep=3 --tries=3 --timeout=120 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/portal/storage/logs/worker.log
stopwaitsecs=3600
```

> `numprocs=2` runs 2 parallel workers. For 66k emails in one campaign, this processes ~2,400 emails/minute at 100ms delay each.

Apply:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start webpenter-worker:*
sudo supervisorctl status
```

After every deployment (code change):
```bash
sudo supervisorctl restart webpenter-worker:*
```

---

## 9. Nginx Configuration

```nginx
server {
    listen 80;
    server_name portal.webpenter.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl;
    server_name portal.webpenter.com;

    ssl_certificate     /etc/letsencrypt/live/portal.webpenter.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/portal.webpenter.com/privkey.pem;

    root /var/www/portal/public;
    index index.php;

    # Increase timeout for long-running admin requests
    fastcgi_read_timeout 120;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
```

```bash
sudo nginx -t
sudo systemctl reload nginx
```

SSL via Let's Encrypt:
```bash
sudo apt install certbot python3-certbot-nginx -y
sudo certbot --nginx -d portal.webpenter.com
```

---

## 10. Scheduled Tasks (Cron)

Add to the server's crontab (`sudo crontab -e -u www-data`):

```cron
* * * * * cd /var/www/portal && php artisan schedule:run >> /dev/null 2>&1
```

---

## 11. Deployment Checklist

Run this sequence every time you deploy new code:

```bash
cd /var/www/portal

# 1. Pull latest
git pull origin master

# 2. Install/update dependencies
composer install --no-dev --optimize-autoloader

# 3. Run any new migrations
php artisan migrate --force

# 4. Clear and rebuild caches
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 5. Restart queue workers
sudo supervisorctl restart webpenter-worker:*
```

---

## 12. Monitoring

### Queue health
```bash
# Live worker status
sudo supervisorctl status

# View failed jobs
php artisan queue:failed

# Retry all failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

### Campaign send progress
- Open **Admin → Email Campaigns → [Campaign Name]**
- Refresh the **Send Log** table to see sent/failed counts in real time

### Worker log
```bash
tail -f /var/www/portal/storage/logs/worker.log
```

### Application log
```bash
tail -f /var/www/portal/storage/logs/laravel.log
```

---

## 13. Sending 66k Emails — Estimated Timeline

| SMTP Service | Rate Limit | Time for 66k emails |
|---|---|---|
| SendGrid (Free) | 100/day | Not suitable |
| SendGrid (Essentials) | 100k/month | ~45 min at 200ms delay |
| Mailgun (Flex) | 5,000/hour | ~13 hours |
| AWS SES | 62k/second (production) | < 5 minutes |
| Mailtrap (testing) | 500/month | Testing only |

Set `Delay Between Emails (ms)` in Marketing Settings to stay within your SMTP provider's rate limit:

```
Rate limit 100/min  →  delay = 600ms
Rate limit 500/min  →  delay = 120ms
Rate limit 3600/hr  →  delay = 1000ms
```

---

## Environment Variables Reference

| Variable | Production value |
|---|---|
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `QUEUE_CONNECTION` | `database` |
| `CACHE_DRIVER` | `file` or `redis` |
| `SESSION_DRIVER` | `file` or `database` |
| `LOG_CHANNEL` | `daily` |
| `LOG_LEVEL` | `error` |
