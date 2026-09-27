<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyCertificate;
use Illuminate\Support\Facades\Auth;

/**
 * Read-only certificate gallery for whoever needs to share issued
 * certificates externally - the Academy Marketing capability (additive -
 * academy_staff_roles, same as instructor/reviewer/accountant - can be held
 * alongside an existing primary role), or Administrator only. Sharing
 * certificates externally is specifically Marketing's job, not HR's - HR
 * can still issue course certificates via A9, just not access this
 * screen. Nothing here can be created/edited; certificates are only ever
 * issued via A4 (auto, on track completion) or A9 (HR, on demand) - this
 * screen just surfaces what already exists.
 */
class AcademyMarketingCertificatesController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyMarketing() || isAdministrator()), 403);
    }

    public function index()
    {
        $this->authorizeStaff();

        $certificates = AcademyCertificate::with('user')
            ->latest('issued_at')
            ->paginate(20);

        return view('academy.marketing-certificates', compact('certificates'));
    }
}
