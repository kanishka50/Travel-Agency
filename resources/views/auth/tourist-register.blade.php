@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-slate-900 flex">
    <!-- Left Side - Image & Branding -->
    <div class="hidden lg:flex lg:w-5/12 relative overflow-hidden">
        <!-- Background Image -->
        <div class="absolute inset-0">
            <img src="https://images.pexels.com/photos/1450353/pexels-photo-1450353.jpeg?auto=compress&cs=tinysrgb&w=1920"
                 alt="Sri Lanka Beach"
                 class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-transparent"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 flex flex-col justify-between p-12 w-full">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-2xl font-bold text-white">Travel<span class="text-amber-400">Agency</span></span>
            </a>

            <!-- Middle Content -->
            <div class="max-w-sm">
                <h1 class="text-4xl font-bold text-white mb-6 leading-tight">
                    Your Next <span class="text-transparent bg-clip-text bg-gradient-to-r from-amber-400 to-orange-400">Adventure</span> Awaits
                </h1>
                <p class="text-white/70 text-lg leading-relaxed mb-8">
                    Join thousands of travelers exploring Sri Lanka's hidden treasures with local experts.
                </p>

                <!-- Features -->
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-white/80">Verified local guides</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-white/80">Best price guarantee</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-white/80">24/7 support during trips</span>
                    </div>
                </div>
            </div>

            <!-- Bottom -->
            <div class="flex items-center gap-4 text-white/50 text-sm">
                <span>&copy; {{ date('Y') }} TravelAgency</span>
            </div>
        </div>
    </div>

    <!-- Right Side - Registration Form -->
    <div class="w-full lg:w-7/12 flex items-center justify-center p-6 lg:p-12 bg-white overflow-y-auto">
        <div class="w-full max-w-xl">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-6">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-3">
                    <div class="w-11 h-11 bg-gradient-to-br from-amber-400 to-orange-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-slate-900">Travel<span class="text-amber-500">Agency</span></span>
                </a>
            </div>

            <!-- Title -->
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-slate-900 mb-2">Create Your Account</h2>
                <p class="text-slate-500">Start your journey with us today!</p>
            </div>

            <!-- Success Message -->
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-emerald-700">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-sm text-red-800 mb-1">Please fix the following errors:</p>
                            <ul class="list-disc list-inside text-sm text-red-700 space-y-0.5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Registration Form -->
            <form method="POST" action="{{ route('tourist.register') }}" class="space-y-6">
                @csrf

                <!-- Personal Information Section -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Personal Information</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Full Name -->
                        <div>
                            <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required autofocus
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('full_name') border-red-400 bg-red-50 @enderror"
                                   placeholder="John Doe">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('email') border-red-400 bg-red-50 @enderror"
                                   placeholder="john@example.com">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('phone') border-red-400 bg-red-50 @enderror"
                                   placeholder="+1 234 567 8900">
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="country" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <input id="country" type="text" name="country" value="{{ old('country') }}" required
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('country') border-red-400 bg-red-50 @enderror"
                                   placeholder="United States">
                        </div>
                    </div>
                </div>

                <!-- Password Section -->
                <div class="pt-2">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">Security</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input id="password" type="password" name="password" required
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all @error('password') border-red-400 bg-red-50 @enderror"
                                   placeholder="Minimum 8 characters">
                            <p class="mt-1.5 text-xs text-slate-500">Must be at least 8 characters</p>
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                                   placeholder="Re-enter password">
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact Section -->
                <div class="pt-2">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Emergency Contact</h3>
                            <p class="text-xs text-slate-500">Optional, but recommended for safety</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <!-- Emergency Contact Name -->
                        <div>
                            <label for="emergency_contact_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Contact Name
                            </label>
                            <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                                   placeholder="Jane Doe">
                        </div>

                        <!-- Emergency Contact Phone -->
                        <div>
                            <label for="emergency_contact_phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                                Contact Phone
                            </label>
                            <input id="emergency_contact_phone" type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                                   class="block w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                                   placeholder="+1 234 567 8900">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-4 px-6 rounded-xl transition-all duration-300 flex items-center justify-center gap-2 shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 hover:-translate-y-0.5">
                        <span>Create Account</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </button>
                </div>

                <!-- Divider -->
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-slate-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-white text-slate-500">Already have an account?</span>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-semibold transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Sign in to your account
                    </a>
                </div>

                <!-- Guide Registration Link -->
                <div class="text-center pb-4">
                    <p class="text-sm text-slate-500">
                        Are you a tour guide?
                        <a href="{{ route('guide.register') }}" class="font-semibold text-slate-700 hover:text-amber-600 transition-colors">
                            Register as a Guide
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
