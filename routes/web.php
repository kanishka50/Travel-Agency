<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TouristController;
use App\Http\Controllers\GuideController;
use App\Http\Controllers\AdminController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\Tourist;
use App\Models\Guide;
use App\Models\Admin;

// Public routes
Route::get('/', function () {
    return view('welcome');
});

// Redirect after login based on user type
Route::get('/dashboard', function () {
    /** @var User $user */
    $user = Auth::user();
    
    if ($user->isTourist()) {
        return redirect()->route('tourist.dashboard');
    } elseif ($user->isGuide()) {
        return redirect()->route('guide.dashboard');
    } elseif ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// Tourist routes
Route::middleware(['auth', 'tourist'])->prefix('tourist')->name('tourist.')->group(function () {
    Route::get('/dashboard', [TouristController::class, 'dashboard'])->name('dashboard');
});

// Guide routes
Route::middleware(['auth', 'guide'])->prefix('guide')->name('guide.')->group(function () {
    Route::get('/dashboard', [GuideController::class, 'dashboard'])->name('dashboard');
});

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
});

// Profile routes (for all authenticated users)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
