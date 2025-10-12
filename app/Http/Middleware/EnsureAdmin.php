<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
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

        // Check if user is an admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Admin access only.');
        }

        return $next($request);
    }
}