<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Mail\DocumentIssuanceMail;
use App\Models\DocumentIssuance;
use App\Models\DocumentTemplate;
use App\Models\SmtpAccount;
use App\Models\User;
use App\Services\DocumentTemplateService;
use App\Utils\Traits\CampaignMailerTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * The issuance flow: HR picks a recipient (an existing User, or types a
 * name/email manually for someone not in the system at all) + a template,
 * the system pre-fills every token it can from real employee data, HR
 * fills in or overrides the rest, previews, then generates the PDF.
 * Access: HR role or Administrator, same gating style as
 * AcademyCourseCertificateController.
 */
class DocumentIssuanceController extends Controller
{
    use CampaignMailerTrait;

    protected function authorizeStaff(): void
    {
        $role = optional(Auth::user())->role?->name;

        abort_unless(Auth::check() && (in_array($role, ['HR', 'hr-document-manager'], true) || isAdministrator()), 403);
    }

    public function create(Request $request, DocumentTemplateService $documents)
    {
        $this->authorizeStaff();

        $employees = User::orderBy('name')->get(['id', 'name', 'email']);
        $templates = DocumentTemplate::where('is_active', true)->orderBy('category')->orderBy('name')->get();

        $mode = $request->input('mode') === 'manual' ? 'manual' : 'existing';
        $employee = ($mode === 'existing' && $request->filled('employee_id')) ? User::find($request->integer('employee_id')) : null;
        $template = $request->filled('template_id') ? DocumentTemplate::find($request->integer('template_id')) : null;

        $ready = $template && ($mode === 'manual' || $employee);
        $tokens = $ready ? $documents->autoFillTokens($employee, Auth::user()) : null;
        $tokenDictionary = DocumentTemplateService::tokenDictionary();

        return view('hr-documents.issue', compact('employees', 'templates', 'employee', 'template', 'tokens', 'tokenDictionary', 'mode'));
    }

    public function preview(Request $request, DocumentTemplateService $documents)
    {
        $this->authorizeStaff();

        [$template, , $tokens] = $this->resolveRequest($request, $documents);

        $pdf = $documents->renderPdf($template, $tokens);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',
        ]);
    }

    public function store(Request $request, DocumentTemplateService $documents)
    {
        $this->authorizeStaff();

        [$template, $employee, $tokens] = $this->resolveRequest($request, $documents);

        $issuance = $documents->issue($template, $employee, $tokens, Auth::user());

        return redirect()->route('hr-documents.issuances.index')
            ->with('status', "{$template->name} issued to {$issuance->recipient_name} ({$issuance->verify_code}).")
            ->with('issued_id', $issuance->id);
    }

    public function index(Request $request)
    {
        $this->authorizeStaff();

        $issuances = DocumentIssuance::with(['template', 'user', 'issuedBy'])
            ->when($request->filled('user_id'), fn ($q) => $q->where('user_id', $request->integer('user_id')))
            ->latest('issued_at')
            ->paginate(20)
            ->withQueryString();

        $employees = User::orderBy('name')->get(['id', 'name']);
        $filterUserId = $request->integer('user_id') ?: null;

        return view('hr-documents.history', compact('issuances', 'employees', 'filterUserId'));
    }

    /**
     * Emails the already-generated PDF to any address (defaults to the
     * recipient's own email, editable) - reuses the same SMTP-account
     * infra as the marketing campaigns feature (CampaignMailerTrait)
     * instead of configuring a mailer from scratch.
     */
    public function sendEmail(Request $request, DocumentIssuance $issuance)
    {
        $this->authorizeStaff();

        $data = $request->validate(['email' => 'required|email']);

        $smtp = SmtpAccount::where('is_active', true)->orderBy('id')->first();

        try {
            $this->getMailer($smtp)
                ->to($data['email'])
                ->send(new DocumentIssuanceMail($issuance));

            return back()->with('status', "Emailed \"{$issuance->template->name}\" to {$data['email']}.");
        } catch (\Throwable $e) {
            Log::error('HR document email failed', ['issuance_id' => $issuance->id, 'error' => $e->getMessage()]);

            return back()->with('error', 'Could not send email: ' . $e->getMessage());
        }
    }

    /** @return array{0: DocumentTemplate, 1: ?User, 2: array} */
    protected function resolveRequest(Request $request, DocumentTemplateService $documents): array
    {
        $data = $request->validate([
            'mode' => 'required|in:existing,manual',
            'template_id' => 'required|exists:document_templates,id',
            'employee_id' => 'required_if:mode,existing|nullable|exists:users,id',
            'tokens' => 'array',
            'tokens.*' => 'nullable|string',
            'tokens.employee_name' => 'required_if:mode,manual|nullable|string',
        ]);

        $template = DocumentTemplate::findOrFail($data['template_id']);
        $employee = $data['mode'] === 'existing' ? User::findOrFail($data['employee_id']) : null;

        $tokens = array_merge(
            $documents->autoFillTokens($employee, Auth::user()),
            array_filter($data['tokens'] ?? [], fn ($v) => $v !== null && $v !== '')
        );

        return [$template, $employee, $tokens];
    }
}
