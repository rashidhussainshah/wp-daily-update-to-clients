# WebPenter Academy & HR/Ops — Master Roadmap

Central reference for two related but separate builds: the student-facing **Academy** (A-series) and internal **HR/Ops automation** (B-series). Nothing here is implemented yet unless marked `[x]`. We go **one item at a time** — see project memory `webpenter-academy-project` and `webpenter-hr-payroll-project` for full background.

Design reference (approved): https://claude.ai/code/artifact/08a89d27-4527-4eac-81a4-f6b1d20ec244

## Principles (apply to every item below)

- **Configurable, never hardcoded.** Any rate, threshold, day-count, or cutoff (fee %, notice-period days, required hours, bonus amounts) is a `setting()`-backed value — the pattern this codebase already uses (`leaves.max_monthly_leaves`, `checkin.late_checkin_fine_enabled`, etc.) — so it can change from the admin panel without a code deploy.
- **Reuse before building new.** Before adding a new table/service/pipeline, check whether an existing one in this codebase already does most of the job (e.g. A4's certificate engine gets reused by B7; A6 needs to check the existing `StudentFee`/`OnlineStudentFee` system first).
- **One item at a time.** We discuss and confirm an item's exact rules before building it — see `feedback_careful_incremental_approach` memory.
- **Live payroll caution.** Anything touching `SalaryCalculationService`, `PaymentCalculationService`, or `UserPayment` changes real people's pay — state the impact plainly before implementing, never silently.

## Quick reference

| ID | Short description |
|---|---|
| A1 | Academy database foundation (tracks, enrollments, AI reviews) |
| A2 | Student dashboard (progress, skills, submissions) |
| A3 | AI-assisted project review (Gemini + reviewer approve/override) |
| A4 | Auto-generated, verifiable completion certificates |
| A5 | Certifications shown on the public developer hire profile |
| A6 | Student enrollment & billing (registration fee, monthly fee, service charge) |
| A7 | Social marketing automation from Academy milestones |
| A8 | Promote a graduating student to a real Developer/DeveloperCard |
| B1 | Slack alert the moment a leave is submitted |
| B2 | Advance-notice leave rule, auto-unpaid on late notice |
| B3 | Month-end hours-shortfall salary deduction |
| B4 | Route attendance deductions correctly for partners vs. salaried BDs |
| B5 | Yearly performance evaluation with an audit log |
| B6 | Developer self-service dashboard (salary, fines, leave, hours, eval) |
| B7 | Positive-conduct certifications (good attendance, zero fines) |
| B8 | Automated "Employee of the Month" |
| B9 | BD incentive for landing direct clients (vs. Upwork/Fiverr) |

## A. WebPenter IT Academy (student-facing)

- [ ] **A1. Data foundation** — the tables and models everything else in this section builds on.
  Plan: 3 migrations — `academy_tracks` (name, designation, curriculum stored as JSON: stages → skills/projects), `developer_academy_enrollments` (user_id, track_id, current_stage, progress JSON, badges_earned JSON), `academy_ai_reviews` (enrollment_id, submission link, AI score/verdict/feedback, reviewer decision). Matching Eloquent models + relationships. Seed a `AcademyTracksSeeder` with the 7 real tracks — full stage/skill/project detail for the 3 already-drafted dev tracks, placeholder stage names for the other 4 until we flesh those out. Register Voyager BREAD for the new tables via the admin UI (matches how `developer_cards`/`expertises` were done — not seeded in code). "Student" = existing `User` with `role_id = STUDENT_ROLE_ID`, no new user table.

- [ ] **A2. Student dashboard** — what a logged-in student actually sees and interacts with day to day.
  Plan: new `/academy/*` route group behind the existing `web` guard + a role check (`role_id == STUDENT_ROLE_ID`). `AcademyController@dashboard` loads the user's enrollment, computes progress % from the JSON (never stored/typed in), renders Blade matching the approved design. Skill-check toggles and the submission form hit small AJAX endpoints (Blade + Alpine.js, no new frontend framework) that patch the enrollment's `progress` JSON in place.

- [ ] **A3. AI-assisted review** — cuts reviewer effort from reading full submissions to confirming an AI verdict.
  Plan: `GeminiReviewService` — `Http::post()` to Gemini's free-tier API with the stage rubric + submission, parsing back `{score, verdict, feedback}` into `academy_ai_reviews`. Runs inline (sync) on submission per the existing `QUEUE_CONNECTION=sync` setup, written as a dispatchable Job so it's a one-line change to make async later. Reviewer queue is a custom Voyager controller (same pattern as `DeveloperPaymentController`) with Approve/Send-Back. Approve fires an event → advances `current_stage`, appends the badge, notifies via the existing Slack `dispatchSync` pattern.

- [ ] **A4. Certificate generation** — the official, verifiable PDF a student gets on completion.
  Plan: `CertificateService` renders the approved certificate design (as a real Blade view with data binding) to PDF via the already-installed `barryvdh/laravel-dompdf` — no new package. Auto-triggers on final-stage approval (same event as A3), stores the PDF + a random verify code on the enrollment. Public `GET /certificate/verify/{code}`, no auth. Starting with just the one approved template — holding off on a template-picker until there's an actual second template to pick.

- [ ] **A5. Badge on public profile** — how a certification becomes visible to a hiring client.
  Plan: extend `DeveloperCategoryController::developerPortfolios()` to eager-load certifications and add them to the JSON response — the half fully deliverable from this repo. Rendering them lives in the separate `webpenter-react` repo, so this item ends at "ship the API, hand off the shape."

- [ ] **A6. Enrollment & billing** — registration fee, recurring monthly fee, and the placement service charge.
  Plan: **check first** whether the existing `Courses`/`StudentFee`/`RykStudentFee`/`OnlineStudentFee` subsystem (a different, already-running student-fee business in this codebase) can be reused/extended before building a second, parallel billing system. The ~50%-style service-charge trigger starts as a manual "mark as placed" admin action rather than an unreliable auto-detection of "earning independently."

- [ ] **A7. Marketing automation** — turning Academy milestones into social content with minimal manual work.
  Plan: a lightweight milestone log (enrollment/badge/certificate/placement events) feeding an admin page for Rumaisha — pick a milestone, get an auto-drafted caption (Gemini), post. Need to inspect how `D:\wp\Linkedin-auto-post` is actually triggered today before designing the integration seam.

- [ ] **A8. Role rollout** — turning a graduated student into a hireable resource.
  Plan: a "Promote to Developer" action that flips `role_id` to `DEVELOPER_ROLE_ID` and optionally creates a pre-filled `DeveloperCard` from the enrollment's track/skills.

## B. Internal HR/Ops automation

- [ ] **B1. Leave → Slack notification** — management finds out the moment a leave is submitted, automatically.
  Plan: new observer on `Leave::created()` (alongside the existing `saving` hook that computes `coo_required`) calling `SendToSlackChannelJob::dispatchSync()` — identical to the pattern in `ContactMessageController`/`FineObserver`. Promote the hardcoded webhook fallback in `Voyager/LeaveController.php:144` into a proper `leaves.slack_webhook_url` setting.

- [ ] **B2. Leave advance-notice rule** — late notice (especially sick leave) converts to unpaid automatically.
  Plan: add `leave_type` (casual/sick) and `is_unpaid` columns. Extend `Leave::booted()`'s existing `saving` hook to compute `is_unpaid` from submission time vs. `start_date`, against two new settings (`leaves.min_advance_notice_days`, `leaves.sick_leave_notice_cutoff_time`). `SalaryCalculationService` deducts unpaid days in full, separate from the existing quota-based deduction. Need to confirm `Leave` creation isn't already restricted to `is_development_team_member` users only before extending this to partners.

- [ ] **B3. Month-end hours-shortfall deduction** — a fairer alternative/complement to the instant late-checkin fine.
  Plan: move the logic that already half-exists in `CalculateMonthlyShortHoursAndFines` into a proper `SalaryCalculationService` method, fixed to use `Contract::monthly_salary`/`daily_salary` (not its current stale unused fields), comparing required hours (9h weekday / 4.5h Saturday, from settings) against actual `Checkin` hours. Its own bucket in `calculateMonthlySalary()`'s output. The existing instant late-checkin fine stays untouched, its own independent toggle.

- [ ] **B4. Partner/BD deduction routing** — attendance penalties hit the right pay component.
  Plan: add `User::hasSalaryContract()` (same active-contract-with-salary check `ProcessMonthlySalaries` already uses). In `PaymentCalculationService`, a partner with no salary contract gets shortfall/unpaid-leave deductions applied against their commission directly; one with a salary contract (e.g. Ayub, Ali Hasan) skips this — already covered via B3.

- [ ] **B5. Yearly performance evaluation** — replacing ad hoc reviews with a tracked, reminded process.
  Plan: new `performance_evaluations` table (user, contract, evaluator, date, rating, notes, salary before/after, decision) with Voyager BREAD for staff to log outcomes. A daily scheduled command checks contract anniversaries / last-evaluation dates and Slack-reminds the CEO/COO when one's due.

- [ ] **B6. Developer self-service dashboard** — one place a resource sees their own standing.
  Plan: one controller pulling together `SalaryCalculationService`'s output, this month's fines/leaves/hours, next evaluation date, and (if applicable) `UserPayment` rows — read-only, no new calculation logic.

- [ ] **B7. Positive recognition — on-time / good-conduct certifications** — the flip side of B2/B3: reward, not just penalize.
  Plan: reuse A4's certificate engine (dompdf + verify page) rather than building a second one — the new pieces are just the qualifying-criteria check (e.g. zero fines + zero shortfall this period) and staff-facing copy. Qualifying criteria and cadence (monthly/quarterly) — **open, to discuss.**

- [ ] **B8. Employee of the Month** — automated, factor-based recognition.
  Plan: a monthly scheduled command scores eligible users and announces via Slack (and possibly A7's marketing feed, if you want it public). **Scoring factors are intentionally not decided yet** — candidates to discuss: attendance/hours record, fines-free streak, leave conduct, evaluation score, client/BD feedback.

- [ ] **B9. BD incentive for direct clients** — motivating the shift away from Upwork/Fiverr's 10-20% platform fees.
  Plan: `PaymentCalculationService` already has a `client_source` concept (fiverr 20% fee, upwork 10%, "other"/direct 0% fee) — a direct client already saves that margin automatically. The open piece is a deliberate BD incentive on top (bonus %, flat bonus, or a better commission tier for direct-sourced income) — **mechanism and amount to discuss**, not decided yet.

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
