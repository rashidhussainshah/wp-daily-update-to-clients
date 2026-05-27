<?php

namespace Database\Seeders;

use App\Models\EmailCampaign;
use Illuminate\Database\Seeder;

class HouzezCampaignSeeder extends Seeder
{
    public function run(): void
    {
        // ── Campaign 1: Houzi Mobile App ────────────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzez — Houzi Mobile App (iOS & Android)'], [
            'subject'     => 'Your Houzez Site, Now a Branded iOS & Android App — Ready in 7 Days',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'houzez_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Over 70% of property searches now happen on mobile. If your Houzez site isn&rsquo;t an app, you&rsquo;re invisible to the buyers and renters who search exclusively on their phones &mdash; and your competitors who <em>do</em> have an app are getting those leads instead.</p>

<p>We&rsquo;ve built and deployed <strong>Houzi</strong> &mdash; a white-label React Native app (iOS &amp; Android) that connects directly to your Houzez site. Your branding, your data, your domain. No rebuilding your backend.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">
  <tr>
    <td width="48%" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#1a1a2e;">What the app includes:</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Property search with map &amp; filters</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Push notifications for saved searches</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Direct agent messaging</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Saved properties &amp; shortlists</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Tour booking &amp; enquiry forms</td></tr>
      </table>
    </td>
    <td width="4%"></td>
    <td width="48%" style="background:#1a1a2e;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#ffffff;">How we deliver it:</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Connects to your live Houzez data via API</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Your logo, colours, and app store name</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Published to App Store &amp; Google Play</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Ready in 7 business days</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Full source code included</td></tr>
      </table>
    </td>
  </tr>
</table>

<p>We&rsquo;ve delivered Houzi apps for Houzez clients across the UK, UAE, and Pakistan. The listing is live on CodeCanyon with <strong>500+ sales</strong> &mdash; but we handle the full setup and customisation for you so you don&rsquo;t have to touch any code.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 8px;font-size:16px;font-weight:700;color:#ffffff;">Ready to have your own real estate app?</p>
      <p style="margin:0 0 18px;font-size:13px;color:rgba(255,255,255,0.75);line-height:1.6;">Reply and I&rsquo;ll send a demo walkthrough of Houzi running on a live Houzez site, plus a setup quote.</p>
      <a href="mailto:sales@webpenter.com?subject=Houzi Mobile App Enquiry" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Get My Houzez App &rarr;</a>
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

Over 70% of property searches now happen on mobile. If your Houzez site isn't an app, you're invisible to buyers and renters who search exclusively on their phones.

We've built and deployed Houzi — a white-label React Native app (iOS & Android) that connects directly to your Houzez site. Your branding, your data, your domain.

WHAT THE APP INCLUDES:
✓ Property search with map & filters
✓ Push notifications for saved searches
✓ Direct agent messaging
✓ Saved properties & shortlists
✓ Tour booking & enquiry forms

HOW WE DELIVER IT:
- Connects to your live Houzez data via API
- Your logo, colours, and app store name
- Published to App Store & Google Play
- Ready in 7 business days
- Full source code included

500+ sales on CodeCanyon. We handle the full setup and customisation for you.

Reply and I'll send a demo walkthrough and a setup quote.

Email: sales@webpenter.com
Subject: Houzi Mobile App Enquiry

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 2: Lead Automation & CRM ──────────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzez — Lead Automation & CRM'], [
            'subject'     => '90% of Real Estate Leads Go Cold Because Nobody Followed Up in Time',
            'from_name'   => 'Zahid Khurshid | Webpenter',
            'from_email'  => 'zaars59208@gmail.com',
            'target_role' => 'houzez_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Studies consistently show that a lead contacted within 5 minutes is <strong>9 times more likely to convert</strong> than one contacted after 30 minutes. Yet most real estate businesses take hours &mdash; or days &mdash; to follow up, because someone has to remember to do it manually.</p>

<p>We&rsquo;ve built a <strong>Lead Automation &amp; CRM integration for Houzez</strong> that follows up with every enquiry automatically, the moment it arrives &mdash; so no lead goes cold while you&rsquo;re showing another property.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;">
  <tr><td style="padding:22px 24px;">
    <p style="margin:0 0 16px;font-size:15px;font-weight:700;color:#1a1a2e;">What gets automated:</p>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr>
        <td style="padding:0 16px 14px 0;vertical-align:top;width:48%;">
          <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#0066cc;text-transform:uppercase;letter-spacing:0.5px;">Instant response</p>
          <p style="margin:0;font-size:13px;color:#4a5568;line-height:1.6;">Lead submits enquiry &rarr; auto-reply in under 60 seconds &rarr; follow-up sequence begins &mdash; while you&rsquo;re still with another client.</p>
        </td>
        <td style="padding:0 0 14px 16px;vertical-align:top;width:48%;border-left:1px solid #e2e8f0;">
          <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#0066cc;text-transform:uppercase;letter-spacing:0.5px;">Drip follow-up</p>
          <p style="margin:0;font-size:13px;color:#4a5568;line-height:1.6;">Day 1, Day 3, Day 7 follow-up emails &amp; WhatsApp messages go out automatically until the lead replies or books a viewing.</p>
        </td>
      </tr>
      <tr>
        <td style="padding:0 16px 0 0;vertical-align:top;border-top:1px solid #e2e8f0;padding-top:14px;">
          <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#0066cc;text-transform:uppercase;letter-spacing:0.5px;">Lead scoring</p>
          <p style="margin:0;font-size:13px;color:#4a5568;line-height:1.6;">Leads scored by budget, location preference, and engagement &mdash; your agents focus on the hottest ones first.</p>
        </td>
        <td style="padding:0 0 0 16px;vertical-align:top;border-left:1px solid #e2e8f0;border-top:1px solid #e2e8f0;padding-top:14px;">
          <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#0066cc;text-transform:uppercase;letter-spacing:0.5px;">Houzez CRM sync</p>
          <p style="margin:0;font-size:13px;color:#4a5568;line-height:1.6;">Every interaction logged in Houzez &mdash; call notes, email history, viewing bookings &mdash; all in one place per lead.</p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>

<p>We&rsquo;ve deployed this for Houzez agencies. Average response time dropped from 4 hours to under 60 seconds. Viewing bookings increased by 35% in the first month.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 18px;font-size:14px;color:rgba(255,255,255,0.85);line-height:1.6;">Reply and I&rsquo;ll show you a live demo of the automation running on a Houzez site and scope out what it would look like for your team.</p>
      <a href="mailto:zaars59208@gmail.com?subject=Houzez Lead Automation Enquiry" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Automate My Lead Follow-Up &rarr;</a>
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

A lead contacted within 5 minutes is 9 times more likely to convert than one contacted after 30 minutes. Yet most real estate businesses take hours to follow up, because someone has to remember to do it manually.

We've built a Lead Automation & CRM integration for Houzez that follows up with every enquiry automatically, the moment it arrives.

WHAT GETS AUTOMATED:

Instant response:
Lead submits enquiry → auto-reply in under 60 seconds → follow-up sequence begins — while you're still with another client.

Drip follow-up:
Day 1, Day 3, Day 7 messages go out automatically until the lead replies or books a viewing.

Lead scoring:
Leads scored by budget, location, and engagement — your agents focus on the hottest ones first.

Houzez CRM sync:
Every interaction logged in Houzez — call notes, emails, viewing bookings — all in one place per lead.

Results from deployed clients:
- Average response time: from 4 hours → under 60 seconds
- Viewing bookings: +35% in first month

Reply and I'll show you a live demo and scope what it would look like for your team.

Email: zaars59208@gmail.com
Subject: Houzez Lead Automation Enquiry

Best regards,
Zahid Khurshid
Senior Developer, Webpenter
https://webpenter.com | WhatsApp: +923336151813
TEXT,
        ]);

        // ── Campaign 3: AI Property Listing Writer ─────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzez — AI Property Listing Writer'], [
            'subject'     => 'Write 50 SEO-Optimised Property Listings in Under an Hour',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'houzez_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Writing a single property listing &mdash; one that actually ranks on Google and convinces a buyer to enquire &mdash; takes 20&ndash;40 minutes if you&rsquo;re doing it properly. Multiply that by your full portfolio and you&rsquo;re looking at days of writing work, just to get your properties online.</p>

<p>We&rsquo;ve built an <strong>AI Listing Writer integrated directly into Houzez</strong> that generates professional, SEO-optimised property descriptions in under 30 seconds per listing.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">

  <!-- How it works row -->
  <tr>
    <td style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:20px 24px;margin-bottom:16px;">
      <p style="margin:0 0 14px;font-size:15px;font-weight:700;color:#1a1a2e;">How it works &mdash; 3 steps:</p>
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="30%" style="text-align:center;padding:0 10px 0 0;vertical-align:top;">
            <div style="width:36px;height:36px;background:#0066cc;border-radius:50%;display:inline-block;text-align:center;line-height:36px;color:#fff;font-weight:700;font-size:16px;">1</div>
            <p style="margin:10px 0 0;font-size:12px;color:#4a5568;line-height:1.6;">Enter basic property details: bedrooms, location, key features</p>
          </td>
          <td width="30%" style="text-align:center;padding:0 5px;vertical-align:top;border-left:1px solid #e2e8f0;border-right:1px solid #e2e8f0;">
            <div style="width:36px;height:36px;background:#0066cc;border-radius:50%;display:inline-block;text-align:center;line-height:36px;color:#fff;font-weight:700;font-size:16px;">2</div>
            <p style="margin:10px 0 0;font-size:12px;color:#4a5568;line-height:1.6;">AI generates a full description &mdash; tone, keywords, highlights, CTA</p>
          </td>
          <td width="30%" style="text-align:center;padding:0 0 0 10px;vertical-align:top;">
            <div style="width:36px;height:36px;background:#0066cc;border-radius:50%;display:inline-block;text-align:center;line-height:36px;color:#fff;font-weight:700;font-size:16px;">3</div>
            <p style="margin:10px 0 0;font-size:12px;color:#4a5568;line-height:1.6;">Review, edit if needed, and publish directly to your Houzez listing</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>

<p style="margin:20px 0 8px;font-size:15px;font-weight:700;color:#1a1a2e;">What every generated listing includes:</p>
<ul>
  <li>Compelling headline and opening paragraph tailored to the buyer persona</li>
  <li>Feature callouts structured for scannability (the way buyers actually read listings)</li>
  <li>Location highlights with neighbourhood context</li>
  <li>SEO keywords woven in naturally &mdash; ranked for local search terms</li>
  <li>Strong call-to-action to drive enquiries</li>
  <li>Tone selector: formal for luxury, conversational for family homes</li>
</ul>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 8px;font-size:16px;font-weight:700;color:#ffffff;">See it write a listing in real time</p>
      <p style="margin:0 0 18px;font-size:13px;color:rgba(255,255,255,0.75);line-height:1.6;">Reply to this email with a property address or a few basic details &mdash; I&rsquo;ll generate a sample listing for your actual property so you can see the quality before committing.</p>
      <a href="mailto:sales@webpenter.com?subject=Houzez AI Listing Writer Demo" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Generate a Sample Listing &rarr;</a>
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

Writing one property listing that ranks on Google and convinces buyers to enquire takes 20-40 minutes. Multiply that by your full portfolio and you're looking at days of writing work.

We've built an AI Listing Writer integrated directly into Houzez that generates professional, SEO-optimised descriptions in under 30 seconds per listing.

HOW IT WORKS — 3 STEPS:
1. Enter basic property details: bedrooms, location, key features
2. AI generates a full description — tone, keywords, highlights, CTA
3. Review, edit if needed, publish directly to your Houzez listing

EVERY GENERATED LISTING INCLUDES:
- Compelling headline tailored to the buyer persona
- Feature callouts structured for scannability
- Location highlights with neighbourhood context
- SEO keywords woven in naturally
- Strong call-to-action to drive enquiries
- Tone selector: formal for luxury, conversational for family homes

SEE IT WRITE A LISTING IN REAL TIME

Reply with a property address or basic details — I'll generate a sample listing for your actual property so you can see the quality before committing.

Email: sales@webpenter.com
Subject: Houzez AI Listing Writer Demo

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);

        // ── Campaign 4: WhatsApp for Real Estate ───────────────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzez — WhatsApp for Real Estate Enquiries'], [
            'subject'     => 'Buyers Reply to WhatsApp 5× Faster Than Email — Is Your Houzez Site Ready?',
            'from_name'   => 'Zahid Khurshid | Webpenter',
            'from_email'  => 'zaars59208@gmail.com',
            'target_role' => 'houzez_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>In most markets &mdash; especially the Middle East, South Asia, and the UK &mdash; buyers and renters are more comfortable messaging on WhatsApp than filling in a contact form. They&rsquo;re already on their phones. They want a fast, personal reply.</p>

<p>If your Houzez site doesn&rsquo;t have a <strong>WhatsApp contact option on every listing</strong>, you&rsquo;re losing those enquiries to agents who do.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;background:#f0fff4;border:1px solid #86efac;border-radius:8px;">
  <tr><td style="padding:22px 24px;">
    <p style="margin:0 0 14px;font-size:15px;font-weight:700;color:#1a1a2e;">What the WhatsApp integration delivers:</p>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;font-weight:700;margin-right:8px;">&#9679;</span>&ldquo;Chat on WhatsApp&rdquo; button on every property listing page</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;font-weight:700;margin-right:8px;">&#9679;</span>Pre-filled message: &ldquo;Hi, I&rsquo;m interested in [Property Name]&rdquo; &mdash; frictionless for buyers</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;font-weight:700;margin-right:8px;">&#9679;</span>Routes to the assigned agent&rsquo;s WhatsApp for that listing</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;font-weight:700;margin-right:8px;">&#9679;</span>Auto-responder when agent is offline &mdash; with listing details and viewing link</td></tr>
      <tr><td style="padding:5px 0;font-size:14px;color:#2d3748;line-height:1.6;"><span style="color:#25D366;font-weight:700;margin-right:8px;">&#9679;</span>All WhatsApp conversations logged back into Houzez CRM</td></tr>
    </table>
  </td></tr>
</table>

<p>We&rsquo;ve deployed this across Houzez sites in Pakistan, UAE, and the UK. Enquiry rates from listing pages increased by an average of <strong>47%</strong> within the first two weeks.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#25D366;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 8px;font-size:16px;font-weight:700;color:#ffffff;">Add WhatsApp to your Houzez site</p>
      <p style="margin:0 0 18px;font-size:13px;color:rgba(255,255,255,0.9);line-height:1.6;">Reply and I&rsquo;ll send a screenshot of how it looks on a live Houzez listing, plus a setup quote.</p>
      <a href="mailto:zaars59208@gmail.com?subject=Houzez WhatsApp Integration" style="display:inline-block;background:#ffffff;color:#128C7E;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Get WhatsApp on My Site &rarr;</a>
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

In most markets — especially the Middle East, South Asia, and the UK — buyers and renters are more comfortable messaging on WhatsApp than filling in a contact form. If your Houzez site doesn't have a WhatsApp option on every listing, you're losing those enquiries.

WHAT THE WHATSAPP INTEGRATION DELIVERS:
● "Chat on WhatsApp" button on every property listing page
● Pre-filled message: "Hi, I'm interested in [Property Name]" — frictionless for buyers
● Routes to the assigned agent's WhatsApp for that listing
● Auto-responder when agent is offline — with listing details and viewing link
● All conversations logged back into Houzez CRM

Results from deployed sites:
- Enquiry rates from listing pages: +47% within the first two weeks

Reply and I'll send a screenshot of how it looks on a live Houzez listing, plus a setup quote.

Email: zaars59208@gmail.com
Subject: Houzez WhatsApp Integration

Best regards,
Zahid Khurshid
Senior Developer, Webpenter
https://webpenter.com | WhatsApp: +923336151813
TEXT,
        ]);

        // ── Campaign 5: Third-Party Portal Listings Sync ───────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzez — Third-Party Portal Listings Sync'], [
            'subject'     => 'Publish to Bayut, Zameen & Rightmove — From One Houzez Dashboard',
            'from_name'   => 'Zahid Khurshid | Webpenter',
            'from_email'  => 'zaars59208@gmail.com',
            'target_role' => 'houzez_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Right now, listing a property across Bayut, Zameen, Rightmove, Property Finder, or Zillow means logging into five different dashboards, reformatting your data for each one, and then repeating the whole process when any detail changes.</p>

<p>We&rsquo;ve built a <strong>multi-portal sync integration for Houzez</strong> that publishes your listings &mdash; and all future changes &mdash; to every portal automatically from inside your Houzez dashboard.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;border:1px solid #e2e8f0;border-radius:8px;overflow:hidden;">

  <tr>
    <td style="background:#1a1a2e;padding:14px 20px;">
      <p style="margin:0;font-size:13px;font-weight:700;color:#ffffff;letter-spacing:0.5px;">PORTALS WE&rsquo;VE INTEGRATED WITH HOUZEZ</p>
    </td>
  </tr>

  <tr>
    <td style="padding:20px;">
      <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
          <td width="48%" style="padding-right:16px;border-right:1px solid #f0f0f0;vertical-align:top;">
            <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Middle East &amp; South Asia</p>
            <p style="margin:0 0 5px;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; Bayut (UAE)</p>
            <p style="margin:0 0 5px;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; Property Finder (UAE/GCC)</p>
            <p style="margin:0 0 5px;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; Zameen (Pakistan)</p>
            <p style="margin:0;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; OLX (South Asia)</p>
          </td>
          <td width="4%"></td>
          <td width="48%" style="padding-left:16px;vertical-align:top;">
            <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:0.5px;">Europe &amp; Global</p>
            <p style="margin:0 0 5px;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; Rightmove (UK)</p>
            <p style="margin:0 0 5px;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; Zoopla (UK)</p>
            <p style="margin:0 0 5px;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; Zillow (USA)</p>
            <p style="margin:0;font-size:13px;color:#2d3748;line-height:1.6;">&#9679; Custom portal feed (XML/API)</p>
          </td>
        </tr>
      </table>
    </td>
  </tr>

  <tr>
    <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:16px 20px;">
      <p style="margin:0;font-size:13px;color:#4a5568;line-height:1.6;"><strong>Every update you make in Houzez</strong> &mdash; price change, photos, status update &mdash; syncs to all portals within minutes. No manual re-entry. No out-of-date listings on external sites.</p>
    </td>
  </tr>

</table>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 8px;font-size:16px;font-weight:700;color:#ffffff;">Tell me which portals you list on</p>
      <p style="margin:0 0 18px;font-size:13px;color:rgba(255,255,255,0.75);line-height:1.6;">Reply with the portals you currently use &mdash; I&rsquo;ll confirm which ones we support and send a fixed-price integration quote.</p>
      <a href="mailto:zaars59208@gmail.com?subject=Houzez Portal Sync Enquiry" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Get My Portal Sync Quote &rarr;</a>
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

Right now, listing a property across Bayut, Zameen, Rightmove, and other portals means logging into five different dashboards and reformatting your data for each one.

We've built a multi-portal sync integration for Houzez that publishes your listings to every portal automatically from inside your Houzez dashboard.

PORTALS WE'VE INTEGRATED WITH HOUZEZ:

Middle East & South Asia:
● Bayut (UAE)
● Property Finder (UAE/GCC)
● Zameen (Pakistan)
● OLX (South Asia)

Europe & Global:
● Rightmove (UK)
● Zoopla (UK)
● Zillow (USA)
● Custom portal feed (XML/API)

Every update you make in Houzez — price change, photos, status — syncs to all portals within minutes. No manual re-entry. No out-of-date listings on external sites.

Reply with the portals you currently use — I'll confirm which we support and send a fixed-price quote.

Email: zaars59208@gmail.com
Subject: Houzez Portal Sync Enquiry

Best regards,
Zahid Khurshid
Senior Developer, Webpenter
https://webpenter.com | WhatsApp: +923336151813
TEXT,
        ]);

        // ── Campaign 6: Real-Time Live Chat + AI Fallback ──────────────────
        EmailCampaign::firstOrCreate(['name' => 'Houzez — Real-Time Live Chat & AI Fallback'], [
            'subject'     => 'Every Visitor Who Leaves Without Enquiring Is a Lost Lead — Here\'s the Fix',
            'from_name'   => 'Rashid | Webpenter',
            'from_email'  => 'sales@webpenter.com',
            'target_role' => 'houzez_client',
            'status'      => 'draft',
            'html_body'   => <<<'HTML'
<p>Hi {{first_name}},</p>

<p>Most visitors to a real estate site leave without ever clicking &ldquo;Contact Agent&rdquo;. They had a question &mdash; about price, availability, the neighbourhood &mdash; but the friction of filling in a form stopped them. So they left and Googled your competitor instead.</p>

<p>We&rsquo;ve built a <strong>Real-Time Live Chat plugin for Houzez</strong> with an <strong>AI fallback</strong> that answers property questions instantly when no agent is online.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:20px 0;">
  <tr>
    <td width="48%" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#1a1a2e;">When an agent is online:</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Live chat on every listing page</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Agent notified on desktop &amp; mobile</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>See which property the visitor is viewing</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Proactive message: &ldquo;Any questions about this property?&rdquo;</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:#4a5568;line-height:1.6;"><span style="color:#27ae60;margin-right:6px;">&#10003;</span>Conversation saved to Houzez CRM</td></tr>
      </table>
    </td>
    <td width="4%"></td>
    <td width="48%" style="background:#1a1a2e;border-radius:8px;padding:18px 20px;vertical-align:top;">
      <p style="margin:0 0 12px;font-size:13px;font-weight:700;color:#ffffff;">When no agent is online (AI takes over):</p>
      <table border="0" cellpadding="0" cellspacing="0">
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Answers FAQs about the property</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Qualifies the lead: budget, timeline, requirements</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Books viewing appointments automatically</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Captures contact details &amp; sends to agent</td></tr>
        <tr><td style="padding:4px 0;font-size:13px;color:rgba(255,255,255,0.8);line-height:1.6;">Escalates complex queries via email</td></tr>
      </table>
    </td>
  </tr>
</table>

<p>The result: your site captures leads at 11 pm on a Sunday just as effectively as it does during business hours. No lead slips through because no one was at their desk.</p>

<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin:24px 0;">
  <tr>
    <td style="background:#1a1a2e;border-radius:8px;padding:24px;text-align:center;">
      <p style="margin:0 0 8px;font-size:16px;font-weight:700;color:#ffffff;">See the chat live on a Houzez site</p>
      <p style="margin:0 0 18px;font-size:13px;color:rgba(255,255,255,0.75);line-height:1.6;">Reply to this email &mdash; I&rsquo;ll send a demo link and a setup quote based on the number of agents on your team.</p>
      <a href="mailto:sales@webpenter.com?subject=Houzez Live Chat Plugin Enquiry" style="display:inline-block;background:#0066cc;color:#ffffff;padding:13px 28px;border-radius:6px;font-weight:700;font-size:14px;text-decoration:none;letter-spacing:0.3px;">Add Live Chat to My Site &rarr;</a>
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

Most visitors to a real estate site leave without clicking "Contact Agent". They had a question but the friction of filling in a form stopped them. So they left and Googled your competitor instead.

We've built a Real-Time Live Chat plugin for Houzez with an AI fallback that answers property questions instantly when no agent is online.

WHEN AN AGENT IS ONLINE:
✓ Live chat on every listing page
✓ Agent notified on desktop & mobile
✓ See which property the visitor is viewing
✓ Proactive message: "Any questions about this property?"
✓ Conversation saved to Houzez CRM

WHEN NO AGENT IS ONLINE (AI takes over):
- Answers FAQs about the property
- Qualifies the lead: budget, timeline, requirements
- Books viewing appointments automatically
- Captures contact details & sends to agent
- Escalates complex queries via email

Result: your site captures leads at 11pm on a Sunday just as effectively as during business hours.

Reply to this email — I'll send a demo link and a setup quote based on the number of agents on your team.

Email: sales@webpenter.com
Subject: Houzez Live Chat Plugin Enquiry

Best regards,
Rashid Bukhari
CEO, Webpenter
https://webpenter.com
TEXT,
        ]);
    }
}
