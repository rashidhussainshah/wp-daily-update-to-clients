<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class GoogleLoginController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        // Redirect the user to Google's authentication page
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google callback.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleCallback()
    {
        try {
            // Retrieve user information from Google
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            // Handle authentication error gracefully
            return redirect()->route('login')->with('error', 'Google authentication failed. Please try again.');
        }

        // Check if a user with the same Google ID already exists
        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // If the user does not exist, create a new user with Google data
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                // Add more fields as needed
            ]);
        }

        // Authenticate the user
        Auth::login($user);

        // Redirect the user after successful login
        return redirect('/dashboard');
    }
}
