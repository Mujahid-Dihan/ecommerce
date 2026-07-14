<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Str;

class OTPLoginController extends Controller
{
    /**
     * Request an OTP for a given phone number.
     */
    public function requestOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $phone = $request->phone;
        
        // Generate a 4 digit OTP
        $code = rand(1000, 9999);

        // Save or update OTP in the database
        Otp::updateOrCreate(
            ['identifier' => $phone],
            [
                'code' => $code,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]
        );

        // For local development, we just log it instead of sending a real SMS
        Log::info("OTP for {$phone} is {$code}");

        return back()->with('status', 'OTP has been sent to your phone number. (Check laravel.log for local testing)');
    }

    /**
     * Verify the OTP and log the user in.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
            'code' => ['required', 'string', 'max:10'],
        ]);

        $otpRecord = Otp::where('identifier', $request->phone)
            ->where('code', $request->code)
            ->first();

        if (!$otpRecord || Carbon::now()->greaterThan($otpRecord->expires_at)) {
            return back()->withErrors(['code' => 'The provided OTP is invalid or has expired.']);
        }

        // Find or create user
        $user = User::firstOrCreate(
            ['phone' => $request->phone],
            [
                'name' => 'User ' . substr($request->phone, -4), // Default name based on phone
                'password' => bcrypt(Str::random(16)), // Dummy password
                // Note: email is technically required by the default users table, 
                // so we generate a dummy one if it doesn't exist
                'email' => $request->phone . '@example.com',
                'is_verified' => true,
            ]
        );

        // Delete the OTP record so it can't be reused
        $otpRecord->delete();

        // Log the user in
        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
