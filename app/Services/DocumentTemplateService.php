<?php

namespace App\Services;

use App\Models\DocumentIssuance;
use App\Models\DocumentTemplate;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

/**
 * Renders and stores HR letters (experience/relieving/internship/etc.) from
 * an HR-authored DocumentTemplate. Mirrors CertificateService's shape: pick
 * a design wrapper by key, pull signatory names/images from Voyager
 * settings rather than hardcoding them, store the PDF, issue a verify code.
 *
 * Token substitution is deliberately dumb (regex swap of {{token}}) - the
 * only "smart" part is which tokens get auto-filled from the employee's
 * real data vs. left for HR to type in. Signatories are NOT tokens - they
 * are company-wide settings rendered straight into the design wrapper's
 * footer, same as CertificateService, so an image-based signature can't
 * accidentally be "overridden" by a stray text field.
 */
class DocumentTemplateService
{
    /** @return array<string, string> token key => human description, for the builder's token palette */
    public static function tokenDictionary(): array
    {
        return [
            'employee_name' => "Recipient's full name",
            'employee_email' => "Recipient's email address",
            'designation' => "Employee's job title / designation",
            'department' => "Employee's department",
            'join_date' => 'Date the employee joined',
            'last_working_day' => "Employee's last working day (relieving/internship letters)",
            'today' => "Today's date (date of issue)",
            'company_name' => 'Company name',
            'reference_number' => 'Auto-generated reference/issuance number',
            'purpose' => 'Purpose / reason line (e.g. "for visa application")',
            'issued_by_name' => 'Name of the staff member generating this letter',
            'issued_by_title' => 'Job title of the staff member generating this letter',
        ];
    }

    /**
     * Best-effort starting values pulled from real data. Every one of these
     * stays editable on the issuance form - Contract's free-text `title`
     * field isn't a reliable designation source and dates are sometimes
     * missing entirely on older contracts, so this is a starting guess, not
     * an authoritative fill. $employee is null in "enter manually" mode
     * (recipient isn't a User in the system at all) - every employee_*
     * field is then simply left blank for HR to type in.
     */
    public function autoFillTokens(?User $employee, ?User $issuedBy = null): array
    {
        $contract = $employee?->contract;

        return [
            'employee_name' => $employee?->name ?? '',
            'employee_email' => $employee?->email ?? '',
            'designation' => $contract?->title ?? '',
            'department' => '',
            'join_date' => $this->formatContractDate($contract?->start_date),
            'last_working_day' => $this->formatContractDate($contract?->end_date),
            'today' => now()->format('F j, Y'),
            'company_name' => setting('hr.document_company_name', 'WebPenter'),
            'reference_number' => '',
            'purpose' => '',
            'issued_by_name' => $issuedBy?->name ?? '',
            'issued_by_title' => optional($issuedBy?->role)->display_name ?: (optional($issuedBy?->role)->name ?? ''),
        ];
    }

    /**
     * Contract's start_date/end_date come back as plain strings, not Carbon
     * instances, on this app's Laravel version (its $dates property isn't
     * actually honored) - parse defensively rather than assume a Carbon
     * object, and swallow garbage/blank values instead of a 500.
     */
    protected function formatContractDate($value): string
    {
        if (empty($value)) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('F j, Y');
        } catch (\Throwable $e) {
            return '';
        }
    }

    /**
     * Both signature slots, each independently either typed text (name +
     * title) or an uploaded image (with the title still printed under it)
     * - configured via Voyager Settings (group "HR Documents"), same place
     * HR already manages the academy certificate signatories. An image
     * setting wins over the typed name when both are present.
     */
    public function signatories(): array
    {
        $slots = [];

        foreach ([1, 2] as $n) {
            $image = setting("hr.document_signatory_{$n}_image");
            $slots[$n] = [
                'name' => setting("hr.document_signatory_{$n}_name", $n === 1 ? 'Rashid Bukhari' : 'Zahid Khurshid'),
                'title' => setting("hr.document_signatory_{$n}_title", $n === 1 ? 'Chief Executive Officer' : 'Founder'),
                'image_url' => $image ? asset('storage/' . $image) : null,
            ];
        }

        return $slots;
    }

    /** Substitutes {{token}} placeholders anywhere in $html with resolved values; unknown tokens are left as-is. */
    public function resolve(string $html, array $tokens): string
    {
        return preg_replace_callback('/\{\{\s*([a-zA-Z0-9_]+)\s*\}\}/', function ($m) use ($tokens) {
            return array_key_exists($m[1], $tokens) ? e((string) $tokens[$m[1]]) : $m[0];
        }, $html);
    }

    /** Wraps a resolved body in the template's chosen design and renders the PDF binary - used for both preview and real issuance. */
    public function renderPdf(DocumentTemplate $template, array $tokens, ?string $verifyCode = null): string
    {
        $design = array_key_exists($template->design, DocumentTemplate::designs())
            ? $template->design
            : DocumentTemplate::DESIGN_CLASSIC;

        $bodyHtml = $this->resolve($template->body, $tokens);

        $html = view("hr.documents.designs.{$design}", [
            'bodyHtml' => $bodyHtml,
            'template' => $template,
            'tokens' => $tokens,
            'signatories' => $this->signatories(),
            'verifyCode' => $verifyCode,
            'verifyUrl' => $verifyCode ? route('hr-documents.verify', $verifyCode) : null,
        ])->render();

        return Pdf::loadHTML($html)->setPaper('a4', 'portrait')->output();
    }

    /**
     * Issues a real, saved letter: merges auto-filled tokens with HR's
     * overrides, snapshots the resolved values (so the letter stays
     * accurate even if the employee's data changes later), renders +
     * stores the PDF, and records who got what, when. $employee is null
     * for a manually-entered recipient - recipient_name/email are copied
     * from the resolved tokens either way, so history never depends on a
     * live User record existing.
     */
    public function issue(DocumentTemplate $template, ?User $employee, array $overrides, ?User $issuedBy): DocumentIssuance
    {
        $tokens = array_merge($this->autoFillTokens($employee, $issuedBy), $overrides);

        $issuance = DocumentIssuance::create([
            'template_id' => $template->id,
            'user_id' => $employee?->id,
            'recipient_name' => $tokens['employee_name'] ?: 'Unnamed recipient',
            'recipient_email' => $tokens['employee_email'] ?: null,
            'issued_by' => $issuedBy?->id,
            'data' => $tokens,
        ]);

        // reference_number defaults to the verify code, which only exists
        // once the row is created - fill it in and re-snapshot before render.
        if (empty($tokens['reference_number'])) {
            $tokens['reference_number'] = $issuance->verify_code;
            $issuance->data = $tokens;
        }

        $pdf = $this->renderPdf($template, $tokens, $issuance->verify_code);
        $path = "hr/documents/{$issuance->verify_code}.pdf";
        Storage::disk('public')->put($path, $pdf);
        $issuance->pdf_path = $path;
        $issuance->save();

        return $issuance;
    }
}
