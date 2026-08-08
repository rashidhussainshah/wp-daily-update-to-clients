<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCampaignAccess
{
    private const ALLOWED_ROLE_IDS = [
        1,  // Administrator
        33, // Bussiness Developer
    ];

    public static function isAllowed(?int $roleId): bool
    {
        return in_array($roleId, self::ALLOWED_ROLE_IDS, true);
    }

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !self::isAllowed($user->role_id)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }

            return redirect()->route('voyager.dashboard')
                ->with('error', 'You do not have access to Email Campaigns.');
        }

        return $next($request);
    }
}
