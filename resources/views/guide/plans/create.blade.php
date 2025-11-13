@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Tour Plan</h1>
                <p class="text-gray-600 mt-2">Fill in the details below to create your tour plan</p>
            </div>
            <a href="{{ route('guide.plans.index') }}" class="text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Errors -->
    @if($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('guide.plans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf

        @include('guide.plans.partials.form')

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end space-x-4 pt-6 border-t">
            <a href="{{ route('guide.plans.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                Cancel
            </a>
            <button type="submit" name="status" value="draft" class="px-6 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                Save as Draft
            </button>
            <button type="submit" name="status" value="active" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Publish Plan
            </button>
        </div>
    </form>
</div>

<script>
    // Handle availability type toggle
    document.addEventListener('DOMContentLoaded', function() {
        const availabilityType = document.getElementById('availability_type');
        const dateRangeFields = document.getElementById('date_range_fields');

        function toggleDateFields() {
            if (availabilityType.value === 'date_range') {
                dateRangeFields.classList.remove('hidden');
            } else {
                dateRangeFields.classList.add('hidden');
            }
        }

        availabilityType.addEventListener('change', toggleDateFields);
        toggleDateFields(); // Initial check
    });
</script>
@endsection
