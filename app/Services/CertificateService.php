<?php

namespace App\Services;

use App\Models\AcademyCertificate;
use App\Models\DeveloperAcademyEnrollment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\SlackAlerts\Jobs\SendToSlackChannelJob;
use TCG\Voyager\Models\Setting;

/**
 * Renders and stores certificates (A4 track certificates, A9 course
 * certificates - both go through this one service). Each certificate picks
 * one of a small set of visual designs (academy.certificates.{design}) -
 * same data, different look, so Marketing has real options to choose from
 * per recipient.
 */
class CertificateService
{
    public function issueTrackCertificate(DeveloperAcademyEnrollment $enrollment, ?string $recipientName = null, string $design = AcademyCertificate::DESIGN_CLASSIC): AcademyCertificate
    {
        $certificate = AcademyCertificate::create([
            'user_id' => $enrollment->user_id,
            'enrollment_id' => $enrollment->id,
            'type' => AcademyCertificate::TYPE_TRACK,
            'design' => $design,
            'title' => $enrollment->track->name,
            'recipient_name' => $recipientName ?: $enrollment->user->name,
        ]);

        $this->render($certificate);
        $this->announceToSlack($certificate);

        return $certificate;
    }

    public function issueCourseCertificate(User $user, int $courseId, string $courseTitle, string $recipientName, ?User $issuedBy = null, string $design = AcademyCertificate::DESIGN_CLASSIC, ?string $achievementNote = null, ?Carbon $issuedAt = null, bool $isStaffCertificate = false): AcademyCertificate
    {
        $certificate = AcademyCertificate::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'type' => AcademyCertificate::TYPE_COURSE,
            'design' => $design,
            'title' => $courseTitle,
            'achievement_note' => $achievementNote,
            // A skill certificate for WebPenter's own (already expert) team
            // drops the "IT Academy" branding and just says "WebPenter" -
            // student track/course certificates are unaffected.
            'is_staff_certificate' => $isStaffCertificate,
            'recipient_name' => $recipientName,
            'issued_by' => $issuedBy?->id,
            // Backdating a certificate (e.g. seeding realistic-looking
            // history) needs its verify code's year to match, or "Issued
            // Jan 2025" next to a 2026-stamped credential ID looks broken.
            'issued_at' => $issuedAt,
            'verify_code' => $issuedAt ? ('WP-ACAD-' . $issuedAt->format('Y') . '-' . strtoupper(Str::random(8))) : null,
        ]);

        $this->render($certificate);
        $this->announceToSlack($certificate);

        return $certificate;
    }

    /**
     * Team-visible motivation, not just a private record - posted once, on
     * actual issuance, not on every re-render (e.g. Marketing switching a
     * design later shouldn't re-announce it). Uses the same Academy Slack
     * webhook check-in/check-out already posts to.
     */
    protected function announceToSlack(AcademyCertificate $certificate): void
    {
        $webhookUrl = setting('academy.slack_webhook_url');

        if (!$webhookUrl) {
            return;
        }

        $companyLabel = $certificate->is_staff_certificate ? 'WebPenter' : 'WebPenter IT Academy';
        $blocks = [
            [
                'type' => 'section',
                'text' => [
                    'type' => 'mrkdwn',
                    'text' => "🎓 *{$certificate->recipient_name}* just earned the *{$certificate->title}* certificate from {$companyLabel}! :tada:",
                ],
            ],
        ];

        try {
            SendToSlackChannelJob::dispatchSync($webhookUrl, null, $blocks);
        } catch (\Throwable $e) {
            Log::error('Academy certificate Slack announcement failed', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Re-render an existing certificate's PDF - used on first issue, when
     * HR adjusts the recipient name (A9), or when Marketing switches its
     * design.
     */
    public function render(AcademyCertificate $certificate): void
    {
        $pdfOutput = $this->renderPdfOutput($certificate);

        $path = "academy/certificates/{$certificate->verify_code}.pdf";
        Storage::disk('public')->put($path, $pdfOutput);

        $certificate->update(['pdf_path' => $path]);
    }

    /**
     * Same render as above, but returns the raw PDF bytes without touching
     * the certificate's stored pdf_path - used for the on-demand "download
     * without signature" variant (for social media) alongside the normal
     * stored file (which keeps whatever the Certification settings say,
     * for a physical handover). $hideSignatures forces BOTH signatures off
     * regardless of settings; leave null to just follow the per-signatory
     * Voyager settings.
     */
    public function renderPdfOutput(AcademyCertificate $certificate, ?bool $hideSignatures = null): string
    {
        $design = array_key_exists($certificate->design, AcademyCertificate::designs())
            ? $certificate->design
            : AcademyCertificate::DESIGN_CLASSIC;

        $showSig1 = !$hideSignatures && $this->settingEnabled('academy.certificate_signatory_1_enabled');
        $showSig2 = !$hideSignatures && $this->settingEnabled('academy.certificate_signatory_2_enabled');

        $html = view("academy.certificates.{$design}", [
            'recipientName' => $certificate->recipient_name,
            'title' => $certificate->title,
            'achievementNote' => $certificate->achievement_note,
            // Staff skill certificates say just "WebPenter" - the "IT
            // Academy" branding is for student track/course certificates.
            'companyLabel' => $certificate->is_staff_certificate ? 'Webpenter' : 'Webpenter IT Academy',
            'type' => $certificate->type,
            'issuedAt' => $certificate->issued_at ?? now(),
            'verifyCode' => $certificate->verify_code,
            'verifyUrl' => route('academy.certificate.verify', $certificate->verify_code),
            // Editable in Voyager (Settings -> Certification) rather than
            // hardcoded, same as every other admin-controllable value.
            // Null name suppresses that signature block entirely (see the
            // @if($signatoryXName || $signatoryXImageUrl) guards in each
            // design template). An uploaded image wins over the typed name
            // when both are present - same convention as the HR document
            // builder's signatories.
            'signatory1Name' => $showSig1 ? setting('academy.certificate_signatory_1_name', 'Rashid Bukhari') : null,
            'signatory1Title' => setting('academy.certificate_signatory_1_title', 'Chief Executive Officer'),
            'signatory1ImageUrl' => $showSig1 ? $this->signatureImageUrl('academy.certificate_signatory_1_image') : null,
            'signatory2Name' => $showSig2 ? setting('academy.certificate_signatory_2_name', 'Zahid Khurshid') : null,
            'signatory2Title' => setting('academy.certificate_signatory_2_title', 'Founder'),
            'signatory2ImageUrl' => $showSig2 ? $this->signatureImageUrl('academy.certificate_signatory_2_image') : null,
            // Only meaningful for a track certificate (tied to an
            // enrollment with an assigned instructor) - a course
            // certificate has no instructor of its own, just whoever in
            // HR issued it.
            'instructorName' => $certificate->enrollment?->instructor?->name,
        ])->render();

        return Pdf::loadHTML($html)->setPaper('a4', 'landscape')->output();
    }

    /**
     * Voyager's setting() helper does `$value ?: $default`, which silently
     * falls back to the default whenever the stored value is the string
     * "0" - PHP treats "0" as falsy, so a deliberately-disabled checkbox
     * setting reads back as if it were never set. Reading the Setting
     * model directly sidesteps that bug entirely. Missing row = enabled
     * (matches the seeder's own default of "1").
     */
    protected function settingEnabled(string $key): bool
    {
        $value = Setting::where('key', $key)->value('value');

        return $value === null || $value === '1';
    }

    /** Public storage URL for an uploaded signature image setting, or null if none is uploaded. */
    protected function signatureImageUrl(string $key): ?string
    {
        $path = Setting::where('key', $key)->value('value');

        return $path ? Storage::disk('public')->url($path) : null;
    }
}
