# CLAUDE.md

Guidance for working on this codebase. This is a **live production system** —
real users, real client data, real money (financials, payroll, invoices),
real emails sent to real clients. Treat every database change as
irreversible unless proven otherwise.

## Seeders — never run a blanket `db:seed`

**Never run `php artisan db:seed` with no `--class` flag.** It runs every
seeder registered in `DatabaseSeeder`, and not all of them are safe to
replay against live data (some are meant for fresh installs only).

Always target the one seeder you actually need:

```
php artisan db:seed --class="Database\Seeders\SomeSpecificSeeder" --force
```

Before running any seeder against production, read it first and confirm it
only touches rows it's meant to (`updateOrCreate`/`firstOrCreate` keyed on a
stable natural key, not a blind `create`/`truncate`). If it's not
idempotent, don't run it twice.

The deploy process here does **not** run seeders automatically — deploying
code does not re-apply seeder changes. If a seeder needs to affect the live
DB, it (or the equivalent migration) must be run explicitly and separately,
and you should say so rather than assume deploy handles it.

## Custom admin modules must not collide with Voyager's auto-BREAD

Several admin sections (`SmtpAccountController`, `EmailCampaignController`,
`EmailSignatureController`, `CampaignAutomationController`,
`FinancialsController`, ...) are **hand-built**: their own controller,
routes (in `routes/web.php`), and Blade views — not Voyager's generic BREAD.

If a row for that table's slug ever exists in the `data_types` table
(e.g. from someone running Voyager's "Generate BREAD" against that table via
the admin UI), Voyager's `Voyager::routes()` call — registered at the *end*
of the same `admin` route group in `routes/web.php` — will auto-register its
own generic `voyager.{slug}.*` routes for the same URIs, **after** the
custom ones.

Laravel's router keys routes by exact `method + URI` and a later route with
the same key **fully replaces** an earlier one in the route collection —
not just "shadows" it, the earlier named route stops existing entirely
(`route('name')` throws `RouteNotFoundException`). This silently broke
`smtp-accounts.index/create/store/edit/destroy` for weeks (only `update`
and `send-test` happened to survive) until the stray `data_types` row
(slug `smtp-accounts`) was found and deleted. See git history around
2026-09-19 for the full diagnosis.

**When adding or auditing a custom admin module:**
1. Confirm there is no `data_types` row for its slug:
   `DB::table('data_types')->where('slug', '<slug>')->exists()` must be `false`.
2. If one exists and isn't supposed to, delete that row (it's Voyager
   config metadata, not app data — safe to remove) and run
   `php artisan route:clear` to be sure no stale route cache is involved.
3. Never use Voyager's "Generate BREAD" UI against a table that already has
   (or will have) a custom controller.

## Every custom admin module needs its own sidebar menu item — seeded

Two features so far were built with fully working routes/controllers/views
but **no sidebar menu item was ever seeded for them**, making them
unreachable from the UI even though nothing was actually broken:

- Email Signatures (`/admin/email-signatures`) — fixed 2026-09-19, see
  `EmailSignatureSettingsSeeder`.
- Financials dashboard (`/admin/financials`) — fixed 2026-09-19, see
  `2026_09_19_000001_add_financials_dashboard_menu_item.php`. Only its
  "Quick Expense (Mobile)" sub-page had a menu item; the dashboard itself
  didn't.

**When you build a new custom admin module, the menu item is part of the
feature, not an afterthought.** Add it in the same PR, via a seeder
(`TCG\Voyager\Models\MenuItem::firstOrCreate(...)`) or migration if the item
also needs a restricted permission row (see next section). Group related
items under a parent via `parent_id` so the sidebar stays organized instead
of a flat list — e.g. Financials-related pages are nested under a single
"Financials" parent item; do the same for any new multi-page module rather
than adding more top-level entries.

## Menu items restricted by custom middleware need a matching permission row

If a page is gated by a custom middleware (not a Voyager BREAD permission
check) — e.g. `EnsureCampaignAccess`, `EnsureFinancialsAccess` — Voyager's
sidebar menu-permission check **fails open** for routes with no matching
`permissions` row: it shows the menu item to every logged-in user even
though the page itself will 403 them. Always add a `permissions` row for
the menu item's derived key (`browse_<table_name>`) and grant it only to
the roles that should see it, mirroring
`2026_07_18_000002_restrict_financials_cash_menu_item.php` and
`2026_09_19_000001_add_financials_dashboard_menu_item.php`.

## New screens (Academy, HR/Ops, or any future module)

- Every new screen must be fully responsive - mobile, tablet, and desktop. No fixed-width layouts that break or require horizontal scrolling on a smaller screen.
- Follow the existing WebPenter theme already established in this codebase rather than introducing a new visual language per screen:
  - The green/white palette and typography (Plus Jakarta Sans) already used across the Academy pages (`resources/views/academy/*.blade.php`).
  - The same card/button/badge/form patterns already in use there (`.card`, `.btn`/`.btn-outline`, `.status` pills, etc.).
  - The Voyager admin rebrand (`public/css/custom.css`, `config/voyager.php` `primary_color`) for anything inside the admin panel.

## Academy & HR Documents module map

- **Roles/capabilities**: `app/Models/AcademyStaffRole.php` (additive capabilities: instructor/reviewer/accountant/marketing/printer/card_manager), `app/Http/Controllers/Voyager/AcademyAwareAuthController.php` (post-login redirect per role), `app/Models/User.php` (`academyLandingRoute()`, `isAcademy*()` helpers).
- **Dashboards**: `resources/views/academy/*-dashboard.blade.php` + matching controllers in `app/Http/Controllers/` - one per role. Docs: `resources/views/academy/help.blade.php` (`AcademyHelpController`), role-scoped.
- **Certificates**: `app/Services/CertificateService.php` (render/issue/Slack), `resources/views/academy/certificates/{classic,linkedin,udemy}.blade.php` (designs), Voyager Settings group "Certification" (signatories, images, show/hide).
- **Marketing**: `AcademyMarketingDashboardController`, `app/Services/AcademySocialPostService.php` (AI post suggestions).
- **Student ID cards**: `AcademyCardBatchController` (Card Manager batches) + `AcademyStudentCardController` (Printer).
- **Instructor payouts**: `AcademyInstructorPayoutController` (Administrator only).
- **HR document builder**: `app/Services/DocumentTemplateService.php` (token substitution + render), `app/Models/DocumentTemplate.php` / `DocumentIssuance.php`, controllers + views under `resources/views/hr-documents/`, PDF designs under `resources/views/hr/documents/designs/`.
- **Seeders**: `database/seeders/AcademySeeder.php` (local/dev, includes test accounts + demo data) vs. `AcademyProductionDeploySeeder.php` (live - real data + roles only, one command for a fresh deploy). This module's seeders follow the same rule as above: run the one you need by `--class`, never a blanket `db:seed`.

## General

- Prefer applying urgent live-DB fixes directly (via `php artisan tinker` or
  a migration `up()`), then commit the equivalent seeder/migration to source
  control for reproducibility on other environments — don't wait on a full
  deploy cycle for something already fixed in production.
- Confirm before any destructive or public-visibility action (force push,
  `git reset --hard`, creating a PR/MR, committing real credentials to a
  public repo). This repo (`rashidhussainshah/wp-daily-update-to-clients`)
  is **public** — several seeders contain real plaintext SMTP passwords
  already committed; don't add more without asking first.
