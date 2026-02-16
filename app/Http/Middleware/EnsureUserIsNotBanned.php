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
            auth()->logout();
            $request->session()->invalidate();

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your account has been suspended.'], 403);
            }

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been suspended. Reason: ' . ($request->user()->ban_reason ?? 'Violation of community guidelines'),
            ]);
        }

        return $next($request);
    }
}
