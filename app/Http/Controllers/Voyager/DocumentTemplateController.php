<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\DocumentTemplate;
use App\Models\User;
use App\Services\DocumentTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * The "custom builder" - HR authors/edits letter templates here (plain
 * textarea body + token palette, no WYSIWYG dependency) instead of a fixed
 * set of hardcoded Blade files. A new letter type is just a new row here.
 * Access: HR role or Administrator, same gating style as
 * AcademyCourseCertificateController.
 */
class DocumentTemplateController extends Controller
{
    protected function authorizeStaff(): void
    {
        $role = optional(Auth::user())->role?->name;

        abort_unless(Auth::check() && (in_array($role, ['HR', 'hr-document-manager'], true) || isAdministrator()), 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $templates = DocumentTemplate::withCount('issuances')->orderBy('category')->orderBy('name')->get();

        return view('hr-documents.templates', compact('templates'));
    }

    public function create()
    {
        $this->authorizeStaff();

        $template = new DocumentTemplate(['design' => DocumentTemplate::DESIGN_CLASSIC, 'is_active' => true]);
        $designs = DocumentTemplate::designs();
        $tokens = DocumentTemplateService::tokenDictionary();
        $employees = User::orderBy('name')->get(['id', 'name']);

        return view('hr-documents.template-form', compact('template', 'designs', 'tokens', 'employees'));
    }

    public function store(Request $request)
    {
        $this->authorizeStaff();

        $data = $this->validated($request);
        $data['created_by'] = Auth::id();

        $template = DocumentTemplate::create($data);

        return redirect()->route('hr-documents.templates.index')->with('status', "Template \"{$template->name}\" saved.");
    }

    public function edit(DocumentTemplate $template)
    {
        $this->authorizeStaff();

        $designs = DocumentTemplate::designs();
        $tokens = DocumentTemplateService::tokenDictionary();
        $employees = User::orderBy('name')->get(['id', 'name']);

        return view('hr-documents.template-form', compact('template', 'designs', 'tokens', 'employees'));
    }

    public function update(Request $request, DocumentTemplate $template)
    {
        $this->authorizeStaff();

        $template->update($this->validated($request));

        return redirect()->route('hr-documents.templates.index')->with('status', "Template \"{$template->name}\" updated.");
    }

    public function destroy(DocumentTemplate $template)
    {
        $this->authorizeStaff();

        $name = $template->name;
        $template->delete();

        return redirect()->route('hr-documents.templates.index')->with('status', "Template \"{$name}\" deleted.");
    }

    /**
     * Renders the CURRENT form contents (not the last saved row) as a real
     * PDF inline, using either a real employee's data or sample placeholder
     * text - so HR can see unsaved edits before hitting Save. Builds a
     * throwaway, unpersisted DocumentTemplate rather than requiring the
     * template to already exist, so this works from both the create and
     * edit forms.
     */
    public function preview(Request $request, DocumentTemplateService $documents)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'design' => 'required|string|in:' . implode(',', array_keys(DocumentTemplate::designs())),
            'body' => 'required|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $template = new DocumentTemplate(['design' => $data['design'], 'body' => $data['body']]);

        if (!empty($data['user_id']) && ($employee = User::find($data['user_id']))) {
            $sample = $documents->autoFillTokens($employee, Auth::user());
        } else {
            $sample = collect(DocumentTemplateService::tokenDictionary())->keys()
                ->mapWithKeys(fn ($key) => [$key => '{{' . $key . '}}'])->all();
            $sample = array_merge($sample, [
                'employee_name' => 'Jane Sample Employee',
                'employee_email' => 'jane.sample@webpenter.test',
                'designation' => 'Software Engineer',
                'department' => 'Development',
                'join_date' => 'January 5, 2023',
                'last_working_day' => now()->format('F j, Y'),
                'today' => now()->format('F j, Y'),
                'company_name' => setting('hr.document_company_name', 'WebPenter'),
                'reference_number' => 'PREVIEW-0000',
                'purpose' => 'for visa application',
                'issued_by_name' => Auth::user()->name,
                'issued_by_title' => 'HR',
            ]);
        }

        $pdf = $documents->renderPdf($template, $sample);

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="preview.pdf"',
        ]);
    }

    protected function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'design' => 'required|string|in:' . implode(',', array_keys(DocumentTemplate::designs())),
            'body' => 'required|string',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
