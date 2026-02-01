@extends('layouts.dashboard')

@section('page-title', 'Create New Tour Plan')

@section('content')
<!-- Header -->
<div class="mb-8">
    <a href="{{ route('guide.plans.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to My Plans
    </a>
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Create New Tour Plan</h1>
            <p class="text-slate-600 mt-2">Fill in the details below to create your tour plan</p>
        </div>
    </div>
</div>

<!-- Errors -->
@if($errors->any())
    <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-5">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-red-900">Please fix the following errors:</h3>
                <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endif

<form action="{{ route('guide.plans.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    @include('guide.plans.partials.form')

    <!-- Submit Buttons -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
            <a href="{{ route('guide.plans.index') }}" class="w-full sm:w-auto px-6 py-3 border border-slate-300 rounded-xl text-slate-700 hover:bg-slate-50 font-semibold text-center transition-colors">
                Cancel
            </a>
            <button type="submit" name="status" value="draft" class="w-full sm:w-auto px-6 py-3 bg-slate-600 hover:bg-slate-700 text-white rounded-xl font-semibold transition-colors">
                Save as Draft
            </button>
            <button type="submit" name="status" value="active" class="w-full sm:w-auto px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white rounded-xl font-semibold shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
                Publish Plan
            </button>
        </div>
    </div>
</form>

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
