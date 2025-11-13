@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex-1">
                <div class="flex items-center space-x-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $plan->title }}</h1>
                    @if($plan->status === 'active')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                    @elseif($plan->status === 'draft')
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                    @else
                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">Inactive</span>
                    @endif
                </div>
                <p class="text-gray-600 mt-2">{{ $plan->num_days }} days / {{ $plan->num_nights }} nights</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('guide.plans.edit', $plan) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Plan
                </a>
                <a href="{{ route('guide.plans.index') }}" class="text-gray-600 hover:text-gray-900">Back to List</a>
            </div>
        </div>
    </div>

    <!-- Status Management -->
    <div class="mb-6 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Plan Status</h3>
        <form action="{{ route('guide.plans.status', $plan) }}" method="POST" class="flex items-center space-x-4">
            @csrf
            @method('PATCH')
            <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="draft" {{ $plan->status === 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="active" {{ $plan->status === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $plan->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Update Status
            </button>
        </form>
    </div>

    <!-- Cover Photo -->
    @if($plan->cover_photo)
        <div class="mb-6">
            <img src="{{ Storage::url($plan->cover_photo) }}" alt="{{ $plan->title }}" class="w-full h-96 object-cover rounded-lg shadow">
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                <div class="prose max-w-none text-gray-700">
                    {!! nl2br(e($plan->description)) !!}
                </div>
            </div>

            <!-- Locations & Destinations -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Locations & Destinations</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Pickup Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->pickup_location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Dropoff Location</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->dropoff_location }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Destinations</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @foreach($plan->destinations as $destination)
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm rounded-full">{{ $destination }}</span>
                            @endforeach
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Trip Focus</dt>
                        <dd class="mt-1 flex flex-wrap gap-2">
                            @foreach($plan->trip_focus_tags as $tag)
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 text-sm rounded-full">{{ $tag }}</span>
                            @endforeach
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Vehicle Information -->
            @if($plan->vehicle_type)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Vehicle Information</h2>
                    <dl class="space-y-3">
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Type</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_type }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_category ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_capacity ?? 'N/A' }} passengers</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Air Conditioning</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_ac ? 'Yes' : 'No' }}</dd>
                        </div>
                        @if($plan->vehicle_description)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $plan->vehicle_description }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Inclusions & Exclusions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">What's Included & Excluded</h2>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-green-700 mb-2">Included</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $plan->inclusions }}</div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-red-700 mb-2">Not Included</h3>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $plan->exclusions }}</div>
                    </div>
                </div>
            </div>

            <!-- Dietary & Accessibility -->
            @if($plan->dietary_options || $plan->accessibility_info)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Special Requirements</h2>
                    @if($plan->dietary_options)
                        <div class="mb-4">
                            <dt class="text-sm font-medium text-gray-500 mb-2">Dietary Options</dt>
                            <dd class="flex flex-wrap gap-2">
                                @foreach($plan->dietary_options as $option)
                                    <span class="px-3 py-1 bg-green-100 text-green-800 text-sm rounded-full">{{ ucfirst(str_replace('_', ' ', $option)) }}</span>
                                @endforeach
                            </dd>
                        </div>
                    @endif
                    @if($plan->accessibility_info)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-2">Accessibility</dt>
                            <dd class="text-sm text-gray-700">{{ $plan->accessibility_info }}</dd>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Cancellation Policy -->
            @if($plan->cancellation_policy)
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Cancellation Policy</h2>
                    <div class="text-sm text-gray-700 whitespace-pre-line">{{ $plan->cancellation_policy }}</div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Views</dt>
                        <dd class="mt-1 text-2xl font-bold text-blue-600">{{ $plan->view_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Bookings</dt>
                        <dd class="mt-1 text-2xl font-bold text-green-600">{{ $plan->booking_count }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->created_at->format('M d, Y') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $plan->updated_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Pricing -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Pricing</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price per Adult</dt>
                        <dd class="mt-1 text-2xl font-bold text-gray-900">${{ number_format($plan->price_per_adult, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Price per Child</dt>
                        <dd class="mt-1 text-xl font-semibold text-gray-900">${{ number_format($plan->price_per_child, 2) }}</dd>
                    </div>
                </dl>
            </div>

            <!-- Group Size -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Group Size</h2>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Minimum</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $plan->min_group_size }} people</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Maximum</dt>
                        <dd class="mt-1 text-lg font-semibold text-gray-900">{{ $plan->max_group_size }} people</dd>
                    </div>
                </dl>
            </div>

            <!-- Availability -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Availability</h2>
                @if($plan->availability_type === 'always_available')
                    <p class="text-sm text-green-600 font-medium">Always Available</p>
                @else
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">From</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $plan->available_start_date?->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">To</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $plan->available_end_date?->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
