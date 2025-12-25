{{-- Price Calculator Component --}}
<div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-6 text-white">
        <div class="flex items-baseline gap-2">
            <span class="text-4xl font-bold font-display">${{ number_format($plan->price_per_adult, 0) }}</span>
            <span class="text-amber-100">/ adult</span>
        </div>
        @if($plan->price_per_child < $plan->price_per_adult)
            <div class="text-amber-100 mt-1">
                ${{ number_format($plan->price_per_child, 0) }} per child
            </div>
        @endif
    </div>

    <div class="p-6">
        <!-- Calculator Section -->
        <div class="mb-6">
            <h4 class="font-display font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                Calculate Your Price
            </h4>

            <!-- Adults -->
            <div class="mb-4">
                <label for="adults" class="block text-sm font-medium text-slate-700 mb-2">Adults</label>
                <div class="flex items-center">
                    <button type="button" onclick="decrementAdults()"
                        class="w-12 h-12 rounded-l-xl border-2 border-r-0 border-slate-200 bg-slate-50 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-colors group">
                        <svg class="w-5 h-5 text-slate-500 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <input type="number" id="adults" value="2" min="1" max="{{ $plan->max_group_size }}"
                        onchange="calculateTotal()"
                        class="w-full h-12 text-center text-lg font-semibold border-2 border-slate-200 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    <button type="button" onclick="incrementAdults()"
                        class="w-12 h-12 rounded-r-xl border-2 border-l-0 border-slate-200 bg-slate-50 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-colors group">
                        <svg class="w-5 h-5 text-slate-500 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Children -->
            <div class="mb-4">
                <label for="children" class="block text-sm font-medium text-slate-700 mb-2">Children (under 12)</label>
                <div class="flex items-center">
                    <button type="button" onclick="decrementChildren()"
                        class="w-12 h-12 rounded-l-xl border-2 border-r-0 border-slate-200 bg-slate-50 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-colors group">
                        <svg class="w-5 h-5 text-slate-500 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </button>
                    <input type="number" id="children" value="0" min="0" max="{{ $plan->max_group_size }}"
                        onchange="calculateTotal()"
                        class="w-full h-12 text-center text-lg font-semibold border-2 border-slate-200 focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-500/20">
                    <button type="button" onclick="incrementChildren()"
                        class="w-12 h-12 rounded-r-xl border-2 border-l-0 border-slate-200 bg-slate-50 hover:bg-amber-50 hover:border-amber-300 flex items-center justify-center transition-colors group">
                        <svg class="w-5 h-5 text-slate-500 group-hover:text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Total Calculation -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl p-4 border border-amber-100">
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-slate-600">Adults × <span id="adults-display" class="font-medium">2</span></span>
                    <span id="adults-subtotal" class="font-semibold text-slate-800">${{ number_format($plan->price_per_adult * 2, 0) }}</span>
                </div>
                <div class="flex justify-between text-sm mb-2">
                    <span class="text-slate-600">Children × <span id="children-display" class="font-medium">0</span></span>
                    <span id="children-subtotal" class="font-semibold text-slate-800">$0</span>
                </div>
                <div class="flex justify-between text-sm mb-3 text-slate-500">
                    <span>Platform fee (10%)</span>
                    <span id="platform-fee">${{ number_format($plan->price_per_adult * 2 * 0.1, 0) }}</span>
                </div>
                <div class="border-t border-amber-200 pt-3 flex justify-between items-center">
                    <span class="font-semibold text-slate-900">Total</span>
                    <span id="total-price" class="text-2xl font-bold text-amber-600">${{ number_format($plan->price_per_adult * 2 * 1.1, 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Booking Button -->
        <div class="space-y-3">
            <p class="text-sm text-slate-500 text-center flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Select a date from the calendar below
            </p>
            @auth
                @if(auth()->user()->isTourist())
                    <button type="button" id="book-now-price-btn" disabled
                        class="w-full py-4 bg-slate-200 text-slate-400 text-center font-semibold rounded-xl cursor-not-allowed transition-all">
                        Select Date to Book
                    </button>
                @elseif(auth()->user()->isGuide())
                    <div class="w-full py-4 bg-slate-100 text-slate-500 text-center font-semibold rounded-xl">
                        Guides cannot book tours
                    </div>
                @endif
            @else
                <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                   class="block w-full py-4 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-center font-semibold rounded-xl hover:from-amber-600 hover:to-orange-600 transition-all shadow-lg shadow-amber-500/30 hover:shadow-amber-500/40 hover:-translate-y-0.5">
                    Login to Book
                </a>
            @endauth
        </div>

        <!-- Send Proposal Button -->
        @if($plan->allow_proposals)
            <div class="mt-3">
                @auth
                    @if(auth()->user()->isTourist())
                        <a href="{{ route('proposals.create', $plan->id) }}"
                           class="block w-full py-4 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-center font-semibold rounded-xl hover:from-slate-800 hover:to-slate-900 transition-all shadow-lg shadow-slate-500/20 hover:shadow-slate-500/30 hover:-translate-y-0.5">
                            <span class="flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Send Custom Proposal
                            </span>
                        </a>
                        @if($plan->min_proposal_price)
                            <p class="text-xs text-slate-500 text-center mt-2">
                                Minimum proposal: ${{ number_format($plan->min_proposal_price, 0) }}
                            </p>
                        @endif
                    @endif
                @else
                    <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                       class="block w-full py-4 bg-gradient-to-r from-slate-700 to-slate-800 text-white text-center font-semibold rounded-xl hover:from-slate-800 hover:to-slate-900 transition-all">
                        Login to Send Proposal
                    </a>
                @endauth
            </div>
        @endif

        <!-- Favorite Button -->
        <div class="mt-3">
            @auth
                <button type="button" onclick="toggleFavorite()"
                    class="w-full py-3 border-2 border-slate-200 text-slate-600 font-semibold rounded-xl hover:border-rose-400 hover:text-rose-500 hover:bg-rose-50 transition-all flex items-center justify-center gap-2">
                    <svg id="favorite-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <span id="favorite-text">Save to Favorites</span>
                </button>
            @else
                <a href="{{ route('login') }}?redirect={{ urlencode(request()->url()) }}"
                   class="block w-full py-3 border-2 border-slate-200 text-slate-600 text-center font-semibold rounded-xl hover:border-rose-400 hover:text-rose-500 hover:bg-rose-50 transition-all">
                    Login to Save
                </a>
            @endauth
        </div>

        <!-- Trust Badges -->
        <div class="mt-6 pt-4 border-t border-slate-100">
            <div class="space-y-2">
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    Free cancellation up to 48 hours
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    Reserve now, pay later
                </div>
                <div class="flex items-center gap-2 text-sm text-slate-600">
                    <div class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    Secure payment
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const pricePerAdult = {{ $plan->price_per_adult }};
    const pricePerChild = {{ $plan->price_per_child }};
    const maxGroupSize = {{ $plan->max_group_size }};
    const minGroupSize = {{ $plan->min_group_size }};

    function calculateTotal() {
        const adults = parseInt(document.getElementById('adults').value) || 0;
        const children = parseInt(document.getElementById('children').value) || 0;

        // Validate total group size
        const total = adults + children;
        if (total > maxGroupSize) {
            alert(`Maximum group size is ${maxGroupSize} people`);
            return;
        }
        if (adults < 1) {
            document.getElementById('adults').value = 1;
            calculateTotal();
            return;
        }

        const adultsSubtotal = adults * pricePerAdult;
        const childrenSubtotal = children * pricePerChild;
        const subtotal = adultsSubtotal + childrenSubtotal;
        const platformFee = subtotal * 0.1;
        const totalPrice = subtotal + platformFee;

        // Update displays
        document.getElementById('adults-display').textContent = adults;
        document.getElementById('children-display').textContent = children;
        document.getElementById('adults-subtotal').textContent = '$' + adultsSubtotal.toFixed(0);
        document.getElementById('children-subtotal').textContent = '$' + childrenSubtotal.toFixed(0);
        document.getElementById('platform-fee').textContent = '$' + platformFee.toFixed(0);
        document.getElementById('total-price').textContent = '$' + totalPrice.toFixed(0);
    }

    function incrementAdults() {
        const input = document.getElementById('adults');
        const current = parseInt(input.value) || 0;
        if (current < maxGroupSize) {
            input.value = current + 1;
            calculateTotal();
        }
    }

    function decrementAdults() {
        const input = document.getElementById('adults');
        const current = parseInt(input.value) || 0;
        if (current > 1) {
            input.value = current - 1;
            calculateTotal();
        }
    }

    function incrementChildren() {
        const input = document.getElementById('children');
        const current = parseInt(input.value) || 0;
        if (current < maxGroupSize) {
            input.value = current + 1;
            calculateTotal();
        }
    }

    function decrementChildren() {
        const input = document.getElementById('children');
        const current = parseInt(input.value) || 0;
        if (current > 0) {
            input.value = current - 1;
            calculateTotal();
        }
    }

    function toggleFavorite() {
        const icon = document.getElementById('favorite-icon');
        const text = document.getElementById('favorite-text');
        const button = icon.closest('button');

        if (icon.getAttribute('fill') === 'currentColor') {
            icon.setAttribute('fill', 'none');
            text.textContent = 'Save to Favorites';
            button.classList.remove('border-rose-400', 'text-rose-500', 'bg-rose-50');
        } else {
            icon.setAttribute('fill', 'currentColor');
            text.textContent = 'Saved!';
            button.classList.add('border-rose-400', 'text-rose-500', 'bg-rose-50');
        }
    }
</script>
