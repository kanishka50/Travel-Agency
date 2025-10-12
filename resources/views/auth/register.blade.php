<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Error Display (if registration fails) -->
        @if ($errors->has('error'))
            <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded">
                {{ $errors->first('error') }}
            </div>
        @endif

        <!-- Full Name -->
        <div>
            <x-input-label for="full_name" :value="__('Full Name')" />
            <x-text-input 
                id="full_name" 
                class="block mt-1 w-full" 
                type="text" 
                name="full_name" 
                :value="old('full_name')" 
                required 
                autofocus 
                autocomplete="name" />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input 
                id="email" 
                class="block mt-1 w-full" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div>
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input 
                id="phone" 
                class="block mt-1 w-full" 
                type="tel" 
                name="phone" 
                :value="old('phone')" 
                required 
                placeholder="+1234567890" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Country -->
        <div>
            <x-input-label for="country" :value="__('Country')" />
            <select 
                id="country" 
                name="country" 
                class="block mt-1 w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" 
                required>
                <option value="">Select your country</option>
                <option value="United States" {{ old('country') == 'United States' ? 'selected' : '' }}>United States</option>
                <option value="United Kingdom" {{ old('country') == 'United Kingdom' ? 'selected' : '' }}>United Kingdom</option>
                <option value="Canada" {{ old('country') == 'Canada' ? 'selected' : '' }}>Canada</option>
                <option value="Australia" {{ old('country') == 'Australia' ? 'selected' : '' }}>Australia</option>
                <option value="Germany" {{ old('country') == 'Germany' ? 'selected' : '' }}>Germany</option>
                <option value="France" {{ old('country') == 'France' ? 'selected' : '' }}>France</option>
                <option value="India" {{ old('country') == 'India' ? 'selected' : '' }}>India</option>
                <option value="China" {{ old('country') == 'China' ? 'selected' : '' }}>China</option>
                <option value="Japan" {{ old('country') == 'Japan' ? 'selected' : '' }}>Japan</option>
                <option value="Singapore" {{ old('country') == 'Singapore' ? 'selected' : '' }}>Singapore</option>
                <option value="Netherlands" {{ old('country') == 'Netherlands' ? 'selected' : '' }}>Netherlands</option>
                <option value="Other" {{ old('country') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input 
                id="password" 
                class="block mt-1 w-full"
                type="password"
                name="password"
                required 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input 
                id="password_confirmation" 
                class="block mt-1 w-full"
                type="password"
                name="password_confirmation" 
                required 
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Emergency Contact Section (Optional) -->
        <div class="pt-6 border-t border-gray-200">
            <h3 class="text-sm font-medium text-gray-700 mb-4">Emergency Contact (Optional)</h3>
            
            <div class="space-y-4">
                <div>
                    <x-input-label for="emergency_contact_name" :value="__('Emergency Contact Name')" />
                    <x-text-input 
                        id="emergency_contact_name" 
                        class="block mt-1 w-full" 
                        type="text" 
                        name="emergency_contact_name" 
                        :value="old('emergency_contact_name')" />
                    <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="emergency_contact_phone" :value="__('Emergency Contact Phone')" />
                    <x-text-input 
                        id="emergency_contact_phone" 
                        class="block mt-1 w-full" 
                        type="tel" 
                        name="emergency_contact_phone" 
                        :value="old('emergency_contact_phone')" 
                        placeholder="+1234567890" />
                    <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end mt-6 pt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" 
               href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>