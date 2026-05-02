<?php

namespace Database\Seeders;

use App\Models\EmailCampaign;
use Illuminate\Database\Seeder;

class EmailCampaignSeeder extends Seeder
{
    public function run(): void
    {
        // ── Campaign 1: BookHere Mobile App ──────────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'BookHere Mobile App — Homey Clients'], [
            'subject'    => 'Turn Your Homey Site Into a Full Mobile Booking App',
            'from_name'  => 'Rashid | Webpenter',
            'from_email' => 'sales@webpenter.com',
            'target_role'=> 'homey_client',
            'status'     => 'draft',
            'html_body'  => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>I noticed you're running a booking or rental site powered by the <strong>Homey theme</strong> — great choice.</p>

<p>We've built <strong>BookHere</strong>, a white-label mobile app (iOS &amp; Android) that connects directly to your Homey site so your guests can <em>search, book, and pay</em> from their phones — no extra backend needed.</p>

<p><strong>Why BookHere?</strong></p>
<ul>
  <li>Ready-made React Native app — your branding, your domain</li>
  <li>Real-time availability synced with your Homey site</li>
  <li>Push notifications for new bookings &amp; reminders</li>
  <li>Stripe &amp; PayPal payment support</li>
  <li>Hotels, car rentals, and venues — all in one app</li>
</ul>

<p>Over 500 Homey site owners are already using it. Getting started takes less than a week.</p>

<p><a class="btn" href="https://bookhere.tech">See BookHere in Action</a></p>

<p>If you'd like a personalised demo or want to know what it would look like on your site, just reply to this email and I'll set it up.</p>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

I noticed you're running a booking or rental site powered by the Homey theme — great choice.

We've built BookHere, a white-label mobile app (iOS & Android) that connects directly to your Homey site so your guests can search, book, and pay from their phones — no extra backend needed.

WHY BOOKHERE?
- Ready-made React Native app — your branding, your domain
- Real-time availability synced with your Homey site
- Push notifications for new bookings & reminders
- Stripe & PayPal payment support
- Hotels, car rentals, and venues — all in one app

Over 500 Homey site owners are already using it. Getting started takes less than a week.

See BookHere: https://bookhere.tech

If you'd like a personalised demo, just reply to this email.

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 2: Zahid's Homey/Houzez Specialist Services ─────────────
        EmailCampaign::firstOrCreate(['name' => 'Zahid Specialist Services — Homey/Houzez'], [
            'subject'    => 'Quick One — Homey & Houzez Performance & Features',
            'from_name'  => 'Zahid Khurshid | Webpenter',
            'from_email' => 'zaars59208@gmail.com',
            'target_role'=> 'homey_client',
            'status'     => 'draft',
            'html_body'  => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>As you know I am a <strong>Favethemes Sr. Developer</strong>. I help Real Estate and Booking sites (Houzez, Homey &amp; Listeo) improve performance and automate key features.</p>

<p>Recently, I've implemented for clients:</p>
<ul>
  <li>⚡ Faster search for large listings</li>
  <li>💳 Automated PayPal payouts</li>
  <li>🗺️ Map-based property filtering</li>
  <li>🛏️ Multi-room booking support</li>
  <li>📅 One Calendar page for all reservations</li>
</ul>

<p>Here's a playlist of my recent work, showcasing features I've built to enhance business websites:<br>
<a href="http://youtube.com/post/UgkxRwZPv6DfHfBKyYI6kTvcn2FDBVgL5L7C">▶ Watch on YouTube</a></p>

<p>If any of this sounds useful, I can share a quick demo or suggest what would work best for your site. I am also available for any type of customization, and available for full-time work as well.</p>

<p><strong>Would you be open to a quick call?</strong></p>

<p>You can check my profiles:<br>
🔗 <a href="https://upwork.com/freelancers/zahidkhurshidchandio">Upwork</a> &nbsp;|&nbsp;
🔗 <a href="https://www.fiverr.com/zaars59208">Fiverr</a></p>

<p>Best regards,<br>
<strong>Zahid Khurshid</strong><br>
Senior Web Developer &mdash; Favethemes (Houzez &amp; Homey Specialist)<br>
WhatsApp: +923336151813 &nbsp;|&nbsp; Slack: zaars59208@gmail.com</p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

As you know I am a Favethemes Sr. Developer. I help Real Estate and Booking sites (Houzez, Homey & Listeo) improve performance and automate key features.

Recently implemented for clients:
- Faster search for large listings
- Automated PayPal payouts
- Map-based property filtering
- Multi-room booking support
- One Calendar page for all reservations

Watch my recent work: http://youtube.com/post/UgkxRwZPv6DfHfBKyYI6kTvcn2FDBVgL5L7C

I can share a quick demo or suggest what would work best for your site. Also available for full-time work.

My profiles:
Upwork: https://upwork.com/freelancers/zahidkhurshidchandio
Fiverr: https://www.fiverr.com/zaars59208

Best regards,
Zahid Khurshid
Senior Web Developer — Favethemes (Houzez & Homey Specialist)
WhatsApp: +923336151813
TEXT,
        ]);

        // ── Campaign 4: Homey Products Showcase ──────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Homey Products Showcase — Full Range'], [
            'subject'     => '9 Add-Ons That Make Your Homey Site More Powerful',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Over the past year we've shipped <strong>9 production-ready add-ons for the Homey theme</strong> — each one solving a real gap that Homey doesn't cover out of the box. Here's the full list, plus something new we're excited about at the bottom.</p>

<!-- ── Product Grid ────────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:28px 0 8px;">

  <!-- Row 1 -->
  <tr>
    <td width="50%" style="padding:0 8px 16px 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#eef4ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">🛏️</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">Multi-Room<br>Booking</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">Let guests book multiple rooms in one listing — ideal for hotels, guesthouses, and shared properties.</p>
            <a href="https://webpenter.com/homey-multi-room" style="font-size:12px;font-weight:700;color:#0066cc;text-decoration:none;letter-spacing:0.3px;">GET THIS ADD-ON &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 16px 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#eef4ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">🗺️</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">Draw-Circle<br>Map Search</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">Draw a radius on the map to find properties in any area — a visual, intuitive search guests love.</p>
            <a href="https://webpenter.com/homey-map-circle-search" style="font-size:12px;font-weight:700;color:#0066cc;text-decoration:none;letter-spacing:0.3px;">GET THIS ADD-ON &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Row 2 -->
  <tr>
    <td width="50%" style="padding:0 8px 16px 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#eef4ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">📅</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">Homey Custom<br>Calendar</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">One unified calendar for all your listings — see every reservation and blocked date at a glance.</p>
            <a href="https://webpenter.com/homey-custom-calendar" style="font-size:12px;font-weight:700;color:#0066cc;text-decoration:none;letter-spacing:0.3px;">GET THIS ADD-ON &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 16px 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#eef4ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">🪪</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">Host Passport<br>Profile Card</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">Airbnb-style host pop-up on every listing page — builds instant trust with guests before they book.</p>
            <a href="https://webpenter.com/homey-host-passport-card" style="font-size:12px;font-weight:700;color:#0066cc;text-decoration:none;letter-spacing:0.3px;">GET THIS ADD-ON &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Row 3 -->
  <tr>
    <td width="50%" style="padding:0 8px 16px 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#eef4ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">🌐</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">Language<br>Switcher</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">Add a native multi-language switcher to Homey — serve international guests without a separate translation plugin.</p>
            <a href="https://webpenter.com/homey-language-switcher" style="font-size:12px;font-weight:700;color:#0066cc;text-decoration:none;letter-spacing:0.3px;">GET THIS ADD-ON &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 16px 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#eef4ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">⚙️</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">Homey Installation<br>Service</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">We configure, style, and launch your Homey site end-to-end — so it's ready for bookings from day one.</p>
            <a href="https://webpenter.com/homey-installation-service" style="font-size:12px;font-weight:700;color:#0066cc;text-decoration:none;letter-spacing:0.3px;">GET THIS ADD-ON &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Row 4 -->
  <tr>
    <td width="50%" style="padding:0 8px 0 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#eef4ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">🔐</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">OTP Registration<br>Plugin</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">Phone OTP verification at registration — stop fake accounts and protect your booking platform.</p>
            <a href="https://webpenter.com/homey-otp-registration" style="font-size:12px;font-weight:700;color:#0066cc;text-decoration:none;letter-spacing:0.3px;">GET THIS ADD-ON &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 0 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e8ecef;border-radius:8px;">
        <tr>
          <td style="padding:20px 20px 18px;">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td style="width:40px;height:40px;background:#fff4e5;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">🔑</td>
                <td style="width:12px;"></td>
                <td style="font-size:14px;font-weight:700;color:#1a1a2e;line-height:1.3;">Homey Login<br>Error Fix</td>
              </tr>
            </table>
            <p style="margin:12px 0 10px;font-size:13px;color:#4a5568;line-height:1.65;">Expert diagnosis and fix for Homey login issues — get your users back in fast, with no downtime.</p>
            <a href="https://webpenter.com/homey-login-fix" style="font-size:12px;font-weight:700;color:#e67e00;text-decoration:none;letter-spacing:0.3px;">FIX MY SITE &rarr;</a>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>

<!-- ── Stripe Connect Feature Block ───────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:28px 0;">
  <tr>
    <td style="background:#635bff;border-radius:10px;padding:28px 28px 24px;">

      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td style="width:48px;height:48px;background:rgba(255,255,255,0.15);border-radius:10px;text-align:center;vertical-align:middle;font-size:24px;">💳</td>
          <td style="width:14px;"></td>
          <td style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.75);letter-spacing:1.5px;text-transform:uppercase;">NEW — FEATURED ADD-ON</td>
        </tr>
      </table>

      <h2 style="margin:16px 0 8px;font-size:20px;color:#ffffff;font-weight:800;line-height:1.3;">
        Stripe Connect — Automated Payouts to Hosts &amp; Admin
      </h2>
      <p style="margin:0 0 16px;font-size:14px;color:rgba(255,255,255,0.88);line-height:1.75;">
        Stop manually sending money to your hosts after every booking. Our <strong style="color:#ffffff;">Stripe Connect integration</strong> splits each payment automatically the moment a booking is confirmed — host receives their payout, you keep your commission. No spreadsheets. No bank transfers. No delays.
      </p>

      <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:22px;">
        <tr>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;padding-right:8px;">&#10003;</td>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;">Host payout triggered automatically on booking confirmation</td>
        </tr>
        <tr>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;padding-right:8px;">&#10003;</td>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;">Admin platform fee retained in your Stripe account instantly</td>
        </tr>
        <tr>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;padding-right:8px;">&#10003;</td>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;">Cancellation &amp; refund flows handled automatically</td>
        </tr>
        <tr>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;padding-right:8px;">&#10003;</td>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;">Plugs into your existing Homey payment setup — no rebuild needed</td>
        </tr>
        <tr>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;padding-right:8px;">&#10003;</td>
          <td style="font-size:13px;color:rgba(255,255,255,0.88);line-height:2;">Full Stripe dashboard visibility for you and your hosts</td>
        </tr>
      </table>

      <a href="https://webpenter.com/homey-stripe-connect" style="display:inline-block;background:#ffffff;color:#635bff;padding:13px 28px;border-radius:6px;font-weight:800;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Automate My Payouts &rarr;</a>
    </td>
  </tr>
</table>

<!-- ── Custom Work CTA ─────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="background:#f8fafc;border:1px solid #e8ecef;border-radius:8px;padding:24px 24px 22px;text-align:center;">
      <p style="margin:0 0 6px;font-size:16px;font-weight:700;color:#1a1a2e;">Need something that's not on this list?</p>
      <p style="margin:0 0 18px;font-size:14px;color:#4a5568;line-height:1.65;">We build custom Homey features, plugins, and integrations on demand. Describe what your site needs and we'll scope it out — usually within 24 hours.</p>
      <a href="mailto:sales@webpenter.com?subject=Custom Homey Work" style="display:inline-block;background:#1a1a2e;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Tell Us What You Need &rarr;</a>
    </td>
  </tr>
</table>

<p style="margin-top:28px;">Just reply to this email or use the button above — we get back within one business day.</p>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a> &nbsp;&bull;&nbsp; <a href="mailto:sales@webpenter.com">sales@webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

Over the past year we've shipped 9 production-ready add-ons for the Homey theme. Here's the full list:

1. MULTI-ROOM BOOKING
   Let guests book multiple rooms in one listing — perfect for hotels and guesthouses.
   https://webpenter.com/homey-multi-room

2. DRAW-CIRCLE MAP SEARCH
   Draw a radius on the map to find nearby properties — a visual search guests love.
   https://webpenter.com/homey-map-circle-search

3. HOMEY CUSTOM CALENDAR
   One unified calendar for all your listings — see every reservation at a glance.
   https://webpenter.com/homey-custom-calendar

4. HOST PASSPORT PROFILE CARD
   Airbnb-style host pop-up on every listing page — builds instant trust with guests.
   https://webpenter.com/homey-host-passport-card

5. LANGUAGE SWITCHER
   Native multi-language switcher for Homey — serve international guests easily.
   https://webpenter.com/homey-language-switcher

6. HOMEY INSTALLATION SERVICE
   We configure, style, and launch your Homey site — ready for bookings from day one.
   https://webpenter.com/homey-installation-service

7. OTP REGISTRATION PLUGIN
   Phone OTP at registration — stop fake accounts and protect your platform.
   https://webpenter.com/homey-otp-registration

8. HOMEY LOGIN ERROR FIX
   Expert diagnosis and fix for Homey login issues — no downtime.
   https://webpenter.com/homey-login-fix

── NEW: STRIPE CONNECT — AUTOMATED PAYOUTS ──────────────────────────────

Stop manually sending money to your hosts. Our Stripe Connect integration splits every booking payment automatically — host receives their payout, you keep your commission. No spreadsheets. No delays.

- Host payout on booking confirmation — automatic
- Admin platform fee retained instantly in your Stripe account
- Cancellation & refund flows handled automatically
- Plugs into your existing Homey payment setup

Get Stripe Connect: https://webpenter.com/homey-stripe-connect

── NEED CUSTOM WORK? ────────────────────────────────────────────────────

Describe what your Homey site needs and we'll scope it out within 24 hours.
Email us: sales@webpenter.com (subject: Custom Homey Work)

──────────────────────────────────────────────────────────────────────────
Just reply or email us — we get back within one business day.

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com | sales@webpenter.com
TEXT,
        ]);

        // ── Campaign 5: WordPress Security Hardening ─────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'WordPress Security Hardening — Homey Sites'], [
            'subject'     => 'Your Homey Site Is Being Attacked Right Now (Here\'s How to Stop It)',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>If your Homey booking site is live, it's already being probed — automatically, every single day. Most WordPress site owners find out they've been hacked only after Google blacklists their domain or their bookings suddenly stop.</p>

<p>We've hardened dozens of Homey and WordPress booking sites and stopped every one of the attacks below. Here's an honest breakdown of what's hitting your site and what we do about it.</p>

<!-- ── Attack List ──────────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">

  <!-- Header row -->
  <tr>
    <td colspan="3" style="background:#1a1a2e;border-radius:8px 8px 0 0;padding:12px 16px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="28%" style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.6);letter-spacing:1px;text-transform:uppercase;">ATTACK</td>
          <td width="38%" style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.6);letter-spacing:1px;text-transform:uppercase;">WHAT IT DOES</td>
          <td width="34%" style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.6);letter-spacing:1px;text-transform:uppercase;">OUR FIX (ALREADY DEPLOYED)</td>
        </tr>
      </table>
    </td>
  </tr>

  <!-- Row 1 -->
  <tr>
    <td colspan="3" style="border:1px solid #e8ecef;border-top:none;padding:0;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr style="border-bottom:1px solid #e8ecef;">
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#fff0f0;color:#c0392b;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">CRITICAL</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">Brute Force &amp;<br>Credential Stuffing</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            Bots hammer your <code style="background:#f4f4f4;padding:1px 4px;border-radius:3px;">/wp-login.php</code> with thousands of username/password combos until one works. Homey's login page is a prime target.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; Rate limiting + IP lockout</span><br>
            <span style="font-size:12px;color:#718096;">Login attempts capped; offending IPs auto-blocked after 5 failures.</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid #e8ecef;">
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#fff0f0;color:#c0392b;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">CRITICAL</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">SQL Injection</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            Malicious SQL injected through search filters, booking forms, or URL parameters to dump your user database, emails, and passwords.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; Parameterised queries + WAF rules</span><br>
            <span style="font-size:12px;color:#718096;">All inputs sanitised; firewall blocks SQL-pattern requests.</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid #e8ecef;">
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#fff0f0;color:#c0392b;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">CRITICAL</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">Malware &amp;<br>Backdoor Injection</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            Hidden PHP files planted in your theme or uploads folder give attackers permanent remote access — even after you change passwords.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; File integrity monitoring</span><br>
            <span style="font-size:12px;color:#718096;">PHP execution blocked in uploads; file changes trigger instant alerts.</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid #e8ecef;">
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#fff8e6;color:#d68910;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">HIGH</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">Cross-Site Scripting (XSS)</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            Script injected into listing descriptions, reviews, or contact forms that runs in your guests' browsers — stealing sessions or redirecting to phishing pages.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; Output escaping + CSP headers</span><br>
            <span style="font-size:12px;color:#718096;">Strict Content Security Policy prevents inline script execution.</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid #e8ecef;">
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#fff8e6;color:#d68910;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">HIGH</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">Outdated Plugin &amp;<br>Theme Exploits</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            Known CVEs in outdated plugins (contact forms, sliders, caching plugins) are published online. Bots scan for them within hours of disclosure.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; Managed update schedule</span><br>
            <span style="font-size:12px;color:#718096;">Plugins reviewed and updated before exploits go public.</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid #e8ecef;">
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#fff8e6;color:#d68910;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">HIGH</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">XML-RPC Abuse</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            WordPress's <code style="background:#f4f4f4;padding:1px 4px;border-radius:3px;">xmlrpc.php</code> is exploited for DDoS amplification and bulk brute-force — your server becomes a weapon against others, and your IP gets blacklisted.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; XML-RPC disabled at server level</span><br>
            <span style="font-size:12px;color:#718096;">Blocked in Nginx/Apache config — not just at plugin level.</span>
          </td>
        </tr>
        <tr style="border-bottom:1px solid #e8ecef;">
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#fff8e6;color:#d68910;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">HIGH</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">File Upload Exploits</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            Malicious PHP files disguised as images uploaded via Homey's listing media uploader — then executed via a direct URL to take over the server.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; MIME validation + PHP execution block</span><br>
            <span style="font-size:12px;color:#718096;">Uploads folder denies script execution at server level.</span>
          </td>
        </tr>
        <tr>
          <td width="28%" style="padding:14px 16px;vertical-align:top;">
            <span style="display:inline-block;background:#f0f4ff;color:#2c5282;font-size:11px;font-weight:700;padding:3px 8px;border-radius:4px;letter-spacing:0.5px;">MEDIUM</span><br>
            <strong style="font-size:13px;color:#1a1a2e;display:block;margin-top:6px;">Sensitive File Exposure<br>(wp-config / debug logs)</strong>
          </td>
          <td width="38%" style="padding:14px 16px;vertical-align:top;font-size:13px;color:#4a5568;line-height:1.6;border-left:1px solid #f0f0f0;">
            Misconfigured servers serve <code style="background:#f4f4f4;padding:1px 4px;border-radius:3px;">wp-config.php</code> or <code style="background:#f4f4f4;padding:1px 4px;border-radius:3px;">debug.log</code> publicly, exposing your database credentials and API keys.
          </td>
          <td width="34%" style="padding:14px 16px;vertical-align:top;border-left:1px solid #f0f0f0;">
            <span style="color:#27ae60;font-weight:700;font-size:13px;">&#10003; Server config + permissions hardened</span><br>
            <span style="font-size:12px;color:#718096;">Sensitive files blocked from public access; debug mode off in production.</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>

</table>

<!-- ── Result block ────────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:8px 0 28px;">
  <tr>
    <td style="background:#f0fff4;border:1px solid #9ae6b4;border-radius:8px;padding:20px 22px;">
      <table border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td style="font-size:22px;padding-right:12px;vertical-align:middle;">🛡️</td>
          <td>
            <strong style="font-size:14px;color:#1a5c34;display:block;margin-bottom:4px;">Every fix above is already live on client sites we manage.</strong>
            <span style="font-size:13px;color:#276749;line-height:1.65;">We've applied this exact security stack to Homey, Houzez, and Listeo sites. None of the sites we hardened have been compromised since. The full audit and hardening usually takes 1–2 business days.</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!-- ── CTA ────────────────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:26px 24px;text-align:center;">
      <p style="margin:0 0 6px;font-size:17px;font-weight:700;color:#ffffff;">Is your Homey site protected?</p>
      <p style="margin:0 0 20px;font-size:13px;color:rgba(255,255,255,0.75);line-height:1.7;">Reply to this email with your site URL and we'll run a free surface-level audit — no commitment. If we find issues, we'll send a report and a fixed-price quote to resolve them.</p>
      <a href="mailto:sales@webpenter.com?subject=WordPress Security Audit Request" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 32px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Request Free Security Audit &rarr;</a>
    </td>
  </tr>
</table>

<p style="margin-top:28px;">Questions? Just reply — we're happy to discuss any specific concern about your site.</p>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a> &nbsp;&bull;&nbsp; <a href="mailto:sales@webpenter.com">sales@webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

If your Homey booking site is live, it's already being probed — automatically, every single day.

Here's an honest breakdown of the 8 most common attacks on WordPress/Homey sites and what we've already deployed to stop each one on client sites:

1. BRUTE FORCE & CREDENTIAL STUFFING [CRITICAL]
   Bots hammer /wp-login.php with thousands of password combos.
   Our fix: Rate limiting + IP lockout after 5 failed attempts.

2. SQL INJECTION [CRITICAL]
   Malicious SQL injected via search filters or booking forms to dump your database.
   Our fix: Parameterised queries everywhere + WAF firewall rules.

3. MALWARE & BACKDOOR INJECTION [CRITICAL]
   Hidden PHP files planted in your theme/uploads for permanent remote access.
   Our fix: File integrity monitoring + PHP execution blocked in uploads.

4. CROSS-SITE SCRIPTING / XSS [HIGH]
   Scripts injected into listings or reviews that run in your guests' browsers.
   Our fix: Output escaping + strict Content Security Policy headers.

5. OUTDATED PLUGIN EXPLOITS [HIGH]
   Published CVEs in old plugins scanned by bots within hours of disclosure.
   Our fix: Managed update schedule before exploits go public.

6. XML-RPC ABUSE [HIGH]
   xmlrpc.php exploited for DDoS amplification and bulk brute-force.
   Our fix: Disabled at server level (Nginx/Apache config, not just plugin).

7. FILE UPLOAD EXPLOITS [HIGH]
   PHP files disguised as images uploaded via Homey's media uploader.
   Our fix: MIME validation + PHP execution denied in uploads folder.

8. SENSITIVE FILE EXPOSURE [MEDIUM]
   wp-config.php or debug.log served publicly, exposing DB credentials.
   Our fix: Server config hardened, debug mode off in production.

────────────────────────────────────────────────────────────────────────
Every fix above is already live on the Homey and WordPress sites we manage.
None of the sites we hardened have been compromised since. Full audit and
hardening typically takes 1–2 business days.

IS YOUR SITE PROTECTED?

Reply with your site URL for a free surface-level audit — no commitment.
We'll send a report and a fixed-price quote if we find issues.

Email: sales@webpenter.com
Subject: WordPress Security Audit Request

────────────────────────────────────────────────────────────────────────
Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com | sales@webpenter.com
TEXT,
        ]);

        // ── Campaign 3: Houzilo Real Estate Platform ──────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzilo Platform — Real Estate Upgrade'], [
            'subject'    => 'Built Your Own Real Estate Platform — No WordPress Needed',
            'from_name'  => 'Rashid | Webpenter',
            'from_email' => 'sales@webpenter.com',
            'target_role'=> 'homey_client',
            'status'     => 'draft',
            'html_body'  => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>If you've ever felt limited by WordPress or wanted more control over your booking and property platform, you're going to like this.</p>

<p>We built <strong><a href="https://houzilo.com">Houzilo</a></strong> — a cutting-edge <strong>Laravel &amp; Vue 3</strong> platform for real estate, booking, and rental businesses. It's what we use for our own clients who've outgrown WordPress.</p>

<p><strong>What you get with Houzilo:</strong></p>
<ul>
  <li>Advanced search with map integration &amp; radius filter</li>
  <li>Full CRM: leads, deals, tours, enquiries</li>
  <li>Stripe subscription billing built-in</li>
  <li>500+ active installations, 50,000+ properties listed</li>
  <li>Multi-language, mobile-first, 99.9% uptime</li>
  <li>Complete admin panel — no coding needed</li>
</ul>

<p>Unlike WordPress themes, Houzilo gives you full source code, direct database control, and scales to millions of listings.</p>

<p><a class="btn" href="https://houzilo.com">Explore Houzilo</a></p>

<p>Want a live demo or a migration consultation from your current Homey site? Reply and I'll book a 20-minute call.</p>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a> &nbsp;|&nbsp;
<a href="mailto:sales@webpenter.com">sales@webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

If you've ever felt limited by WordPress or wanted more control over your booking platform, you're going to like this.

We built Houzilo (https://houzilo.com) — a Laravel & Vue 3 platform for real estate, booking, and rental businesses.

WHAT YOU GET:
- Advanced search with map integration & radius filter
- Full CRM: leads, deals, tours, enquiries
- Stripe subscription billing built-in
- 500+ active installations, 50,000+ properties listed
- Multi-language, mobile-first, 99.9% uptime
- Complete admin panel — no coding needed

Want a live demo or migration consultation? Reply and I'll book a 20-minute call.

Best regards,
Rashid Bukhari
CEO, Webpenter
https://houzilo.com | https://webpenter.com
TEXT,
        ]);

        // ── Campaign 6: WordPress Speed Optimisation ──────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'WordPress Speed Optimisation — Homey Sites'], [
            'subject'     => 'Your Homey Site Scored 23/100 on Google — Let\'s Fix That',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'homey_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>We ran a speed audit on a sample of Homey booking sites last month. The average Google PageSpeed score was <strong>23 out of 100</strong>. Slow pages kill bookings — <strong>53% of visitors leave a site that takes more than 3 seconds to load.</strong></p>

<!-- ── Speed Score Visual ─────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0 28px;">
  <tr>
    <td style="background:#0f172a;border-radius:10px;padding:28px 24px 24px;">

      <!-- Label row -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
        <tr>
          <td style="text-align:center;">
            <span style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.5);letter-spacing:1.5px;text-transform:uppercase;">TYPICAL HOMEY SITE — BEFORE OPTIMISATION</span>
          </td>
        </tr>
        <tr>
          <td style="text-align:center;padding-top:10px;">
            <span style="font-size:64px;font-weight:900;color:#ef4444;line-height:1;">23</span>
            <span style="font-size:28px;font-weight:400;color:rgba(255,255,255,0.4);line-height:1;"> / 100</span>
          </td>
        </tr>
        <tr>
          <td style="text-align:center;padding-top:6px;">
            <span style="display:inline-block;background:#ef4444;color:#fff;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;">Poor</span>
          </td>
        </tr>
      </table>

      <!-- Score bar -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:6px;">
        <tr>
          <td width="50%" style="background:#ef4444;height:10px;border-radius:6px 0 0 6px;"></td>
          <td width="35%" style="background:#f97316;height:10px;"></td>
          <td width="15%" style="background:#22c55e;height:10px;border-radius:0 6px 6px 0;"></td>
        </tr>
      </table>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="50%" style="font-size:10px;color:rgba(255,255,255,0.4);text-align:left;padding-top:4px;">0 — Poor</td>
          <td width="35%" style="font-size:10px;color:rgba(255,255,255,0.4);text-align:center;padding-top:4px;">50 — Needs Work</td>
          <td width="15%" style="font-size:10px;color:rgba(255,255,255,0.4);text-align:right;padding-top:4px;">90 — Good</td>
        </tr>
      </table>

      <!-- After row -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:20px;border-top:1px solid rgba(255,255,255,0.08);padding-top:18px;">
        <tr>
          <td style="text-align:center;">
            <span style="font-size:11px;font-weight:700;color:rgba(255,255,255,0.5);letter-spacing:1.5px;text-transform:uppercase;">AFTER WEBPENTER OPTIMISATION</span>
          </td>
        </tr>
        <tr>
          <td style="text-align:center;padding-top:10px;">
            <span style="font-size:64px;font-weight:900;color:#22c55e;line-height:1;">94</span>
            <span style="font-size:28px;font-weight:400;color:rgba(255,255,255,0.4);line-height:1;"> / 100</span>
          </td>
        </tr>
        <tr>
          <td style="text-align:center;padding-top:6px;">
            <span style="display:inline-block;background:#22c55e;color:#fff;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;letter-spacing:1px;text-transform:uppercase;">Excellent</span>
          </td>
        </tr>
      </table>
      <!-- After bar -->
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:12px;">
        <tr>
          <td width="94%" style="background:#22c55e;height:10px;border-radius:6px 0 0 6px;"></td>
          <td width="6%"  style="background:rgba(255,255,255,0.12);height:10px;border-radius:0 6px 6px 0;"></td>
        </tr>
      </table>

    </td>
  </tr>
</table>

<!-- ── Before / After Metrics Table ──────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:28px;border:1px solid #e8ecef;border-radius:8px;overflow:hidden;">
  <!-- Header -->
  <tr>
    <td style="background:#f8fafc;padding:10px 16px;border-bottom:1px solid #e8ecef;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="40%" style="font-size:11px;font-weight:700;color:#64748b;letter-spacing:1px;text-transform:uppercase;">METRIC</td>
          <td width="30%" style="font-size:11px;font-weight:700;color:#ef4444;letter-spacing:1px;text-transform:uppercase;text-align:center;">BEFORE</td>
          <td width="30%" style="font-size:11px;font-weight:700;color:#22c55e;letter-spacing:1px;text-transform:uppercase;text-align:center;">AFTER</td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Row 1 -->
  <tr>
    <td style="padding:0 16px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f0f0f0;">
        <tr>
          <td width="40%" style="padding:13px 0;font-size:13px;font-weight:600;color:#1e293b;">Page Load Time</td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">8.2 s</span>
          </td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#f0fdf4;color:#16a34a;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">0.9 s</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Row 2 -->
  <tr>
    <td style="padding:0 16px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f0f0f0;">
        <tr>
          <td width="40%" style="padding:13px 0;font-size:13px;font-weight:600;color:#1e293b;">PageSpeed Score</td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">23 / 100</span>
          </td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#f0fdf4;color:#16a34a;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">94 / 100</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Row 3 -->
  <tr>
    <td style="padding:0 16px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f0f0f0;">
        <tr>
          <td width="40%" style="padding:13px 0;font-size:13px;font-weight:600;color:#1e293b;">LCP — Largest Content</td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">6.8 s</span>
          </td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#f0fdf4;color:#16a34a;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">1.1 s</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Row 4 -->
  <tr>
    <td style="padding:0 16px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-bottom:1px solid #f0f0f0;">
        <tr>
          <td width="40%" style="padding:13px 0;font-size:13px;font-weight:600;color:#1e293b;">Time to First Byte (TTFB)</td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">2.4 s</span>
          </td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#f0fdf4;color:#16a34a;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">0.18 s</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
  <!-- Row 5 -->
  <tr>
    <td style="padding:0 16px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="40%" style="padding:13px 0;font-size:13px;font-weight:600;color:#1e293b;">Total Page Size</td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#fef2f2;color:#dc2626;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">5.1 MB</span>
          </td>
          <td width="30%" style="padding:13px 0;text-align:center;">
            <span style="display:inline-block;background:#f0fdf4;color:#16a34a;font-size:13px;font-weight:700;padding:3px 10px;border-radius:4px;">0.8 MB</span>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<!-- ── What slows your Homey site down ───────────────────────────────── -->
<p style="margin:0 0 6px;font-size:16px;font-weight:700;color:#0f172a;">What's slowing your Homey site down</p>
<p style="margin:0 0 20px;font-size:14px;color:#475569;line-height:1.7;">Homey is a feature-rich theme — that's also what makes it heavy. These are the top culprits we find on every unoptimised Homey site:</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
  <!-- Row 1 -->
  <tr>
    <td width="50%" style="padding:0 8px 14px 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #fecaca;border-radius:8px;background:#fff;">
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#fef2f2;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🖼️</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">Unoptimised<br>Listing Images</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Full-size JPEGs served as-is. A single listing page loads 3–8 MB of images alone.</p>
        </td></tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 14px 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #fecaca;border-radius:8px;background:#fff;">
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#fef2f2;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🗄️</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">No Page or<br>Object Caching</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Every page visit re-runs all PHP and database queries from scratch — even for the same content.</p>
        </td></tr>
      </table>
    </td>
  </tr>
  <!-- Row 2 -->
  <tr>
    <td width="50%" style="padding:0 8px 14px 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #fecaca;border-radius:8px;background:#fff;">
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#fef2f2;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🔌</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">Bloated Plugin<br>Stack</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">20–40 plugins loading scripts and styles on every page, most of which aren't needed on that page at all.</p>
        </td></tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 14px 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #fecaca;border-radius:8px;background:#fff;">
        <tr><td style="padding:16px 18px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#fef2f2;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🌍</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">No CDN — Assets<br>Served from One Server</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Every image, script, and CSS file travels from one server to every visitor worldwide — adding 1–3 s of extra latency.</p>
        </td></tr>
      </table>
    </td>
  </tr>
</table>

<!-- ── What we optimise ───────────────────────────────────────────────── -->
<p style="margin:0 0 6px;font-size:16px;font-weight:700;color:#0f172a;">The 6 optimisations we run on every site</p>
<p style="margin:0 0 20px;font-size:14px;color:#475569;line-height:1.7;">This is the exact stack we've deployed on Homey sites to bring load times under 1 second:</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
  <!-- Row 1 -->
  <tr>
    <td width="50%" style="padding:0 8px 14px 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:8px;">
        <tr><td style="padding:16px 18px 14px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#eff6ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">⚡</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">Full-Page &amp;<br>Object Caching</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Redis or Memcached object cache + full HTML page cache. TTFB drops to under 200 ms instantly.</p>
        </td></tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 14px 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:8px;">
        <tr><td style="padding:16px 18px 14px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#eff6ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🖼️</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">Image Optimisation<br>&amp; WebP Conversion</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Bulk compress all listing images, convert to WebP, enable lazy loading. Page weight cut by 80%.</p>
        </td></tr>
      </table>
    </td>
  </tr>
  <!-- Row 2 -->
  <tr>
    <td width="50%" style="padding:0 8px 14px 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:8px;">
        <tr><td style="padding:16px 18px 14px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#eff6ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🌐</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">CDN Integration<br>(Cloudflare / BunnyCDN)</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Static assets delivered from servers closest to each visitor. Eliminates geographic latency worldwide.</p>
        </td></tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 14px 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:8px;">
        <tr><td style="padding:16px 18px 14px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#eff6ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">✂️</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">CSS / JS Minify,<br>Defer &amp; Async</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Unused CSS removed, scripts deferred so they don't block the first paint. Render-blocking eliminated.</p>
        </td></tr>
      </table>
    </td>
  </tr>
  <!-- Row 3 -->
  <tr>
    <td width="50%" style="padding:0 8px 0 0;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:8px;">
        <tr><td style="padding:16px 18px 14px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#eff6ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🗄️</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">Database<br>Optimisation</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">Clean up autoloaded data, expired transients, post revisions. Slow queries identified and indexed.</p>
        </td></tr>
      </table>
    </td>
    <td width="50%" style="padding:0 0 0 8px;vertical-align:top;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border:1px solid #e2e8f0;border-radius:8px;">
        <tr><td style="padding:16px 18px 14px;">
          <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
            <tr>
              <td style="width:36px;height:36px;background:#eff6ff;border-radius:8px;text-align:center;vertical-align:middle;font-size:18px;">🔌</td>
              <td style="width:10px;"></td>
              <td style="font-size:13px;font-weight:700;color:#0f172a;line-height:1.3;">Plugin Audit &amp;<br>Lightweight Swap</td>
            </tr>
          </table>
          <p style="margin:0;font-size:12px;color:#64748b;line-height:1.6;">We audit every plugin, remove dead weight, and replace heavy ones with lighter alternatives that do the same job.</p>
        </td></tr>
      </table>
    </td>
  </tr>
</table>

<!-- ── Core Web Vitals explainer ──────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:28px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
  <tr>
    <td style="padding:20px 22px 16px;">
      <p style="margin:0 0 14px;font-size:14px;font-weight:700;color:#0f172a;">Why Core Web Vitals matter for your bookings</p>
      <table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="33%" style="padding:0 8px 0 0;vertical-align:top;text-align:center;">
            <div style="font-size:22px;margin-bottom:6px;">🎯</div>
            <strong style="font-size:13px;color:#0f172a;display:block;margin-bottom:4px;">LCP</strong>
            <span style="font-size:11px;color:#64748b;line-height:1.5;display:block;">Largest Contentful Paint — how fast your hero image and listing photos appear. Google wants this under <strong>2.5 s</strong>.</span>
          </td>
          <td width="33%" style="padding:0 8px;vertical-align:top;text-align:center;border-left:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
            <div style="font-size:22px;margin-bottom:6px;">📐</div>
            <strong style="font-size:13px;color:#0f172a;display:block;margin-bottom:4px;">CLS</strong>
            <span style="font-size:11px;color:#64748b;line-height:1.5;display:block;">Cumulative Layout Shift — elements jumping around while the page loads, making guests click the wrong button. Target: <strong>under 0.1</strong>.</span>
          </td>
          <td width="33%" style="padding:0 0 0 8px;vertical-align:top;text-align:center;">
            <div style="font-size:22px;margin-bottom:6px;">⚡</div>
            <strong style="font-size:13px;color:#0f172a;display:block;margin-bottom:4px;">INP</strong>
            <span style="font-size:11px;color:#64748b;line-height:1.5;display:block;">Interaction to Next Paint — lag when a guest taps "Book Now" or searches dates. Google wants this under <strong>200 ms</strong>.</span>
          </td>
        </tr>
      </table>
      <p style="margin:14px 0 0;font-size:12px;color:#94a3b8;line-height:1.6;border-top:1px solid #e2e8f0;padding-top:12px;">
        Google uses Core Web Vitals as a <strong>direct ranking signal</strong>. A slow Homey site ranks lower in search results — costing you organic bookings every single day.
      </p>
    </td>
  </tr>
</table>

<!-- ── Social proof ───────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:28px;">
  <tr>
    <td width="33%" style="text-align:center;padding:0 8px 0 0;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#0f172a;border-radius:8px;padding:20px 12px;">
        <tr><td style="padding:20px 12px;text-align:center;">
          <div style="font-size:32px;font-weight:900;color:#22c55e;line-height:1;">9&times;</div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:6px;line-height:1.4;">faster average<br>page load</div>
        </td></tr>
      </table>
    </td>
    <td width="33%" style="text-align:center;padding:0 4px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#0f172a;border-radius:8px;">
        <tr><td style="padding:20px 12px;text-align:center;">
          <div style="font-size:32px;font-weight:900;color:#22c55e;line-height:1;">80%</div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:6px;line-height:1.4;">reduction in<br>page weight</div>
        </td></tr>
      </table>
    </td>
    <td width="33%" style="text-align:center;padding:0 0 0 8px;">
      <table width="100%" border="0" cellpadding="0" cellspacing="0" style="background:#0f172a;border-radius:8px;">
        <tr><td style="padding:20px 12px;text-align:center;">
          <div style="font-size:32px;font-weight:900;color:#22c55e;line-height:1;">94</div>
          <div style="font-size:11px;color:rgba(255,255,255,0.6);margin-top:6px;line-height:1.4;">avg. PageSpeed<br>score after</div>
        </td></tr>
      </table>
    </td>
  </tr>
</table>

<!-- ── CTA ────────────────────────────────────────────────────────────── -->
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="background:#0f172a;border-radius:10px;padding:28px 24px;text-align:center;">
      <p style="margin:0 0 4px;font-size:18px;font-weight:800;color:#ffffff;line-height:1.3;">Find out your site's real score — free</p>
      <p style="margin:0 0 22px;font-size:13px;color:rgba(255,255,255,0.65);line-height:1.7;">Reply with your site URL and we'll run a full PageSpeed + Core Web Vitals audit, send you the results, and give you a fixed-price quote to fix everything.</p>
      <a href="mailto:sales@webpenter.com?subject=WordPress Speed Audit Request" style="display:inline-block;background:#22c55e;color:#fff;padding:14px 36px;border-radius:6px;font-weight:800;font-size:15px;text-decoration:none;letter-spacing:0.3px;">Get My Free Speed Audit &rarr;</a>
      <p style="margin:18px 0 0;font-size:12px;color:rgba(255,255,255,0.4);">Takes 24 hours &bull; No commitment &bull; Full report included</p>
    </td>
  </tr>
</table>

<p style="margin-top:28px;">Questions? Just reply — happy to walk through any of this on a quick call.</p>

<p>Best regards,<br>
<strong>Rashid Bukhari</strong><br>
CEO, Webpenter<br>
<a href="https://webpenter.com">webpenter.com</a> &nbsp;&bull;&nbsp; <a href="mailto:sales@webpenter.com">sales@webpenter.com</a></p>
HTML,
            'text_body' => <<<'TEXT'
Hi {{first_name}},

We ran a speed audit on Homey booking sites last month. The average Google PageSpeed score was 23 out of 100. Slow pages kill bookings — 53% of visitors leave a site that takes more than 3 seconds to load.

TYPICAL RESULTS AFTER OUR OPTIMISATION:
────────────────────────────────────────
Metric                Before    After
────────────────────────────────────────
Page Load Time        8.2 s     0.9 s
PageSpeed Score       23/100    94/100
LCP (Largest Content) 6.8 s     1.1 s
Time to First Byte    2.4 s     0.18 s
Total Page Size       5.1 MB    0.8 MB
────────────────────────────────────────

WHAT'S SLOWING YOUR HOMEY SITE DOWN:
1. Unoptimised listing images (3–8 MB per page)
2. No page or object caching (PHP + DB runs on every visit)
3. Bloated plugin stack loading scripts on every page
4. No CDN — all assets served from one server worldwide

THE 6 OPTIMISATIONS WE RUN ON EVERY SITE:
1. Full-Page & Object Caching (Redis/Memcached) — TTFB drops under 200ms
2. Image Optimisation & WebP Conversion — page weight cut by 80%
3. CDN Integration (Cloudflare / BunnyCDN) — eliminates geographic latency
4. CSS / JS Minify, Defer & Async — render-blocking eliminated
5. Database Optimisation — expired transients, slow queries, missing indexes
6. Plugin Audit & Lightweight Swap — dead weight removed

WHY CORE WEB VITALS MATTER:
- LCP (Largest Contentful Paint): Google wants under 2.5 s
- CLS (Cumulative Layout Shift): elements jumping while page loads — target under 0.1
- INP (Interaction to Next Paint): lag on "Book Now" tap — target under 200 ms
Google uses these as a direct ranking signal. A slow Homey site ranks lower, costing you organic bookings every day.

OUR RESULTS ACROSS CLIENT SITES:
- 9× faster average page load
- 80% reduction in page weight
- 94 avg. PageSpeed score after optimisation

────────────────────────────────────────────────────────────────────────
GET A FREE SPEED AUDIT

Reply with your site URL — we'll run a full PageSpeed + Core Web Vitals audit,
send you the results, and give a fixed-price quote to fix everything.

Email: sales@webpenter.com
Subject: WordPress Speed Audit Request

Takes 24 hours · No commitment · Full report included.
────────────────────────────────────────────────────────────────────────
Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com | sales@webpenter.com
TEXT,
        ]);
    }
}
