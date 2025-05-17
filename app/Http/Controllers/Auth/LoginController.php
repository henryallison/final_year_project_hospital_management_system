<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override this to update user session details on login
     */
    protected function authenticated(Request $request, $user)
    {
        $user->update([
            'is_active' => 1,
            'last_login_at' => Carbon::now(),
            'last_login_ip' => $request->ip(),
        ]);

        // Log successful login
        Log::info("User logged in: {$user->email} (ID: {$user->id}) from IP: {$request->ip()}");
    }

    /**
     * Override logout to set is_active to false
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->update(['is_active' => 0]);
            // Log logout action
            Log::info("User logged out: {$user->email} (ID: {$user->id})");
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle failed login attempts
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        // Log failed login attempt
        Log::warning("Failed login attempt for email: {$request->email} from IP: {$request->ip()}");

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }

    /**
     * Handle too many login attempts
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        // Log lockout event
        Log::warning("Account locked due to too many login attempts for email: {$request->email} from IP: {$request->ip()}");

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
    }
}
