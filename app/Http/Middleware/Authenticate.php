<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            // This app has no standalone 'login' route - everyone (admin
            // staff and, now, Academy students/instructors) authenticates
            // through Voyager's login screen against the same users table.
            return route('voyager.login');
        }
    }
}
