@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('guide.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('guide.vehicles.index') }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-emerald-600 md:ml-2">My Vehicles</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">Add New Vehicle</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Add New Vehicle</h1>
            <p class="text-gray-600 mt-2">Add a vehicle to use for your tours</p>
        </div>

        <!-- Form -->
        <form action="{{ route('guide.vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Vehicle Information -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Vehicle Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Vehicle Type -->
                    <div>
                        <label for="vehicle_type" class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type <span class="text-red-500">*</span></label>
                        <select name="vehicle_type" id="vehicle_type" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('vehicle_type') border-red-500 @enderror" required>
                            <option value="">Select type...</option>
                            @foreach($vehicleTypes as $value => $label)
                                <option value="{{ $value }}" {{ old('vehicle_type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Make -->
                    <div>
                        <label for="make" class="block text-sm font-medium text-gray-700 mb-1">Make <span class="text-red-500">*</span></label>
                        <input type="text" name="make" id="make" value="{{ old('make') }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('make') border-red-500 @enderror" placeholder="e.g., Toyota, Honda" required>
                        @error('make')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                        <input type="text" name="model" id="model" value="{{ old('model') }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('model') border-red-500 @enderror" placeholder="e.g., HiAce, CR-V" required>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <input type="number" name="year" id="year" value="{{ old('year') }}" min="1990" max="{{ date('Y') + 1 }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('year') border-red-500 @enderror" placeholder="e.g., 2020">
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- License Plate -->
                    <div>
                        <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">License Plate <span class="text-red-500">*</span></label>
                        <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 uppercase @error('license_plate') border-red-500 @enderror" placeholder="e.g., ABC-1234" required>
                        @error('license_plate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Seating Capacity -->
                    <div>
                        <label for="seating_capacity" class="block text-sm font-medium text-gray-700 mb-1">Seating Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="seating_capacity" id="seating_capacity" value="{{ old('seating_capacity') }}" min="1" max="50" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('seating_capacity') border-red-500 @enderror" placeholder="e.g., 7" required>
                        <p class="mt-1 text-xs text-gray-500">Number of passengers (excluding driver)</p>
                        @error('seating_capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Air Conditioning -->
                <div class="mt-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="has_ac" value="1" {{ old('has_ac') ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-gray-700">Vehicle has air conditioning</span>
                    </label>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('description') border-red-500 @enderror" placeholder="Optional: Add any additional details about the vehicle...">{{ old('description') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Max 500 characters</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Photos Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Vehicle Photos</h2>
                <p class="text-sm text-gray-600 mb-4">Upload up to 5 photos of your vehicle. First photo will be set as primary.</p>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Photos</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-emerald-400 transition" id="photos-dropzone">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="photos" class="relative cursor-pointer rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                    <span>Upload files</span>
                                    <input id="photos" name="photos[]" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB each (max 5 photos)</p>
                        </div>
                    </div>

                    <!-- Photo Previews -->
                    <div id="photos-preview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4 hidden"></div>

                    <!-- Primary Photo Selection -->
                    <div id="primary-photo-selection" class="mt-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Primary Photo</label>
                        <select name="primary_photo" id="primary_photo" class="w-full md:w-64 rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500">
                        </select>
                    </div>

                    @error('photos')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('photos.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Documents Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Vehicle Documents</h2>
                <p class="text-sm text-gray-600 mb-4">Upload registration and insurance documents (optional but recommended).</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Registration Document -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-3">Vehicle Registration</h3>

                        <div>
                            <label for="registration_document" class="block text-sm font-medium text-gray-700 mb-2">Upload Document</label>
                            <input type="file" name="registration_document" id="registration_document"
                                accept=".pdf,.jpeg,.jpg,.png"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <p class="mt-1 text-xs text-gray-500">PDF, JPG, PNG up to 5MB</p>
                            @error('registration_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Insurance Document -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-3">Vehicle Insurance</h3>

                        <div>
                            <label for="insurance_document" class="block text-sm font-medium text-gray-700 mb-2">Upload Document</label>
                            <input type="file" name="insurance_document" id="insurance_document"
                                accept=".pdf,.jpeg,.jpg,.png"
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                            <p class="mt-1 text-xs text-gray-500">PDF, JPG, PNG up to 5MB</p>
                            @error('insurance_document')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('guide.vehicles.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                    Add Vehicle
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Multiple photos preview
    const photosInput = document.getElementById('photos');
    const photosPreview = document.getElementById('photos-preview');
    const primaryPhotoSelection = document.getElementById('primary-photo-selection');
    const primaryPhotoSelect = document.getElementById('primary_photo');

    let selectedFiles = [];

    photosInput.addEventListener('change', function(e) {
        const files = Array.from(this.files);

        if (files.length > 5) {
            alert('You can upload a maximum of 5 photos.');
            this.value = '';
            return;
        }

        selectedFiles = files;
        updatePhotoPreviews();
    });

    function updatePhotoPreviews() {
        photosPreview.innerHTML = '';
        primaryPhotoSelect.innerHTML = '';

        if (selectedFiles.length === 0) {
            photosPreview.classList.add('hidden');
            primaryPhotoSelection.classList.add('hidden');
            return;
        }

        photosPreview.classList.remove('hidden');
        primaryPhotoSelection.classList.remove('hidden');

        selectedFiles.forEach((file, index) => {
            // Create preview element
            const previewDiv = document.createElement('div');
            previewDiv.className = 'relative group';

            const reader = new FileReader();
            reader.onload = function(e) {
                previewDiv.innerHTML = `
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-lg border-2 ${index === 0 ? 'border-emerald-500' : 'border-gray-200'}">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-center justify-center">
                        <button type="button" onclick="removePhoto(${index})" class="text-white text-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    ${index === 0 ? '<span class="absolute top-1 left-1 bg-emerald-500 text-white text-xs px-2 py-0.5 rounded">Primary</span>' : ''}
                `;
            };
            reader.readAsDataURL(file);

            photosPreview.appendChild(previewDiv);

            // Add to primary photo select
            const option = document.createElement('option');
            option.value = index;
            option.textContent = `Photo ${index + 1}: ${file.name}`;
            if (index === 0) option.selected = true;
            primaryPhotoSelect.appendChild(option);
        });
    }

    function removePhoto(index) {
        const dt = new DataTransfer();
        selectedFiles.forEach((file, i) => {
            if (i !== index) dt.items.add(file);
        });
        photosInput.files = dt.files;
        selectedFiles = Array.from(dt.files);
        updatePhotoPreviews();
    }

    // Update primary photo indicator when selection changes
    primaryPhotoSelect.addEventListener('change', function() {
        const selectedIndex = parseInt(this.value);
        const previews = photosPreview.querySelectorAll('img');
        previews.forEach((img, index) => {
            if (index === selectedIndex) {
                img.classList.remove('border-gray-200');
                img.classList.add('border-emerald-500');
            } else {
                img.classList.remove('border-emerald-500');
                img.classList.add('border-gray-200');
            }
        });

        // Update primary badge
        const badges = photosPreview.querySelectorAll('span.absolute');
        badges.forEach(badge => badge.remove());

        const selectedPreview = photosPreview.children[selectedIndex];
        if (selectedPreview) {
            const badge = document.createElement('span');
            badge.className = 'absolute top-1 left-1 bg-emerald-500 text-white text-xs px-2 py-0.5 rounded';
            badge.textContent = 'Primary';
            selectedPreview.appendChild(badge);
        }
    });
</script>
@endpush
@endsection
