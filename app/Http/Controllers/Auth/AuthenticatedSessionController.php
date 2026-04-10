<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     * Modified to include conditional verification check.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Step 1: Validate the user's credentials
        $credentials = $request->only('email', 'password');
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'The provided credentials are incorrect.']);
        }

        // Step 2: Check if verification is required based on the method requiresVerification
        if ($this->requiresVerification($user)) {
            // If verification is required, generate a code, send it, store user id and redirect to verification form
            $verificationCode = rand(100000, 999999);
            cache()->put('verification_code_' . $user->id, $verificationCode, now()->addMinutes(10));
            Mail::send('emails.verify-login', [
                'verificationCode' => $verificationCode,
            ], function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Vérifier votre email');
            });
            session(['unverified_user_id' => $user->id]);
            return redirect()->route('verification.show');
        }


        // Step 3: If no verification is needed, log in the user directly
        Auth::login($user);
        return redirect()->intended(route('dashboard', absolute: false));
    }


    /**
     * Determine if a user needs to verify login based on email_verified_at timestamp.
     *
     * @param  \App\Models\User $user
     * @return bool
     */
    private function requiresVerification(\App\Models\User $user): bool
    {
        // If email_verified_at is null the user needs to verify
        if (!$user->email_verified_at) {
            return true;
        }


        // Parse the email_verified_at timestamp
        $lastVerified = Carbon::parse($user->email_verified_at);
        // Get the current time
        $now = Carbon::now();


        // Calculate the number of days since last verification
        $daysSinceLastVerification = $now->diffInDays($lastVerified);

        // generate a random number between 3 and 10 as a delay for the verification
        $randomDelay = rand(3, 10);

        //return if the days since last verification are greater than the random delay
        return $daysSinceLastVerification >= $randomDelay;
    }


    /**
     * Show the verification form.
     */
    public function showVerificationForm(): View
    {
        return view('auth.verify-login');
    }

    /**
     * Handle the incoming verification code and log in the user if code is correct.
     */
    public function verifyCode(Request $request): RedirectResponse
    {
        // Validate the code
        $request->validate([
            'verification_code' => 'required|integer',
        ]);

        // Retrieve user id stored in the session
        $userId = session('unverified_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['email' => 'Session expirée. Veuillez vous reconnecter.']);
        }

        // Retrieve the verification code stored in cache
        $cachedCode = cache()->get('verification_code_' . $userId);

        // Verify if the provided code matches the stored code
        if ($cachedCode && $request->verification_code == $cachedCode) {
            // Get the user from db using the user id stored
            $user = \App\Models\User::find($userId);
            //Log the user in
            Auth::login($user);
            //Update the email_verified_at timestamp
            $user->update(['email_verified_at' => now()]);

            // Clear verification state
            cache()->forget('verification_code_' . $userId);
            session()->forget('unverified_user_id');

            // Redirect to the dashboard
            return redirect()->route('dashboard');
        }
        // if the code is incorrect, return the user to the same page with error message
        return back()->withErrors(['verification_code' => 'Le code est incorrect ou a expiré.']);
    }

    /**
     * Destroy an authenticated session and clear the verification states.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clears the verification state if the user is in the middle of the process
        $userId = session('unverified_user_id');
        if ($userId) {
            cache()->forget('verification_code_' . $userId);
            session()->forget('unverified_user_id');
        }

        // Logout the user
        Auth::guard('web')->logout();

        // Invalidate and regenerate session token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to home
        return redirect('/');
    }
}
