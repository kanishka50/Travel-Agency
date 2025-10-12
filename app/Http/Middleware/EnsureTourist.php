<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTourist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        // Check if user is a tourist
        if (!auth()->user()->isTourist()) {
            abort(403, 'Unauthorized. Tourist access only.');
        }

        return $next($request);
    }
}