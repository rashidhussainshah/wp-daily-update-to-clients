# Project Instructions

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
- **Seeders**: `database/seeders/AcademySeeder.php` (local/dev, includes test accounts + demo data) vs. `AcademyProductionDeploySeeder.php` (live - real data + roles only, one command for a fresh deploy).
