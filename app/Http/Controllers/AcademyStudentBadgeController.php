<?php

namespace App\Http\Controllers;

use App\Models\DeveloperAcademyEnrollment;

/**
 * Public, no-login page a scanned ID-card QR code lands on - deliberately
 * NOT the same as A14's parent view (which shows check-in/attendance
 * history, appropriate for a parent but not for a stranger scanning a
 * badge at an event). Shows only what's safe to be public: photo, name,
 * track, and level badge.
 */
class AcademyStudentBadgeController extends Controller
{
    public function show(string $token)
    {
        $enrollment = DeveloperAcademyEnrollment::where('parent_view_token', $token)
            ->with('user', 'track')
            ->firstOrFail();

        return view('academy.student-badge', [
            'enrollment' => $enrollment,
            'level' => $enrollment->levelTier(),
        ]);
    }
}
