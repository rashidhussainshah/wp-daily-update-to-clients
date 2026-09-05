<?php

namespace App\Http\Controllers\Voyager;

use App\Http\Controllers\Controller;
use App\Models\AcademyCourse;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * A9 - HR issues a lighter, single-course certificate on demand, reusing
 * A4's CertificateService. Access: HR role or Administrator.
 */
class AcademyCourseCertificateController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (optional(Auth::user()->role)->name === 'HR' || isAdministrator()), 403);
    }

    public function create()
    {
        $this->authorizeStaff();

        $courses = AcademyCourse::all();
        $students = User::onlyItAcademyStudent()->get();

        return view('academy.course-certificate', compact('courses', 'students'));
    }

    public function store(Request $request, CertificateService $certificates)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:academy_courses,id',
            'recipient_name' => 'required|string|max:255',
        ]);

        $user = User::findOrFail($data['user_id']);
        $course = AcademyCourse::findOrFail($data['course_id']);

        $certificate = $certificates->issueCourseCertificate($user, $course->id, $course->name, $data['recipient_name'], Auth::user());

        return back()->with('status', "Certificate issued: {$certificate->verify_code}")->with('download_url', asset('storage/' . $certificate->pdf_path));
    }
}
