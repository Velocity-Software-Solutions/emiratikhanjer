<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Honeypot
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $trap = $request->input('_hp', '');
        $ts   = (int) $request->input('_hpt', 0);
        $minSeconds = 3; // require user to spend at least 3s on the form

        if (!empty($trap)) {
            return response()->json(['message' => 'Unprocessable'], 422);
        }

        if ($ts > 0) {
            $age = time() - $ts;
            if ($age < $minSeconds) {
                return response()->json(['message' => 'Too fast'], 422);
            }
        }

        return $next($request);
    }
}
