<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TouristController extends Controller
{
    public function dashboard()
    {
        // Get the logged-in tourist's profile
        $tourist = auth()->user()->tourist;
        
        // Return the dashboard view with tourist data
        return view('tourist.dashboard', [
            'tourist' => $tourist,
        ]);
    }
}