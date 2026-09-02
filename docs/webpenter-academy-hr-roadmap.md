# WebPenter Academy & HR/Ops — Roadmap

Working backlog for two related but separate builds. Nothing here is implemented yet unless marked `[x]`. We're going one item at a time — see project memory `webpenter-academy-project` and `webpenter-hr-payroll-project` for full background/decisions on each.

Design reference (approved): https://claude.ai/code/artifact/08a89d27-4527-4eac-81a4-f6b1d20ec244

Each item below has a **Plan** — my current thinking on how to build it. These are proposals, not commitments — they'll get revised as we discuss each one before building it.

## A. WebPenter IT Academy (student-facing)

- [ ] **A1. Data foundation**
  Plan: 3 migrations — `academy_tracks` (name, designation, curriculum stored as JSON: stages → skills/projects), `developer_academy_enrollments` (user_id, track_id, current_stage, progress JSON, badges_earned JSON), `academy_ai_reviews` (enrollment_id, submission link, AI score/verdict/feedback, reviewer decision). Matching Eloquent models + relationships. Seed a `AcademyTracksSeeder` with the 7 real tracks — full stage/skill/project detail for the 3 already-drafted dev tracks, placeholder stage names for the other 4 until we flesh those out. Register Voyager BREAD for the new tables via the admin UI (matches how `developer_cards`/`expertises` were done — not seeded in code). "Student" = existing `User` with `role_id = STUDENT_ROLE_ID`, no new user table.

- [ ] **A2. Student dashboard**
  Plan: new `/academy/*` route group behind the existing `web` guard + a role check (`role_id == STUDENT_ROLE_ID`). `AcademyController@dashboard` loads the user's enrollment, computes progress % from the JSON (never stored/typed in), renders Blade matching the approved design. Skill-check toggles and the submission form hit small AJAX endpoints (Blade + Alpine.js, no new frontend framework) that patch the enrollment's `progress` JSON in place.

- [ ] **A3. AI-assisted review**
  Plan: `GeminiReviewService` — `Http::post()` to Gemini's free-tier API with the stage rubric + submission, parsing back `{score, verdict, feedback}` into `academy_ai_reviews`. Runs inline (sync) on submission per the existing `QUEUE_CONNECTION=sync` setup, written as a dispatchable Job so it's a one-line change to make async later. Reviewer queue is a custom Voyager controller (same pattern as `DeveloperPaymentController`) with Approve/Send-Back. Approve fires an event → advances `current_stage`, appends the badge, notifies via the existing Slack `dispatchSync` pattern.

- [ ] **A4. Certificate generation**
  Plan: `CertificateService` renders the approved certificate design (as a real Blade view with data binding) to PDF via the already-installed `barryvdh/laravel-dompdf` — no new package. Auto-triggers on final-stage approval (same event as A3), stores the PDF + a random verify code on the enrollment. Public `GET /certificate/verify/{code}`, no auth. Starting with just the one approved template — I'd hold off building a template-picker until there's an actual second template to pick, rather than over-building the config UI now.

- [ ] **A5. Badge on public profile**
  Plan: extend `DeveloperCategoryController::developerPortfolios()` to eager-load certifications and add them to the JSON response — that's the half I can fully deliver from this repo. Rendering them on webpenter.com is in the separate `webpenter-react` repo, so this item really ends with "ship the API, hand off the shape" rather than a fully closed loop from here alone.

- [ ] **A6. Enrollment & billing**
  Plan: **before designing new tables** — this codebase already has a `Courses`/`StudentFee`/`RykStudentFee`/`OnlineStudentFee` subsystem with monthly-fee commands (`Add*StudentFeesForCurrentMonth`) for a different, existing student-fee business. I want to check that first — reusing it (or extending it) likely beats building a second, parallel billing system from scratch. The 50%-service-charge trigger I'd start as a manual "mark as placed" admin action rather than trying to auto-detect "earning independently," since that's not reliably detectable from data we have.

- [ ] **A7. Marketing automation**
  Plan: a lightweight milestone log (enrollment/badge/certificate/placement events) feeding an admin page for Rumaisha — pick a milestone, get an auto-drafted caption (Gemini again), post. Before deciding the LinkedIn integration seam, I need to actually look at how `D:\wp\Linkedin-auto-post` is triggered today (cron? manual run? does it read a queue/file?) rather than guessing an interface to it.

- [ ] **A8. Role rollout**
  Plan: a "Promote to Developer" action that flips `role_id` to `DEVELOPER_ROLE_ID` and optionally creates a pre-filled `DeveloperCard` from the enrollment's track/skills — this is what actually connects the Academy to the existing hire-marketplace system.

## B. Internal HR/Ops automation

- [ ] **B1. Leave → Slack notification**
  Plan: new observer on `Leave::created()` (alongside the existing `saving` hook that computes `coo_required`) calling `SendToSlackChannelJob::dispatchSync()` — the identical pattern already used in `ContactMessageController`/`FineObserver`. Promote the currently-hardcoded webhook fallback in `Voyager/LeaveController.php:144` into a proper `leaves.slack_webhook_url` setting instead of leaving it hardcoded.

- [ ] **B2. Leave advance-notice rule**
  Plan: add `leave_type` (casual/sick) and `is_unpaid` columns. Extend `Leave::booted()`'s existing `saving` hook to also compute `is_unpaid` by comparing submission time to `start_date` against two new settings (`leaves.min_advance_notice_days`, `leaves.sick_leave_notice_cutoff_time`) — configurable, not hardcoded, so you can tune the thresholds without a code change. `SalaryCalculationService` deducts unpaid-leave days in full, separate from the existing quota-based deduction. Before building the "applies to partners too" part, I need to confirm `Leave` creation isn't already restricted to `is_development_team_member` users only.

- [ ] **B3. Month-end hours-shortfall deduction**
  Plan: move the logic that already half-exists in `CalculateMonthlyShortHoursAndFines` into a proper `SalaryCalculationService` method, fixed to use `Contract::monthly_salary`/`daily_salary` (not the stale unused fields it currently references), comparing required hours (9h weekday / 4.5h Saturday, from settings) against actual `Checkin` hours. Adds as its own bucket in `calculateMonthlySalary()`'s output, shown in `ProcessMonthlySalaries`. The existing instant late-checkin fine stays completely untouched, its own independent toggle.

- [ ] **B4. Partner/BD deduction routing**
  Plan: add a `User::hasSalaryContract()` helper (same active-contract-with-salary check `ProcessMonthlySalaries` already uses). In `PaymentCalculationService`, before finalizing a development-partner-type payment: if the partner has no salary contract, apply the same shortfall/unpaid-leave deduction against their commission directly; if they do have one (e.g. Ayub, Ali Hasan), skip — it's already been deducted via their salary in B3.

- [ ] **B5. Yearly performance evaluation**
  Plan: new `performance_evaluations` table (user, contract, evaluator, date, rating, notes, salary before/after, decision) with Voyager BREAD for staff to log outcomes. A daily scheduled command checks contract anniversaries / last-evaluation dates and Slack-reminds the CEO/COO when one's due — same notification pattern as B1.

- [ ] **B6. Developer self-service dashboard**
  Plan: one controller pulling together `SalaryCalculationService`'s output, this month's fines/leaves/hours, next evaluation date, and (if applicable) `UserPayment` rows — purely a read-only view over existing services, no new calculation logic.

- [ ] **B7. Positive recognition — on-time / good-conduct certifications**
  The flip side of B3/B2: a resource with no short-hours deduction, no unpaid leave, no fines for a period earns a certification, not just avoids a penalty. Plan: reuse the exact same certificate engine as A4 (dompdf, a verify-code page) rather than building a second one — the only new pieces are the criteria check (e.g. "zero fines + zero shortfall this quarter") and the staff-facing template/copy. Exact qualifying criteria and cadence (monthly? quarterly?) — **open, to discuss.**

- [ ] **B8. Employee of the Month**
  Automated announcement based on a combination of factors — **factors themselves are not decided yet, to be discussed** (candidates likely include attendance/hours record, fines-free streak, leave conduct, evaluation score, client/BD feedback — not assumed, just listed as candidates). Once factors are agreed: a monthly scheduled command scores eligible users, picks a winner (or flags a tie for manual pick), and announces via Slack — possibly feeding A7's marketing feed too if you want it publicized externally. Nothing else planned here until the scoring factors are settled.

## Team roster reference (Academy + HR roles)

| Person | Role |
|---|---|
| Zaars | Founder & Technical Lead — Super Admin |
| Rashid Bukhari | CEO — Program Owner |
| Ayub Khokhar | COO & BD — Ops Admin |
| Ahmad Raza | Sr. Full Stack Engineer — Technical Reviewer |
| Ali Hasan | BD + AI/ML — Technical Reviewer + BD |
| Mehtab | Instructor (Foundation + WordPress) |
| Rumaisha Asif | Marketing |
| Shan Balouch | HR |
