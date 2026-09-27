<?php

namespace Database\Seeders;

use App\Models\AcademyTrack;
use Illuminate\Database\Seeder;

/**
 * Two things, both requested together:
 *
 *  1. Every project across every track (existing 4 flagship tracks + the 3
 *     lighter ones) goes from a plain string to {title, steps: [...]} - a
 *     genuine step-by-step "what do I actually need to do" breakdown, not
 *     just a project name. The student dashboard already knows how to
 *     render this (falls back to a plain string for anything not yet
 *     migrated, so this is safe to apply incrementally).
 *  2. Two new tracks: YouTube & Content Automation, and Business
 *     Development & Freelancing - both with the same compulsory GitHub
 *     stage every other track has, and the same {title, steps} project
 *     format from day one.
 *
 * Idempotent for the new tracks (firstOrCreate by name). The project-detail
 * rewrite on EXISTING tracks always re-applies (same reasoning as the
 * GitHub-stage seeder: this is a content correction, safe to run more than
 * once since it always produces the same end state).
 */
class AcademyProjectDetailsAndNewTracksSeeder extends Seeder
{
    public function run(): void
    {
        $this->updateExistingTrackProjects();
        $this->expandDigitalFoundations();
        $this->createNewTracks();
    }

    protected function updateExistingTrackProjects(): void
    {
        $updates = [
            'Python & AI/ML Engineer' => [
                'Python Core & Engineering Fundamentals' => [
                    $this->project('CLI Expense Tracker', [
                        'Set up a Python project folder with a virtual environment (venv) and a git repository.',
                        'Build a menu-driven command-line app: add an expense, list expenses, show a running total.',
                        'Store expenses in a JSON or CSV file - no database yet.',
                        'Validate input (amount must be a positive number, date must be a real date) and handle bad input without crashing.',
                        'Write at least 3 pytest tests covering the add and list logic.',
                        'Push the finished project to GitHub with a README explaining how to run it.',
                    ]),
                    $this->project('Library Management System (OOP)', [
                        'Design classes for Book, Member, and Library using proper OOP (encapsulation, not just functions).',
                        'Implement borrow/return logic that tracks which member has which book.',
                        'Prevent borrowing a book that is already checked out - handle this as a real business rule, not an afterthought.',
                        'Persist the library state to a file so data survives between runs.',
                        'Write pytest tests for at least the borrow/return rules.',
                        'Push to GitHub with a short README describing the class design.',
                    ]),
                    $this->project('Small REST API client', [
                        'Pick a free public API (e.g. a weather or currency API) and read its docs.',
                        'Write a Python script using requests to fetch and parse data from it.',
                        'Handle real-world failure cases: network errors, bad API keys, empty responses.',
                        'Cache the last successful response to a local file so the script still works offline.',
                        'Write a short CLI wrapper so a user can query it without editing code.',
                        'Push to GitHub with example commands in the README.',
                    ]),
                    $this->project('A pytest-covered utility library', [
                        'Pick 4-5 small, genuinely reusable functions (e.g. string cleaning, date formatting, file helpers).',
                        'Package them as an installable local module with a clear folder structure.',
                        'Write pytest tests achieving good coverage of edge cases, not just the happy path.',
                        'Add docstrings to every public function.',
                        'Set up a simple GitHub Actions workflow that runs pytest on every push.',
                    ]),
                ],
                'Data Handling & Analysis' => [
                    $this->project('Sales Data Analyzer + dashboard', [
                        'Find or create a realistic sales dataset (CSV) with at least 500 rows.',
                        'Use Pandas to clean it - handle missing values, fix inconsistent formatting, remove duplicates.',
                        'Compute key metrics: revenue by month, top products, top regions.',
                        'Build 3-4 charts with Matplotlib or Seaborn that actually answer a business question.',
                        'Summarize your findings in a short written report, not just charts.',
                    ]),
                    $this->project('Real messy-dataset cleaning pipeline', [
                        'Find a genuinely messy public dataset (inconsistent dates, mixed types, missing values).',
                        'Write a repeatable cleaning script (a function, not one-off notebook cells).',
                        'Document every cleaning decision you made and why.',
                        'Validate the cleaned output against basic sanity checks (row counts, value ranges).',
                        'Push the before/after data and the script to GitHub.',
                    ]),
                    $this->project('SQL + Python reporting tool', [
                        'Set up a small MySQL/SQLite database with at least 3 related tables.',
                        'Write SQL queries (joins, group by, aggregates) to answer 3-4 real business questions.',
                        'Connect Python to the database and run those queries programmatically.',
                        'Export the results to a formatted report (CSV or a simple PDF).',
                        'Push the schema, sample data, and script to GitHub.',
                    ]),
                ],
                'Machine Learning Fundamentals' => [
                    $this->project('Sentiment/Spam Classifier', [
                        'Find a labeled text dataset (spam/ham or positive/negative reviews).',
                        'Preprocess the text (cleaning, tokenizing, vectorizing with TF-IDF or similar).',
                        'Train at least 2 different scikit-learn classifiers and compare their accuracy.',
                        'Evaluate with more than just accuracy - check precision/recall, especially for imbalanced data.',
                        'Write a short section explaining which model you chose and why.',
                    ]),
                    $this->project('House Price Predictor', [
                        'Get a housing dataset with numeric and categorical features.',
                        'Handle missing values and encode categorical features properly.',
                        'Train a regression model and evaluate with RMSE/R², not just "it ran".',
                        'Identify which features actually matter most to the prediction.',
                        'Push the notebook and a short write-up of your findings to GitHub.',
                    ]),
                    $this->project('Churn Predictor', [
                        'Get a customer churn dataset (or a similar imbalanced classification problem).',
                        'Handle class imbalance properly (don\'t just report accuracy on an imbalanced dataset).',
                        'Train and tune a classifier, then evaluate with precision/recall/F1.',
                        'Explain in plain language which customers are most at risk and why, based on the model.',
                    ]),
                    $this->project('A small LLM-powered feature (e.g. text summarizer)', [
                        'Pick a real, small problem an LLM can help with (summarizing, tagging, extracting info).',
                        'Write clear, tested prompts - don\'t just wing it, iterate on the prompt.',
                        'Wrap it in a simple script or small API endpoint.',
                        'Handle the case where the AI response is malformed or the API call fails.',
                        'Document the prompt design decisions in your README.',
                    ]),
                ],
                'Deployment & Production' => [
                    $this->project('Deploy a trained model as a documented API', [
                        'Wrap your best model from Stage 3 in a FastAPI or Flask app.',
                        'Add input validation - the API should reject bad requests clearly, not crash.',
                        'Write API documentation (FastAPI gives you this almost for free - use it).',
                        'Deploy it to a free host (Render/Railway) so it has a real public URL.',
                        'Test the live endpoint with real requests, not just localhost.',
                    ]),
                    $this->project('A small full-stack demo suitable to show a client', [
                        'Build a minimal frontend (even a simple HTML form) that calls your deployed API.',
                        'Make sure the whole flow works end-to-end for someone who has never seen the code.',
                        'Write a short "how to use this" guide aimed at a non-technical client.',
                        'Record a 2-3 minute screen recording demoing it, as if presenting to a client.',
                    ]),
                    $this->project('Capstone combining Stages 2-3 into one presentable product', [
                        'Pick one real problem that needs both data analysis (Stage 2) and ML (Stage 3).',
                        'Build the full pipeline: raw data -> cleaned data -> trained model -> usable output.',
                        'Deploy the final result so it is genuinely usable by someone else.',
                        'Write full project documentation as if handing this off to another developer.',
                        'Present it as your portfolio centerpiece - this is what you show employers.',
                    ]),
                ],
            ],
            'Full Stack Laravel/PHP Developer' => [
                'PHP & Laravel Fundamentals' => [
                    $this->project('Task Manager CRUD', [
                        'Set up a fresh Laravel project and a database connection.',
                        'Build a Task model, migration, and controller with full CRUD (create/read/update/delete).',
                        'Add validation on the create/edit forms - required fields, sensible limits.',
                        'Use Blade properly - layouts, includes, no copy-pasted HTML across views.',
                        'Push to GitHub with clear commit history, not one giant commit.',
                    ]),
                    $this->project('A blog with categories/tags', [
                        'Model Posts, Categories, and Tags with proper relationships (belongsTo/belongsToMany).',
                        'Build an admin area to manage posts, and a public area to read them.',
                        'Add search/filter by category or tag on the public side.',
                        'Handle image uploads for post thumbnails.',
                        'Push to GitHub with the migrations and seeders included so it runs for anyone.',
                    ]),
                    $this->project('A validated contact form with mail', [
                        'Build a contact form with server-side validation (Laravel Form Requests).',
                        'Send the submission as an actual email using Laravel\'s Mail facade.',
                        'Show clear success/error feedback to the user.',
                        'Protect it from spam with basic rate limiting or a honeypot field.',
                    ]),
                ],
                'APIs & Authentication' => [
                    $this->project('An authenticated REST API for an inventory system', [
                        'Design the database schema for products, categories, and stock levels.',
                        'Build a full REST API (index/show/store/update/destroy) using API Resources for clean output.',
                        'Add authentication with Sanctum - the API should reject unauthenticated requests.',
                        'Add proper Form Request validation on every write endpoint.',
                        'Write Pest/PHPUnit tests for at least the core endpoints.',
                        'Document the API endpoints (a simple markdown table is fine) and push to GitHub.',
                    ]),
                    $this->project('A queue-powered notification system', [
                        'Pick a real trigger (e.g. new order, new signup) that should send a notification.',
                        'Build the notification as a queued Job, not a synchronous call.',
                        'Run a real queue worker and confirm jobs actually process in the background.',
                        'Handle job failures gracefully (retries, logging) instead of silently losing them.',
                        'Push to GitHub with instructions on how to run the queue worker.',
                    ]),
                ],
                'Advanced Laravel & Real-World Patterns' => [
                    $this->project('A role-permissioned admin panel', [
                        'Design at least 2 distinct roles with different permissions (e.g. Admin vs Editor).',
                        'Use Policies or Gates to enforce permissions - not just hiding buttons in the UI.',
                        'Build the actual admin screens the roles need to manage real data.',
                        'Test that a lower-permission user genuinely cannot perform a restricted action, not just that the button is hidden.',
                    ]),
                    $this->project('A payment-integrated booking feature', [
                        'Build a booking flow: pick a slot/item, confirm details, pay.',
                        'Integrate a real (sandbox/test-mode) payment gateway - don\'t fake the payment step.',
                        'Handle the payment webhook/callback properly, including failure cases.',
                        'Show the user a clear confirmation once payment succeeds.',
                    ]),
                    $this->project('A Voyager-based admin CRUD system', [
                        'Install Voyager into a project and register BREAD for at least 2 related models.',
                        'Customize at least one BREAD screen beyond the defaults (custom field type or view).',
                        'Set up roles/permissions so a non-admin sees a restricted menu.',
                        'Document what you customized and why in the README.',
                    ]),
                ],
                'Deployment & Client Readiness' => [
                    $this->project('Deploy a Laravel app with real CI/CD', [
                        'Deploy any earlier project to a real VPS or Hostinger-style host.',
                        'Set up a GitHub Actions workflow that runs tests and deploys on push to main.',
                        'Configure .env properly for production (never commit real secrets).',
                        'Set up SSL and confirm the live site actually serves over HTTPS.',
                    ]),
                    $this->project('A full-stack Laravel + React/Vue app', [
                        'Build a Laravel API backend and a separate React or Vue frontend consuming it.',
                        'Handle authentication across the two (token-based, e.g. Sanctum SPA auth).',
                        'Deploy both halves so the whole thing works as one live product.',
                    ]),
                    $this->project('A client-presentable capstone', [
                        'Combine what you\'ve built into one polished, deployed product.',
                        'Write documentation aimed at a client, not a developer.',
                        'Record a short demo video walking through the main features.',
                        'Be ready to explain, in plain language, every technical decision you made.',
                    ]),
                ],
            ],
            'WordPress Developer & Customization Specialist' => [
                'WordPress Fundamentals & Theme Customization' => [
                    $this->project('A page-builder business site', [
                        'Install WordPress locally and pick a business niche (e.g. a local restaurant, a clinic).',
                        'Build a full multi-page site using Elementor or WPBakery - home, about, services, contact.',
                        'Make sure it is genuinely responsive on mobile, not just "looks fine on desktop".',
                        'Set up a working contact form connected to email.',
                    ]),
                    $this->project('A custom-styled child theme', [
                        'Create a proper child theme (not editing the parent theme directly).',
                        'Override at least 2 template files and add custom CSS/functions.php code.',
                        'Confirm the child theme survives a parent theme update (that\'s the whole point).',
                        'Push the child theme folder to GitHub with a README on how to activate it.',
                    ]),
                ],
                'Custom Plugins & Post Types' => [
                    $this->project('A custom booking/review plugin', [
                        'Register a custom post type for bookings or reviews (not a page/post hack).',
                        'Build an admin screen to manage the custom data.',
                        'Add a front-end form so real users can submit new entries.',
                        'Sanitize and validate every piece of user input - this is a common security gap.',
                        'Push the plugin as its own folder/repo with an activation-tested README.',
                    ]),
                    $this->project('A CPT-driven listing site', [
                        'Design a custom post type + custom taxonomy for a listing site (e.g. properties, cars, jobs).',
                        'Build ACF (Advanced Custom Fields) fields for the listing details.',
                        'Build filterable/searchable listing pages using WP_Query.',
                        'Make sure the WP REST API exposes this data cleanly if someone wanted to build an app on it.',
                    ]),
                ],
                'Real Estate & Booking Theme Specialization' => [
                    $this->project('A fully customized Houzez/Homey site for a mock client', [
                        'Install Houzez or Homey and configure it for a realistic mock client brief.',
                        'Customize beyond default settings - custom fields, custom search filters, branding.',
                        'Set up at least one payment-gated feature (e.g. featured listing payment).',
                        'Optimize page load speed - measure before/after with a real tool (PageSpeed Insights).',
                    ]),
                    $this->project('A custom checkout flow', [
                        'Customize WooCommerce checkout beyond the default fields/layout.',
                        'Add custom validation or a custom field required for this business type.',
                        'Test the full flow with a real sandbox payment gateway.',
                    ]),
                    $this->project('A custom Gutenberg block', [
                        'Build a genuinely custom Gutenberg block (not just a reusable block using core blocks).',
                        'Make it configurable with real block attributes/settings in the editor.',
                        'Document how to use it for a non-technical content editor.',
                    ]),
                ],
                'Deployment, Maintenance & Client Readiness' => [
                    $this->project('A production deployment with staging', [
                        'Set up a staging environment separate from production.',
                        'Deploy a real change through staging -> review -> production properly.',
                        'Configure backups and confirm you can actually restore from one.',
                    ]),
                    $this->project('A full site migration', [
                        'Migrate a WordPress site from one host/domain to another.',
                        'Handle the database URL replacements correctly (not just search-replace in a text editor).',
                        'Verify every page, image, and plugin still works after migration.',
                    ]),
                    $this->project('A client support runbook', [
                        'Write a real runbook: common client requests and exactly how you\'d resolve each.',
                        'Include a security hardening checklist you\'d run on every new client site.',
                        'Include your process for handling a "the site is down" emergency.',
                    ]),
                ],
            ],
            'React / React Native Developer' => [
                'JavaScript & React Fundamentals' => [
                    $this->project('A product filter app', [
                        'Build a product listing page with real (mock) data - at least 15-20 items.',
                        'Add filtering (by category/price) and sorting, all in React state.',
                        'Use React Router to give each product its own detail page/URL.',
                        'Keep components small and reusable - not one giant component file.',
                    ]),
                    $this->project('A multi-page routed site', [
                        'Build at least 4 distinct pages with React Router (not just conditional rendering).',
                        'Add a shared layout (nav/footer) that persists across page changes.',
                        'Handle a 404/not-found route properly.',
                    ]),
                ],
                'State Management & API Integration' => [
                    $this->project('A weather dashboard on a live API', [
                        'Connect to a real weather API and display live data.',
                        'Handle loading and error states properly - no blank screen on failure.',
                        'Use React Query (or similar) for caching so you\'re not hammering the API.',
                        'Let the user search a new city and see the state update correctly.',
                    ]),
                    $this->project('An authenticated app', [
                        'Build login/signup screens that call a real (or mocked) API.',
                        'Store the JWT securely and attach it to authenticated requests.',
                        'Protect at least one route so it redirects unauthenticated users to login.',
                        'Write a couple of React Testing Library tests for the login flow.',
                    ]),
                ],
                'React Native & Mobile Development' => [
                    $this->project('A booking/listing app UI mirroring BookHere\'s real flow (search, request-to-book, chat)', [
                        'Build the search/browse screen with real (mock) listing data.',
                        'Build a request-to-book flow with at least 2-3 steps.',
                        'Build a basic chat UI (doesn\'t need to be real-time yet, just the screens/flow).',
                        'Use React Navigation properly - tabs and stack navigation together.',
                    ]),
                    $this->project('Push notification integration', [
                        'Set up push notifications on a real device or emulator (Expo notifications or similar).',
                        'Trigger a notification from a real event (not just a test button).',
                        'Handle the app opening to the right screen when a notification is tapped.',
                    ]),
                ],
                'Full Stack Integration, Deployment & Client Readiness' => [
                    $this->project('A deployed React web app', [
                        'Deploy your best React project to Vercel or Netlify.',
                        'Confirm environment variables and API URLs are correctly configured for production.',
                    ]),
                    $this->project('A packaged React Native build', [
                        'Build an installable app package using EAS Build (or equivalent).',
                        'Test the built app on a real device, not just the dev server.',
                    ]),
                    $this->project('A capstone web+mobile app sharing one backend', [
                        'Design one backend API that both your web and mobile app consume.',
                        'Make sure a real action on web is reflected correctly when checked on mobile.',
                        'Present this as your portfolio centerpiece with a short demo video.',
                    ]),
                ],
            ],
            'Frontend Fundamentals: HTML, CSS, JavaScript & Bootstrap' => [
                'HTML5 Fundamentals' => [
                    $this->project('A semantic, accessible multi-section personal profile page', [
                        'Structure the page with real semantic tags (header, nav, main, section, footer) - not divs for everything.',
                        'Add a working contact form with proper labels for every input.',
                        'Add alt text to every image - this is not optional.',
                        'Validate your HTML with the W3C validator and fix every error.',
                    ]),
                ],
                'CSS3 & Responsive Design' => [
                    $this->project('A fully responsive landing page (mobile, tablet, desktop)', [
                        'Design the layout mobile-first, then add breakpoints for tablet/desktop.',
                        'Use Flexbox and/or Grid properly - no float-based layouts.',
                        'Test it by actually resizing the browser and on a real phone if possible.',
                        'Add at least one simple CSS animation or transition.',
                    ]),
                ],
                'Bootstrap 5' => [
                    $this->project('A responsive multi-page business website built on Bootstrap', [
                        'Build at least 3 pages (home, services, contact) using Bootstrap\'s grid.',
                        'Use real Bootstrap components (navbar, cards, modal) - not just utility classes.',
                        'Customize Bootstrap\'s default look with your own Sass variables, not just default blue.',
                    ]),
                ],
                'JavaScript Fundamentals' => [
                    $this->project('An interactive to-do app', [
                        'Add, complete, and delete tasks - all working with real DOM manipulation.',
                        'Persist tasks in localStorage so they survive a page refresh.',
                        'Validate input - no adding empty tasks.',
                    ]),
                    $this->project('A weather-lookup page powered by a public API', [
                        'Fetch real data from a public weather API using fetch().',
                        'Handle the loading state and API errors (e.g. city not found) gracefully.',
                        'Display the result cleanly - no raw JSON dumped on the page.',
                    ]),
                ],
                'Capstone Project' => [
                    $this->project('A complete, responsive multi-page website with a working JS-validated contact form, deployed and demoed', [
                        'Combine everything: semantic HTML, responsive CSS/Bootstrap, and real JavaScript interactivity.',
                        'The contact form must validate on the client side before it would submit.',
                        'Deploy it somewhere real (GitHub Pages, Netlify) so it has a live URL.',
                        'Record a short walkthrough explaining what you built.',
                    ]),
                ],
            ],
            'PHP & WordPress Developer' => [
                'PHP Fundamentals' => [
                    $this->project('A simple PHP contact form with server-side validation', [
                        'Build a form that posts to a PHP script, not just client-side JS validation.',
                        'Validate every field server-side (required, email format, length limits).',
                        'Sanitize input before using it anywhere, to avoid basic security issues.',
                        'Show clear success/error messages back to the user.',
                    ]),
                ],
                'MySQL & Database Basics' => [
                    $this->project('A small PHP+MySQL guestbook or feedback app', [
                        'Design a simple table (name, message, date) and connect PHP to MySQL.',
                        'Insert new entries and display all existing ones on the page.',
                        'Use prepared statements - not raw string-concatenated SQL (a real security habit).',
                    ]),
                ],
                'WordPress Fundamentals' => [
                    $this->project('A custom-styled child theme on a starter theme', [
                        'Create a proper child theme, not edits to the parent theme directly.',
                        'Customize the header/footer and add your own CSS.',
                        'Confirm it survives a parent theme update.',
                    ]),
                ],
                'WordPress Customization' => [
                    $this->project('A fully customized WordPress business site', [
                        'Build a complete site for a realistic business brief using a page builder.',
                        'Customize beyond the theme defaults - colors, fonts, layout.',
                        'Make sure it is mobile-responsive and loads reasonably fast.',
                    ]),
                    $this->project('A basic WooCommerce storefront', [
                        'Set up WooCommerce with at least 5-10 real products.',
                        'Configure a working cart and checkout flow (sandbox payment is fine).',
                        'Test the full purchase flow yourself, start to finish.',
                    ]),
                ],
                'Capstone Project' => [
                    $this->project('A complete, customized WordPress site for a mock client brief, deployed and demoed', [
                        'Take a written mock client brief and build the full site from it.',
                        'Deploy it to a real host so it has a live URL.',
                        'Write a short client-facing handover document.',
                        'Record a short walkthrough demoing the finished site.',
                    ]),
                ],
            ],
        ];

        foreach ($updates as $trackName => $stageProjectMap) {
            $track = AcademyTrack::where('name', $trackName)->first();

            if (!$track) {
                $this->command?->warn("Skipping {$trackName}: track not found.");
                continue;
            }

            $curriculum = $track->curriculum;
            $changed = false;

            foreach ($curriculum['stages'] as &$stage) {
                if (isset($stageProjectMap[$stage['title']])) {
                    $stage['projects'] = $stageProjectMap[$stage['title']];
                    $changed = true;
                }
            }
            unset($stage);

            if ($changed) {
                $track->update(['curriculum' => $curriculum]);
                $this->command?->info("Added step-by-step project detail to {$trackName}.");
            }
        }
    }

    /**
     * Per your explicit list - basic computer knowledge, MS Office, Excel,
     * PowerPoint, typing, mail, ChatGPT, YouTube overview, how to use
     * software - for students who genuinely don't know computer basics
     * yet. Adds what wasn't already covered (email, YouTube) and gives
     * every project step-by-step detail too.
     */
    protected function expandDigitalFoundations(): void
    {
        $track = AcademyTrack::where('name', 'Digital & AI Foundations')->first();

        if (!$track) {
            $this->command?->warn('Skipping Digital & AI Foundations: track not found.');
            return;
        }

        $curriculum = $track->curriculum;

        foreach ($curriculum['stages'] as &$stage) {
            if ($stage['title'] === 'Typing & Computer Fundamentals') {
                $stage['skills'] = [
                    'Touch-typing speed & accuracy goals',
                    'File & folder management (creating, renaming, organizing)',
                    'Operating system basics (settings, installing/uninstalling software)',
                    'Using a web browser confidently (tabs, bookmarks, downloads)',
                    'Email basics - sending, replying, attachments, professional tone',
                    'Safe browsing & basic cybersecurity hygiene',
                ];
                $stage['projects'] = [
                    $this->project('A typing-speed benchmark showing measurable improvement', [
                        'Take a typing test on day one and record your words-per-minute and accuracy.',
                        'Practice daily using a free typing tutor site for the length of this stage.',
                        'Take the same test again and record the improvement.',
                    ]),
                    $this->project('Send a professional email with an attachment', [
                        'Write a clear, professional email (proper greeting, clear subject line, polite closing).',
                        'Attach a file correctly and confirm the recipient can open it.',
                        'Reply to a real email thread keeping the conversation readable.',
                    ]),
                ];
            }

            if ($stage['title'] === 'MS Office Essentials') {
                $stage['projects'] = [
                    $this->project('A formatted business document in Word', [
                        'Create a document with proper headings, consistent fonts, and page numbers.',
                        'Use styles (not manual formatting) so headings are consistent throughout.',
                        'Add a table and a simple image, formatted to look professional.',
                    ]),
                    $this->project('An Excel workbook with formulas, a chart, and a pivot table', [
                        'Enter a realistic dataset (e.g. monthly sales or expenses).',
                        'Use formulas (SUM, AVERAGE, IF) instead of typing numbers manually.',
                        'Build a chart that clearly shows a trend in the data.',
                        'Build a simple pivot table summarizing the data by category.',
                    ]),
                ];
            }

            if ($stage['title'] === 'AI Tools for Productivity') {
                $stage['skills'] = [
                    'Prompt-writing basics (ChatGPT / Gemini)',
                    'Using AI for writing & research',
                    'Using AI to help with spreadsheets/data',
                    'YouTube overview - using it to learn a new skill efficiently',
                    'Recognizing AI mistakes - always verify output',
                ];
                $stage['projects'] = [
                    $this->project('An AI-assisted written report with a short note on what the student checked/corrected', [
                        'Use ChatGPT or Gemini to help draft a short report on a topic you choose.',
                        'Fact-check at least 3 claims the AI made - note what was right and what needed fixing.',
                        'Rewrite the final version in your own words where needed.',
                    ]),
                    $this->project('Learn one new skill using only YouTube, and document how', [
                        'Pick a small skill you don\'t know yet (e.g. a keyboard shortcut, a basic Excel trick).',
                        'Find and follow a YouTube tutorial to learn it.',
                        'Write a short summary of what you learned and rate how clear the tutorial was.',
                    ]),
                ];
            }

            if ($stage['title'] === 'Intro to Programming Logic') {
                $stage['projects'] = [
                    $this->project('A simple script that takes input and makes a decision (e.g. a basic calculator or quiz)', [
                        'Draw a simple flowchart of what your program should do before writing any code.',
                        'Write a small Python or JavaScript script that takes user input.',
                        'Add at least one if/else decision based on that input.',
                        'Test it with a few different inputs, including ones that might break it.',
                    ]),
                ];
            }

            if ($stage['title'] === 'Capstone Project') {
                $stage['projects'] = [
                    $this->project('A small portfolio: one polished Excel/Word deliverable, one AI-assisted write-up, and the programming-logic exercise, presented together', [
                        'Gather your best Word document, Excel workbook, and AI-assisted report from this track.',
                        'Clean each one up so it looks genuinely professional.',
                        'Present all three together (a simple folder or single PDF is fine) as if showing an employer.',
                    ]),
                ];
            }
        }
        unset($stage);

        $track->update(['curriculum' => $curriculum]);
        $this->command?->info('Expanded Digital & AI Foundations (email, YouTube overview) with step-by-step projects.');
    }

    protected function createNewTracks(): void
    {
        $defaultInstructorId = \App\Models\User::where('email', 'sainmehtab15@gmail.com')->value('id'); // Mehtab sain

        $tracks = [
            [
                'name' => 'YouTube & Content Automation',
                'designation' => 'YouTube Content Creator & Automation Specialist',
                'description' => 'Planning, producing, and automating a real YouTube channel - scripting, editing, thumbnails, SEO, and using automation/AI tools to scale content output.',
                'is_open_for_enrollment' => true,
                'registration_fee_enabled' => true,
                'registration_fee_amount' => 1500,
                'monthly_fee_amount' => 4000,
                'default_instructor_id' => $defaultInstructorId,
                'curriculum' => [
                    'stages' => [
                        $this->githubStage(),
                        [
                            'title' => 'YouTube Fundamentals & Channel Strategy',
                            'duration' => '2 weeks',
                            'skills' => [
                                'Choosing a niche and target audience',
                                'YouTube algorithm basics - what actually drives views',
                                'Channel branding (banner, logo, about section)',
                                'Content calendar planning',
                                'Competitor/channel research',
                            ],
                            'projects' => [
                                $this->project('A complete channel setup with a 30-day content plan', [
                                    'Pick a specific niche and define who the target viewer is.',
                                    'Set up channel branding - banner, profile picture, channel description.',
                                    'Research 5 successful channels in the same niche and note what works.',
                                    'Build a 30-day content calendar with real video topics, not vague ideas.',
                                ]),
                            ],
                        ],
                        [
                            'title' => 'Scripting, Filming & Editing Basics',
                            'duration' => '3 weeks',
                            'skills' => [
                                'Writing a hook and script structure that keeps viewers watching',
                                'Basic filming setup (lighting, audio, framing) with just a phone',
                                'Video editing fundamentals (cuts, pacing, captions, music)',
                                'Using CapCut or a similar free/accessible editor',
                            ],
                            'projects' => [
                                $this->project('A fully scripted, filmed, and edited 3-5 minute video', [
                                    'Write a full script with a hook in the first 5 seconds.',
                                    'Film it with clean audio and decent lighting - phone camera is fine.',
                                    'Edit it with cuts, at least one text overlay, and background music.',
                                    'Export in the correct resolution/aspect ratio for YouTube.',
                                ]),
                            ],
                        ],
                        [
                            'title' => 'Thumbnails, SEO & Canva',
                            'duration' => '2 weeks',
                            'skills' => [
                                'Thumbnail design principles (contrast, faces, text size)',
                                'Using Canva to design thumbnails efficiently',
                                'YouTube SEO - titles, descriptions, tags, keyword research',
                                'A/B testing thumbnails and titles',
                            ],
                            'projects' => [
                                $this->project('3 thumbnail variants + optimized title/description/tags for one video', [
                                    'Design 3 different thumbnail concepts in Canva for the same video.',
                                    'Get feedback from at least 2 people on which thumbnail they\'d click.',
                                    'Write an SEO-optimized title, description, and tag list using real keyword research.',
                                ]),
                            ],
                        ],
                        [
                            'title' => 'Automation & Scaling',
                            'duration' => '3 weeks',
                            'skills' => [
                                'Using AI (ChatGPT/Gemini) for script drafts and idea generation',
                                'Batch content production workflows',
                                'Scheduling tools and upload automation basics',
                                'Analytics - reading YouTube Studio data and adjusting strategy',
                            ],
                            'projects' => [
                                $this->project('A documented, semi-automated content production workflow', [
                                    'Design a repeatable workflow: idea -> AI-assisted script draft -> filming -> editing -> upload.',
                                    'Use an AI tool for at least the ideation or first-draft scripting step.',
                                    'Produce 2 videos using this workflow and time how long each stage takes.',
                                    'Review YouTube Studio analytics on a real or practice upload and note 2 things you\'d change.',
                                ]),
                            ],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Business Development & Freelancing',
                'designation' => 'Business Development Executive / Freelance Consultant',
                'description' => 'How to actually win work as a freelancer or agency BD - platform mastery (Upwork/Fiverr/Freelancer.com), proposal writing, client communication, direct-client outreach, and the design/video basics needed to pitch effectively.',
                'is_open_for_enrollment' => true,
                'registration_fee_enabled' => true,
                'registration_fee_amount' => 1500,
                'monthly_fee_amount' => 4500,
                'default_instructor_id' => $defaultInstructorId,
                'curriculum' => [
                    'stages' => [
                        $this->githubStage(),
                        [
                            'title' => 'Freelance Platforms & Profile Optimization',
                            'duration' => '2 weeks',
                            'skills' => [
                                'Upwork, Fiverr, and Freelancer.com - how each platform actually works',
                                'Writing a profile that gets noticed (headline, overview, portfolio)',
                                'Understanding platform fees, ranking, and Job Success Score',
                                'Building a basic portfolio even with no prior client work',
                            ],
                            'projects' => [
                                $this->project('A fully optimized profile on at least one platform', [
                                    'Write a headline and overview that clearly states who you help and how.',
                                    'Add at least 3 portfolio pieces (real past work, or self-initiated samples).',
                                    'Get feedback from someone else on the profile before publishing.',
                                    'Research 5 top-ranked profiles in your niche and note what they do well.',
                                ]),
                            ],
                        ],
                        [
                            'title' => 'Bidding, Proposals & Winning Projects',
                            'duration' => '3 weeks',
                            'skills' => [
                                'Reading a job post properly before bidding',
                                'Writing a proposal that isn\'t a generic template',
                                'Pricing a project (fixed vs hourly, and how to estimate)',
                                'How "locking"/winning a project actually works on each platform',
                                'Following up without being pushy',
                            ],
                            'projects' => [
                                $this->project('5 real, tailored proposals submitted to real job posts', [
                                    'Find 5 real job posts that genuinely match your skills.',
                                    'Write a unique, tailored proposal for each - no copy-paste templates.',
                                    'Price each one with a clear justification for the number you chose.',
                                    'Track which proposals get a response and note any patterns.',
                                ]),
                            ],
                        ],
                        [
                            'title' => 'Direct Client Outreach & Communication',
                            'duration' => '3 weeks',
                            'skills' => [
                                'Finding direct clients outside the platforms (LinkedIn, cold email, local business)',
                                'Writing a cold outreach message that gets replies',
                                'Client communication skills - setting expectations, handling scope changes',
                                'Basic contracts and getting paid safely',
                            ],
                            'projects' => [
                                $this->project('A direct outreach campaign to 10 real prospects', [
                                    'Identify 10 real potential clients who could plausibly need your service.',
                                    'Write a short, personalized outreach message for each (not a mass blast).',
                                    'Send them and track response rate.',
                                    'Write a short reflection on what worked and what you\'d change.',
                                ]),
                            ],
                        ],
                        [
                            'title' => 'Pitch Assets: Canva, Basic Video & Tech Literacy',
                            'duration' => '2 weeks',
                            'skills' => [
                                'Canva for pitch decks, portfolio graphics, and social thumbnails',
                                'Basic video editing for a client intro or portfolio video',
                                'Enough web/mobile development knowledge to speak credibly with technical clients',
                                'Presenting yourself and your work confidently in a call',
                            ],
                            'projects' => [
                                $this->project('A pitch deck + a 60-90 second intro video', [
                                    'Design a short pitch deck in Canva (5-8 slides: who you are, what you do, portfolio, pricing, CTA).',
                                    'Script and record a 60-90 second video introducing yourself and your services.',
                                    'Edit the video with basic cuts and a title card.',
                                    'Present both to a mock "client" (a classmate or instructor) and get feedback.',
                                ]),
                            ],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($tracks as $track) {
            $created = AcademyTrack::firstOrCreate(['name' => $track['name']], $track);
            $this->command?->info(($created->wasRecentlyCreated ? 'Created' : 'Already exists').": {$track['name']}");
        }
    }

    protected function project(string $title, array $steps): array
    {
        return ['title' => $title, 'steps' => $steps];
    }

    protected function githubStage(): array
    {
        return [
            'title' => 'Git & GitHub Basics (Compulsory)',
            'duration' => '1 week',
            'skills' => [
                'Installing Git & configuring your identity',
                'git init / add / commit / status / log',
                'Creating a GitHub account & repository',
                'git push / pull, remotes',
                'Branching & merging basics',
                'Writing a clear README',
                'Opening your first pull request',
            ],
            'projects' => [
                $this->project('Push a small starter project to a public GitHub repo with a proper README and at least 3 commits', [
                    'Install Git and set your name/email with git config.',
                    'Create a new GitHub repository for this track.',
                    'Make at least 3 separate, meaningful commits (not one giant commit).',
                    'Write a clear README explaining what the repo is for.',
                    'Push everything and share the repo link - this is the link every later stage in this track expects.',
                ]),
            ],
        ];
    }
}
