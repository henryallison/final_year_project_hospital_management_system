<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminVerified
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->get('admin_verified', false)) {
            return redirect('/')
                ->with('admin_verify_error', 'Admin verification required');
        }

        // Check if verification expired (5 minutes)
        $verifiedAt = $request->session()->get('admin_verified_at');
        if (now()->diffInMinutes($verifiedAt) > 5) {
            $request->session()->forget(['admin_verified', 'admin_verified_at']);
            return redirect('/')
                ->with('admin_verify_error', 'Admin session expired. Please verify again.');
        }

        return $next($request);
    }
}
