<?php

namespace Database\Seeders;

use App\Models\AcademyTrack;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Two changes to the curriculum, requested after the first 4 tracks were
 * already live and (for Python & AI/ML Engineer) had a real test enrollment
 * against it - so this is a data migration on top of AcademyTracksSeeder,
 * not an edit to it (same reasoning as never editing an already-run
 * schema migration):
 *
 *  1. A compulsory "Git & GitHub Basics" stage, prepended as stage 0 of
 *     EVERY track (the original 4 plus the 3 new ones below). Every
 *     student proves they can create a repo and push code before any real
 *     project stage begins.
 *  2. Three new tracks: Frontend Fundamentals, PHP & WordPress Developer
 *     (a lighter/faster on-ramp than the existing, deeper "WordPress
 *     Developer & Customization Specialist" track), and Digital & AI
 *     Foundations (non-technical - typing, MS Office, AI tools, a gentle
 *     first taste of programming logic).
 *
 * Fee amounts and the default instructor below are placeholders, flagged
 * as such - edit them via the Academy Tracks screen in Voyager
 * (admin/academy-tracks) once real pricing is confirmed.
 *
 * Idempotent - safe to re-run: skips a track whose stage 0 is already the
 * GitHub stage, and firstOrCreate()s the 3 new tracks by name.
 *
 * Run: php artisan db:seed --class=AcademyGithubStageAndNewTracksSeeder
 */
class AcademyGithubStageAndNewTracksSeeder extends Seeder
{
    public function run(): void
    {
        $this->prependGithubStageToExistingTracks();
        $this->createNewTracks();
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
                'Push a small starter project to a public GitHub repo with a proper README and at least 3 commits - the link you submit here is the one every later stage in this track expects.',
            ],
        ];
    }

    protected function prependGithubStageToExistingTracks(): void
    {
        $existingTrackNames = [
            'Python & AI/ML Engineer',
            'Full Stack Laravel/PHP Developer',
            'WordPress Developer & Customization Specialist',
            'React / React Native Developer',
        ];

        $githubTitle = $this->githubStage()['title'];

        foreach ($existingTrackNames as $name) {
            $track = AcademyTrack::where('name', $name)->first();

            if (!$track) {
                $this->command?->warn("Skipping '{$name}': track not found (was AcademyTracksSeeder run?).");
                continue;
            }

            $curriculum = $track->curriculum ?? ['stages' => []];
            $firstStageTitle = $curriculum['stages'][0]['title'] ?? null;

            if ($firstStageTitle === $githubTitle) {
                $this->command?->info("Skipping '{$name}': GitHub stage already present.");
                continue;
            }

            array_unshift($curriculum['stages'], $this->githubStage());
            $track->update(['curriculum' => $curriculum]);
            $this->command?->info("Prepended GitHub stage to '{$name}'.");
        }
    }

    protected function createNewTracks(): void
    {
        $defaultInstructorId = User::where('email', 'sainmehtab15@gmail.com')->value('id'); // Mehtab sain

        $tracks = [
            [
                'name' => 'Frontend Fundamentals: HTML, CSS, JavaScript & Bootstrap',
                'designation' => 'Junior Frontend Developer',
                'description' => 'An entry-level track for absolute beginners: hand-coded responsive websites from semantic HTML through Bootstrap and core JavaScript, no framework required yet.',
                'is_open_for_enrollment' => true,
                'registration_fee_enabled' => true,
                'registration_fee_amount' => 1500,
                'monthly_fee_amount' => 4000,
                'default_instructor_id' => $defaultInstructorId,
                'curriculum' => [
                    'stages' => [
                        $this->githubStage(),
                        [
                            'title' => 'HTML5 Fundamentals',
                            'duration' => '2 weeks',
                            'skills' => ['Semantic HTML5', 'Forms & validation attributes', 'Accessibility basics (alt text, labels, landmarks)', 'Tables & media embedding'],
                            'projects' => ['A semantic, accessible multi-section personal profile page'],
                        ],
                        [
                            'title' => 'CSS3 & Responsive Design',
                            'duration' => '3 weeks',
                            'skills' => ['Box model & positioning', 'Flexbox', 'CSS Grid', 'Media queries / mobile-first design', 'CSS variables', 'Basic animations & transitions'],
                            'projects' => ['A fully responsive landing page (mobile, tablet, desktop)'],
                        ],
                        [
                            'title' => 'Bootstrap 5',
                            'duration' => '2 weeks',
                            'skills' => ['Grid system', 'Components (navbar, cards, modals, carousels)', 'Utility classes', 'Customizing Bootstrap with Sass variables'],
                            'projects' => ['A responsive multi-page business website built on Bootstrap'],
                        ],
                        [
                            'title' => 'JavaScript Fundamentals',
                            'duration' => '4 weeks',
                            'skills' => ['Variables/functions/arrays/objects', 'DOM selection & manipulation', 'Event handling', 'Form validation in JS', 'fetch() & working with a public API', 'Using an AI assistant productively for debugging'],
                            'projects' => ['An interactive to-do app', 'A weather-lookup page powered by a public API'],
                        ],
                        [
                            'title' => 'Capstone Project',
                            'duration' => '2 weeks',
                            'skills' => ['Combining HTML/CSS/Bootstrap/JS into one project', 'Basic performance & cross-browser checks', 'Presenting work to a reviewer'],
                            'projects' => ['A complete, responsive multi-page website with a working JS-validated contact form, deployed and demoed'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'PHP & WordPress Developer',
                'designation' => 'WordPress Site Builder',
                'description' => 'A faster on-ramp than the deeper WordPress Developer & Customization Specialist track: core PHP plus practical WordPress site-building and customization, aimed at getting a student job-ready for WebPenter\'s WordPress client work sooner.',
                'is_open_for_enrollment' => true,
                'registration_fee_enabled' => true,
                'registration_fee_amount' => 1500,
                'monthly_fee_amount' => 5000,
                'default_instructor_id' => $defaultInstructorId,
                'curriculum' => [
                    'stages' => [
                        $this->githubStage(),
                        [
                            'title' => 'PHP Fundamentals',
                            'duration' => '3 weeks',
                            'skills' => ['PHP syntax & control structures', 'Functions & arrays', 'Basic OOP (classes/objects)', 'Working with forms & $_POST/$_GET', 'Basic security (sanitizing input)'],
                            'projects' => ['A simple PHP contact form with server-side validation'],
                        ],
                        [
                            'title' => 'MySQL & Database Basics',
                            'duration' => '2 weeks',
                            'skills' => ['SQL fundamentals (SELECT/INSERT/UPDATE/DELETE)', 'phpMyAdmin', 'Understanding the WordPress database schema (wp_posts, wp_postmeta, etc.)'],
                            'projects' => ['A small PHP+MySQL guestbook or feedback app'],
                        ],
                        [
                            'title' => 'WordPress Fundamentals',
                            'duration' => '3 weeks',
                            'skills' => ['Installation & the admin dashboard', 'Themes vs. plugins', 'Child themes', 'Hooks & filters (intro)', 'The Loop', 'WP-CLI basics'],
                            'projects' => ['A custom-styled child theme on a starter theme'],
                        ],
                        [
                            'title' => 'WordPress Customization',
                            'duration' => '4 weeks',
                            'skills' => ['Elementor / page builders', 'WooCommerce basics (products, cart, checkout)', 'Plugin configuration & troubleshooting', 'Basic ACF usage', 'Performance & caching plugins'],
                            'projects' => ['A fully customized WordPress business site', 'A basic WooCommerce storefront'],
                        ],
                        [
                            'title' => 'Capstone Project',
                            'duration' => '2 weeks',
                            'skills' => ['Full site build from brief to delivery', 'Hosting/deployment basics', 'Client-style documentation'],
                            'projects' => ['A complete, customized WordPress site for a mock client brief, deployed and demoed'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Digital & AI Foundations',
                'designation' => 'Digital Skills & AI-Assisted Productivity',
                'description' => 'A non-technical, entry-level track covering computer/typing fundamentals, MS Office, practical AI-tool usage, and a first gentle taste of programming logic - a good on-ramp before the technical tracks, or a standalone office-productivity certification.',
                'is_open_for_enrollment' => true,
                'registration_fee_enabled' => false,
                'registration_fee_amount' => 0,
                'monthly_fee_amount' => 2000,
                'default_instructor_id' => $defaultInstructorId,
                'curriculum' => [
                    'stages' => [
                        $this->githubStage(),
                        [
                            'title' => 'Typing & Computer Fundamentals',
                            'duration' => '1 week',
                            'skills' => ['Touch-typing speed & accuracy goals', 'File & folder management', 'Operating system basics', 'Safe browsing & basic cybersecurity hygiene'],
                            'projects' => ['A typing-speed benchmark showing measurable improvement'],
                        ],
                        [
                            'title' => 'MS Office Essentials',
                            'duration' => '2 weeks',
                            'skills' => ['Word: formatting, styles, mail merge', 'Excel: formulas, charts, pivot tables', 'PowerPoint: structuring a clear presentation'],
                            'projects' => ['A formatted business document in Word', 'An Excel workbook with formulas, a chart, and a pivot table'],
                        ],
                        [
                            'title' => 'AI Tools for Productivity',
                            'duration' => '1 week',
                            'skills' => ['Prompt-writing basics (ChatGPT / Gemini)', 'Using AI for writing & research', 'Using AI to help with spreadsheets/data', 'Recognizing AI mistakes - always verify output'],
                            'projects' => ['An AI-assisted written report with a short note on what the student checked/corrected'],
                        ],
                        [
                            'title' => 'Intro to Programming Logic',
                            'duration' => '2 weeks',
                            'skills' => ['What a program is - flowcharts & step-by-step thinking', 'Variables & simple if/else logic', 'A first tiny script (Python or JavaScript)'],
                            'projects' => ['A simple script that takes input and makes a decision (e.g. a basic calculator or quiz)'],
                        ],
                        [
                            'title' => 'Capstone Project',
                            'duration' => '1 week',
                            'skills' => ['Bringing Office, AI, and basic scripting together', 'Presenting a small portfolio of work'],
                            'projects' => ['A small portfolio: one polished Excel/Word deliverable, one AI-assisted write-up, and the programming-logic exercise, presented together'],
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
}
