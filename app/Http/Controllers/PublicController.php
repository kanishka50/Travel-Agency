<?php

namespace App\Http\Controllers;

use App\Models\GuidePlan;
use App\Models\TouristRequest;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    /**
     * Show the about page
     */
    public function about()
    {
        return view('public.about');
    }

    /**
     * Show the contact page
     */
    public function contact()
    {
        return view('public.contact');
    }

    /**
     * Handle contact form submission
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        // For now, just log the contact form submission
        // In production, you would send an email or save to database
        \Log::info('Contact form submission', $validated);

        return back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }

    /**
     * Show the privacy policy page
     */
    public function privacyPolicy()
    {
        return view('public.privacy-policy');
    }

    /**
     * Show the terms of service page
     */
    public function terms()
    {
        return view('public.terms');
    }

    /**
     * Show all tour packages (guide plans) - public page
     */
    public function tourPackages(Request $request)
    {
        $query = GuidePlan::with(['guide'])
            ->where('status', 'active');

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('destinations', 'like', "%{$search}%");
            });
        }

        // Duration filter
        if ($request->filled('duration')) {
            switch ($request->duration) {
                case 'short':
                    $query->where('duration_days', '<=', 3);
                    break;
                case 'medium':
                    $query->whereBetween('duration_days', [4, 7]);
                    break;
                case 'long':
                    $query->where('duration_days', '>', 7);
                    break;
            }
        }

        // Price filter
        if ($request->filled('min_price')) {
            $query->where('price_per_person', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_per_person', '<=', $request->max_price);
        }

        $plans = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('public.tour-packages.index', compact('plans'));
    }

    /**
     * Show a single tour package (guide plan)
     */
    public function showTourPackage(GuidePlan $plan)
    {
        if ($plan->status !== 'active') {
            abort(404);
        }

        $plan->load(['guide', 'reviews.tourist', 'photos', 'itineraries', 'addons']);

        // Get similar packages
        $similarPackages = GuidePlan::where('status', 'active')
            ->where('id', '!=', $plan->id)
            ->limit(4)
            ->get();

        return view('public.tour-packages.show', compact('plan', 'similarPackages'));
    }

    /**
     * Show all tour requests - public page (for guides to see)
     */
    public function tourRequests(Request $request)
    {
        $query = TouristRequest::with(['tourist'])
            ->where('status', 'open')
            ->where('expires_at', '>', now());

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Duration filter
        if ($request->filled('duration')) {
            switch ($request->duration) {
                case 'short':
                    $query->where('duration_days', '<=', 3);
                    break;
                case 'medium':
                    $query->whereBetween('duration_days', [4, 7]);
                    break;
                case 'long':
                    $query->where('duration_days', '>', 7);
                    break;
            }
        }

        // Budget filter
        if ($request->filled('min_budget')) {
            $query->where('budget_max', '>=', $request->min_budget);
        }
        if ($request->filled('max_budget')) {
            $query->where('budget_min', '<=', $request->max_budget);
        }

        $requests = $query->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        return view('public.tour-requests.index', compact('requests'));
    }

    /**
     * Show a single tour request
     */
    public function showTourRequest(TouristRequest $request)
    {
        if ($request->status !== 'open' || $request->expires_at <= now()) {
            abort(404, 'This tour request is no longer available.');
        }

        $request->load(['tourist', 'bids']);

        return view('public.tour-requests.show', compact('request'));
    }
}
