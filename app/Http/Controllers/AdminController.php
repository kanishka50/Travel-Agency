<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Booking;
use App\Models\GuideRegistrationRequest;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in admin's profile
        $admin = auth()->user()->admin;
        
        // Get some statistics for the dashboard
        $stats = [
            'total_users' => User::count(),
            'total_tourists' => User::where('user_type', 'tourist')->count(),
            'total_guides' => User::where('user_type', 'guide')->count(),
            'pending_registrations' => GuideRegistrationRequest::where('status', 'documents_pending')->count(),
            'total_bookings' => Booking::count(),
        ];
        
        // Return the dashboard view with admin data and stats
        return view('admin.dashboard', [
            'admin' => $admin,
            'stats' => $stats,
        ]);
    }
}