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

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user || !in_array(strtolower($user->email), self::ALLOWED, true)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Access denied.'], 403);
            }

            return redirect()->route('voyager.dashboard')
                ->with('error', 'You do not have access to Email Campaigns.');
        }

        return $next($request);
    }
}
