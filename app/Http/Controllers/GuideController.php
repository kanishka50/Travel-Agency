<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GuideController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in guide's profile
        $guide = auth()->user()->guide;
        
        // Return the dashboard view with guide data
        return view('guide.dashboard', [
            'guide' => $guide,
        ]);
    }
}