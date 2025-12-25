@extends('layouts.public')

@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ url('/') }}" class="text-gray-500 hover:text-emerald-600">Home</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('tour-packages.index') }}" class="text-gray-500 hover:text-emerald-600">Tour Packages</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li><a href="{{ route('tour-packages.show', $plan) }}" class="text-gray-500 hover:text-emerald-600">{{ Str::limit($plan->title, 20) }}</a></li>
                <li><span class="text-gray-400">/</span></li>
                <li class="text-gray-900 font-medium">Book</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Complete Your Booking</h1>
            <p class="text-gray-600">You're booking: <span class="font-semibold text-emerald-600">{{ $plan->title }}</span></p>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Start Date:</span>
                    <p class="font-semibold">{{ $startDate->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">End Date:</span>
                    <p class="font-semibold">{{ $endDate->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Duration:</span>
                    <p class="font-semibold">{{ $plan->num_days }} {{ Str::plural('day', $plan->num_days) }}</p>
                </div>
            </div>
        </div>

    <!-- Error Messages -->
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <p class="text-red-700 font-semibold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside text-red-600">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Multi-Step Form -->
    <div x-data="{
        currentStep: 1,
        numAdults: {{ old('num_adults', 1) }},
        numChildren: {{ old('num_children', 0) }},
        childrenAges: {{ json_encode(old('children_ages', [])) }},
        selectedAddons: {{ json_encode(old('selected_addons', [])) }},
        pricePerAdult: {{ $plan->price_per_adult }},
        pricePerChild: {{ $plan->price_per_child }},
        addons: {{ json_encode($plan->addons ?? []) }},

        get basePrice() {
            return (this.numAdults * this.pricePerAdult) + (this.numChildren * this.pricePerChild);
        },

        get addonsTotal() {
            let total = 0;
            this.selectedAddons.forEach(addon => {
                total += addon.price * addon.quantity;
            });
            return total;
        },

        get subtotal() {
            return this.basePrice + this.addonsTotal;
        },

        get platformFee() {
            return this.subtotal * 0.10;
        },

        get totalAmount() {
            return this.subtotal + this.platformFee;
        },

        updateChildrenAges() {
            while(this.childrenAges.length < this.numChildren) {
                this.childrenAges.push(0);
            }
            if(this.childrenAges.length > this.numChildren) {
                this.childrenAges = this.childrenAges.slice(0, this.numChildren);
            }
        },

        toggleAddon(addonId, addonName, addonPrice) {
            const index = this.selectedAddons.findIndex(a => a.addon_id === addonId);
            if(index >= 0) {
                this.selectedAddons.splice(index, 1);
            } else {
                this.selectedAddons.push({
                    addon_id: addonId,
                    name: addonName,
                    quantity: 1,
                    price: addonPrice
                });
            }
        },

        isAddonSelected(addonId) {
            return this.selectedAddons.some(a => a.addon_id === addonId);
        },

        getAddon(addonId) {
            return this.selectedAddons.find(a => a.addon_id === addonId);
        },

        nextStep() {
            this.currentStep++;
        },

        prevStep() {
            this.currentStep--;
        },

        canProceedStep1() {
            if(this.numAdults < 1) return false;
            if(this.numChildren < 0) return false;
            if(this.numChildren > 0 && this.childrenAges.length !== this.numChildren) return false;
            return true;
        }
    }" class="bg-white rounded-lg shadow-sm">

        <!-- Progress Steps -->
        <div class="border-b border-gray-200 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div :class="currentStep >= 1 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600'"
                         class="w-8 h-8 rounded-full flex items-center justify-center font-semibold">1</div>
                    <span :class="currentStep >= 1 ? 'text-gray-900 font-semibold' : 'text-gray-500'">Travelers</span>
                </div>

                <div class="flex-1 h-1 mx-4 bg-gray-200">
                    <div :class="currentStep >= 2 ? 'bg-emerald-600' : 'bg-gray-200'"
                         class="h-full transition-all duration-300"
                         :style="'width: ' + (currentStep >= 2 ? '100%' : '0%')"></div>
                </div>

                <div class="flex items-center space-x-2">
                    <div :class="currentStep >= 2 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600'"
                         class="w-8 h-8 rounded-full flex items-center justify-center font-semibold">2</div>
                    <span :class="currentStep >= 2 ? 'text-gray-900 font-semibold' : 'text-gray-500'">Add-ons</span>
                </div>

                <div class="flex-1 h-1 mx-4 bg-gray-200">
                    <div :class="currentStep >= 3 ? 'bg-emerald-600' : 'bg-gray-200'"
                         class="h-full transition-all duration-300"
                         :style="'width: ' + (currentStep >= 3 ? '100%' : '0%')"></div>
                </div>

                <div class="flex items-center space-x-2">
                    <div :class="currentStep >= 3 ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-600'"
                         class="w-8 h-8 rounded-full flex items-center justify-center font-semibold">3</div>
                    <span :class="currentStep >= 3 ? 'text-gray-900 font-semibold' : 'text-gray-500'">Review</span>
                </div>
            </div>
        </div>

        <form action="{{ route('tourist.bookings.store') }}" method="POST">
            @csrf
            <input type="hidden" name="plan_id" value="{{ $plan->id }}">
            <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">

            <!-- Step 1: Traveler Details -->
            <div x-show="currentStep === 1" class="p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Traveler Information</h2>

                <!-- Number of Adults -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Adults</label>
                    <input type="number"
                           name="num_adults"
                           x-model.number="numAdults"
                           min="1"
                           max="50"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                    <p class="mt-1 text-sm text-gray-500">Minimum 1 adult required</p>
                </div>

                <!-- Number of Children -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Number of Children (0-17 years)</label>
                    <input type="number"
                           name="num_children"
                           x-model.number="numChildren"
                           @input="updateChildrenAges()"
                           min="0"
                           max="50"
                           required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent">
                </div>

                <!-- Children Ages -->
                <div x-show="numChildren > 0" class="space-y-3">
                    <label class="block text-sm font-medium text-gray-700">Children's Ages</label>
                    <template x-for="(age, index) in childrenAges" :key="index">
                        <div class="flex items-center space-x-3">
                            <span class="text-sm text-gray-600 w-20">Child <span x-text="index + 1"></span>:</span>
                            <input type="number"
                                   :name="'children_ages[' + index + ']'"
                                   x-model.number="childrenAges[index]"
                                   min="0"
                                   max="17"
                                   required
                                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                                   placeholder="Age">
                            <span class="text-sm text-gray-500">years</span>
                        </div>
                    </template>
                </div>

                <!-- Special Requests / Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Special Requests or Notes (Optional)</label>
                    <textarea name="tourist_notes"
                              rows="4"
                              maxlength="1000"
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                              placeholder="Any dietary restrictions, accessibility needs, or special requests...">{{ old('tourist_notes') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">Maximum 1000 characters</p>
                </div>

                <!-- Price Summary -->
                <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200">
                    <h3 class="font-semibold text-gray-900 mb-2">Price Breakdown</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Adults (<span x-text="numAdults"></span> x $<span x-text="pricePerAdult.toFixed(2)"></span>):</span>
                            <span class="font-semibold">$<span x-text="(numAdults * pricePerAdult).toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between" x-show="numChildren > 0">
                            <span class="text-gray-600">Children (<span x-text="numChildren"></span> x $<span x-text="pricePerChild.toFixed(2)"></span>):</span>
                            <span class="font-semibold">$<span x-text="(numChildren * pricePerChild).toFixed(2)"></span></span>
                        </div>
                        <div class="border-t border-emerald-200 pt-1 mt-2">
                            <div class="flex justify-between font-semibold text-base">
                                <span>Base Price:</span>
                                <span>$<span x-text="basePrice.toFixed(2)"></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-end">
                    <button type="button"
                            @click="nextStep()"
                            :disabled="!canProceedStep1()"
                            :class="canProceedStep1() ? 'bg-emerald-600 hover:bg-emerald-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed'"
                            class="px-6 py-2 rounded-lg font-semibold transition-colors">
                        Continue to Add-ons
                    </button>
                </div>
            </div>

            <!-- Step 2: Add-ons -->
            <div x-show="currentStep === 2" class="p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Optional Add-ons</h2>

                @if($plan->addons && count($plan->addons) > 0)
                    <div class="space-y-4">
                        @foreach($plan->addons as $addon)
                            <div class="border border-gray-200 rounded-lg p-4 hover:border-emerald-300 transition-colors"
                                 :class="isAddonSelected({{ $addon->id }}) ? 'bg-emerald-50 border-emerald-300' : 'bg-white'">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="checkbox"
                                                   @change="toggleAddon({{ $addon->id }}, '{{ addslashes($addon->addon_name) }}', {{ $addon->price_per_person }})"
                                                   :checked="isAddonSelected({{ $addon->id }})"
                                                   class="w-5 h-5 text-emerald-600 rounded focus:ring-2 focus:ring-emerald-500">
                                            <div class="ml-3">
                                                <span class="font-semibold text-gray-900">{{ $addon->addon_name }}</span>
                                                @if($addon->addon_description)
                                                    <p class="text-sm text-gray-600 mt-1">{{ $addon->addon_description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                    <div class="ml-4 text-right">
                                        <p class="font-semibold text-gray-900">${{ number_format($addon->price_per_person, 2) }}</p>
                                        <p class="text-sm text-gray-500">per person</p>
                                    </div>
                                </div>

                                <!-- Quantity selector (shown when addon is selected) -->
                                <div x-show="isAddonSelected({{ $addon->id }})" class="mt-3 flex items-center space-x-3">
                                    <label class="text-sm text-gray-600">Quantity:</label>
                                    <input type="number"
                                           :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][quantity]'"
                                           x-model.number="getAddon({{ $addon->id }}).quantity"
                                           min="1"
                                           max="50"
                                           class="w-20 px-3 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-emerald-500">
                                    <input type="hidden"
                                           :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][addon_id]'"
                                           :value="{{ $addon->id }}">
                                    <input type="hidden"
                                           :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][name]'"
                                           value="{{ addslashes($addon->addon_name) }}">
                                    <input type="hidden"
                                           :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][price]'"
                                           :value="{{ $addon->price_per_person }}">
                                    <span class="text-sm text-gray-600">
                                        Total: $<span x-text="(getAddon({{ $addon->id }}).quantity * {{ $addon->price_per_person }}).toFixed(2)"></span>
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p>No add-ons available for this tour.</p>
                        <p class="text-sm mt-2">You can proceed to the next step.</p>
                    </div>
                @endif

                <!-- Add-ons Total -->
                <div class="bg-emerald-50 rounded-lg p-4 border border-emerald-200" x-show="selectedAddons.length > 0">
                    <div class="flex justify-between font-semibold">
                        <span>Add-ons Total:</span>
                        <span>$<span x-text="addonsTotal.toFixed(2)"></span></span>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between">
                    <button type="button"
                            @click="prevStep()"
                            class="px-6 py-2 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Back
                    </button>
                    <button type="button"
                            @click="nextStep()"
                            class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold transition-colors">
                        Continue to Review
                    </button>
                </div>
            </div>

            <!-- Step 3: Review & Submit -->
            <div x-show="currentStep === 3" class="p-6 space-y-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Review Your Booking</h2>

                <!-- Tour Details -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <h3 class="font-semibold text-gray-900">Tour Details</h3>
                    <div class="grid grid-cols-2 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Tour:</span>
                            <p class="font-semibold">{{ $plan->title }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Guide:</span>
                            <p class="font-semibold">{{ $plan->guide->user->name }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">Start Date:</span>
                            <p class="font-semibold">{{ $startDate->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <span class="text-gray-600">End Date:</span>
                            <p class="font-semibold">{{ $endDate->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Travelers Summary -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <h3 class="font-semibold text-gray-900">Travelers</h3>
                    <div class="text-sm space-y-1">
                        <p><span class="text-gray-600">Adults:</span> <span class="font-semibold" x-text="numAdults"></span></p>
                        <p x-show="numChildren > 0"><span class="text-gray-600">Children:</span> <span class="font-semibold" x-text="numChildren"></span></p>
                    </div>
                </div>

                <!-- Selected Add-ons -->
                <div class="bg-gray-50 rounded-lg p-4 space-y-2" x-show="selectedAddons.length > 0">
                    <h3 class="font-semibold text-gray-900">Selected Add-ons</h3>
                    <template x-for="addon in selectedAddons" :key="addon.addon_id">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600"><span x-text="addon.name"></span> (x<span x-text="addon.quantity"></span>)</span>
                            <span class="font-semibold">$<span x-text="(addon.price * addon.quantity).toFixed(2)"></span></span>
                        </div>
                    </template>
                </div>

                <!-- Final Price Breakdown -->
                <div class="bg-emerald-50 rounded-lg p-6 border-2 border-emerald-200 space-y-3">
                    <h3 class="font-semibold text-gray-900 text-lg">Price Summary</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Base Price:</span>
                            <span class="font-semibold">$<span x-text="basePrice.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between" x-show="addonsTotal > 0">
                            <span class="text-gray-600">Add-ons Total:</span>
                            <span class="font-semibold">$<span x-text="addonsTotal.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span class="font-semibold">$<span x-text="subtotal.toFixed(2)"></span></span>
                        </div>
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-500">Platform Fee (10%):</span>
                            <span class="text-gray-700">$<span x-text="platformFee.toFixed(2)"></span></span>
                        </div>
                        <div class="border-t-2 border-emerald-300 pt-2 mt-2">
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <span>Total Amount:</span>
                                <span class="text-emerald-600">$<span x-text="totalAmount.toFixed(2)"></span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms & Conditions -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <label class="flex items-start cursor-pointer">
                        <input type="checkbox"
                               name="agree_terms"
                               value="1"
                               required
                               class="w-5 h-5 text-emerald-600 rounded focus:ring-2 focus:ring-emerald-500 mt-1">
                        <div class="ml-3">
                            <span class="font-semibold text-gray-900">I agree to the terms and conditions</span>
                            <p class="text-sm text-gray-600 mt-1">
                                By checking this box, I acknowledge that I have read and agree to the booking terms,
                                cancellation policy, and privacy policy. I understand that this booking is subject to
                                guide approval and payment confirmation.
                            </p>
                        </div>
                    </label>
                </div>

                <!-- Navigation -->
                <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                    <button type="button"
                            @click="prevStep()"
                            class="px-6 py-2 border border-gray-300 rounded-lg font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
                        Back
                    </button>
                    <button type="submit"
                            class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-lg transition-colors shadow-lg hover:shadow-xl">
                        Confirm Booking
                    </button>
                </div>

                <p class="text-xs text-center text-gray-500">
                    After confirmation, you'll receive booking details via email and can proceed to payment.
                </p>
            </div>
        </form>
    </div>
    </div>
</div>
@endsection
