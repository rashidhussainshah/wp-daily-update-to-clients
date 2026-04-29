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
    }
}
