@extends('layouts.public')

@section('content')
<div class="bg-emerald-600 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-white mb-2">Tour Packages</h1>
        <p class="text-emerald-100">Explore curated tour packages from our expert local guides</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Filters -->
    <div class="bg-white rounded-xl shadow-sm border p-6 mb-8">
        <form action="{{ route('tour-packages.index') }}" method="GET" class="grid md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Search packages...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                <select name="duration" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <option value="">All durations</option>
                    <option value="short" {{ request('duration') == 'short' ? 'selected' : '' }}>1-3 days</option>
                    <option value="medium" {{ request('duration') == 'medium' ? 'selected' : '' }}>4-7 days</option>
                    <option value="long" {{ request('duration') == 'long' ? 'selected' : '' }}>8+ days</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Max Price ($)</label>
                <input type="number" name="max_price" value="{{ request('max_price') }}"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                       placeholder="Max price">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-emerald-600 text-white py-2 px-4 rounded-lg hover:bg-emerald-700 transition">
                    Search
                </button>
            </div>
        </form>
    </div>

    <!-- Results Count -->
    <div class="mb-6">
        <p class="text-gray-600">Showing {{ $plans->count() }} of {{ $plans->total() }} packages</p>
    </div>

    <!-- Tour Packages Grid -->
    @if($plans->count() > 0)
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($plans as $plan)
                <div class="bg-white rounded-xl shadow-sm border overflow-hidden hover:shadow-md transition group">
                    @if($plan->cover_image)
                        <div class="h-48 overflow-hidden">
                            <img src="{{ Storage::url($plan->cover_image) }}" alt="{{ $plan->title }}"
                                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                        </div>
                    @else
                        <div class="h-48 bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                            <svg class="w-16 h-16 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="p-5">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded">
                                {{ $plan->duration_days }} {{ Str::plural('day', $plan->duration_days) }}
                            </span>
                            @if($plan->guide)
                                <span class="px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                                    {{ $plan->guide->guide_type_label }}
                                </span>
                            @endif
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">{{ $plan->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ Str::limit($plan->description, 100) }}</p>

                        <div class="flex items-center justify-between">
                            <div>
                                <span class="text-2xl font-bold text-emerald-600">${{ number_format($plan->price_per_person, 0) }}</span>
                                <span class="text-gray-500 text-sm">/person</span>
                            </div>
                            <a href="{{ route('tour-packages.show', $plan) }}"
                               class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-medium hover:bg-emerald-700 transition">
                                View Details
                            </a>
                        </div>

                        @if($plan->guide)
                            <div class="mt-4 pt-4 border-t flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                                    @if($plan->guide->profile_photo)
                                        <img src="{{ Storage::url($plan->guide->profile_photo) }}" alt="{{ $plan->guide->full_name }}"
                                             class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <span class="text-emerald-600 font-semibold text-xs">
                                            {{ strtoupper(substr($plan->guide->full_name, 0, 1)) }}
                                        </span>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $plan->guide->full_name }}</p>
                                    @if($plan->guide->average_rating > 0)
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                            <span class="text-xs text-gray-500">{{ number_format($plan->guide->average_rating, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $plans->links() }}
        </div>
    @else
        <div class="bg-white rounded-xl p-12 text-center border">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No packages found</h3>
            <p class="text-gray-500 mb-6">Try adjusting your search filters or check back later.</p>
            <a href="{{ route('tour-packages.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
                Clear all filters
            </a>
        </div>
    @endif
</div>
@endsection
