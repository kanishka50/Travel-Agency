@extends('layouts.dashboard')

@section('page-title', 'Add New Vehicle')

@section('content')
<!-- Page Header -->
<div class="mb-8">
    <a href="{{ route('guide.vehicles.index') }}" class="inline-flex items-center gap-2 text-amber-600 hover:text-amber-700 font-medium mb-4 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Back to My Vehicles
    </a>
    <h1 class="text-2xl font-bold text-slate-900">Add New Vehicle</h1>
    <p class="text-slate-500 mt-1">Add a vehicle to use for your tours</p>
</div>

<!-- Form -->
<form action="{{ route('guide.vehicles.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Vehicle Information -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-amber-100 to-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Vehicle Information</h2>
                <p class="text-sm text-slate-500">Basic details about your vehicle</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Vehicle Type -->
            <div>
                <label for="vehicle_type" class="block text-sm font-semibold text-slate-700 mb-2">Vehicle Type <span class="text-red-500">*</span></label>
                <select name="vehicle_type" id="vehicle_type" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 @error('vehicle_type') border-red-500 @enderror" required>
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
                <label for="make" class="block text-sm font-semibold text-slate-700 mb-2">Make <span class="text-red-500">*</span></label>
                <input type="text" name="make" id="make" value="{{ old('make') }}" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 @error('make') border-red-500 @enderror" placeholder="e.g., Toyota, Honda" required>
                @error('make')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Model -->
            <div>
                <label for="model" class="block text-sm font-semibold text-slate-700 mb-2">Model <span class="text-red-500">*</span></label>
                <input type="text" name="model" id="model" value="{{ old('model') }}" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 @error('model') border-red-500 @enderror" placeholder="e.g., HiAce, CR-V" required>
                @error('model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Year -->
            <div>
                <label for="year" class="block text-sm font-semibold text-slate-700 mb-2">Year</label>
                <input type="number" name="year" id="year" value="{{ old('year') }}" min="1990" max="{{ date('Y') + 1 }}" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 @error('year') border-red-500 @enderror" placeholder="e.g., 2020">
                @error('year')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- License Plate -->
            <div>
                <label for="license_plate" class="block text-sm font-semibold text-slate-700 mb-2">License Plate <span class="text-red-500">*</span></label>
                <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate') }}" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 uppercase @error('license_plate') border-red-500 @enderror" placeholder="e.g., ABC-1234" required>
                @error('license_plate')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Seating Capacity -->
            <div>
                <label for="seating_capacity" class="block text-sm font-semibold text-slate-700 mb-2">Seating Capacity <span class="text-red-500">*</span></label>
                <input type="number" name="seating_capacity" id="seating_capacity" value="{{ old('seating_capacity') }}" min="1" max="50" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 @error('seating_capacity') border-red-500 @enderror" placeholder="e.g., 7" required>
                <p class="mt-1 text-xs text-slate-500">Number of passengers (excluding driver)</p>
                @error('seating_capacity')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Air Conditioning -->
        <div class="mt-6">
            <label class="inline-flex items-center">
                <input type="checkbox" name="has_ac" value="1" {{ old('has_ac') ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                <span class="ml-2 text-sm text-slate-700">Vehicle has air conditioning</span>
            </label>
        </div>

        <!-- Description -->
        <div class="mt-6">
            <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Description</label>
            <textarea name="description" id="description" rows="3" class="w-full rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500 @error('description') border-red-500 @enderror" placeholder="Optional: Add any additional details about the vehicle...">{{ old('description') }}</textarea>
            <p class="mt-1 text-xs text-slate-500">Max 500 characters</p>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Photos Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-pink-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Vehicle Photos</h2>
                <p class="text-sm text-slate-500">Upload up to 5 photos of your vehicle. First photo will be set as primary.</p>
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Upload Photos</label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-xl hover:border-amber-400 transition bg-slate-50" id="photos-dropzone">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <div class="flex text-sm text-slate-600">
                        <label for="photos" class="relative cursor-pointer rounded-md font-semibold text-amber-600 hover:text-amber-500">
                            <span>Upload files</span>
                            <input id="photos" name="photos[]" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
                        </label>
                        <p class="pl-1">or drag and drop</p>
                    </div>
                    <p class="text-xs text-slate-500">PNG, JPG, WEBP up to 2MB each (max 5 photos)</p>
                </div>
            </div>

            <!-- Photo Previews -->
            <div id="photos-preview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4 hidden"></div>

            <!-- Primary Photo Selection -->
            <div id="primary-photo-selection" class="mt-4 hidden">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Select Primary Photo</label>
                <select name="primary_photo" id="primary_photo" class="w-full md:w-64 rounded-xl border-slate-200 focus:border-amber-500 focus:ring-amber-500">
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
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-10 h-10 bg-gradient-to-br from-cyan-100 to-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-lg font-bold text-slate-900">Vehicle Documents</h2>
                <p class="text-sm text-slate-500">Upload registration and insurance documents (optional but recommended)</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Registration Document -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">Vehicle Registration</h3>
                </div>

                <div>
                    <label for="registration_document" class="block text-sm font-medium text-slate-700 mb-2">Upload Document</label>
                    <input type="file" name="registration_document" id="registration_document"
                        accept=".pdf,.jpeg,.jpg,.png"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="mt-1 text-xs text-slate-500">PDF, JPG, PNG up to 5MB</p>
                    @error('registration_document')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Insurance Document -->
            <div class="bg-slate-50 rounded-xl p-5 border border-slate-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-slate-900">Vehicle Insurance</h3>
                </div>

                <div>
                    <label for="insurance_document" class="block text-sm font-medium text-slate-700 mb-2">Upload Document</label>
                    <input type="file" name="insurance_document" id="insurance_document"
                        accept=".pdf,.jpeg,.jpg,.png"
                        class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                    <p class="mt-1 text-xs text-slate-500">PDF, JPG, PNG up to 5MB</p>
                    @error('insurance_document')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Buttons -->
    <div class="flex justify-end gap-4">
        <a href="{{ route('guide.vehicles.index') }}" class="px-6 py-2.5 border border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 transition">
            Cancel
        </a>
        <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-xl shadow-lg shadow-amber-500/25 hover:shadow-amber-500/40 transition-all">
            Add Vehicle
        </button>
    </div>
</form>

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
                    <img src="${e.target.result}" alt="Preview ${index + 1}" class="w-full h-24 object-cover rounded-xl border-2 ${index === 0 ? 'border-amber-500' : 'border-slate-200'}">
                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition rounded-xl flex items-center justify-center">
                        <button type="button" onclick="removePhoto(${index})" class="text-white text-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                    ${index === 0 ? '<span class="absolute top-1 left-1 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-lg">Primary</span>' : ''}
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
                img.classList.remove('border-slate-200');
                img.classList.add('border-amber-500');
            } else {
                img.classList.remove('border-amber-500');
                img.classList.add('border-slate-200');
            }
        });

        // Update primary badge
        const badges = photosPreview.querySelectorAll('span.absolute');
        badges.forEach(badge => badge.remove());

        const selectedPreview = photosPreview.children[selectedIndex];
        if (selectedPreview) {
            const badge = document.createElement('span');
            badge.className = 'absolute top-1 left-1 bg-amber-500 text-white text-xs px-2 py-0.5 rounded-lg';
            badge.textContent = 'Primary';
            selectedPreview.appendChild(badge);
        }
    });
</script>
@endpush
@endsection
