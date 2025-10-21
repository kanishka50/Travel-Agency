<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;

class AdminAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Get authenticated user
        $user = Auth::user();

        // Check if user is admin
        if (!$user || $user->user_type !== 'admin' || !$user->admin()->exists()) {
            // Not an admin - logout and redirect
            Auth::logout();
            return redirect()->route('login')->with('error', 'You do not have admin access.');
        }

        return $next($request);
    }
}