<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsNotBanned
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->is_banned) {

            if ($request->expectsJson()) {
                // Revoke the current API token (Sanctum)
                if ($request->user()->currentAccessToken()) {
                    $request->user()->currentAccessToken()->delete();
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been suspended.',
                ], 403);
            }

            auth()->logout();
            $request->session()->invalidate();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Reason: ' . ($request->user()->ban_reason ?? 'Violation of community guidelines'),
            ]);
        }

        return $next($request);
    }
}
