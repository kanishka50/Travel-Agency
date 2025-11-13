{{-- Price Calculator Component --}}
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="mb-4">
        <div class="text-3xl font-bold text-gray-900">
            ${{ number_format($plan->price_per_adult, 0) }}
        </div>
        <div class="text-sm text-gray-600">per adult</div>
        @if($plan->price_per_child < $plan->price_per_adult)
            <div class="text-sm text-gray-600 mt-1">
                ${{ number_format($plan->price_per_child, 0) }} per child
            </div>
        @endif
    </div>

    <div class="border-t border-b py-4 mb-4">
        <h4 class="font-semibold text-gray-900 mb-3">Calculate Your Price</h4>

        <!-- Adults -->
        <div class="mb-3">
            <label for="adults" class="block text-sm text-gray-700 mb-1">Adults</label>
            <div class="flex items-center">
                <button type="button" onclick="decrementAdults()"
                    class="w-10 h-10 rounded-l-lg border border-gray-300 bg-gray-50 hover:bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>
                <input type="number" id="adults" value="2" min="1" max="{{ $plan->max_group_size }}"
                    onchange="calculateTotal()"
                    class="w-full h-10 text-center border-t border-b border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="incrementAdults()"
                    class="w-10 h-10 rounded-r-lg border border-gray-300 bg-gray-50 hover:bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Children -->
        <div class="mb-3">
            <label for="children" class="block text-sm text-gray-700 mb-1">Children (under 12)</label>
            <div class="flex items-center">
                <button type="button" onclick="decrementChildren()"
                    class="w-10 h-10 rounded-l-lg border border-gray-300 bg-gray-50 hover:bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                    </svg>
                </button>
                <input type="number" id="children" value="0" min="0" max="{{ $plan->max_group_size }}"
                    onchange="calculateTotal()"
                    class="w-full h-10 text-center border-t border-b border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="incrementChildren()"
                    class="w-10 h-10 rounded-r-lg border border-gray-300 bg-gray-50 hover:bg-gray-100 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Total Calculation -->
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Adults × <span id="adults-display">2</span></span>
                <span id="adults-subtotal" class="font-medium">${{ number_format($plan->price_per_adult * 2, 0) }}</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Children × <span id="children-display">0</span></span>
                <span id="children-subtotal" class="font-medium">$0</span>
            </div>
            <div class="flex justify-between text-sm mb-2 text-gray-500">
                <span>Platform fee (10%)</span>
                <span id="platform-fee">${{ number_format($plan->price_per_adult * 2 * 0.1, 0) }}</span>
            </div>
            <div class="border-t pt-2 flex justify-between">
                <span class="font-semibold text-gray-900">Total</span>
                <span id="total-price" class="text-xl font-bold text-blue-600">${{ number_format($plan->price_per_adult * 2 * 1.1, 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Booking Button -->
    @auth
        <a href="#" class="block w-full py-3 bg-blue-600 text-white text-center font-semibold rounded-lg hover:bg-blue-700 transition mb-3">
            Book Now
        </a>
    @else
        <a href="{{ route('login') }}" class="block w-full py-3 bg-blue-600 text-white text-center font-semibold rounded-lg hover:bg-blue-700 transition mb-3">
            Login to Book
        </a>
    @endauth

    <!-- Favorite Button -->
    @auth
        <button type="button" onclick="toggleFavorite()"
            class="w-full py-3 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:border-red-500 hover:text-red-500 transition flex items-center justify-center">
            <svg id="favorite-icon" class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
            <span id="favorite-text">Save to Favorites</span>
        </button>
    @else
        <a href="{{ route('login') }}" class="block w-full py-3 border-2 border-gray-300 text-gray-700 text-center font-semibold rounded-lg hover:border-red-500 hover:text-red-500 transition">
            Login to Save
        </a>
    @endauth

    <div class="mt-4 text-xs text-gray-500 text-center">
        ✓ Free cancellation up to 48 hours before<br>
        ✓ Reserve now, pay later
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
        // TODO: Implement favorite toggle with AJAX
        const icon = document.getElementById('favorite-icon');
        const text = document.getElementById('favorite-text');

        // Toggle state (this would be replaced with actual API call)
        if (icon.getAttribute('fill') === 'currentColor') {
            icon.setAttribute('fill', 'none');
            text.textContent = 'Save to Favorites';
        } else {
            icon.setAttribute('fill', 'currentColor');
            text.textContent = 'Saved';
        }
    }
</script>
