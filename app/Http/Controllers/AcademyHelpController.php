<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

/**
 * One shared, role-scoped documentation page instead of 8 separate ones -
 * every section below is gated by the SAME check the real screen uses, so a
 * viewer only ever sees instructions for jobs they actually hold (e.g. a
 * Reviewer never sees how Accountant payouts work). Administrator sees
 * every section, since they already have access to every underlying screen
 * anyway - there's no new information exposed by showing them the docs too.
 */
class AcademyHelpController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = optional($user)->role?->name;

        $sections = [
            'student' => $user->isItAcademyStudent(),
            'instructor' => $user->isAcademyInstructor() || isAdministrator(),
            'reviewer' => $user->isAcademyReviewer() || isAdministrator(),
            'accountant' => $user->isAcademyAccountant() || isAdministrator(),
            'marketing' => $user->isAcademyMarketing() || isAdministrator(),
            'printer' => $user->isAcademyPrinter() || isAdministrator(),
            'card_manager' => $user->isAcademyCardManager() || isAdministrator(),
            'hr' => in_array($role, ['HR', 'hr-document-manager'], true) || isAdministrator(),
        ];

        abort_unless(collect($sections)->contains(true), 403);

        return view('academy.help', compact('sections'));
    }
}
