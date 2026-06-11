<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureFinancialsAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private const ALLOWED = [
        'rashid.bukhari78600@gmail.com',
        'zaars59208@gmail.com',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || !in_array($user->email, self::ALLOWED)) {
            abort(403, 'Access restricted to authorised accounts only.');
        }

        return $next($request);
    }
}
