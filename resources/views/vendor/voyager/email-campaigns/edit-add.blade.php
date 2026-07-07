@extends('voyager::master')

@section('page_title', isset($campaign) ? 'Edit Campaign' : 'New Campaign')

@section('page_header')
<div class="container-fluid">
    <h1 class="page-title">
        <i class="voyager-mail"></i> {{ isset($campaign) ? 'Edit: '.$campaign->name : 'New Campaign' }}
    </h1>
    <a href="{{ route('email-campaigns.index') }}" class="btn btn-default">
        <i class="voyager-angle-left"></i> Back
    </a>
</div>
@stop

@section('content')
<div class="page-content container-fluid">

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="campaign-form" method="POST"
          action="{{ isset($campaign) ? route('email-campaigns.update', $campaign->id) : route('email-campaigns.store') }}">
        @csrf
        @if(isset($campaign)) @method('PUT') @endif

        <div class="row">
            {{-- Left column: campaign meta ─────────────────────────────── --}}
            <div class="col-md-4">
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Campaign Settings</h3></div>
                    <div class="panel-body">

                        <div class="form-group">
                            <label>Campaign Name *</label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $campaign->name ?? '') }}" required placeholder="e.g. BookHere Launch — April 2026">
                        </div>

                        <div class="form-group">
                            <label>Email Subject *</label>
                            <input type="text" name="subject" class="form-control"
                                   value="{{ old('subject', $campaign->subject ?? '') }}" required placeholder="What will recipients see in their inbox?">
                        </div>

                        <div class="form-group">
                            <label>From Name</label>
                            <input type="text" name="from_name" class="form-control"
                                   value="{{ old('from_name', $campaign->from_name ?? setting('marketing.from_name', 'Webpenter')) }}">
                        </div>

                        <div class="form-group">
                            <label>From Email</label>
                            <input type="email" name="from_email" class="form-control"
                                   value="{{ old('from_email', $campaign->from_email ?? setting('marketing.from_email', 'sales@webpenter.com')) }}">
                        </div>

                        <div class="form-group">
                            <label>SMTP Account <span class="text-danger">*</span></label>
                            <select name="smtp_account_id" class="form-control" required>
                                <option value="">— select sender SMTP —</option>
                                @foreach($smtpAccounts ?? [] as $smtp)
                                    <option value="{{ $smtp->id }}"
                                        {{ old('smtp_account_id', $campaign->smtp_account_id ?? '') == $smtp->id ? 'selected' : '' }}>
                                        {{ $smtp->name }}
                                    </option>
                                @endforeach
                            </select>
                            <span class="help-block">Emails will be sent from this account's address.</span>
                        </div>

                        <div class="form-group">
                            <label>Target Role</label>
                            <select name="target_role" class="form-control">
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('target_role', $campaign->target_role ?? 'homey_client') === $role->name ? 'selected' : '' }}>
                                        {{ $role->display_name }} ({{ $role->name }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <hr>
                        <p class="text-muted" style="font-size:12px;">
                            <strong>Tip:</strong> Use <code>&#123;&#123;name&#125;&#125;</code> or <code>&#123;&#123;first_name&#125;&#125;</code> in subject or body for personalisation.
                        </p>

                        <button type="submit" class="btn btn-success btn-block">
                            <i class="voyager-save"></i> {{ isset($campaign) ? 'Update Campaign' : 'Create Campaign' }}
                        </button>
                    </div>
                </div>

                {{-- Quick Templates ──────────────────────────────────────── --}}
                <div class="panel panel-bordered">
                    <div class="panel-heading"><h3 class="panel-title">Quick Templates</h3></div>
                    <div class="panel-body">
                        <p class="text-muted" style="font-size:12px;">Click a template to load it into the editor.</p>
                        <button type="button" class="btn btn-default btn-block btn-sm" onclick="loadTemplate('bookhere')">
                            📱 BookHere Mobile App
                        </button>
                        <button type="button" class="btn btn-default btn-block btn-sm" style="margin-top:6px;" onclick="loadTemplate('zahid')">
                            💼 Zahid — Specialist Services
                        </button>
                        <button type="button" class="btn btn-default btn-block btn-sm" style="margin-top:6px;" onclick="loadTemplate('houzilo')">
                            🏠 Houzilo Platform
                        </button>
                    </div>
                </div>
            </div>

            {{-- Right column: email body ────────────────────────────────── --}}
            <div class="col-md-8">
                <div class="panel panel-bordered">
                    <div class="panel-heading">
                        <h3 class="panel-title">Email Body (HTML)</h3>
                        <div class="panel-actions" style="padding:8px 16px;">
                            <button type="button" class="btn btn-xs btn-info" onclick="toggleTextMode()">
                                Toggle Plain Text
                            </button>
                        </div>
                    </div>
                    <div class="panel-body" style="padding:0;">
                        <textarea id="html_body" name="html_body"
                                  style="width:100%;min-height:500px;font-family:monospace;font-size:12px;line-height:1.5;border:none;outline:none;resize:vertical;padding:16px;box-sizing:border-box;white-space:pre;overflow-wrap:normal;overflow-x:auto;background:#1e1e2e;color:#cdd6f4;">{{ old('html_body', $campaign->html_body ?? '') }}</textarea>
                    </div>
                </div>

                <div class="panel panel-bordered" id="plain-text-panel" style="display:none;">
                    <div class="panel-heading"><h3 class="panel-title">Plain Text Version</h3></div>
                    <div class="panel-body">
                        <textarea name="text_body" class="form-control" rows="12"
                                  placeholder="Plain text fallback (auto-generated from HTML if left empty)...">{{ old('text_body', $campaign->text_body ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@stop

@section('css')
<style>
#html_body:focus { outline: 2px solid #0066cc; }
</style>
@stop

@section('javascript')
@verbatim
<script>
// ── Toggle plain text panel ────────────────────────────────────────────────
function toggleTextMode() {
    const panel = document.getElementById('plain-text-panel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}

// ── Quick templates ────────────────────────────────────────────────────────
const templates = {
    bookhere: {
        subject: "Turn Your Homey Site Into a Full Mobile Booking App",
        from_name: "Rashid | Webpenter",
        from_email: "sales@webpenter.com",
        html: `<p>Hi {{first_name}},</p>
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
<p><a href="https://bookhere.tech">See BookHere in action →</a></p>
<p>If you'd like a personalised demo, just reply to this email.</p>
<p>Best regards,<br><strong>Rashid Bukhari</strong><br>CEO, Webpenter<br><a href="https://webpenter.com">webpenter.com</a></p>`
    },
    zahid: {
        subject: "Quick One — Homey & Houzez Performance & Features",
        from_name: "Zahid Khurshid | Webpenter",
        from_email: "zaars59208@gmail.com",
        html: `<p>Hi {{first_name}},</p>
<p>As you know I am a <strong>Favethemes Sr. Developer</strong>. I help Real Estate and Booking sites (Houzez, Homey &amp; Listeo) improve performance and automate key features.</p>
<p>Recently, I've implemented for clients:</p>
<ul>
  <li>⚡ Faster search for large listings</li>
  <li>💳 Automated PayPal payouts</li>
  <li>🗺️ Map-based property filtering</li>
  <li>🛏️ Multi-room booking support</li>
  <li>📅 One Calendar page for all reservations</li>
</ul>
<p>Here's a playlist of my recent work: <a href="http://youtube.com/post/UgkxRwZPv6DfHfBKyYI6kTvcn2FDBVgL5L7C">▶ Watch on YouTube</a></p>
<p>If any of this sounds useful, I can share a quick demo or suggest what would work best for your site. Also available for full-time work.</p>
<p>My profiles:<br>
🔗 <a href="https://upwork.com/freelancers/zahidkhurshidchandio">Upwork</a> &nbsp;|&nbsp; 🔗 <a href="https://www.fiverr.com/zaars59208">Fiverr</a></p>
<p>Best regards,<br><strong>Zahid Khurshid</strong><br>Senior Web Developer — Favethemes (Houzez &amp; Homey Specialist)<br>WhatsApp: +923336151813</p>`
    },
    houzilo: {
        subject: "Built Your Own Real Estate Platform — No WordPress Needed",
        from_name: "Rashid | Webpenter",
        from_email: "sales@webpenter.com",
        html: `<p>Hi {{first_name}},</p>
<p>If you've ever felt limited by WordPress or wanted more control over your booking platform, you'll like this.</p>
<p>We built <strong><a href="https://houzilo.com">Houzilo</a></strong> — a <strong>Laravel &amp; Vue 3</strong> platform for real estate, booking, and rental businesses. It's what we use for clients who've outgrown WordPress.</p>
<p><strong>What you get with Houzilo:</strong></p>
<ul>
  <li>Advanced search with map integration &amp; radius filter</li>
  <li>Full CRM: leads, deals, tours, enquiries</li>
  <li>Stripe subscription billing built-in</li>
  <li>500+ active installations, 50,000+ properties listed</li>
  <li>Multi-language, mobile-first, 99.9% uptime</li>
  <li>Complete admin panel — no coding needed</li>
</ul>
<p>Want a live demo or migration consultation from your current Homey site? Reply and I'll book a 20-minute call.</p>
<p>Best regards,<br><strong>Rashid Bukhari</strong><br>CEO, Webpenter<br><a href="https://webpenter.com">webpenter.com</a></p>`
    }
};

function loadTemplate(key) {
    if (!confirm('This will replace the current email content. Continue?')) return;
    const t = templates[key];
    document.getElementById('html_body').value = t.html;
    document.querySelector('[name="subject"]').value = t.subject;
    document.querySelector('[name="from_name"]').value = t.from_name;
    document.querySelector('[name="from_email"]').value = t.from_email;
}
</script>
@endverbatim
@stop
