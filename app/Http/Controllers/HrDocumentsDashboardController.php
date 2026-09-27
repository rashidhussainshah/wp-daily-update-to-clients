<?php

namespace App\Http\Controllers;

use App\Models\DocumentIssuance;
use App\Models\DocumentTemplate;
use Illuminate\Support\Facades\Auth;

/**
 * Landing page for the HR document builder - same "own clean dashboard per
 * role" pattern as the Academy instructor/accountant/marketing dashboards,
 * so HR (or the dedicated hr-document-manager role) has one easy place to
 * land instead of hunting through Voyager's sidebar.
 */
class HrDocumentsDashboardController extends Controller
{
    protected function authorizeStaff(): void
    {
        $role = optional(Auth::user())->role?->name;

        abort_unless(Auth::check() && (in_array($role, ['HR', 'hr-document-manager'], true) || isAdministrator()), 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $activeTemplates = DocumentTemplate::where('is_active', true)->count();
        $issuedThisMonth = DocumentIssuance::whereBetween('issued_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $issuedTotal = DocumentIssuance::count();

        $recent = DocumentIssuance::with(['template', 'issuedBy'])->latest('issued_at')->limit(6)->get();

        return view('hr-documents.dashboard', compact('activeTemplates', 'issuedThisMonth', 'issuedTotal', 'recent'));
    }
}
