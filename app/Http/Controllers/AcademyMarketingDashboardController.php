<?php

namespace App\Http\Controllers;

use App\Models\AcademyCertificate;
use App\Models\User;
use App\Services\AcademySocialPostService;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * The Marketing day-to-day screen - a real gallery (photo, level badge,
 * download, ready-to-paste share text) instead of the plain Voyager table,
 * same shift as the accountant/instructor/reviewer dashboards. Still
 * entirely read-only - certificates are only ever issued via A4 (auto) or
 * A9 (HR, on demand).
 *
 * Access: Academy Marketing capability or Administrator only - sharing
 * certificates externally is specifically Marketing's job, not HR's (HR
 * can still issue course certificates via A9, just not access this
 * sharing screen).
 */
class AcademyMarketingDashboardController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::check() && (Auth::user()->isAcademyMarketing() || isAdministrator()), 403);
    }

    public function index(Request $request)
    {
        $this->authorizeStaff();

        $design = $request->query('design');
        $resourceId = $request->query('resource');
        $sort = $request->query('sort', 'recent');

        $certificates = AcademyCertificate::with('user', 'enrollment.track', 'course')
            ->when($design, fn ($q) => $q->where('design', $design))
            ->when($resourceId, fn ($q) => $q->where('user_id', $resourceId))
            ->whereNull('posted_at')
            ->orderBy('issued_at', $sort === 'oldest' ? 'asc' : 'desc')
            ->paginate(12, ['*'], 'page')
            ->withQueryString();

        $postedCertificates = AcademyCertificate::with('user', 'enrollment.track', 'course', 'postedBy')
            ->when($design, fn ($q) => $q->where('design', $design))
            ->when($resourceId, fn ($q) => $q->where('user_id', $resourceId))
            ->whereNotNull('posted_at')
            ->latest('posted_at')
            ->paginate(12, ['*'], 'posted_page')
            ->withQueryString();

        $designs = AcademyCertificate::designs();

        // Only people who actually have a certificate show up in the
        // filter - no point offering hundreds of students/staff who have
        // none.
        $resources = User::whereIn('id', AcademyCertificate::query()->distinct()->pluck('user_id'))
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('academy.marketing-dashboard', compact('certificates', 'postedCertificates', 'designs', 'design', 'resources', 'resourceId', 'sort'));
    }

    /**
     * Marks a certificate as posted once Marketing has actually shared it -
     * it then drops out of the "to post" gallery into the Already Posted
     * list below, so the same certificate doesn't get posted twice.
     */
    public function markPosted(AcademyCertificate $certificate)
    {
        $this->authorizeStaff();

        $certificate->update(['posted_at' => now(), 'posted_by' => Auth::id()]);

        return back()->with('status', "Marked \"{$certificate->title}\" as posted.");
    }

    /**
     * Re-renders the certificate on the fly with the CEO/Founder signature
     * forced on or off, without touching the originally stored PDF -
     * "without" for posting on social media, "with" for a physical
     * handover, independent of whatever the Certification settings default
     * to. Streamed straight to the browser, never saved to disk.
     */
    public function downloadPdf(AcademyCertificate $certificate, Request $request, CertificateService $certificates)
    {
        $this->authorizeStaff();

        $hideSignatures = $request->query('signatures') === '0';

        $pdf = $certificates->renderPdfOutput($certificate, $hideSignatures);
        $filename = $hideSignatures ? "{$certificate->verify_code}-no-signature.pdf" : "{$certificate->verify_code}-signed.pdf";

        return response($pdf, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * AI-suggested title/description/hashtags for one certificate - no
     * actual posting (no social API credentials configured), just
     * something better than a blank page to copy from. AJAX, returns JSON.
     */
    public function suggestPost(AcademyCertificate $certificate, AcademySocialPostService $service)
    {
        $this->authorizeStaff();

        return response()->json($service->suggest($certificate));
    }
}
