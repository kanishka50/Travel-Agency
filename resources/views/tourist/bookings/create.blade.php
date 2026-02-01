@extends('layouts.dashboard')

@section('page-title', 'Complete Your Booking')

@section('content')
<!-- Back Button -->
<div class="mb-6">
    <a href="{{ route('tour-packages.show', $plan) }}" class="inline-flex items-center text-slate-600 hover:text-amber-600 transition-colors group">
        <svg class="w-5 h-5 mr-2 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Tour Package
    </a>
</div>

<!-- Header -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex items-center gap-4 mb-4">
        <div class="w-12 h-12 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Complete Your Booking</h1>
            <p class="text-slate-500">You're booking: <span class="font-semibold text-amber-600">{{ $plan->title }}</span></p>
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
        <div class="bg-slate-50 rounded-xl p-3">
            <span class="text-slate-500">Start Date:</span>
            <p class="font-semibold text-slate-900">{{ $startDate->format('M d, Y') }}</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-3">
            <span class="text-slate-500">End Date:</span>
            <p class="font-semibold text-slate-900">{{ $endDate->format('M d, Y') }}</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-3">
            <span class="text-slate-500">Duration:</span>
            <p class="font-semibold text-slate-900">{{ $plan->num_days }} {{ Str::plural('day', $plan->num_days) }}</p>
        </div>
    </div>
</div>

<!-- Error Messages -->
@if(session('error'))
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    </div>
@endif

@if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <p class="text-red-700 font-semibold mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-red-600 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
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
}" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

    <!-- Progress Steps -->
    <div class="border-b border-slate-200 px-6 py-4 bg-slate-50">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div :class="currentStep >= 1 ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/25' : 'bg-slate-200 text-slate-600'"
                     class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-all duration-300">1</div>
                <span :class="currentStep >= 1 ? 'text-slate-900 font-semibold' : 'text-slate-500'" class="hidden sm:inline">Travelers</span>
            </div>

            <div class="flex-1 h-1 mx-4 bg-slate-200 rounded-full overflow-hidden">
                <div :class="currentStep >= 2 ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 'bg-slate-200'"
                     class="h-full transition-all duration-500"
                     :style="'width: ' + (currentStep >= 2 ? '100%' : '0%')"></div>
            </div>

            <div class="flex items-center space-x-2">
                <div :class="currentStep >= 2 ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/25' : 'bg-slate-200 text-slate-600'"
                     class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-all duration-300">2</div>
                <span :class="currentStep >= 2 ? 'text-slate-900 font-semibold' : 'text-slate-500'" class="hidden sm:inline">Add-ons</span>
            </div>

            <div class="flex-1 h-1 mx-4 bg-slate-200 rounded-full overflow-hidden">
                <div :class="currentStep >= 3 ? 'bg-gradient-to-r from-amber-500 to-orange-500' : 'bg-slate-200'"
                     class="h-full transition-all duration-500"
                     :style="'width: ' + (currentStep >= 3 ? '100%' : '0%')"></div>
            </div>

            <div class="flex items-center space-x-2">
                <div :class="currentStep >= 3 ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/25' : 'bg-slate-200 text-slate-600'"
                     class="w-8 h-8 rounded-full flex items-center justify-center font-semibold transition-all duration-300">3</div>
                <span :class="currentStep >= 3 ? 'text-slate-900 font-semibold' : 'text-slate-500'" class="hidden sm:inline">Review</span>
            </div>
        </div>
    </div>

    <form action="{{ route('tourist.bookings.store') }}" method="POST">
        @csrf
        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
        <input type="hidden" name="start_date" value="{{ $startDate->format('Y-m-d') }}">

        <!-- Step 1: Traveler Details -->
        <div x-show="currentStep === 1" class="p-6 space-y-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Traveler Information</h2>
                    <p class="text-sm text-slate-500">Enter details about the travelers</p>
                </div>
            </div>

            <!-- Number of Adults -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Number of Adults *</label>
                <input type="number"
                       name="num_adults"
                       x-model.number="numAdults"
                       min="1"
                       max="50"
                       required
                       class="w-full rounded-xl border-slate-200 focus:ring-amber-500 focus:border-amber-500">
                <p class="mt-1 text-sm text-slate-500">Minimum 1 adult required</p>
            </div>

            <!-- Number of Children -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Number of Children (0-17 years)</label>
                <input type="number"
                       name="num_children"
                       x-model.number="numChildren"
                       @input="updateChildrenAges()"
                       min="0"
                       max="50"
                       required
                       class="w-full rounded-xl border-slate-200 focus:ring-amber-500 focus:border-amber-500">
            </div>

            <!-- Children Ages -->
            <div x-show="numChildren > 0" class="space-y-3">
                <label class="block text-sm font-semibold text-slate-700">Children's Ages</label>
                <template x-for="(age, index) in childrenAges" :key="index">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-slate-600 w-20">Child <span x-text="index + 1"></span>:</span>
                        <input type="number"
                               :name="'children_ages[' + index + ']'"
                               x-model.number="childrenAges[index]"
                               min="0"
                               max="17"
                               required
                               class="flex-1 rounded-xl border-slate-200 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="Age">
                        <span class="text-sm text-slate-500">years</span>
                    </div>
                </template>
            </div>

            <!-- Special Requests / Notes -->
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-2">Special Requests or Notes (Optional)</label>
                <textarea name="tourist_notes"
                          rows="4"
                          maxlength="1000"
                          class="w-full rounded-xl border-slate-200 focus:ring-amber-500 focus:border-amber-500"
                          placeholder="Any dietary restrictions, accessibility needs, or special requests...">{{ old('tourist_notes') }}</textarea>
                <p class="mt-1 text-sm text-slate-500">Maximum 1000 characters</p>
            </div>

            <!-- Price Summary -->
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                <h3 class="font-semibold text-slate-900 mb-3">Price Breakdown</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Adults (<span x-text="numAdults"></span> x $<span x-text="pricePerAdult.toFixed(2)"></span>):</span>
                        <span class="font-semibold text-slate-900">$<span x-text="(numAdults * pricePerAdult).toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between" x-show="numChildren > 0">
                        <span class="text-slate-600">Children (<span x-text="numChildren"></span> x $<span x-text="pricePerChild.toFixed(2)"></span>):</span>
                        <span class="font-semibold text-slate-900">$<span x-text="(numChildren * pricePerChild).toFixed(2)"></span></span>
                    </div>
                    <div class="border-t border-amber-200 pt-2 mt-2">
                        <div class="flex justify-between font-semibold text-base">
                            <span class="text-slate-900">Base Price:</span>
                            <span class="text-amber-600">$<span x-text="basePrice.toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-end pt-4">
                <button type="button"
                        @click="nextStep()"
                        :disabled="!canProceedStep1()"
                        :class="canProceedStep1() ? 'bg-gradient-to-r from-amber-500 to-orange-500 text-white shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40' : 'bg-slate-200 text-slate-500 cursor-not-allowed'"
                        class="px-6 py-3 rounded-xl font-semibold transition-all duration-300">
                    Continue to Add-ons
                </button>
            </div>
        </div>

        <!-- Step 2: Add-ons -->
        <div x-show="currentStep === 2" class="p-6 space-y-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Optional Add-ons</h2>
                    <p class="text-sm text-slate-500">Enhance your experience with these extras</p>
                </div>
            </div>

            @if($plan->addons && count($plan->addons) > 0)
                <div class="space-y-4">
                    @foreach($plan->addons as $addon)
                        <div class="border border-slate-200 rounded-xl p-4 hover:border-amber-300 transition-colors cursor-pointer"
                             :class="isAddonSelected({{ $addon->id }}) ? 'bg-amber-50 border-amber-300' : 'bg-white'">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="checkbox"
                                               @change="toggleAddon({{ $addon->id }}, '{{ addslashes($addon->addon_name) }}', {{ $addon->price_per_person }})"
                                               :checked="isAddonSelected({{ $addon->id }})"
                                               class="w-5 h-5 text-amber-600 rounded focus:ring-2 focus:ring-amber-500 border-slate-300">
                                        <div class="ml-3">
                                            <span class="font-semibold text-slate-900">{{ $addon->addon_name }}</span>
                                            @if($addon->addon_description)
                                                <p class="text-sm text-slate-600 mt-1">{{ $addon->addon_description }}</p>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                                <div class="ml-4 text-right">
                                    <p class="font-semibold text-amber-600">${{ number_format($addon->price_per_person, 2) }}</p>
                                    <p class="text-sm text-slate-500">per person</p>
                                </div>
                            </div>

                            <!-- Quantity selector (shown when addon is selected) -->
                            <div x-show="isAddonSelected({{ $addon->id }})" class="mt-3 flex items-center space-x-3 pt-3 border-t border-amber-200">
                                <label class="text-sm text-slate-600">Quantity:</label>
                                <input type="number"
                                       :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][quantity]'"
                                       x-model.number="getAddon({{ $addon->id }}).quantity"
                                       min="1"
                                       max="50"
                                       class="w-20 px-3 py-1.5 rounded-lg border-slate-200 focus:ring-amber-500 focus:border-amber-500 text-center">
                                <input type="hidden"
                                       :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][addon_id]'"
                                       :value="{{ $addon->id }}">
                                <input type="hidden"
                                       :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][name]'"
                                       value="{{ addslashes($addon->addon_name) }}">
                                <input type="hidden"
                                       :name="'selected_addons[' + selectedAddons.findIndex(a => a.addon_id === {{ $addon->id }}) + '][price]'"
                                       :value="{{ $addon->price_per_person }}">
                                <span class="text-sm font-semibold text-amber-600">
                                    Total: $<span x-text="(getAddon({{ $addon->id }}).quantity * {{ $addon->price_per_person }}).toFixed(2)"></span>
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gradient-to-br from-slate-100 to-slate-200 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 mb-2">No add-ons available</h3>
                    <p class="text-slate-500">This tour doesn't have any optional add-ons. You can proceed to the next step.</p>
                </div>
            @endif

            <!-- Add-ons Total -->
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-200" x-show="selectedAddons.length > 0">
                <div class="flex justify-between font-semibold">
                    <span class="text-slate-900">Add-ons Total:</span>
                    <span class="text-amber-600">$<span x-text="addonsTotal.toFixed(2)"></span></span>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between pt-4">
                <button type="button"
                        @click="prevStep()"
                        class="px-6 py-3 border border-slate-200 rounded-xl font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Back
                </button>
                <button type="button"
                        @click="nextStep()"
                        class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                    Continue to Review
                </button>
            </div>
        </div>

        <!-- Step 3: Review & Submit -->
        <div x-show="currentStep === 3" class="p-6 space-y-6">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Review Your Booking</h2>
                    <p class="text-sm text-slate-500">Please verify all details before confirming</p>
                </div>
            </div>

            <!-- Tour Details -->
            <div class="bg-slate-50 rounded-xl p-4 space-y-3">
                <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Tour Details
                </h3>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <span class="text-slate-500">Tour:</span>
                        <p class="font-semibold text-slate-900">{{ $plan->title }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500">Guide:</span>
                        <p class="font-semibold text-slate-900">{{ $plan->guide->user->name }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500">Start Date:</span>
                        <p class="font-semibold text-slate-900">{{ $startDate->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-slate-500">End Date:</span>
                        <p class="font-semibold text-slate-900">{{ $endDate->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Travelers Summary -->
            <div class="bg-slate-50 rounded-xl p-4 space-y-2">
                <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Travelers
                </h3>
                <div class="text-sm space-y-1">
                    <p><span class="text-slate-500">Adults:</span> <span class="font-semibold text-slate-900" x-text="numAdults"></span></p>
                    <p x-show="numChildren > 0"><span class="text-slate-500">Children:</span> <span class="font-semibold text-slate-900" x-text="numChildren"></span></p>
                </div>
            </div>

            <!-- Selected Add-ons -->
            <div class="bg-slate-50 rounded-xl p-4 space-y-2" x-show="selectedAddons.length > 0">
                <h3 class="font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Selected Add-ons
                </h3>
                <template x-for="addon in selectedAddons" :key="addon.addon_id">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600"><span x-text="addon.name"></span> (x<span x-text="addon.quantity"></span>)</span>
                        <span class="font-semibold text-slate-900">$<span x-text="(addon.price * addon.quantity).toFixed(2)"></span></span>
                    </div>
                </template>
            </div>

            <!-- Final Price Breakdown -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-6 border-2 border-amber-200 space-y-3">
                <h3 class="font-bold text-slate-900 text-lg flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    Price Summary
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-600">Base Price:</span>
                        <span class="font-semibold text-slate-900">$<span x-text="basePrice.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between" x-show="addonsTotal > 0">
                        <span class="text-slate-600">Add-ons Total:</span>
                        <span class="font-semibold text-slate-900">$<span x-text="addonsTotal.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-600">Subtotal:</span>
                        <span class="font-semibold text-slate-900">$<span x-text="subtotal.toFixed(2)"></span></span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-slate-500">Platform Fee (10%):</span>
                        <span class="text-slate-700">$<span x-text="platformFee.toFixed(2)"></span></span>
                    </div>
                    <div class="border-t-2 border-amber-300 pt-3 mt-3">
                        <div class="flex justify-between text-xl font-bold">
                            <span class="text-slate-900">Total Amount:</span>
                            <span class="text-amber-600">$<span x-text="totalAmount.toFixed(2)"></span></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms & Conditions -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4">
                <label class="flex items-start cursor-pointer">
                    <input type="checkbox"
                           name="agree_terms"
                           value="1"
                           required
                           class="w-5 h-5 text-amber-600 rounded focus:ring-2 focus:ring-amber-500 border-slate-300 mt-0.5">
                    <div class="ml-3">
                        <span class="font-semibold text-slate-900">I agree to the terms and conditions</span>
                        <p class="text-sm text-slate-600 mt-1">
                            By checking this box, I acknowledge that I have read and agree to the booking terms,
                            cancellation policy, and privacy policy. I understand that this booking is subject to
                            guide approval and payment confirmation.
                        </p>
                    </div>
                </label>
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center pt-4 border-t border-slate-200">
                <button type="button"
                        @click="prevStep()"
                        class="px-6 py-3 border border-slate-200 rounded-xl font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                    Back
                </button>
                <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-amber-500 to-orange-500 text-white rounded-xl font-semibold text-lg shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all duration-300">
                    Confirm Booking
                </button>
            </div>

            <p class="text-xs text-center text-slate-500">
                After confirmation, you'll receive booking details via email and can proceed to payment.
            </p>
        </div>
    </form>
</div>
@endsection
