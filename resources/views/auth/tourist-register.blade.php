<x-guest-layout>
    <div class="mb-4">
        <h2 class="text-2xl font-bold text-gray-800 text-center">Tourist Registration</h2>
        <p class="text-gray-600 text-center mt-2">Join us and start your adventure!</p>
    </div>

    <!-- Session Status -->
    @if (session('success'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('success') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="mb-4">
            <div class="font-medium text-sm text-red-600">
                Whoops! Something went wrong.
            </div>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tourist.register') }}">
        @csrf

        <!-- Full Name -->
        <div>
            <x-input-label for="full_name" :value="__('Full Name')" />
            <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name')" required autofocus />
            <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <p class="mt-1 text-sm text-gray-500">Minimum 8 characters</p>
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Phone -->
        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone Number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <!-- Country -->
        <div class="mt-4">
            <x-input-label for="country" :value="__('Country')" />
            <x-text-input id="country" class="block mt-1 w-full" type="text" name="country" :value="old('country')" required />
            <x-input-error :messages="$errors->get('country')" class="mt-2" />
        </div>

        <!-- Emergency Contact Section -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Emergency Contact (Optional)</h3>

            <!-- Emergency Contact Name -->
            <div class="mt-4">
                <x-input-label for="emergency_contact_name" :value="__('Emergency Contact Name')" />
                <x-text-input id="emergency_contact_name" class="block mt-1 w-full" type="text" name="emergency_contact_name" :value="old('emergency_contact_name')" />
                <x-input-error :messages="$errors->get('emergency_contact_name')" class="mt-2" />
            </div>

            <!-- Emergency Contact Phone -->
            <div class="mt-4">
                <x-input-label for="emergency_contact_phone" :value="__('Emergency Contact Phone')" />
                <x-text-input id="emergency_contact_phone" class="block mt-1 w-full" type="tel" name="emergency_contact_phone" :value="old('emergency_contact_phone')" />
                <x-input-error :messages="$errors->get('emergency_contact_phone')" class="mt-2" />
            </div>
        </div>

        <div class="flex items-center justify-between mt-6">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ml-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>

        <!-- Guide Registration Link -->
        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
            <p class="text-sm text-gray-600">
                Are you a tour guide?
                <a href="{{ route('guide.register') }}" class="underline text-indigo-600 hover:text-indigo-900">
                    Register as a Guide
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
