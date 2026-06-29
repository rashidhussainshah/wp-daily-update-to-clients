<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureCampaignAccess
{
    private const ALLOWED = [
        'alihasanwebpenter@gmail.com',
        'ayubkhokhar786@gmail.com',
        'zaars59208@gmail.com',
        'rashid.bukhari78600@gmail.com',
    ];

    public static function isAllowed(string $email): bool
    {
        return in_array(strtolower($email), self::ALLOWED, true);
    }

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !self::isAllowed($user->email)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }

            return redirect()->route('voyager.dashboard')
                ->with('error', 'You do not have access to Email Campaigns.');
        }

        return $next($request);
    }
}
