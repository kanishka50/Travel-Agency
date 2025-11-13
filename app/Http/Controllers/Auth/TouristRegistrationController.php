<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\TouristWelcome;
use App\Models\Tourist;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

class TouristRegistrationController extends Controller
{
    /**
     * Show the tourist registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.tourist-register');
    }

    /**
     * Handle tourist registration
     */
    public function register(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:100'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            // Use database transaction to ensure both records are created
            DB::transaction(function () use ($validated, $request) {
                // Create User account
                $user = User::create([
                    'email' => $validated['email'],
                    'password' => Hash::make($validated['password']),
                    'user_type' => 'tourist',
                    'status' => 'active',
                ]);

                // Create Tourist profile
                $tourist = Tourist::create([
                    'user_id' => $user->id,
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                    'country' => $validated['country'],
                    'emergency_contact_name' => $validated['emergency_contact_name'] ?? null,
                    'emergency_contact_phone' => $validated['emergency_contact_phone'] ?? null,
                ]);

                // Fire the Registered event (triggers email verification)
                event(new Registered($user));

                // Send welcome email
                try {
                    Mail::to($user->email)->send(new TouristWelcome($tourist));
                } catch (\Exception $e) {
                    // Log email error but don't fail registration
                    \Log::error('Failed to send tourist welcome email: ' . $e->getMessage());
                }

                // Mark email as verified immediately for tourists
                $user->email_verified_at = now();
                $user->save();

                // Log the user in
                Auth::login($user);

                // Store success message in session
                session()->flash('registration_success', true);
            });

            return redirect()->route('tourist.dashboard')
                ->with('success', 'Registration successful! Welcome to Travel Agency.');

        } catch (\Exception $e) {
            return back()
                ->withInput($request->except('password', 'password_confirmation'))
                ->withErrors(['error' => 'Registration failed. Please try again. Error: ' . $e->getMessage()]);
        }
    }
}
