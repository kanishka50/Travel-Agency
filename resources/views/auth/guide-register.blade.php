@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-slate-50">
    <!-- Hero Header -->
    <div class="bg-slate-900 py-12 relative overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-72 h-72 bg-orange-500/10 rounded-full blur-3xl"></div>
        </div>
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-400 to-orange-500 rounded-2xl mb-6 shadow-lg shadow-amber-500/25">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Become a Tour Guide</h1>
            <p class="text-slate-400 max-w-2xl mx-auto">
                Join our platform and connect with tourists from around the world.
                Fill out the registration form below and our team will review your application.
            </p>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 -mt-6">
        <!-- Error Messages -->
        @if ($errors->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-center gap-3">
                    <svg class="h-5 w-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-sm text-red-700">{{ $errors->first('error') }}</p>
                </div>
            </div>
        @endif

        @if($errors->any() && !$errors->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="h-5 w-5 text-red-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h3 class="text-red-800 font-semibold mb-1">Please correct the following errors:</h3>
                        <ul class="list-disc list-inside text-red-700 text-sm space-y-0.5">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('guide-registration.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Personal Information Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <span class="flex items-center justify-center w-10 h-10 bg-amber-100 text-amber-600 rounded-xl font-bold">1</span>
                    <h2 class="text-xl font-bold text-slate-900">Personal Information</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Full Name -->
                    <div>
                        <label for="full_name" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="full_name"
                            name="full_name"
                            value="{{ old('full_name') }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="Enter your full name">
                        @error('full_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="your@email.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="+94771234567"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- National ID -->
                    <div>
                        <label for="national_id" class="block text-sm font-medium text-slate-700 mb-1.5">
                            National ID Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="national_id"
                            name="national_id"
                            value="{{ old('national_id') }}"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="Enter your NIC number">
                        @error('national_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Years of Experience -->
                    <div>
                        <label for="years_experience" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Years of Guiding Experience <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            id="years_experience"
                            name="years_experience"
                            value="{{ old('years_experience') }}"
                            min="0"
                            max="50"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="0">
                        @error('years_experience')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guide Type -->
                    <div>
                        <label for="guide_type" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Guide Type <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="guide_type"
                            name="guide_type"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all">
                            <option value="">Select Guide Type</option>
                            @foreach(\App\Models\Guide::GUIDE_TYPES as $value => $label)
                                <option value="{{ $value }}" {{ old('guide_type') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('guide_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <span class="flex items-center justify-center w-10 h-10 bg-amber-100 text-amber-600 rounded-xl font-bold">2</span>
                    <h2 class="text-xl font-bold text-slate-900">Professional Information</h2>
                </div>

                <div class="space-y-4">
                    <!-- Languages Spoken -->
                    <div>
                        <label for="languages-select" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Languages Spoken <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="languages-select"
                            name="languages[]"
                            multiple
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="Search and select languages...">
                            @foreach(config('languages') as $value => $label)
                                <option value="{{ $value }}" {{ in_array($value, old('languages', [])) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Type to search from 100+ languages</p>
                        @error('languages')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Expertise Areas -->
                    <div>
                        <label for="expertise-areas-select" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Areas of Expertise <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <select
                            id="expertise-areas-select"
                            name="expertise_areas[]"
                            multiple
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="Search and select expertise areas...">
                            @foreach(config('expertise_areas') as $value => $label)
                                <option value="{{ $value }}" {{ in_array($value, old('expertise_areas', [])) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Type to search from 100+ expertise areas</p>
                        @error('expertise_areas')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Regions Can Guide -->
                    <div>
                        <label for="regions-select" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Regions You Can Guide <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="regions-select"
                            name="regions_can_guide[]"
                            multiple
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="Search and select regions...">
                            @foreach($regions as $value => $label)
                                <option value="{{ $value }}" {{ in_array($value, old('regions_can_guide', [])) ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-slate-500 mt-1">Type to search from 60+ tourist destinations across Sri Lanka</p>
                        @error('regions_can_guide')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Experience Description -->
                    <div>
                        <label for="experience_description" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Brief Description of Your Experience <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="experience_description"
                            name="experience_description"
                            rows="4"
                            minlength="50"
                            maxlength="1000"
                            required
                            class="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-500 transition-all"
                            placeholder="Tell us about your guiding experience, notable tours, or special skills...">{{ old('experience_description') }}</textarea>
                        <p class="text-xs text-slate-500 mt-1">Minimum 50 characters, maximum 1000 characters</p>
                        @error('experience_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-6">
                    <span class="flex items-center justify-center w-10 h-10 bg-amber-100 text-amber-600 rounded-xl font-bold">3</span>
                    <h2 class="text-xl font-bold text-slate-900">Documents & Photos</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Profile Photo -->
                    <div class="md:col-span-2">
                        <label for="profile_photo" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Profile Photo <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="file"
                            id="profile_photo"
                            name="profile_photo"
                            accept="image/jpeg,image/jpg,image/png"
                            required
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100 file:font-medium file:cursor-pointer file:transition-colors">
                        <p class="text-xs text-slate-500 mt-1">Max 5MB, JPG or PNG format</p>
                        @error('profile_photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- National ID Document -->
                    <div>
                        <label for="national_id_doc" class="block text-sm font-medium text-slate-700 mb-1.5">
                            National ID Copy <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input
                            type="file"
                            id="national_id_doc"
                            name="national_id_doc"
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:font-medium file:cursor-pointer file:transition-colors">
                        <p class="text-xs text-slate-500 mt-1">PDF or Image, Max 10MB</p>
                        @error('national_id_doc')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Driving License -->
                    <div>
                        <label for="driving_license" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Driving License <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input
                            type="file"
                            id="driving_license"
                            name="driving_license"
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:font-medium file:cursor-pointer file:transition-colors">
                        <p class="text-xs text-slate-500 mt-1">PDF or Image, Max 10MB</p>
                        @error('driving_license')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guide Certificate -->
                    <div>
                        <label for="guide_certificate" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Tourism Authority Certificate <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input
                            type="file"
                            id="guide_certificate"
                            name="guide_certificate"
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:font-medium file:cursor-pointer file:transition-colors">
                        <p class="text-xs text-slate-500 mt-1">PDF or Image, Max 10MB</p>
                        @error('guide_certificate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Language Qualification -->
                    <div>
                        <label for="language_qualification" class="block text-sm font-medium text-slate-700 mb-1.5">
                            Language Qualification <span class="text-slate-400 text-xs">(Optional)</span>
                        </label>
                        <input
                            type="file"
                            id="language_qualification"
                            name="language_qualification"
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 file:font-medium file:cursor-pointer file:transition-colors">
                        <p class="text-xs text-slate-500 mt-1">PDF or Image, Max 10MB</p>
                        @error('language_qualification')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-amber-800">
                            <strong>Note:</strong> Vehicle information can be added later from your guide dashboard after registration is approved.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit"
                            class="flex-1 px-6 py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl hover:from-amber-600 hover:to-orange-600 font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all text-center">
                        Submit Registration Request
                    </button>
                    <a href="{{ url('/') }}"
                       class="px-6 py-4 bg-slate-100 text-slate-700 rounded-xl hover:bg-slate-200 font-semibold transition-colors text-center">
                        Cancel
                    </a>
                </div>
                <p class="text-xs text-slate-500 text-center mt-4">
                    By submitting this form, you agree to our
                    <a href="{{ route('terms') }}" class="text-amber-600 hover:underline">Terms of Service</a> and
                    <a href="{{ route('privacy-policy') }}" class="text-amber-600 hover:underline">Privacy Policy</a>.
                </p>
            </div>
        </form>

        <!-- Already have an account? -->
        <div class="text-center mt-8 pb-8">
            <p class="text-slate-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold">Sign in</a>
            </p>
        </div>
    </div>
</div>
@endsection
