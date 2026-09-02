# WebPenter Academy & HR/Ops — Roadmap

Working backlog for two related but separate builds. Nothing here is implemented yet unless marked `[x]`. We're going one item at a time — see project memory `webpenter-academy-project` and `webpenter-hr-payroll-project` for full background/decisions on each.

Design reference (approved): https://claude.ai/code/artifact/08a89d27-4527-4eac-81a4-f6b1d20ec244

## A. WebPenter IT Academy (student-facing)

- [ ] **A1. Data foundation** — `academy_tracks`, `developer_academy_enrollments`, `academy_ai_reviews` tables. Seed real tracks: ML/AI Engineer, Full Stack AI Developer, AI Application Developer, WordPress Development & Customization, Full Stack Laravel/PHP Developer, Digital Marketing (Meta & Google Ads), SEO & AI Content Tools.
- [ ] **A2. Student dashboard** — real Webpenter login + role (student), stepper/progress, skills checklist, project submission form.
- [ ] **A3. AI-assisted review** — Gemini pre-review on submission, reviewer queue (approve/override), auto stage-advance + badge on approval.
- [ ] **A4. Certificate generation** — configurable (student/track/template/signers), PDF via dompdf (already installed), public no-login verify page.
- [ ] **A5. Badge/certificate on public profile** — extend existing `/developer-portfolios/{id}` API with certifications; render on webpenter-react public developer page.
- [ ] **A6. Enrollment & billing** — registration fee toggle, monthly fee tracking, the ~50% service-charge trigger once a student starts earning independently. *(Open question: exact detection mechanism for "started earning independently.")*
- [ ] **A7. Marketing automation** — milestone feed (enrollment/badge/certificate/placement) → Rumaisha's post composer (simple or carousel) → LinkedIn auto-post first (reuse the existing D:\wp\Linkedin-auto-post tool), then Instagram/Facebook. *(Open question: how much is auto-posted vs Rumaisha-reviewed first.)*
- [ ] **A8. Role rollout** — flip `role_id` from Student → Developer when a student graduates/gets engaged (mechanism already exists in `User` model, just needs the workflow trigger).

## B. Internal HR/Ops automation

- [ ] **B1. Leave → Slack notification** — auto-post to Slack when a leave is submitted, so management/colleagues are informed immediately (no separate Microsoft Teams integration exists or was confirmed needed).
- [ ] **B2. Leave advance-notice rule** — leaves need advance notice; sick leave needs notice before a cutoff; late notice auto-converts the leave to **unpaid** (full-day deduction, not just quota-exceeded). Applies to partners too. *(Open question: exact day/time thresholds — will be configurable settings, not hardcoded.)*
- [ ] **B3. Month-end hours-shortfall deduction** — new deduction bucket in `SalaryCalculationService`/`ProcessMonthlySalaries`, built from existing `Checkin` data, replacing the disconnected `CalculateMonthlyShortHoursAndFines` prototype. Runs **independently** of the existing instant late-checkin fine toggle (`checkin.late_checkin_fine_enabled`) — both can be on at once.
- [ ] **B4. Partner/BD deduction routing** — pure revenue-share partners (development_partner share type, no salary contract) get attendance deductions taken from their commission via `PaymentCalculationService`. Anyone with an active salary `Contract` (e.g. Ayub, Ali Hasan as BDs) gets deductions routed through the normal salary calc instead.
- [ ] **B5. Yearly performance evaluation** — new model + persisted audit log (what/when/who decided), tied to `Contract` and salary increments. Automated reminder (Slack) when someone's annual review is due — no one has to remember.
- [ ] **B6. Developer self-service dashboard** — salary breakdown, fines, leave balance, check-in/checkout hours vs required, next evaluation due date, and (for partners) pending/approved `UserPayment` rows. Read-only, built on existing services.

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
