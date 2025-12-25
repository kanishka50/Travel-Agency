<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Guide\GuidePlanController;
use App\Http\Controllers\PublicPlanController;
use App\Http\Controllers\PublicController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Tourist;
use App\Models\Guide;
use App\Models\Admin;
use App\Http\Controllers\GuideRegistrationController;
use App\Http\Controllers\Auth\TouristRegistrationController;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Public Static Pages
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy-policy', [PublicController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PublicController::class, 'terms'])->name('terms');

// Public Tour Packages (Guide Plans) - New public-facing routes
Route::get('/tour-packages', [PublicController::class, 'tourPackages'])->name('tour-packages.index');
Route::get('/tour-packages/{plan}', [PublicController::class, 'showTourPackage'])->name('tour-packages.show');

// Public Tour Requests - New public-facing routes
Route::get('/tour-requests', [PublicController::class, 'tourRequests'])->name('tour-requests.index');
Route::get('/tour-requests/{request}', [PublicController::class, 'showTourRequest'])->name('tour-requests.show');

// Legacy Plan Browsing Routes - Redirect to new tour-packages URLs
Route::get('/plans', fn() => redirect()->route('tour-packages.index', request()->query(), 301));
Route::get('/plans/{id}', fn($id) => redirect()->route('tour-packages.show', ['plan' => $id], 301));

// Availability API routes
Route::get('/api/plans/{plan}/availability', [App\Http\Controllers\AvailabilityController::class, 'getPlanAvailability'])->name('api.plans.availability');
Route::post('/api/plans/{plan}/check-dates', [App\Http\Controllers\AvailabilityController::class, 'checkDates'])->name('api.plans.check-dates');

// Redirect after login based on user type
// Both tourists and guides go to home page - they can access dashboard via profile dropdown
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();

    if ($user->isTourist() || $user->isGuide()) {
        return redirect()->route('welcome');
    } elseif ($user->isAdmin()) {
        return redirect('/admin');
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Tourist routes
Route::middleware(['auth', 'tourist'])->prefix('tourist')->name('tourist.')->group(function () {
    Route::get('/dashboard', [TouristController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [TouristController::class, 'settings'])->name('settings');

    // Tourist Request routes (moved here from separate group)
    Route::get('/requests', [App\Http\Controllers\TouristRequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/create', [App\Http\Controllers\TouristRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [App\Http\Controllers\TouristRequestController::class, 'store'])->name('requests.store');
    Route::get('/requests/{touristRequest}', [App\Http\Controllers\TouristRequestController::class, 'show'])->name('requests.show');
    Route::get('/requests/{touristRequest}/edit', [App\Http\Controllers\TouristRequestController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{touristRequest}', [App\Http\Controllers\TouristRequestController::class, 'update'])->name('requests.update');
    Route::post('/requests/{touristRequest}/close', [App\Http\Controllers\TouristRequestController::class, 'close'])->name('requests.close');
    Route::delete('/requests/{touristRequest}', [App\Http\Controllers\TouristRequestController::class, 'destroy'])->name('requests.destroy');

    // Bid routes (Tourist viewing and responding to bids)
    Route::get('/requests/{touristRequest}/bids/{bid}', [App\Http\Controllers\BidController::class, 'show'])->name('bids.show');
    Route::post('/requests/{touristRequest}/bids/{bid}/accept', [App\Http\Controllers\BidController::class, 'accept'])->name('bids.accept');
    Route::post('/requests/{touristRequest}/bids/{bid}/reject', [App\Http\Controllers\BidController::class, 'reject'])->name('bids.reject');

    // Tourist Complaint Routes
    Route::get('/complaints', [App\Http\Controllers\Tourist\TouristComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [App\Http\Controllers\Tourist\TouristComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [App\Http\Controllers\Tourist\TouristComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}', [App\Http\Controllers\Tourist\TouristComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/response', [App\Http\Controllers\Tourist\TouristComplaintController::class, 'addResponse'])->name('complaints.addResponse');
    Route::delete('/complaints/{complaint}/withdraw', [App\Http\Controllers\Tourist\TouristComplaintController::class, 'withdraw'])->name('complaints.withdraw');
});

// Legacy Tourist Request routes (kept for backward compatibility - redirects to new paths)
// These old routes point to the new controller locations
Route::middleware(['auth', 'tourist'])->group(function () {
    Route::redirect('/tourist-requests', '/tourist/requests');
    Route::redirect('/tourist-requests/create', '/tourist/requests/create');
});

// Tourist Booking routes
Route::middleware(['auth', 'tourist'])->prefix('tourist')->name('tourist.')->group(function () {
    Route::get('/bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/download-agreement', [App\Http\Controllers\BookingController::class, 'downloadAgreement'])->name('bookings.download-agreement');

    // Tourist Proposal routes
    Route::get('/proposals', [App\Http\Controllers\PlanProposalController::class, 'touristIndex'])->name('proposals.index');
    Route::get('/proposals/{proposal}', [App\Http\Controllers\PlanProposalController::class, 'touristShow'])->name('proposals.show');
});

// Legacy Booking routes (kept for backward compatibility)
Route::middleware(['auth', 'tourist'])->group(function () {
    Route::redirect('/bookings', '/tourist/bookings');
    Route::get('/bookings/create', [App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/download-agreement', [App\Http\Controllers\BookingController::class, 'downloadAgreement'])->name('bookings.download-agreement');

    // Payment routes
    Route::post('/payment/checkout/{booking}', [App\Http\Controllers\PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
    Route::get('/payment/success/{booking}', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{booking}', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');

    // Proposal routes (creating and canceling)
    Route::get('/plans/{plan}/propose', [App\Http\Controllers\PlanProposalController::class, 'create'])->name('proposals.create');
    Route::post('/plans/{plan}/propose', [App\Http\Controllers\PlanProposalController::class, 'store'])->name('proposals.store');
    Route::post('/proposals/{proposal}/cancel', [App\Http\Controllers\PlanProposalController::class, 'cancel'])->name('proposals.cancel');
});

// Stripe Webhook (No authentication - Stripe will verify via signature)
Route::post('/webhook/stripe', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('webhook.stripe');

// Guide routes
Route::middleware(['auth', 'guide'])->prefix('guide')->name('guide.')->group(function () {
    Route::get('/dashboard', [GuideController::class, 'dashboard'])->name('dashboard');
    Route::get('/settings', [GuideController::class, 'settings'])->name('settings');

    // Guide Plan Management Routes
    Route::resource('plans', GuidePlanController::class)->except(['index', 'show']);
    Route::get('/plans', [GuidePlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/{plan}', [GuidePlanController::class, 'show'])->name('plans.show');
    Route::post('/plans/{plan}/duplicate', [GuidePlanController::class, 'duplicate'])->name('plans.duplicate');
    Route::patch('/plans/{plan}/status', [GuidePlanController::class, 'updateStatus'])->name('plans.status');
    Route::delete('/plans/{plan}/photos/{photo}', [GuidePlanController::class, 'deletePhoto'])->name('plans.photos.delete');

    // Guide Booking Routes
    Route::get('/bookings', [GuideController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [GuideController::class, 'showBooking'])->name('bookings.show');

    // Guide Request Routes (Browse tourist requests and submit bids)
    Route::get('/requests', [App\Http\Controllers\Guide\RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{touristRequest}', [App\Http\Controllers\Guide\RequestController::class, 'show'])->name('requests.show');

    // Guide Request Proposal Routes (Guide creates proposals for Tourist Requests)
    Route::get('/requests/{touristRequest}/propose', [App\Http\Controllers\Guide\BidController::class, 'create'])->name('request-proposals.create');
    Route::post('/requests/{touristRequest}/propose', [App\Http\Controllers\Guide\BidController::class, 'store'])->name('request-proposals.store');
    Route::post('/request-proposals/{bid}/withdraw', [App\Http\Controllers\Guide\BidController::class, 'withdraw'])->name('request-proposals.withdraw');

    // Guide Plan Proposal Routes
    Route::get('/proposals', [App\Http\Controllers\PlanProposalController::class, 'guideIndex'])->name('proposals.index');
    Route::get('/proposals/{proposal}', [App\Http\Controllers\PlanProposalController::class, 'guideShow'])->name('proposals.show');
    Route::post('/proposals/{proposal}/accept', [App\Http\Controllers\PlanProposalController::class, 'accept'])->name('proposals.accept');
    Route::post('/proposals/{proposal}/reject', [App\Http\Controllers\PlanProposalController::class, 'reject'])->name('proposals.reject');

    // Guide Payment Routes
    Route::get('/payments', [App\Http\Controllers\Guide\GuidePaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/{payment}', [App\Http\Controllers\Guide\GuidePaymentController::class, 'show'])->name('payments.show');

    // Guide Complaint Routes
    Route::get('/complaints', [App\Http\Controllers\Guide\GuideComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/complaints/create', [App\Http\Controllers\Guide\GuideComplaintController::class, 'create'])->name('complaints.create');
    Route::post('/complaints', [App\Http\Controllers\Guide\GuideComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}', [App\Http\Controllers\Guide\GuideComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/response', [App\Http\Controllers\Guide\GuideComplaintController::class, 'addResponse'])->name('complaints.addResponse');
    Route::delete('/complaints/{complaint}/withdraw', [App\Http\Controllers\Guide\GuideComplaintController::class, 'withdraw'])->name('complaints.withdraw');

    // Guide Vehicle Management Routes
    Route::get('/vehicles', [App\Http\Controllers\Guide\VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicles/create', [App\Http\Controllers\Guide\VehicleController::class, 'create'])->name('vehicles.create');
    Route::post('/vehicles', [App\Http\Controllers\Guide\VehicleController::class, 'store'])->name('vehicles.store');
    Route::get('/vehicles/{vehicle}', [App\Http\Controllers\Guide\VehicleController::class, 'show'])->name('vehicles.show');
    Route::get('/vehicles/{vehicle}/edit', [App\Http\Controllers\Guide\VehicleController::class, 'edit'])->name('vehicles.edit');
    Route::put('/vehicles/{vehicle}', [App\Http\Controllers\Guide\VehicleController::class, 'update'])->name('vehicles.update');
    Route::patch('/vehicles/{vehicle}/toggle-status', [App\Http\Controllers\Guide\VehicleController::class, 'toggleStatus'])->name('vehicles.toggle-status');
    Route::delete('/vehicles/{vehicle}', [App\Http\Controllers\Guide\VehicleController::class, 'destroy'])->name('vehicles.destroy');

    // Booking Vehicle Assignment Routes
    Route::get('/bookings/{booking}/vehicle/assign', [App\Http\Controllers\Guide\BookingVehicleController::class, 'showAssignForm'])->name('bookings.vehicle.assign');
    Route::post('/bookings/{booking}/vehicle/assign-saved', [App\Http\Controllers\Guide\BookingVehicleController::class, 'assignSavedVehicle'])->name('bookings.vehicle.assign-saved');
    Route::post('/bookings/{booking}/vehicle/assign-temporary', [App\Http\Controllers\Guide\BookingVehicleController::class, 'assignTemporaryVehicle'])->name('bookings.vehicle.assign-temporary');
    Route::get('/bookings/{booking}/vehicle', [App\Http\Controllers\Guide\BookingVehicleController::class, 'viewAssignment'])->name('bookings.vehicle.view');
    Route::delete('/bookings/{booking}/vehicle', [App\Http\Controllers\Guide\BookingVehicleController::class, 'removeAssignment'])->name('bookings.vehicle.remove');
});


// Profile routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Guide Registration Routes (Public - No Authentication Required)
Route::get('/become-a-guide', [GuideRegistrationController::class, 'create'])
    ->name('guide.register');

Route::post('/become-a-guide', [GuideRegistrationController::class, 'store'])
    ->name('guide-registration.store');

Route::get('/guide-registration-success', [GuideRegistrationController::class, 'success'])
    ->name('guide-registration.success');

// Tourist Registration Routes (Public - No Authentication Required)
Route::get('/register/tourist', [TouristRegistrationController::class, 'showRegistrationForm'])
    ->name('tourist.register.form')
    ->middleware('guest');

Route::post('/register/tourist', [TouristRegistrationController::class, 'register'])
    ->name('tourist.register')
    ->middleware('guest');

require __DIR__.'/auth.php';
