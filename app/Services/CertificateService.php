<?php

namespace App\Services;

use App\Models\AcademyCertificate;
use App\Models\DeveloperAcademyEnrollment;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 * Renders and stores certificates (A4 track certificates, A9 course
 * certificates - both go through this one service and one Blade template).
 */
class CertificateService
{
    public function issueTrackCertificate(DeveloperAcademyEnrollment $enrollment, ?string $recipientName = null): AcademyCertificate
    {
        $certificate = AcademyCertificate::create([
            'user_id' => $enrollment->user_id,
            'enrollment_id' => $enrollment->id,
            'type' => AcademyCertificate::TYPE_TRACK,
            'title' => $enrollment->track->name,
            'recipient_name' => $recipientName ?: $enrollment->user->name,
        ]);

        $this->render($certificate);

        return $certificate;
    }

    public function issueCourseCertificate(User $user, int $courseId, string $courseTitle, string $recipientName, ?User $issuedBy = null): AcademyCertificate
    {
        $certificate = AcademyCertificate::create([
            'user_id' => $user->id,
            'course_id' => $courseId,
            'type' => AcademyCertificate::TYPE_COURSE,
            'title' => $courseTitle,
            'recipient_name' => $recipientName,
            'issued_by' => $issuedBy?->id,
        ]);

        $this->render($certificate);

        return $certificate;
    }

    /**
     * Re-render an existing certificate's PDF - used both on first issue and
     * whenever HR adjusts the recipient name (A9).
     */
    public function render(AcademyCertificate $certificate): void
    {
        $html = view('academy.certificate', [
            'recipientName' => $certificate->recipient_name,
            'title' => $certificate->title,
            'type' => $certificate->type,
            'issuedAt' => $certificate->issued_at ?? now(),
            'verifyCode' => $certificate->verify_code,
            'verifyUrl' => route('academy.certificate.verify', $certificate->verify_code),
        ])->render();

        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'landscape');

        $path = "academy/certificates/{$certificate->verify_code}.pdf";
        Storage::disk('public')->put($path, $pdf->output());

        $certificate->update(['pdf_path' => $path]);
    }
}
