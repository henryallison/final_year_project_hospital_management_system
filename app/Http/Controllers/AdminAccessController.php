<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

class AdminAccessController extends Controller
{
    public function verifyCode(Request $request)
    {
        // Get the admin code from config with proper validation
        $validCode = $this->getValidAdminCode();
        if ($validCode === null) {
            Log::error('Admin access code not properly configured in system');
            return response()->json([
                'success' => false,
                'message' => 'System configuration error. Please contact administrator.'
            ], 500);
        }

        Log::info('Admin code verification attempt', [
            'received_code' => $request->code,
            'expected_code' => $validCode,
            'ip' => $request->ip()
        ]);

        // Rate limiting (5 attempts per minute)
        $executed = RateLimiter::attempt(
            'admin-code:'.$request->ip(),
            $perMinute = 5,
            function() {}
        );

        if (!$executed) {
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please try again in a minute.'
            ], 429);
        }

        // Validate input
        $request->validate([
            'code' => 'required|digits:10'
        ]);

        // Secure comparison with type safety
        if (hash_equals((string)$validCode, (string)$request->code)) {
            $request->session()->put([
                'admin_verified' => true,
                'admin_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'redirect' => route('register')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid admin code. Please try again.'
        ], 401);
    }

    /**
     * Get and validate the admin code from configuration
     */
    protected function getValidAdminCode(): ?string
    {
        $code = config('app.admin_access_code');

        // Check if code exists and is a string
        if (empty($code) || !is_string($code)) {
            return null;
        }

        // Remove any accidental whitespace
        $code = trim($code);

        // Verify it's exactly 10 digits
        if (!preg_match('/^\d{10}$/', $code)) {
            return null;
        }

        return $code;
    }
}
