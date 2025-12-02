<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tourist Registration - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-2xl">
            <!-- Logo and Header -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <h1 class="text-4xl font-bold text-blue-600">TravelAgency</h1>
                    <p class="text-gray-600 text-sm mt-1">Discover Your Next Adventure</p>
                </a>
            </div>

            <!-- Registration Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <!-- Title -->
                <div class="text-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-900">Join as a Tourist</h2>
                    <p class="text-gray-600 mt-2">Start your journey with us today!</p>
                </div>

                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800">{{ session('success') }}</p>
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="font-medium text-sm text-red-800 mb-2">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Registration Form -->
                <form method="POST" action="{{ route('tourist.register') }}" class="space-y-5">
                    @csrf

                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Full Name -->
                            <div>
                                <label for="full_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input id="full_name" type="text" name="full_name" value="{{ old('full_name') }}" required autofocus
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('full_name') border-red-500 @enderror"
                                       placeholder="John Doe">
                                @error('full_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                                       placeholder="john@example.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('phone') border-red-500 @enderror"
                                       placeholder="+1 234 567 8900">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input id="country" type="text" name="country" value="{{ old('country') }}" required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('country') border-red-500 @enderror"
                                       placeholder="United States">
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="border-b border-gray-200 pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Security</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Password -->
                            <div>
                                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password" type="password" name="password" required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                                       placeholder="Minimum 8 characters">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @else
                                    <p class="mt-1 text-sm text-gray-500">Must be at least 8 characters</p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <input id="password_confirmation" type="password" name="password_confirmation" required
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Re-enter password">
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact Section -->
                    <div class="pb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Emergency Contact</h3>
                        <p class="text-sm text-gray-600 mb-4">Optional, but recommended for your safety</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Emergency Contact Name -->
                            <div>
                                <label for="emergency_contact_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Contact Name
                                </label>
                                <input id="emergency_contact_name" type="text" name="emergency_contact_name" value="{{ old('emergency_contact_name') }}"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Jane Doe">
                            </div>

                            <!-- Emergency Contact Phone -->
                            <div>
                                <label for="emergency_contact_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Contact Phone
                                </label>
                                <input id="emergency_contact_phone" type="tel" name="emergency_contact_phone" value="{{ old('emergency_contact_phone') }}"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="+1 234 567 8900">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-4">
                        <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center shadow-lg shadow-blue-500/50">
                            <span>Create Account</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Already Registered -->
                    <div class="text-center pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Already have an account?
                            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700">
                                Sign in here
                            </a>
                        </p>
                    </div>

                    <!-- Guide Registration Link -->
                    <div class="text-center pt-2">
                        <p class="text-sm text-gray-600">
                            Are you a tour guide?
                            <a href="{{ route('guide.register') }}" class="font-semibold text-green-600 hover:text-green-700">
                                Register as a Guide
                            </a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="/" class="text-sm text-gray-600 hover:text-gray-900 inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Homepage
                </a>
            </div>
        </div>
    </div>
</body>
</html>
