<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Help &amp; Documentation</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
<style>
    :root{ --ink:#0f172a; --ink-soft:#334155; --muted:#64748b; --muted-soft:#94a3b8; --line:#e2e8f0; --line-soft:#edf1ef; --bg:#f8faf9; --white:#fff;
        --g-50:#f0fdf4; --g-100:#dcfce7; --g-500:#22c55e; --g-600:#16a34a; --g-700:#15803d; }
    *{ box-sizing:border-box; }
    body{ margin:0; font-family:'Plus Jakarta Sans',system-ui,sans-serif; background:var(--bg); color:var(--ink); }
    .wrap{ max-width:880px; margin:0 auto; padding:40px 24px 64px; }
    .card{ background:var(--white); border:1px solid var(--line); border-radius:16px; padding:22px 26px; margin-bottom:20px; }
    h1{ font-size:26px; font-weight:800; color:var(--ink); margin:0 0 4px; }
    .sub{ color:var(--muted); font-size:13.5px; }
    h2{ font-size:16px; font-weight:800; color:var(--ink); margin:0 0 3px; }
    .section-sub{ color:var(--muted); font-size:12.5px; margin-bottom:14px; line-height:1.55; }
    .top-bar{ display:flex; justify-content:flex-end; margin-bottom:12px; }
    .logout-form button{ background:none; border:0; color:var(--muted); font-size:13px; font-weight:600; cursor:pointer; text-decoration:underline; padding:0; font-family:inherit; }
    .nav-tabs{ display:flex; flex-wrap:wrap; gap:8px; margin-bottom:20px; position:sticky; top:0; background:var(--bg); padding:8px 0; z-index:5; }
    .nav-tabs a{ padding:7px 13px; border-radius:999px; border:1.5px solid var(--line); color:var(--ink-soft); text-decoration:none; font-size:12px; font-weight:600; background:var(--white); }
    .nav-tabs a:hover{ border-color:var(--g-600); color:var(--g-700); }
    .open-link{ display:inline-block; margin-top:10px; padding:8px 14px; border-radius:9px; background:var(--g-600); color:#fff; text-decoration:none; font-weight:700; font-size:12.5px; }
    .flow-steps{ counter-reset:step; margin:12px 0 0; padding:0; list-style:none; }
    .flow-steps li{ counter-increment:step; position:relative; padding:0 0 14px 32px; font-size:13px; color:var(--ink-soft); line-height:1.55; }
    .flow-steps li:last-child{ padding-bottom:0; }
    .flow-steps li::before{ content:counter(step); position:absolute; left:0; top:0; width:22px; height:22px; border-radius:50%; background:var(--g-100); color:var(--g-700); font-size:11px; font-weight:800; display:flex; align-items:center; justify-content:center; }
    .flow-steps li strong{ color:var(--ink); }
    .example{ background:var(--bg); border:1px dashed var(--line); border-radius:10px; padding:12px 14px; margin-top:10px; font-size:12px; color:var(--ink-soft); }
    .example .lbl{ font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.04em; color:var(--muted-soft); margin-bottom:6px; }
    .pill{ display:inline-block; padding:3px 10px; border-radius:999px; font-size:10.5px; font-weight:700; margin-right:4px; }
    .pill-paid{ background:var(--g-100); color:var(--g-700); }
    .pill-due{ background:#fef3c7; color:#b45309; }
    .token-row{ display:flex; flex-wrap:wrap; gap:6px; margin-top:8px; }
    .token{ background:var(--g-50); border:1px solid var(--g-100); color:var(--g-700); border-radius:999px; padding:3px 9px; font-size:10.5px; font-weight:700; font-family:ui-monospace,Menlo,monospace; }
    .empty{ text-align:center; padding:40px 20px; color:var(--muted); }
    @media (max-width:640px){ .wrap{ padding:24px 16px; } }
</style>
</head>
<body>
<div class="wrap">
    <div class="top-bar">
        <form class="logout-form" method="POST" action="{{ route('voyager.logout') }}">
            @csrf
            <button type="submit">Log out</button>
        </form>
    </div>

    <div class="card">
        <h1>Help &amp; Documentation</h1>
        <div class="sub">Only the jobs you actually hold are shown below - your own workflow, made simple.</div>
    </div>

    <div class="nav-tabs">
        @if($sections['student'])<a href="#student">Student</a>@endif
        @if($sections['instructor'])<a href="#instructor">Instructor</a>@endif
        @if($sections['reviewer'])<a href="#reviewer">Reviewer</a>@endif
        @if($sections['accountant'])<a href="#accountant">Accountant</a>@endif
        @if($sections['marketing'])<a href="#marketing">Marketing</a>@endif
        @if($sections['printer'])<a href="#printer">Printer</a>@endif
        @if($sections['card_manager'])<a href="#card_manager">Card Manager</a>@endif
        @if($sections['hr'])<a href="#hr">HR Documents</a>@endif
    </div>

    @if($sections['student'])
    <div class="card" id="student">
        <h2>🎓 Your Learning Journey</h2>
        <div class="section-sub">Everything you do as a student, from daily check-in to earning your certificate.</div>
        <ul class="flow-steps">
            <li><strong>Check in every morning</strong> with your plan for the day, and <strong>check out every evening</strong> with what you actually finished - this is how your instructor and reviewer know what you're working on.</li>
            <li><strong>Work through your stages</strong> in order - each has skills to tick off and projects to complete. Click "View Steps" on any project for a full step-by-step breakdown.</li>
            <li><strong>Submit your work</strong> when a stage is done - your reviewer checks it with AI-assisted feedback and either approves it or sends it back with notes.</li>
            <li><strong>Pay your monthly fee</strong> and upload proof of payment if asked - your accountant confirms it on their end.</li>
            <li><strong>Earn your certificate</strong> once your track (or a course) is complete - it shows up right on your dashboard with a Download button, and WebPenter announces it on Slack.</li>
        </ul>
        <a href="{{ route('academy.dashboard') }}" class="open-link">Open My Dashboard &rarr;</a>
    </div>
    @endif

    @if($sections['instructor'])
    <div class="card" id="instructor">
        <h2>👨‍🏫 Instructor</h2>
        <div class="section-sub">Your students, their progress, and what you've earned.</div>
        <ul class="flow-steps">
            <li><strong>See your students</strong> - everyone currently assigned to you, their track, current stage, and completion percentage.</li>
            <li><strong>Track your commission</strong> - each paid fee invoice credits you a commission, shown per student with a running total for this month and all-time.</li>
            <li><strong>Get paid</strong> - an Administrator reviews and marks your commission paid from Instructor Payouts; you'll see the payment reflected once it's done.</li>
        </ul>
        <div class="example">
            <div class="lbl">Example - a student's fee history</div>
            <span class="pill pill-paid">Sep 2026: Paid · Rs. 2,400</span>
            <span class="pill pill-due">Oct 2026: Due</span>
        </div>
        <a href="{{ route('academy.instructor') }}" class="open-link">Open My Students &rarr;</a>
    </div>
    @endif

    @if($sections['reviewer'])
    <div class="card" id="reviewer">
        <h2>📝 Reviewer</h2>
        <div class="section-sub">Checking student project submissions.</div>
        <ul class="flow-steps">
            <li><strong>Open your queue</strong> - every submission waiting on you, oldest first.</li>
            <li><strong>Read the AI feedback</strong> shown inline for each submission - a starting point, not a verdict; use your own judgement.</li>
            <li><strong>Approve</strong> to move the student to their next stage, or <strong>Send Back</strong> with a note explaining what needs fixing.</li>
        </ul>
        <a href="{{ route('academy.reviewer.index') }}" class="open-link">Open Review Queue &rarr;</a>
    </div>
    @endif

    @if($sections['accountant'])
    <div class="card" id="accountant">
        <h2>💰 Accountant</h2>
        <div class="section-sub">Fee collection and the active-student roster.</div>
        <ul class="flow-steps">
            <li><strong>Review monthly invoices</strong> - every student's registration + monthly fee, and whether it's paid or due.</li>
            <li><strong>Mark a fee paid</strong> once you've confirmed payment - attach the proof if the student uploaded one, it's shown right there.</li>
            <li><strong>Manage the roster</strong> from the Students tab - remove someone who's withdrawn, see everyone active at a glance.</li>
        </ul>
        <div class="example">
            <div class="lbl">Example - invoice status</div>
            <span class="pill pill-paid">Paid</span>
            <span class="pill pill-due">Due</span>
        </div>
        <a href="{{ route('academy.accountant.index') }}" class="open-link">Open Fee Dashboard &rarr;</a>
    </div>
    @endif

    @if($sections['marketing'])
    <div class="card" id="marketing">
        <h2>📣 Marketing</h2>
        <div class="section-sub">Sharing student and team achievements.</div>
        <ul class="flow-steps">
            <li><strong>Browse every certificate</strong> issued so far - filter by resource (a specific person), by design (Classic / LinkedIn-style / Udemy-style), or sort most recent/oldest first.</li>
            <li><strong>Download the PDF</strong> or open the public verification page for any certificate. Signatures shown follow whatever's set in Voyager Settings (Certification) - Marketing doesn't need to choose anything.</li>
            <li><strong>Click "✨ AI Suggest Post"</strong> on a certificate - it drafts a title, description, and hashtags for you. Use the Copy button next to each field, then post it yourself on LinkedIn/Instagram/Facebook/X.</li>
            <li><strong>Click "✅ Mark as Posted"</strong> once you've actually shared it - it moves to the "Already Posted" list below, so it's always clear what's still pending vs. done.</li>
        </ul>
        <div class="example">
            <div class="lbl">Example - AI-suggested hashtags</div>
            <span class="token">#WebPenterAcademy</span><span class="token">#StudentSuccess</span><span class="token">#CareerGrowth</span>
        </div>
        <a href="{{ route('academy.marketing.index') }}" class="open-link">Open Certificates &rarr;</a>
    </div>
    @endif

    @if($sections['printer'])
    <div class="card" id="printer">
        <h2>🖨️ Printer</h2>
        <div class="section-sub">Producing physical student ID cards.</div>
        <ul class="flow-steps">
            <li><strong>Check "Ready-to-Print Batches"</strong> at the top of the page - these are prepared by the Card Manager and waiting on you.</li>
            <li><strong>Print This Batch</strong> opens a print-ready sheet of vertical ID cards, one per student, each with a QR code.</li>
            <li><strong>Mark Batch Printed</strong> once it's done, or print any student directly yourself further down the page without waiting on a prepared batch.</li>
        </ul>
        <a href="{{ route('academy.student-cards.index') }}" class="open-link">Open Card Printing &rarr;</a>
    </div>
    @endif

    @if($sections['card_manager'])
    <div class="card" id="card_manager">
        <h2>🗂️ Card Manager</h2>
        <div class="section-sub">Preparing batches of students for the Printer.</div>
        <ul class="flow-steps">
            <li><strong>Filter students</strong> by track, instructor, or search by name - or tick "only students not in any batch yet" to find who still needs a card.</li>
            <li><strong>Add them to a batch</strong> - start a new one or add to an existing draft you're still building up.</li>
            <li><strong>Mark it Ready to Print</strong> once you're done - it appears on the Printer's screen immediately.</li>
        </ul>
        <a href="{{ route('academy.card-batches.index') }}" class="open-link">Open Print Batches &rarr;</a>
    </div>
    @endif

    @if($sections['hr'])
    <div class="card" id="hr">
        <h2>📄 HR Documents (Experience Letters)</h2>
        <div class="section-sub">Building letter templates and issuing them to staff.</div>
        <ul class="flow-steps">
            <li><strong>Write a template once</strong> using tokens like <code>@{{employee_name}}</code> - click a token chip to insert it, no code needed for a new letter type.</li>
            <li><strong>Issue a letter</strong> - pick an existing employee (auto-fills their details) or enter someone manually if they're not in the system.</li>
            <li><strong>Preview before you generate</strong>, then save - it's stored with a verify code anyone can check publicly.</li>
            <li><strong>From History</strong>: Preview, Download, open the Verify page, or Email the PDF straight to the recipient.</li>
        </ul>
        <div class="token-row">
            <span class="token">@{{employee_name}}</span><span class="token">@{{designation}}</span><span class="token">@{{join_date}}</span><span class="token">@{{last_working_day}}</span>
        </div>
        <a href="{{ route('hr-documents.dashboard') }}" class="open-link">Open HR Documents &rarr;</a>
    </div>
    @endif
</div>
</body>
</html>
