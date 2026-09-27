<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\VoyagerAuthController;

/**
 * Single login page for both existing Voyager admin staff and Academy-only
 * users (students, instructors, reviewers with no browse_admin permission).
 *
 * Voyager's own VoyagerAuthController always sends everyone to
 * voyager.dashboard (admin/dashboard) after login, and also bounces an
 * already-logged-in visitor of the login page straight back there. That
 * loops forever for anyone without browse_admin: admin/dashboard has no
 * permission -> VoyagerAdminMiddleware sends them to '/' -> '/' redirects
 * to admin/login -> already logged in -> back to admin/dashboard -> ...
 *
 * This overrides just the redirect target, based on what the user actually
 * has access to, and is registered in routes/web.php ahead of
 * Voyager::routes() so it's matched first for admin/login - no vendor
 * files touched, and no need to override Voyager's global controller
 * namespace (which would require stubbing out every Voyager controller,
 * not just this one).
 */
class AcademyAwareAuthController extends VoyagerAuthController
{
    public function login()
    {
        if ($this->guard()->user()) {
            return redirect()->to($this->resolveRedirectTarget());
        }

        return Voyager::view('voyager::login');
    }

    /**
     * Preempts $redirectTo (from Laravel's RedirectsUsers trait) exactly
     * like Voyager's own version - postLogin() itself needs no override,
     * it already routes through this via redirect()->intended($this->redirectPath()).
     */
    public function redirectTo()
    {
        return $this->resolveRedirectTarget();
    }

    /**
     * Voyager's own admin/logout is gated by VoyagerAdminMiddleware, which
     * requires browse_admin - an Academy student/instructor/reviewer
     * without it would get silently bounced back to their dashboard
     * instead of actually logging out. This shadows admin/logout the same
     * way (registered after Voyager::routes() in routes/web.php) with no
     * such gate - anyone authenticated can log themselves out.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('voyager.login');
    }

    protected function resolveRedirectTarget(): string
    {
        $user = Auth::user();

        if (!$user) {
            return route('voyager.dashboard');
        }

        // Academy capability comes FIRST, even for someone who also holds
        // browse_admin (e.g. an Academy Instructor/Accountant now also
        // granted just enough Voyager access for self-service Leaves) -
        // their Academy dashboard stays the default landing page; Voyager
        // is reached via the "Go to Voyager Admin" toggle on that
        // dashboard, not the other way around. Same priority order used by
        // the navbar's quick-link (User::academyLandingRoute()) - kept as
        // one source of truth.
        if (method_exists($user, 'academyLandingRoute') && $academyRoute = $user->academyLandingRoute()) {
            return route($academyRoute);
        }

        // Same idea as Academy capabilities above, for the dedicated
        // restricted HR-documents-only role (no browse_admin) - land them
        // straight on their own dashboard rather than the browse_admin
        // fallback below (which they don't hold) or academy.dashboard
        // (nonsensical for a non-Academy role). The full 'HR' role is
        // unaffected - real HR staff keep landing on /admin as before and
        // reach this screen via its sidebar menu link.
        if (optional($user->role)->name === 'hr-document-manager') {
            return route('hr-documents.dashboard');
        }

        if ($user->hasPermission('browse_admin')) {
            return route('voyager.dashboard');
        }

        // No admin access and no recognized Academy role - never send them
        // back to voyager.dashboard, that's the loop this class exists to
        // avoid. academy.dashboard shows a plain "no active enrollment"
        // message instead of bouncing forever.
        return route('academy.dashboard');
    }
}
