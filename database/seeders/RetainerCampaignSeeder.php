<?php

namespace Database\Seeders;

use App\Models\EmailCampaign;
use Illuminate\Database\Seeder;

class RetainerCampaignSeeder extends Seeder
{
    public function run(): void
    {
        // ── Campaign 1: Homey Retainer ──────────────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey — Monthly Maintenance & Support Retainer'], [
            'subject'     => 'Your Homey Site Broke at 2am. Who Fixed It?',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>A plugin update conflicts with Homey. Your booking form stops working. Guests try to book and get a blank screen. By the time you notice, you&rsquo;ve lost two days of reservations.</p>

<p>This happens more often than most Homey site owners admit &mdash; and it almost always happens at the worst possible time.</p>

<p>We now offer <strong>monthly maintenance retainers</strong> specifically for Homey sites. We handle every update, monitor your uptime, back up your data daily, and fix anything that breaks &mdash; before your guests ever see it.</p>

<!-- ── Pricing table ───────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>

    <!-- Watchdog -->
    <td width="31%" style="vertical-align:top;padding-right:10px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
        <tr><td style="background:#f8fafc;padding:18px 18px 14px;text-align:center;border-bottom:1px solid #e2e8f0;">
          <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;">Watchdog</p>
          <p style="margin:0;font-size:28px;font-weight:900;color:#1a1a2e;line-height:1;">$49<span style="font-size:13px;font-weight:400;color:#94a3b8;">/mo</span></p>
        </td></tr>
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Plugin &amp; core updates</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Daily backups (30-day retention)</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Uptime monitoring</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Weekly security scan</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#94a3b8;line-height:1.5;"><span style="margin-right:6px;">&mdash;</span>Email support (48hr)</td></tr>
          </table>
          <p style="margin:14px 0 0;font-size:11px;color:#94a3b8;line-height:1.4;">Best for: Hobby or low-traffic sites</p>
        </td></tr>
      </table>
    </td>

    <!-- Guardian (highlighted) -->
    <td width="38%" style="vertical-align:top;padding:0 5px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:2px solid #1a1a2e;border-radius:10px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.10);">
        <tr><td style="background:#1a1a2e;padding:18px 18px 14px;text-align:center;">
          <p style="margin:0 0 2px;font-size:10px;font-weight:700;color:#fbbf24;text-transform:uppercase;letter-spacing:1.5px;">Most Popular</p>
          <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#ffffff;text-transform:uppercase;letter-spacing:1px;">Guardian</p>
          <p style="margin:0;font-size:28px;font-weight:900;color:#ffffff;line-height:1;">$149<span style="font-size:13px;font-weight:400;color:rgba(255,255,255,0.5);">/mo</span></p>
        </td></tr>
        <tr><td style="padding:16px 18px;background:#fff;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Everything in Watchdog</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Free malware removal</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Monthly speed &amp; DB optimisation</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span><strong>2 hrs dev support</strong>/month</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Monthly health report</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Priority email support (24hr)</td></tr>
          </table>
          <p style="margin:14px 0 0;font-size:11px;color:#94a3b8;line-height:1.4;">Best for: Active booking sites &amp; businesses</p>
        </td></tr>
      </table>
    </td>

    <!-- Partner -->
    <td width="31%" style="vertical-align:top;padding-left:10px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
        <tr><td style="background:#f8fafc;padding:18px 18px 14px;text-align:center;border-bottom:1px solid #e2e8f0;">
          <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;">Partner</p>
          <p style="margin:0;font-size:28px;font-weight:900;color:#1a1a2e;line-height:1;">$399<span style="font-size:13px;font-weight:400;color:#94a3b8;">/mo</span></p>
        </td></tr>
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Everything in Guardian</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span><strong>5 hrs dev support</strong>/month</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>WhatsApp direct access</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Quarterly strategy call</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Same-day response</td></tr>
          </table>
          <p style="margin:14px 0 0;font-size:11px;color:#94a3b8;line-height:1.4;">Best for: High-revenue or multi-property</p>
        </td></tr>
      </table>
    </td>

  </tr>
</table>

<!-- ── Launch offer ───────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:8px 0 28px;">
  <tr>
    <td style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:16px 20px;text-align:center;">
      <p style="margin:0;font-size:13px;color:#92400e;line-height:1.6;">
        <strong>Launch offer &mdash; first month free</strong> on Guardian and Partner for anyone who signs up before 15 June 2026.
        Reply with <strong>&ldquo;RETAINER&rdquo;</strong> to claim it.
      </p>
    </td>
  </tr>
</table>

<!-- ── CTA ────────────────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 6px;font-size:16px;font-weight:700;color:#ffffff;">Stop worrying about your site. We&rsquo;ll handle it.</p>
      <p style="margin:0 0 20px;font-size:13px;color:rgba(255,255,255,0.7);line-height:1.6;">Reply to this email with the plan you want and your site URL &mdash; we&rsquo;ll get you set up within 24 hours. No contract, cancel any time.</p>
      <a href="mailto:sales@webpenter.com?subject=Retainer Signup" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 32px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Sign Up for a Retainer &rarr;</a>
    </td>
  </tr>
</table>

<p style="margin-top:24px;font-size:13px;color:#64748b;">Questions about what&rsquo;s included? Just reply and I&rsquo;ll walk you through it.</p>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

A plugin update conflicts with Homey. Your booking form stops working. Guests see a blank screen. By the time you notice, you've lost two days of reservations.

This happens more often than most Homey site owners admit — and it almost always happens at the worst possible time.

We now offer monthly maintenance retainers specifically for Homey sites.

────────────────────────────────────────────────────────────────────────
WATCHDOG — $49/month
✓ Plugin & core updates
✓ Daily backups (30-day retention)
✓ Uptime monitoring
✓ Weekly security scan
✓ Email support (48hr)
Best for: Hobby or low-traffic sites
────────────────────────────────────────────────────────────────────────
GUARDIAN — $149/month  ← Most Popular
✓ Everything in Watchdog
✓ Free malware removal
✓ Monthly speed & DB optimisation
✓ 2 hrs dev support per month
✓ Monthly health report
✓ Priority email support (24hr)
Best for: Active booking sites & businesses
────────────────────────────────────────────────────────────────────────
PARTNER — $399/month
✓ Everything in Guardian
✓ 5 hrs dev support per month
✓ WhatsApp direct access
✓ Quarterly strategy call
✓ Same-day response
Best for: High-revenue or multi-property sites
────────────────────────────────────────────────────────────────────────

LAUNCH OFFER: First month free on Guardian and Partner for signups before 15 June 2026.
Reply with "RETAINER" to claim it.

No contract. Cancel any time.

Reply with the plan you want and your site URL — we'll get you set up within 24 hours.

Email: sales@webpenter.com
Subject: Retainer Signup

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 2: Houzez Retainer ─────────────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzez — Monthly Maintenance & Support Retainer'], [
            'subject'     => 'Your Houzez Site Goes Down. Your Leads Stop. Who Do You Call?',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>For a real estate agency, your website <em>is</em> your lead pipeline. When it goes down &mdash; or worse, when a form breaks silently and you don&rsquo;t know for days &mdash; you&rsquo;re not just losing traffic. You&rsquo;re losing buyers and sellers who went to a competitor&rsquo;s site instead.</p>

<p>We now offer <strong>monthly maintenance retainers for Houzez sites</strong>. We handle updates, monitoring, backups, and technical fixes &mdash; so your site stays up, fast, and generating leads every single day.</p>

<!-- ── Pricing table ───────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>

    <!-- Watchdog -->
    <td width="31%" style="vertical-align:top;padding-right:10px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
        <tr><td style="background:#f8fafc;padding:18px 18px 14px;text-align:center;border-bottom:1px solid #e2e8f0;">
          <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;">Watchdog</p>
          <p style="margin:0;font-size:28px;font-weight:900;color:#1a1a2e;line-height:1;">$49<span style="font-size:13px;font-weight:400;color:#94a3b8;">/mo</span></p>
        </td></tr>
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Houzez &amp; plugin updates</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Daily backups (30-day retention)</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Uptime monitoring</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Weekly security scan</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#94a3b8;line-height:1.5;"><span style="margin-right:6px;">&mdash;</span>Email support (48hr)</td></tr>
          </table>
          <p style="margin:14px 0 0;font-size:11px;color:#94a3b8;line-height:1.4;">Best for: Small or single-agent sites</p>
        </td></tr>
      </table>
    </td>

    <!-- Guardian (highlighted) -->
    <td width="38%" style="vertical-align:top;padding:0 5px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:2px solid #1a1a2e;border-radius:10px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.10);">
        <tr><td style="background:#1a1a2e;padding:18px 18px 14px;text-align:center;">
          <p style="margin:0 0 2px;font-size:10px;font-weight:700;color:#fbbf24;text-transform:uppercase;letter-spacing:1.5px;">Most Popular</p>
          <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#ffffff;text-transform:uppercase;letter-spacing:1px;">Guardian</p>
          <p style="margin:0;font-size:28px;font-weight:900;color:#ffffff;line-height:1;">$149<span style="font-size:13px;font-weight:400;color:rgba(255,255,255,0.5);">/mo</span></p>
        </td></tr>
        <tr><td style="padding:16px 18px;background:#fff;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Everything in Watchdog</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Free malware removal</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Monthly speed &amp; DB optimisation</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span><strong>2 hrs dev support</strong>/month</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Monthly performance report</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Priority email support (24hr)</td></tr>
          </table>
          <p style="margin:14px 0 0;font-size:11px;color:#94a3b8;line-height:1.4;">Best for: Active agencies &amp; lead-gen sites</p>
        </td></tr>
      </table>
    </td>

    <!-- Partner -->
    <td width="31%" style="vertical-align:top;padding-left:10px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;">
        <tr><td style="background:#f8fafc;padding:18px 18px 14px;text-align:center;border-bottom:1px solid #e2e8f0;">
          <p style="margin:0 0 4px;font-size:13px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;">Partner</p>
          <p style="margin:0;font-size:28px;font-weight:900;color:#1a1a2e;line-height:1;">$399<span style="font-size:13px;font-weight:400;color:#94a3b8;">/mo</span></p>
        </td></tr>
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Everything in Guardian</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span><strong>5 hrs dev support</strong>/month</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>WhatsApp direct access</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Quarterly strategy call</td></tr>
            <tr><td style="padding:4px 0;font-size:12px;color:#4a5568;line-height:1.5;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Same-day response</td></tr>
          </table>
          <p style="margin:14px 0 0;font-size:11px;color:#94a3b8;line-height:1.4;">Best for: Multi-agent agencies</p>
        </td></tr>
      </table>
    </td>

  </tr>
</table>

<!-- ── Social proof block ─────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:8px 0 20px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
  <tr><td style="padding:18px 22px;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td width="40px" style="vertical-align:top;padding-right:14px;font-size:28px;line-height:1;">&#128172;</td>
        <td style="vertical-align:top;">
          <p style="margin:0 0 6px;font-size:13px;font-style:italic;color:#334155;line-height:1.7;">&ldquo;We had a Houzez update break our property search on a Friday evening. Webpenter had it fixed before Saturday morning. That&rsquo;s the kind of support you can&rsquo;t put a price on.&rdquo;</p>
          <p style="margin:0;font-size:12px;color:#94a3b8;">Guardian plan client &mdash; Real estate agency, UAE</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>

<!-- ── Launch offer ───────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:0 0 28px;">
  <tr>
    <td style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:16px 20px;text-align:center;">
      <p style="margin:0;font-size:13px;color:#92400e;line-height:1.6;">
        <strong>Launch offer &mdash; first month free</strong> on Guardian and Partner for anyone who signs up before 15 June 2026.
        Reply with <strong>&ldquo;RETAINER&rdquo;</strong> to claim it.
      </p>
    </td>
  </tr>
</table>

<!-- ── CTA ────────────────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 6px;font-size:16px;font-weight:700;color:#ffffff;">Your site keeps your agency running. We&rsquo;ll keep your site running.</p>
      <p style="margin:0 0 20px;font-size:13px;color:rgba(255,255,255,0.7);line-height:1.6;">Reply with the plan you want and your site URL &mdash; we&rsquo;ll have you onboarded in 24 hours. No contract, cancel any time.</p>
      <a href="mailto:sales@webpenter.com?subject=Houzez Retainer Signup" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 32px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Sign Up for a Retainer &rarr;</a>
    </td>
  </tr>
</table>

<p style="margin-top:24px;font-size:13px;color:#64748b;">Questions about what&rsquo;s included? Just reply &mdash; happy to walk through it.</p>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

For a real estate agency, your website is your lead pipeline. When it goes down — or when a form breaks silently and you don't know for days — you're losing buyers and sellers who went to a competitor instead.

We now offer monthly maintenance retainers for Houzez sites.

────────────────────────────────────────────────────────────────────────
WATCHDOG — $49/month
✓ Houzez & plugin updates
✓ Daily backups (30-day retention)
✓ Uptime monitoring
✓ Weekly security scan
✓ Email support (48hr)
Best for: Small or single-agent sites
────────────────────────────────────────────────────────────────────────
GUARDIAN — $149/month  ← Most Popular
✓ Everything in Watchdog
✓ Free malware removal
✓ Monthly speed & DB optimisation
✓ 2 hrs dev support per month
✓ Monthly performance report
✓ Priority email support (24hr)
Best for: Active agencies & lead-gen sites
────────────────────────────────────────────────────────────────────────
PARTNER — $399/month
✓ Everything in Guardian
✓ 5 hrs dev support per month
✓ WhatsApp direct access
✓ Quarterly strategy call
✓ Same-day response
Best for: Multi-agent agencies
────────────────────────────────────────────────────────────────────────

"We had a Houzez update break our property search on a Friday evening.
Webpenter had it fixed before Saturday morning."
— Guardian plan client, UAE

LAUNCH OFFER: First month free on Guardian and Partner for signups before 15 June 2026.
Reply with "RETAINER" to claim it.

No contract. Cancel any time.

Reply with the plan you want and your site URL.

Email: sales@webpenter.com
Subject: Houzez Retainer Signup

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);
    }
}
