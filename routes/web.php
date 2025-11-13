<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Guide\GuidePlanController;
use App\Http\Controllers\PublicPlanController;
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

// Public Plan Browsing Routes (No Authentication Required)
Route::get('/plans', [PublicPlanController::class, 'index'])->name('plans.index');
Route::get('/plans/{id}', [PublicPlanController::class, 'show'])->name('plans.show');

// Availability API routes
Route::get('/api/plans/{plan}/availability', [App\Http\Controllers\AvailabilityController::class, 'getPlanAvailability'])->name('api.plans.availability');
Route::post('/api/plans/{plan}/check-dates', [App\Http\Controllers\AvailabilityController::class, 'checkDates'])->name('api.plans.check-dates');

// Redirect after login based on user type
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();

    if ($user->isTourist()) {
        return redirect()->route('tourist.dashboard');
    } elseif ($user->isGuide()) {
        return redirect()->route('guide.dashboard');
    } elseif ($user->isAdmin()) {
        return redirect('/admin');
    }

    return redirect('/');
})->middleware(['auth'])->name('dashboard');

// Tourist routes
Route::middleware(['auth', 'tourist'])->prefix('tourist')->name('tourist.')->group(function () {
    Route::get('/dashboard', [TouristController::class, 'dashboard'])->name('dashboard');
});

// Tourist Request routes (Tourist only)
Route::middleware(['auth', 'tourist'])->group(function () {
    Route::get('/tourist-requests', [App\Http\Controllers\TouristRequestController::class, 'index'])->name('tourist-requests.index');
    Route::get('/tourist-requests/create', [App\Http\Controllers\TouristRequestController::class, 'create'])->name('tourist-requests.create');
    Route::post('/tourist-requests', [App\Http\Controllers\TouristRequestController::class, 'store'])->name('tourist-requests.store');
    Route::get('/tourist-requests/{touristRequest}', [App\Http\Controllers\TouristRequestController::class, 'show'])->name('tourist-requests.show');
    Route::get('/tourist-requests/{touristRequest}/edit', [App\Http\Controllers\TouristRequestController::class, 'edit'])->name('tourist-requests.edit');
    Route::put('/tourist-requests/{touristRequest}', [App\Http\Controllers\TouristRequestController::class, 'update'])->name('tourist-requests.update');
    Route::post('/tourist-requests/{touristRequest}/close', [App\Http\Controllers\TouristRequestController::class, 'close'])->name('tourist-requests.close');
    Route::delete('/tourist-requests/{touristRequest}', [App\Http\Controllers\TouristRequestController::class, 'destroy'])->name('tourist-requests.destroy');

    // Bid routes (Tourist viewing and responding to bids)
    Route::get('/tourist-requests/{touristRequest}/bids/{bid}', [App\Http\Controllers\BidController::class, 'show'])->name('bids.show');
    Route::post('/tourist-requests/{touristRequest}/bids/{bid}/accept', [App\Http\Controllers\BidController::class, 'accept'])->name('bids.accept');
    Route::post('/tourist-requests/{touristRequest}/bids/{bid}/reject', [App\Http\Controllers\BidController::class, 'reject'])->name('bids.reject');
});

// Booking routes (Tourist only)
Route::middleware(['auth', 'tourist'])->group(function () {
    Route::get('/bookings/create', [App\Http\Controllers\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings', [App\Http\Controllers\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}/download-agreement', [App\Http\Controllers\BookingController::class, 'downloadAgreement'])->name('bookings.download-agreement');

    // Payment routes
    Route::post('/payment/checkout/{booking}', [App\Http\Controllers\PaymentController::class, 'createCheckoutSession'])->name('payment.checkout');
    Route::get('/payment/success/{booking}', [App\Http\Controllers\PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{booking}', [App\Http\Controllers\PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Stripe Webhook (No authentication - Stripe will verify via signature)
Route::post('/webhook/stripe', [App\Http\Controllers\PaymentController::class, 'webhook'])->name('webhook.stripe');

// Guide routes
Route::middleware(['auth', 'guide'])->prefix('guide')->name('guide.')->group(function () {
    Route::get('/dashboard', [GuideController::class, 'dashboard'])->name('dashboard');

    // Guide Plan Management Routes
    Route::resource('plans', GuidePlanController::class)->except(['index', 'show']);
    Route::get('/plans', [GuidePlanController::class, 'index'])->name('plans.index');
    Route::get('/plans/{plan}', [GuidePlanController::class, 'show'])->name('plans.show');
    Route::post('/plans/{plan}/duplicate', [GuidePlanController::class, 'duplicate'])->name('plans.duplicate');
    Route::patch('/plans/{plan}/status', [GuidePlanController::class, 'updateStatus'])->name('plans.status');

    // Guide Booking Routes
    Route::get('/bookings', [GuideController::class, 'bookings'])->name('bookings');
    Route::get('/bookings/{booking}', [GuideController::class, 'showBooking'])->name('bookings.show');

    // Guide Request Routes (Browse tourist requests and submit bids)
    Route::get('/requests', [App\Http\Controllers\Guide\RequestController::class, 'index'])->name('requests.index');
    Route::get('/requests/{touristRequest}', [App\Http\Controllers\Guide\RequestController::class, 'show'])->name('requests.show');

    // Guide Bid Routes
    Route::get('/requests/{touristRequest}/bid', [App\Http\Controllers\Guide\BidController::class, 'create'])->name('bids.create');
    Route::post('/requests/{touristRequest}/bid', [App\Http\Controllers\Guide\BidController::class, 'store'])->name('bids.store');
    Route::post('/bids/{bid}/withdraw', [App\Http\Controllers\Guide\BidController::class, 'withdraw'])->name('bids.withdraw');
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
