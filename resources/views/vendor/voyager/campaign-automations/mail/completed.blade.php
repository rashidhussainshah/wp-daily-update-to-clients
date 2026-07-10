<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 30px; }
.wrap { background: #fff; max-width: 560px; margin: 0 auto; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
.header { background: #1a1a2e; color: #fff; padding: 24px 28px; }
.header h1 { margin: 0; font-size: 18px; font-weight: 600; }
.header p { margin: 4px 0 0; font-size: 13px; opacity: .7; }
.body { padding: 28px; }
.stat-row { display: flex; gap: 16px; margin: 20px 0; }
.stat { flex: 1; background: #f8fafc; border-radius: 8px; padding: 16px; text-align: center; }
.stat .num { font-size: 28px; font-weight: 700; }
.stat .label { font-size: 12px; color: #888; margin-top: 4px; }
.stat.sent .num { color: #27ae60; }
.stat.failed .num { color: #e74c3c; }
.detail { font-size: 13px; color: #555; line-height: 1.7; }
.detail strong { color: #333; }
.footer { background: #f8fafc; padding: 16px 28px; font-size: 11px; color: #aaa; border-top: 1px solid #eee; }
</style>
</head>
<body>
<div class="wrap">
    <div class="header">
        <h1>Automation Complete</h1>
        <p>{{ $automation->name }}</p>
    </div>
    <div class="body">
        <div class="stat-row">
            <div class="stat sent">
                <div class="num">{{ $totalSent }}</div>
                <div class="label">Emails Sent</div>
            </div>
            <div class="stat failed">
                <div class="num">{{ $totalFailed }}</div>
                <div class="label">Failed</div>
            </div>
        </div>
        <div class="detail">
            <strong>Campaign:</strong> {{ $automation->campaign->name ?? '—' }}<br>
            <strong>Target Role:</strong> {{ $automation->target_role }}<br>
            <strong>Completed At:</strong> {{ now()->format('d M Y, h:i A') }}<br>
        </div>
    </div>
    <div class="footer">
        This notification was sent automatically by the Webpenter portal campaign automation system.
    </div>
</div>
</body>
</html>
