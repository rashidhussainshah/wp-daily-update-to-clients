<?php

namespace Database\Seeders;

use App\Models\EmailCampaign;
use Illuminate\Database\Seeder;

class HomeyCampaignSeeder extends Seeder
{
    public function run(): void
    {
        // ── Campaign 1: Channel Manager ─────────────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey — Channel Manager (Airbnb & Booking.com Sync)'], [
            'subject'     => 'Stop Managing 5 Calendars — Your Homey Site Can Do It All',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>If you're listing on Airbnb, Booking.com, and VRBO <em>as well as</em> your Homey site, you already know the problem: the moment a booking arrives on one platform, you're rushing to block dates on the other four before a double booking happens.</p>

<p>We've built a <strong>Channel Manager integration for Homey</strong> that syncs your availability, rates, and bookings across every platform in real time — from inside your Homey dashboard.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
  <tr><td style="padding:22px 24px;">
    <p style="margin:0 0 14px;font-size:15px;font-weight:700;color:#1a1a2e;">What the Channel Manager does:</p>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#27ae60;font-weight:700;margin-right:8px;">&#10003;</span>Two-way sync with Airbnb, Booking.com, VRBO &amp; Expedia &mdash; automatically</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#27ae60;font-weight:700;margin-right:8px;">&#10003;</span>Rate changes pushed to all channels simultaneously from one place</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#27ae60;font-weight:700;margin-right:8px;">&#10003;</span>New bookings from any channel block your Homey calendar instantly</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#27ae60;font-weight:700;margin-right:8px;">&#10003;</span>Cancellations re-open availability everywhere within seconds</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#27ae60;font-weight:700;margin-right:8px;">&#10003;</span>Unified inbox &mdash; all guest messages in one place, no more app-switching</td></tr>
    </table>
  </td></tr>
</table>

<p>We've deployed this on 30+ Homey sites. Double bookings dropped to zero. Manual calendar management went from 2 hours a day to nothing.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 8px;font-size:16px;font-weight:700;color:#ffffff;">Ready to stop managing calendars manually?</p>
      <p style="margin:0 0 18px;font-size:13px;color:rgba(255,255,255,0.75);line-height:1.6;">Reply with the platforms you&rsquo;re currently listing on &mdash; I&rsquo;ll send a short demo video and a fixed-price setup quote within 24 hours.</p>
      <a href="mailto:sales@webpenter.com?subject=Homey Channel Manager Enquiry" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Get Channel Manager Set Up &rarr;</a>
    </td>
  </tr>
</table>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

If you're listing on Airbnb, Booking.com, and VRBO as well as your Homey site, you already know the problem: the moment a booking arrives on one platform, you're rushing to block dates on the other four.

We've built a Channel Manager integration for Homey that syncs your availability, rates, and bookings across every platform in real time.

WHAT IT DOES:
✓ Two-way sync with Airbnb, Booking.com, VRBO & Expedia — automatically
✓ Rate changes pushed to all channels simultaneously
✓ New bookings block your Homey calendar instantly
✓ Cancellations re-open availability everywhere within seconds
✓ Unified inbox — all guest messages in one place

We've deployed this on 30+ Homey sites. Double bookings dropped to zero. Manual calendar work went from 2 hours a day to nothing.

Reply with the platforms you're currently listing on — I'll send a short demo video and a fixed-price setup quote within 24 hours.

Email: sales@webpenter.com
Subject: Homey Channel Manager Enquiry

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 2: AI Auto-Reply Support Bot ──────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey — AI Auto-Reply Support Bot'], [
            'subject'     => 'Your Homey Site Answering Guest Questions 24/7 — Without You',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Your guests ask the same questions before every booking: <em>&ldquo;Is parking available?&rdquo;, &ldquo;What&rsquo;s the check-in time?&rdquo;, &ldquo;Do you allow early check-in?&rdquo;</em></p>

<p>Every hour those questions go unanswered, you&rsquo;re losing bookings to a competitor who replied faster. Our <strong>WordPress AI Support Bot</strong> answers instantly &mdash; any time of day or night.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">
  <tr>
    <td width="48%" style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 10px;font-size:12px;font-weight:700;color:#dc2626;text-transform:uppercase;letter-spacing:0.8px;">Without the bot</p>
      <p style="margin:0 0 8px;font-size:13px;color:#4a5568;line-height:1.6;">Guest asks a question at 11 pm</p>
      <p style="margin:0 0 8px;font-size:13px;color:#4a5568;line-height:1.6;">You reply next morning &mdash; 9 hours later</p>
      <p style="margin:0;font-size:13px;color:#dc2626;font-weight:600;">Guest already booked elsewhere</p>
    </td>
    <td width="4%"></td>
    <td width="48%" style="background:#f0fff4;border:1px solid #9ae6b4;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 10px;font-size:12px;font-weight:700;color:#16a34a;text-transform:uppercase;letter-spacing:0.8px;">With the AI bot</p>
      <p style="margin:0 0 8px;font-size:13px;color:#4a5568;line-height:1.6;">Guest asks a question at 11 pm</p>
      <p style="margin:0 0 8px;font-size:13px;color:#4a5568;line-height:1.6;">Bot replies in under 5 seconds</p>
      <p style="margin:0;font-size:13px;color:#16a34a;font-weight:600;">Guest books. You wake up to a confirmation.</p>
    </td>
  </tr>
</table>

<p style="margin:20px 0 8px;font-size:15px;font-weight:700;color:#1a1a2e;">What the bot handles automatically:</p>
<ul>
  <li>Property FAQs &mdash; check-in times, parking, amenities, house rules</li>
  <li>Availability questions linked to your live Homey calendar</li>
  <li>Price enquiries with instant quote calculation</li>
  <li>Escalation to you when a question needs a human answer</li>
  <li>AI upgrade available &mdash; understands natural language, not just keywords</li>
</ul>

<p>The bot handles <strong>80% of pre-booking questions</strong> without you. For anything it can&rsquo;t answer, it captures the guest&rsquo;s details and pings you with a summary &mdash; so you only deal with the serious enquiries.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 18px;font-size:14px;color:rgba(255,255,255,0.85);line-height:1.6;">Reply and I&rsquo;ll show you how the bot looks live on a Homey site &mdash; takes 5 minutes to see.</p>
      <a href="mailto:sales@webpenter.com?subject=Homey AI Bot Demo" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">See the AI Bot in Action &rarr;</a>
    </td>
  </tr>
</table>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

Your guests ask the same questions before every booking: "Is parking available?", "What's the check-in time?", "Do you allow early check-in?"

Every hour those questions go unanswered, you're losing bookings to a competitor who replied faster.

WITHOUT THE BOT:
Guest asks at 11pm → You reply next morning → Guest already booked elsewhere.

WITH THE AI BOT:
Guest asks at 11pm → Bot replies in 5 seconds → Guest books. You wake up to a confirmation.

WHAT THE BOT HANDLES:
- Property FAQs — check-in, parking, amenities, house rules
- Availability questions linked to your live Homey calendar
- Price enquiries with instant quote calculation
- Escalation to you when a question needs a human answer
- AI upgrade: understands natural language, not just keywords

The bot handles 80% of pre-booking questions without you.

Reply and I'll show you how the bot looks on a Homey site — takes 5 minutes to see.

Email: sales@webpenter.com
Subject: Homey AI Bot Demo

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 3: WhatsApp Booking Notifications ─────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey — WhatsApp Booking Notifications'], [
            'subject'     => 'New Booking on Your Homey Site? Know in 3 Seconds — on WhatsApp',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Email notifications get buried. SMS is expensive. But you check WhatsApp within seconds &mdash; so that&rsquo;s where your Homey booking alerts should land.</p>

<p>We&rsquo;ve built a <strong>WhatsApp Notifications plugin for Homey</strong> that sends instant messages to you and your guests the moment something happens on your site.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">
  <tr>
    <td width="48%" style="background:#f0fff4;border:1px solid #86efac;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#1a1a2e;">You get a WhatsApp when:</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>New booking &mdash; guest name, dates, total</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>Booking cancelled (with reason)</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>Guest sends a message</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>Payment received or failed</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>New review posted</td></tr>
      </table>
    </td>
    <td width="4%"></td>
    <td width="48%" style="background:#f0fff4;border:1px solid #86efac;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#1a1a2e;">Your guests get a WhatsApp for:</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>Booking confirmation (instant)</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>Check-in instructions</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>24-hour arrival reminder</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>Post-stay review request</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;margin-right:6px;">&#9679;</span>All branded with your property name</td></tr>
      </table>
    </td>
  </tr>
</table>

<p>Setup takes less than a day and runs on the WhatsApp Business API &mdash; no manual forwarding, no third-party app-switching required.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#25D366;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 8px;font-size:16px;font-weight:700;color:#ffffff;">Get WhatsApp working with your Homey site</p>
      <p style="margin:0 0 18px;font-size:13px;color:rgba(255,255,255,0.9);line-height:1.6;">Reply to this email &mdash; I&rsquo;ll send a short video of the notifications in action and a setup quote.</p>
      <a href="mailto:sales@webpenter.com?subject=Homey WhatsApp Notifications" style="display:inline-block;background:#ffffff;color:#128C7E;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Set Up WhatsApp Alerts &rarr;</a>
    </td>
  </tr>
</table>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

Email notifications get buried. SMS is expensive. But you check WhatsApp within seconds — so that's where your Homey booking alerts should land.

We've built a WhatsApp Notifications plugin for Homey that sends instant messages to you and your guests.

YOU GET A WHATSAPP WHEN:
● New booking — guest name, dates, total
● Booking cancelled (with reason)
● Guest sends a message
● Payment received or failed
● New review posted

YOUR GUESTS GET:
● Booking confirmation (instant)
● Check-in instructions
● 24-hour arrival reminder
● Post-stay review request
● All branded with your property name

Setup takes less than a day and runs on the WhatsApp Business API.

Reply — I'll send a short video of the notifications in action and a setup quote.

Email: sales@webpenter.com
Subject: Homey WhatsApp Notifications

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 4: Dynamic Pricing Engine ─────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey — Dynamic Pricing Engine'], [
            'subject'     => 'Your Competitors Filled Up Last Weekend. Your Calendar Didn\'t.',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>During peak weekends and local events, demand for your property is 3&ndash;5&times; higher than usual. If your nightly rate doesn&rsquo;t move with demand, you&rsquo;re leaving serious money behind &mdash; while a competitor with smarter pricing fills up faster <em>and</em> earns more per booking.</p>

<p>We&rsquo;ve built a <strong>Dynamic Pricing Engine for Homey</strong> that adjusts your rates automatically, based on real-time signals &mdash; without you touching anything.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">
  <tr>
    <td width="48%" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#1a1a2e;">Signals that adjust your price:</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;">&#128197; Day of week &amp; lead time</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;">&#128197; Local events &amp; public holidays</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;">&#128200; Competitor rates in your area</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;">&#127765; Seasonal demand curves</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;">&#128278; Last-minute discount rules</td></tr>
      </table>
    </td>
    <td width="4%"></td>
    <td width="48%" style="background:#1a1a2e;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#ffffff;">You stay in full control:</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Set your floor and ceiling price</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Approve or override any rate change</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Full pricing history &amp; audit log</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Works across all your listings</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Syncs to all connected OTA channels</td></tr>
      </table>
    </td>
  </tr>
</table>

<p>Homey site owners using the engine have seen an average <strong>22% increase in monthly revenue</strong> &mdash; not from more bookings, but from charging the right price at the right time.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#0066cc;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 18px;font-size:14px;color:rgba(255,255,255,0.9);line-height:1.6;">Reply and I&rsquo;ll put together a revenue projection based on your current rates and typical demand in your market. No commitment.</p>
      <a href="mailto:sales@webpenter.com?subject=Homey Dynamic Pricing Enquiry" style="display:inline-block;background:#ffffff;color:#0066cc;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Get My Revenue Projection &rarr;</a>
    </td>
  </tr>
</table>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

During peak weekends and local events, demand for your property is 3-5x higher than usual. If your nightly rate doesn't move with demand, you're leaving money behind while a competitor with smarter pricing fills up faster.

We've built a Dynamic Pricing Engine for Homey that adjusts your rates automatically, based on real-time signals.

SIGNALS THAT ADJUST YOUR PRICE:
- Day of week & lead time
- Local events & public holidays
- Competitor rates in your area
- Seasonal demand curves
- Last-minute discount rules

YOU STAY IN FULL CONTROL:
- Set your floor and ceiling price
- Approve or override any rate change
- Full pricing history & audit log
- Works across all your listings
- Syncs to all connected OTA channels

Homey site owners using the engine have seen an average 22% increase in monthly revenue — not from more bookings, but from charging the right price at the right time.

Reply and I'll put together a revenue projection based on your current rates. No commitment.

Email: sales@webpenter.com
Subject: Homey Dynamic Pricing Enquiry

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 5: SimplifyRes & Third-Party Listings Sync ────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey — SimplifyRes & Third-Party Listings Sync'], [
            'subject'     => 'List Once on Your Homey Site. Publish Everywhere Automatically.',
            'from_name'   => 'Zahid Khurshid | Webpenter',
            'from_email'  => 'zaars59208@gmail.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Right now, when you add a new property or update a listing on your Homey site, you probably have to log into Airbnb, Booking.com, VRBO, and every other platform separately to make the same change again. That&rsquo;s not a workflow &mdash; it&rsquo;s a daily time drain.</p>

<p>We&rsquo;ve integrated <strong>SimplifyRes</strong> with Homey &mdash; so you manage everything in one place and it syncs everywhere else automatically.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">
  <tr>
    <td style="background:#1a1a2e;padding:14px 20px;">
      <p style="margin:0;font-size:13px;font-weight:700;color:#ffffff;letter-spacing:0.5px;">ONE CHANGE IN HOMEY &rarr; UPDATES EVERYWHERE</p>
    </td>
  </tr>
  <tr>
    <td style="padding:20px;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="48%" style="padding-right:16px;border-right:1px solid #f0f0f0;vertical-align:top;">
            <p style="margin:0 0 10px;font-size:13px;font-weight:700;color:#1a1a2e;">Synced automatically:</p>
            <p style="margin:0 0 6px;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; Listing title, description &amp; photos</p>
            <p style="margin:0 0 6px;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; Availability calendar &amp; blocked dates</p>
            <p style="margin:0 0 6px;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; Nightly rates &amp; seasonal pricing</p>
            <p style="margin:0;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; Minimum stay rules</p>
          </td>
          <td width="4%"></td>
          <td width="48%" style="padding-left:16px;vertical-align:top;">
            <p style="margin:0 0 10px;font-size:13px;font-weight:700;color:#1a1a2e;">Platforms supported:</p>
            <p style="margin:0 0 6px;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; Airbnb &amp; Booking.com</p>
            <p style="margin:0 0 6px;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; VRBO &amp; Expedia</p>
            <p style="margin:0 0 6px;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; TripAdvisor &amp; HomeAway</p>
            <p style="margin:0;font-size:13px;color:#4a5568;line-height:1.6;">&#9679; SimplifyRes network &amp; more</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<p>We&rsquo;ve already delivered this integration for Homey clients. Setup is a one-time job &mdash; once it&rsquo;s live, you never manually update another platform again.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 6px;font-size:16px;font-weight:700;color:#1a1a2e;">Interested? I can show you exactly how it works.</p>
      <p style="margin:0 0 18px;font-size:13px;color:#64748b;line-height:1.6;">Reply with the platforms you currently list on &mdash; I&rsquo;ll scope the integration and send a fixed-price quote.</p>
      <a href="mailto:zaars59208@gmail.com?subject=Homey SimplifyRes Integration" style="display:inline-block;background:#1a1a2e;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Get Integration Quote &rarr;</a>
    </td>
  </tr>
</table>

<p>Best regards,<br>
<strong>Zahid Khurshid</strong><br>
Senior Developer, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a> &nbsp;|&nbsp; WhatsApp: +923336151813</p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

Right now, when you update a listing on your Homey site, you probably have to log into Airbnb, Booking.com, VRBO, and every other platform separately to repeat the same change. That's not a workflow — it's a daily time drain.

We've integrated SimplifyRes with Homey — so you manage everything in one place and it syncs everywhere automatically.

ONE CHANGE IN HOMEY → UPDATES EVERYWHERE

Synced automatically:
● Listing title, description & photos
● Availability calendar & blocked dates
● Nightly rates & seasonal pricing
● Minimum stay rules

Platforms supported:
● Airbnb & Booking.com
● VRBO & Expedia
● TripAdvisor & HomeAway
● SimplifyRes network & more

Setup is a one-time job — once it's live, you never manually update another platform again.

Reply with the platforms you currently use — I'll scope the integration and send a fixed-price quote.

Email: zaars59208@gmail.com
Subject: Homey SimplifyRes Integration

Best regards,
Zahid Khurshid
Senior Developer, Webpenter
https://webpenter.com | WhatsApp: +923336151813
TEXT,
        ]);

        // ── Campaign 6: Virtual Tour Integration ───────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey — Virtual Tour Integration'], [
            'subject'     => 'Homey Listings with Virtual Tours Book 3× Faster — Here\'s Why',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Photos can be deceiving &mdash; which is why guests hesitate before booking a property they haven&rsquo;t seen in person. A <strong>360&deg; virtual tour</strong> removes that hesitation completely. Guests explore every room before they book, which means fewer pre-booking questions and far fewer post-arrival disappointments.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">
  <tr>
    <td width="30%" style="text-align:center;padding:0 6px 0 0;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#0f172a;border-radius:8px;">
        <tr><td style="padding:20px 10px;text-align:center;">
          <div style="font-size:30px;font-weight:900;color:#22c55e;line-height:1;">3&times;</div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:6px;line-height:1.4;">more bookings<br>vs photos only</div>
        </td></tr>
      </table>
    </td>
    <td width="30%" style="text-align:center;padding:0 3px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#0f172a;border-radius:8px;">
        <tr><td style="padding:20px 10px;text-align:center;">
          <div style="font-size:30px;font-weight:900;color:#22c55e;line-height:1;">68%</div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:6px;line-height:1.4;">longer time<br>on listing page</div>
        </td></tr>
      </table>
    </td>
    <td width="30%" style="text-align:center;padding:0 0 0 6px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#0f172a;border-radius:8px;">
        <tr><td style="padding:20px 10px;text-align:center;">
          <div style="font-size:30px;font-weight:900;color:#22c55e;line-height:1;">41%</div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:6px;line-height:1.4;">fewer pre-booking<br>questions</div>
        </td></tr>
      </table>
    </td>
  </tr>
</table>

<p style="margin:20px 0 8px;font-size:15px;font-weight:700;color:#1a1a2e;">What we deliver:</p>
<ul>
  <li>Professional 360&deg; photography of your property &mdash; or import your existing Matterport / Google tour</li>
  <li>Embedded interactive viewer on your Homey listing &mdash; no app download needed</li>
  <li>Room-by-room navigation with hotspot labels for amenity callouts</li>
  <li>Fully mobile-responsive &mdash; works on every device</li>
  <li>SEO benefit &mdash; Google indexes 360&deg; content and rewards longer page dwell time</li>
</ul>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 18px;font-size:14px;color:rgba(255,255,255,0.85);line-height:1.6;">Reply to this email &mdash; I&rsquo;ll send a live example on a Homey listing and a quote based on your number of properties.</p>
      <a href="mailto:sales@webpenter.com?subject=Homey Virtual Tour Enquiry" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Add Virtual Tours to My Listings &rarr;</a>
    </td>
  </tr>
</table>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

Photos can be deceiving — which is why guests hesitate before booking a property they haven't seen in person. A 360° virtual tour removes that hesitation completely.

THE RESULTS:
- 3× more bookings vs. listings with photos only
- 68% longer time on listing page
- 41% fewer pre-booking questions

WHAT WE DELIVER:
- Professional 360° photography (or import existing Matterport/Google tour)
- Embedded interactive viewer on your Homey listing — no app needed
- Room-by-room navigation with hotspot labels
- Fully mobile-responsive
- SEO benefit — Google rewards longer dwell time

Reply to this email — I'll send a live example on a Homey listing and a quote based on your number of properties.

Email: sales@webpenter.com
Subject: Homey Virtual Tour Enquiry

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);
    }
}
