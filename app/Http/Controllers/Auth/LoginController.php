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

        // Add proxy trust middleware
        $this->middleware(function ($request, $next) {
            $request->setTrustedProxies(
                [$request->server->get('REMOTE_ADDR')], // Trust the immediate connection
                Request::HEADER_X_FORWARDED_FOR |
                Request::HEADER_X_FORWARDED_HOST |
                Request::HEADER_X_FORWARDED_PORT |
                Request::HEADER_X_FORWARDED_PROTO
            );
            return $next($request);
        });
    }

    /**
     * Get the real client IP address with proxy support
     */
    protected function getClientIp(Request $request): string
    {
        $ip = $request->ip();

        // If we still get localhost, check headers directly
        if (in_array($ip, ['127.0.0.1', '::1'])) {
            $ip = $request->header('X-Forwarded-For') ??
                  $request->header('X-Real-IP') ??
                  $ip;

            // Handle multiple IPs in X-Forwarded-For
            if (str_contains($ip, ',')) {
                $ips = explode(',', $ip);
                $ip = trim($ips[0]); // Get the original client IP (first in chain)
            }
        }

        return $ip ?: 'unknown';
    }

    /**
     * Update user session details on login with real IP
     */
    protected function authenticated(Request $request, $user)
    {
        $ip = $this->getClientIp($request);

        $user->update([
            'is_active' => 1,
            'last_login_at' => Carbon::now(),
            'last_login_ip' => $ip,
        ]);

        Log::info("User logged in: {$user->email} (ID: {$user->id}) from IP: {$ip}");
    }

    /**
     * Handle logout with activity tracking
     */
    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->update(['is_active' => 0]);
            $ip = $this->getClientIp($request);
            Log::info("User logged out: {$user->email} (ID: {$user->id}) from IP: {$ip}");
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Handle failed login attempts with real IP
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $ip = $this->getClientIp($request);
        Log::warning("Failed login attempt for email: {$request->email} from IP: {$ip}");

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }

    /**
     * Handle lockouts with real IP
     */
    protected function sendLockoutResponse(Request $request)
    {
        $seconds = $this->limiter()->availableIn(
            $this->throttleKey($request)
        );

        $ip = $this->getClientIp($request);
        Log::warning("Account locked for email: {$request->email} from IP: {$ip}");

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
