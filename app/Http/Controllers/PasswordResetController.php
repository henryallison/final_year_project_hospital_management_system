<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordResetCodeMail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;



class PasswordResetController extends Controller
{
    // Rate limiting for password reset requests
    const MAX_ATTEMPTS = 3;
    const DECAY_MINUTES = 15;

    /**
     * Show the password reset request form.
     */
    public function showRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a 4-digit verification code to the user's email.
     */
    public function sendResetCode(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            $code = PasswordResetCode::generateCode();

            PasswordResetCode::updateOrCreate(
                ['email' => $user->email],
                ['code' => $code, 'created_at' => \Carbon\Carbon::now('Africa/Kigali')]
            );

            // Log email attempt
            \Log::info("Attempting to send code to: {$user->email}");

            // ✅ Send the mail immediately (no queue)
            Mail::to($user->email)->send(new PasswordResetCodeMail($code));

            \Log::info("Created at: " . \Carbon\Carbon::now('Africa/Kigali')->toDateTimeString());

            \Log::info("Code sent successfully to {$user->email}");

            return redirect()->route('password.code.verify')->with([
                'status' => 'Verification code sent!',
                'email' => $user->email
            ]);
        } catch (\Exception $e) {
            \Log::error("Email send failed: " . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send code. Please try again.']);
        }
    }

    /**
     * Show the verification code entry form.
     */
    public function showVerifyCodeForm()
    {
        return view('auth.passwords.verify-code');
    }

    /**
     * Verify the 4-digit code and redirect to password reset.
     */
    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'code' => 'required|digits:4'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $resetCode = PasswordResetCode::where('email', $data['email'])
            ->where('code', $data['code'])
            ->first();

        if (!$resetCode) {
            return back()->withErrors([
                'code' => 'The verification code is invalid.'
            ])->withInput();
        }

        // ✅ Correct: Pass the real 4-digit code to the reset form
        // Fix: Pass token as route parameter and email as query string
        return redirect()->route('password.reset', [
            'token' => $data['code'],  // This matches the {token} in the route URI
            'email' => $data['email']   // This will be added as query parameter
        ]);
    }


    public function resetPassword(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email|exists:users,email', // Ensure the email exists in the users table
            'password' => 'required|min:8|confirmed',  // Ensuring password is at least 8 characters and confirmed
            'token' => 'required' // Ensure the token is provided
        ]);

        // Debugging: Log the email and token to confirm they are passed correctly
        \Log::info('Password Reset Attempt', [
            'email' => $request->email,
            'token' => $request->token
        ]);

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Log if no user is found with the provided email
            \Log::error('Password reset failed: No user found with email', ['email' => $request->email]);
            return back()->withErrors(['email' => 'No user found with that email.']);
        }

        // Check if the reset code is valid (it exists in the PasswordResetCode table)
        $resetCode = PasswordResetCode::where('email', $request->email)
            ->where('code', $request->token)  // Using the token as the reset code
            ->first();

        if (!$resetCode) {
            // Log error if the token is invalid
            \Log::error('Password reset failed: Invalid reset token', ['email' => $request->email, 'token' => $request->token]);
            return back()->withErrors(['token' => 'The password reset token is invalid.']);
        }

        // Update the user's password
        try {
            $user->password = bcrypt($request->password);  // Hash the new password
            $user->save();  // Save the user record with the new password
            \Log::info('Password successfully reset for user', ['email' => $request->email]);
        } catch (\Exception $e) {
            // Log error if saving the password fails
            \Log::error('Password reset failed: Error saving password', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);
            return back()->withErrors(['error' => 'There was an error resetting your password. Please try again later.']);
        }

        // Delete the reset code after password is reset
        $resetCode->delete();

        // Redirect to the login page with a success message
        return redirect()->route('login')->with('status', 'Your password has been reset successfully. Please log in.');
    }


    public function showResetForm(Request $request, $token)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email // email comes from query string
        ]);
    }
    /**
     * Convert technical errors to user-friendly messages
     */
    protected function getUserFriendlyError(\Exception $e): string
    {
        $message = $e->getMessage();

        return match (true) {
            str_contains($message, 'getaddrinfo') => 'Unable to connect to mail server. Please try again later.',
            str_contains($message, 'authentication') => 'Email service authentication failed. Please contact support.',
            str_contains($message, 'TLS') || str_contains($message, 'SSL') => 'Secure connection to mail service failed.',
            str_contains($message, 'timed out') => 'Mail service is currently unavailable. Please try again later.',
            str_contains($message, 'quota') => 'Email quota exceeded. Please try again in an hour.',
            default => 'We encountered an error sending your verification code. Please try again.'
        };
    }
}
