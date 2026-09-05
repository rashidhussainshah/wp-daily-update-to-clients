<?php

namespace Database\Seeders;

use App\Models\AcademyTrack;
use Illuminate\Database\Seeder;

/**
 * Seeds the 4 fully-designed, user-approved Academy tracks. Curriculum is
 * stored as JSON on academy_tracks.curriculum (see A1) - staff manage/edit
 * this afterward through Voyager, not by re-running this seeder.
 *
 * Only run once, manually: php artisan db:seed --class=AcademyTracksSeeder
 */
class AcademyTracksSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->tracks() as $track) {
            AcademyTrack::firstOrCreate(['name' => $track['name']], $track);
        }
    }

    protected function tracks(): array
    {
        return [
            [
                'name' => 'Python & AI/ML Engineer',
                'designation' => 'ML / AI Engineer',
                'description' => 'From Python fundamentals to deploying production ML models, including LLM-based features and client-facing delivery.',
                'curriculum' => [
                    'stages' => [
                        [
                            'title' => 'Python Core & Engineering Fundamentals',
                            'duration' => '6-8 weeks',
                            'skills' => [
                                'Python syntax & OOP', 'Git workflow', 'Clean functions & modules',
                                'Exception handling', 'File I/O & JSON/CSV', 'Core data structures & algorithms',
                                'pytest', 'PEP8 / linting', 'Using an AI assistant productively for boilerplate & debugging',
                            ],
                            'projects' => ['CLI Expense Tracker', 'Library Management System (OOP)', 'Small REST API client', 'A pytest-covered utility library'],
                        ],
                        [
                            'title' => 'Data Handling & Analysis',
                            'duration' => '6 weeks',
                            'skills' => [
                                'NumPy', 'Pandas', 'Matplotlib / Seaborn', 'SQL fundamentals + Python-DB integration',
                                'Basic statistics', 'Jupyter workflow', 'Data cleaning', 'Feature engineering basics',
                            ],
                            'projects' => ['Sales Data Analyzer + dashboard', 'Real messy-dataset cleaning pipeline', 'SQL + Python reporting tool'],
                        ],
                        [
                            'title' => 'Machine Learning Fundamentals',
                            'duration' => '8 weeks',
                            'skills' => [
                                'Scikit-learn (regression/classification/clustering)', 'Train/test + cross-validation',
                                'Evaluation metrics', 'Feature scaling/encoding', 'Imbalanced data handling',
                                'Intro NLP', 'When to reach for deep learning vs. classical ML', 'Transfer learning basics',
                                'Prompt engineering for LLM-based features',
                            ],
                            'projects' => ['Sentiment/Spam Classifier', 'House Price Predictor', 'Churn Predictor', 'A small LLM-powered feature (e.g. text summarizer)'],
                        ],
                        [
                            'title' => 'Deployment & Production',
                            'duration' => '6 weeks',
                            'skills' => [
                                'FastAPI/Flask model serving', 'Docker basics', 'Deploying to Render/Railway/AWS',
                                'API documentation', 'Model versioning basics', 'Monitoring a deployed model',
                                'Client-facing communication',
                            ],
                            'projects' => ['Deploy a trained model as a documented API', 'A small full-stack demo suitable to show a client', 'Capstone combining Stages 2-3 into one presentable product'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'Full Stack Laravel/PHP Developer',
                'designation' => 'Full Stack Laravel/PHP Developer',
                'description' => 'PHP and Laravel from fundamentals to production deployment, including the same Voyager-admin patterns used internally at WebPenter.',
                'curriculum' => [
                    'stages' => [
                        [
                            'title' => 'PHP & Laravel Fundamentals',
                            'duration' => '6-8 weeks',
                            'skills' => ['PHP OOP', 'Composer', 'MVC', 'Laravel routing/controllers/Blade', 'Eloquent basics', 'Validation', 'Middleware', 'Artisan', 'Git', 'AI-assisted debugging'],
                            'projects' => ['Task Manager CRUD', 'A blog with categories/tags', 'A validated contact form with mail'],
                        ],
                        [
                            'title' => 'APIs & Authentication',
                            'duration' => '6 weeks',
                            'skills' => ['REST API design', 'Sanctum/Passport', 'API resources', 'Form Request validation', 'Pagination/filtering', 'PHPUnit/Pest', 'Queues & jobs', 'Basic caching'],
                            'projects' => ['An authenticated REST API for an inventory system', 'A queue-powered notification system'],
                        ],
                        [
                            'title' => 'Advanced Laravel & Real-World Patterns',
                            'duration' => '6-8 weeks',
                            'skills' => ['Service/repository pattern', 'Events/listeners/observers', 'Policies & gates', 'File storage', 'Payment gateway integration', 'DB design/optimization', 'Voyager-style admin panel patterns'],
                            'projects' => ['A role-permissioned admin panel', 'A payment-integrated booking feature', 'A Voyager-based admin CRUD system'],
                        ],
                        [
                            'title' => 'Deployment & Client Readiness',
                            'duration' => '6 weeks',
                            'skills' => ['VPS/Hostinger deployment', '.env/environment config', 'GitHub Actions CI/CD', 'Integrating a Vue/React frontend', 'Nginx/SSL basics', 'Client communication & documentation'],
                            'projects' => ['Deploy a Laravel app with real CI/CD', 'A full-stack Laravel + React/Vue app', 'A client-presentable capstone'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'WordPress Developer & Customization Specialist',
                'designation' => 'WordPress Developer & Customization Specialist',
                'description' => 'WordPress theme/plugin customization through to real-estate/booking theme specialization, mirroring WebPenter\'s actual Houzez/Homey client work.',
                'curriculum' => [
                    'stages' => [
                        [
                            'title' => 'WordPress Fundamentals & Theme Customization',
                            'duration' => '6 weeks',
                            'skills' => ['Themes/plugins/hooks/filters', 'The Loop', 'Child themes', 'Elementor/WPBakery', 'WooCommerce basics', 'WP-CLI', 'Git'],
                            'projects' => ['A page-builder business site', 'A custom-styled child theme'],
                        ],
                        [
                            'title' => 'Custom Plugins & Post Types',
                            'duration' => '6-8 weeks',
                            'skills' => ['Custom post types/taxonomies', 'ACF', 'Building a plugin from scratch', 'WP REST API', 'Security basics (sanitize/escape/nonces)'],
                            'projects' => ['A custom booking/review plugin', 'A CPT-driven listing site'],
                        ],
                        [
                            'title' => 'Real Estate & Booking Theme Specialization',
                            'duration' => '6 weeks',
                            'skills' => ['Deep Houzez/Homey customization', 'WooCommerce custom checkout', 'Payment gateways', 'Performance optimization', 'Basic Gutenberg/headless-WP integration'],
                            'projects' => ['A fully customized Houzez/Homey site for a mock client', 'A custom checkout flow', 'A custom Gutenberg block'],
                        ],
                        [
                            'title' => 'Deployment, Maintenance & Client Readiness',
                            'duration' => '6 weeks',
                            'skills' => ['Hosting/staging', 'Migrations', 'Security hardening', 'Backups', 'Technical SEO', 'Client onboarding/support under time pressure'],
                            'projects' => ['A production deployment with staging', 'A full site migration', 'A client support runbook'],
                        ],
                    ],
                ],
            ],
            [
                'name' => 'React / React Native Developer',
                'designation' => 'React / React Native Developer',
                'description' => 'React and React Native from fundamentals through mobile development, mirroring WebPenter\'s own BookHere product.',
                'curriculum' => [
                    'stages' => [
                        [
                            'title' => 'JavaScript & React Fundamentals',
                            'duration' => '6-8 weeks',
                            'skills' => ['Modern JS (ES6+/async)', 'React core (hooks, JSX, state/props)', 'React Router', 'Git'],
                            'projects' => ['A product filter app', 'A multi-page routed site'],
                        ],
                        [
                            'title' => 'State Management & API Integration',
                            'duration' => '6 weeks',
                            'skills' => ['Context API', 'Redux Toolkit/Zustand', 'React Query/axios', 'JWT auth flows', 'React Testing Library basics'],
                            'projects' => ['A weather dashboard on a live API', 'An authenticated app'],
                        ],
                        [
                            'title' => 'React Native & Mobile Development',
                            'duration' => '8 weeks',
                            'skills' => ['React Native fundamentals', 'React Navigation', 'Camera/location/push notifications', 'Connecting to a WordPress/REST backend'],
                            'projects' => ['A booking/listing app UI mirroring BookHere\'s real flow (search, request-to-book, chat)', 'Push notification integration'],
                        ],
                        [
                            'title' => 'Full Stack Integration, Deployment & Client Readiness',
                            'duration' => '6 weeks',
                            'skills' => ['Vercel/Netlify deploy', 'EAS Build/App Store basics', 'Performance optimization', 'Calling an LLM API from a frontend', 'Client demo skills'],
                            'projects' => ['A deployed React web app', 'A packaged React Native build', 'A capstone web+mobile app sharing one backend'],
                        ],
                    ],
                ],
            ],
        ];
    }
}
