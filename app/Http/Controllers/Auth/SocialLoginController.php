<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SocialLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialLoginController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     */
    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from the provider.
     */
    public function callback($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Authentication failed. Please try again.');
        }

        // Check if a user with this social login already exists
        $socialLogin = SocialLogin::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialLogin) {
            // Login the associated user
            Auth::login($socialLogin->user, true);
            return redirect()->intended(route('dashboard', absolute: false));
        }

        // Check if user exists with the same email
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            // Create a new user
            $user = User::create([
                'name' => $socialUser->getName() ?? 'User',
                'email' => $socialUser->getEmail(),
                'password' => bcrypt(Str::random(16)), // Dummy password
                'is_verified' => true,
                'email_verified_at' => now(),
            ]);
        }

        // Link the social profile to the user
        SocialLogin::create([
            'user_id' => $user->id,
            'provider' => $provider,
            'provider_id' => $socialUser->getId(),
        ]);

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
