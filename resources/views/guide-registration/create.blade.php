<x-guest-layout>
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Become a Tour Guide</h1>
        
        <p class="mb-6 text-gray-600">
            Join our platform and connect with tourists from around the world. 
            Fill out the registration form below and our team will review your application.
        </p>

        @if ($errors->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded mb-6">
                {{ $errors->first('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('guide-registration.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Personal Information Section -->
            <div class="bg-white p-6 rounded border">
                <h2 class="text-lg font-medium mb-4">Personal Information</h2>

                <div class="space-y-4">
                    <!-- Full Name -->
                    <div>
                        <x-input-label for="full_name" :value="__('Full Name *')" />
                        <x-text-input 
                            id="full_name" 
                            class="block mt-1 w-full" 
                            type="text" 
                            name="full_name" 
                            :value="old('full_name')" 
                            required />
                        <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email Address *')" />
                        <x-text-input 
                            id="email" 
                            class="block mt-1 w-full" 
                            type="email" 
                            name="email" 
                            :value="old('email')" 
                            required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-input-label for="phone" :value="__('Phone Number *')" />
                        <x-text-input 
                            id="phone" 
                            class="block mt-1 w-full" 
                            type="tel" 
                            name="phone" 
                            :value="old('phone')" 
                            placeholder="+94771234567"
                            required />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- National ID -->
                    <div>
                        <x-input-label for="national_id" :value="__('National ID Number *')" />
                        <x-text-input 
                            id="national_id" 
                            class="block mt-1 w-full" 
                            type="text" 
                            name="national_id" 
                            :value="old('national_id')" 
                            required />
                        <x-input-error :messages="$errors->get('national_id')" class="mt-2" />
                    </div>

                    <!-- Years of Experience -->
                    <div>
                        <x-input-label for="years_experience" :value="__('Years of Guiding Experience *')" />
                        <x-text-input 
                            id="years_experience" 
                            class="block mt-1 w-full" 
                            type="number" 
                            name="years_experience" 
                            :value="old('years_experience')" 
                            min="0"
                            max="50"
                            required />
                        <x-input-error :messages="$errors->get('years_experience')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Professional Information Section -->
            <div class="bg-white p-6 rounded border">
                <h2 class="text-lg font-medium mb-4">Professional Information</h2>

                <div class="space-y-4">
                    <!-- Languages Spoken -->
                    <div>
                        <x-input-label for="languages" :value="__('Languages Spoken * (Select all that apply)')" />
                        <div class="mt-2 space-y-2">
                            @foreach(['English', 'Sinhala', 'Tamil', 'German', 'French', 'Spanish', 'Japanese', 'Chinese', 'Arabic', 'Italian'] as $language)
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="languages[]" 
                                        value="{{ $language }}"
                                        {{ in_array($language, old('languages', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2">{{ $language }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('languages')" class="mt-2" />
                    </div>

                    <!-- Expertise Areas -->
                    <div>
                        <x-input-label for="expertise_areas" :value="__('Areas of Expertise * (Select all that apply)')" />
                        <div class="mt-2 space-y-2">
                            @foreach(['Cultural Sites', 'Wildlife', 'Adventure', 'Beaches', 'Hill Country', 'Tea Plantations', 'Religious Sites', 'Photography Tours', 'Food Tours', 'Wellness & Ayurveda'] as $area)
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="expertise_areas[]" 
                                        value="{{ $area }}"
                                        {{ in_array($area, old('expertise_areas', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2">{{ $area }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('expertise_areas')" class="mt-2" />
                    </div>

                    <!-- Regions Can Guide -->
                    <div>
                        <x-input-label for="regions_can_guide" :value="__('Regions You Can Guide * (Select all that apply)')" />
                        <div class="mt-2 space-y-2">
                            @foreach(['Colombo', 'Kandy', 'Nuwara Eliya', 'Ella', 'Sigiriya', 'Galle', 'Yala', 'Arugam Bay', 'Trincomalee', 'Jaffna', 'Anuradhapura', 'Polonnaruwa'] as $region)
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        name="regions_can_guide[]" 
                                        value="{{ $region }}"
                                        {{ in_array($region, old('regions_can_guide', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2">{{ $region }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('regions_can_guide')" class="mt-2" />
                    </div>

                    <!-- Experience Description -->
                    <div>
                        <x-input-label for="experience_description" :value="__('Brief Description of Your Experience (Optional)')" />
                        <textarea 
                            id="experience_description" 
                            name="experience_description" 
                            rows="4"
                            maxlength="1000"
                            class="block mt-1 w-full border-gray-300 rounded-md shadow-sm"
                            placeholder="Tell us about your guiding experience, notable tours, or special skills...">{{ old('experience_description') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">Maximum 1000 characters</p>
                        <x-input-error :messages="$errors->get('experience_description')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-white p-6 rounded border">
                <h2 class="text-lg font-medium mb-4">Documents & Photos</h2>

                <div class="space-y-4">
                    <!-- Profile Photo -->
                    <div>
                        <x-input-label for="profile_photo" :value="__('Profile Photo * (Max 5MB)')" />
                        <input 
                            type="file" 
                            id="profile_photo" 
                            name="profile_photo" 
                            accept="image/jpeg,image/jpg,image/png"
                            required
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                    </div>

                    <!-- National ID Document -->
                    <div>
                        <x-input-label for="national_id_doc" :value="__('National ID Copy (PDF/Image, Max 10MB)')" />
                        <input 
                            type="file" 
                            id="national_id_doc" 
                            name="national_id_doc" 
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('national_id_doc')" class="mt-2" />
                    </div>

                    <!-- Driving License -->
                    <div>
                        <x-input-label for="driving_license" :value="__('Driving License (PDF/Image, Max 10MB)')" />
                        <input 
                            type="file" 
                            id="driving_license" 
                            name="driving_license" 
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('driving_license')" class="mt-2" />
                    </div>

                    <!-- Guide Certificate -->
                    <div>
                        <x-input-label for="guide_certificate" :value="__('Tourism Authority Guide Certificate (PDF/Image, Max 10MB)')" />
                        <input 
                            type="file" 
                            id="guide_certificate" 
                            name="guide_certificate" 
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('guide_certificate')" class="mt-2" />
                    </div>

                    <!-- Language Qualification -->
                    <div>
                        <x-input-label for="language_qualification" :value="__('Language Qualification Certificates (PDF/Image, Max 10MB)')" />
                        <input 
                            type="file" 
                            id="language_qualification" 
                            name="language_qualification" 
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('language_qualification')" class="mt-2" />
                    </div>

                    <!-- Vehicle Photo -->
                    <div>
                        <x-input-label for="vehicle_photo" :value="__('Vehicle Photo (If Applicable, Max 5MB)')" />
                        <input 
                            type="file" 
                            id="vehicle_photo" 
                            name="vehicle_photo" 
                            accept="image/jpeg,image/jpg,image/png"
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('vehicle_photo')" class="mt-2" />
                    </div>

                    <!-- Vehicle License -->
                    <div>
                        <x-input-label for="vehicle_license" :value="__('Vehicle Registration/License (PDF/Image, Max 10MB)')" />
                        <input 
                            type="file" 
                            id="vehicle_license" 
                            name="vehicle_license" 
                            accept=".pdf,image/jpeg,image/jpg,image/png"
                            class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100" />
                        <x-input-error :messages="$errors->get('vehicle_license')" class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end">
                <x-primary-button class="px-6 py-3">
                    {{ __('Submit Registration Request') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>