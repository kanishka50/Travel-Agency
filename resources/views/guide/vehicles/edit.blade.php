@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-50 via-white to-teal-50 py-8">
    <div class="max-w-4xl mx-auto px-4">
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <a href="{{ route('guide.vehicles.show', $vehicle) }}" class="ml-1 text-sm font-medium text-gray-500 hover:text-emerald-600 md:ml-2">{{ $vehicle->make }} {{ $vehicle->model }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-emerald-600 md:ml-2">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Vehicle</h1>
            <p class="text-gray-600 mt-2">Update your vehicle information</p>
        </div>

        <!-- Form -->
        <form action="{{ route('guide.vehicles.update', $vehicle) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

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
                                <option value="{{ $value }}" {{ old('vehicle_type', $vehicle->vehicle_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('vehicle_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Make -->
                    <div>
                        <label for="make" class="block text-sm font-medium text-gray-700 mb-1">Make <span class="text-red-500">*</span></label>
                        <input type="text" name="make" id="make" value="{{ old('make', $vehicle->make) }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('make') border-red-500 @enderror" placeholder="e.g., Toyota, Honda" required>
                        @error('make')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-1">Model <span class="text-red-500">*</span></label>
                        <input type="text" name="model" id="model" value="{{ old('model', $vehicle->model) }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('model') border-red-500 @enderror" placeholder="e.g., HiAce, CR-V" required>
                        @error('model')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Year -->
                    <div>
                        <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <input type="number" name="year" id="year" value="{{ old('year', $vehicle->year) }}" min="1990" max="{{ date('Y') + 1 }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('year') border-red-500 @enderror" placeholder="e.g., 2020">
                        @error('year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- License Plate -->
                    <div>
                        <label for="license_plate" class="block text-sm font-medium text-gray-700 mb-1">License Plate <span class="text-red-500">*</span></label>
                        <input type="text" name="license_plate" id="license_plate" value="{{ old('license_plate', $vehicle->license_plate) }}" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 uppercase @error('license_plate') border-red-500 @enderror" placeholder="e.g., ABC-1234" required>
                        @error('license_plate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Seating Capacity -->
                    <div>
                        <label for="seating_capacity" class="block text-sm font-medium text-gray-700 mb-1">Seating Capacity <span class="text-red-500">*</span></label>
                        <input type="number" name="seating_capacity" id="seating_capacity" value="{{ old('seating_capacity', $vehicle->seating_capacity) }}" min="1" max="50" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('seating_capacity') border-red-500 @enderror" placeholder="e.g., 7" required>
                        <p class="mt-1 text-xs text-gray-500">Number of passengers (excluding driver)</p>
                        @error('seating_capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Air Conditioning -->
                <div class="mt-6">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="has_ac" value="1" {{ old('has_ac', $vehicle->has_ac) ? 'checked' : '' }} class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-2 text-sm text-gray-700">Vehicle has air conditioning</span>
                    </label>
                </div>

                <!-- Description -->
                <div class="mt-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="3" class="w-full rounded-lg border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 @error('description') border-red-500 @enderror" placeholder="Optional: Add any additional details about the vehicle...">{{ old('description', $vehicle->description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Max 500 characters</p>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Photos Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Vehicle Photos</h2>
                <p class="text-sm text-gray-600 mb-4">Manage your vehicle photos. You can have up to 5 photos.</p>

                <!-- Existing Photos -->
                @if($vehicle->photos->count() > 0)
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-3">Current Photos</label>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                            @foreach($vehicle->photos as $photo)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Vehicle photo"
                                        class="w-full h-24 object-cover rounded-lg border-2 {{ $photo->is_primary ? 'border-emerald-500' : 'border-gray-200' }}">

                                    @if($photo->is_primary)
                                        <span class="absolute top-1 left-1 bg-emerald-500 text-white text-xs px-2 py-0.5 rounded">Primary</span>
                                    @endif

                                    <div class="absolute bottom-1 right-1 flex space-x-1">
                                        @if(!$photo->is_primary)
                                            <label class="bg-emerald-500 text-white text-xs px-2 py-1 rounded cursor-pointer hover:bg-emerald-600" title="Set as primary">
                                                <input type="radio" name="primary_photo_id" value="{{ $photo->id }}" class="sr-only">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </label>
                                        @endif
                                        <label class="bg-red-500 text-white text-xs px-2 py-1 rounded cursor-pointer hover:bg-red-600" title="Delete">
                                            <input type="checkbox" name="delete_photos[]" value="{{ $photo->id }}" class="sr-only delete-photo-checkbox">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="mt-2 text-xs text-gray-500">Click the star to set as primary photo, or the trash icon to mark for deletion.</p>
                    </div>
                @endif

                <!-- Add New Photos -->
                @php
                    $remainingSlots = 5 - $vehicle->photos->count();
                @endphp
                @if($remainingSlots > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Add New Photos ({{ $remainingSlots }} slots available)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-emerald-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="new_photos" class="relative cursor-pointer rounded-md font-medium text-emerald-600 hover:text-emerald-500">
                                        <span>Upload files</span>
                                        <input id="new_photos" name="new_photos[]" type="file" class="sr-only" accept="image/jpeg,image/png,image/jpg,image/webp" multiple>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB each</p>
                            </div>
                        </div>

                        <!-- New Photo Previews -->
                        <div id="new-photos-preview" class="mt-4 grid grid-cols-2 md:grid-cols-5 gap-4 hidden"></div>

                        @error('new_photos')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('new_photos.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <p class="text-sm text-amber-600 bg-amber-50 rounded-lg p-3">Maximum 5 photos reached. Delete some photos to add new ones.</p>
                @endif
            </div>

            <!-- Documents Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Vehicle Documents</h2>
                <p class="text-sm text-gray-600 mb-4">Manage your vehicle registration and insurance documents.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Registration Document -->
                    <div class="border border-gray-200 rounded-lg p-4">
                        <h3 class="font-medium text-gray-900 mb-3">Vehicle Registration</h3>

                        @php $registrationDoc = $vehicle->registration_document; @endphp

                        @if($registrationDoc)
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-900">Current Document</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $registrationDoc->document_path) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 text-sm">View</a>
                                </div>
                                <label class="mt-2 flex items-center">
                                    <input type="checkbox" name="delete_registration" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-red-600">Remove this document</span>
                                </label>
                            </div>
                        @endif

                        <div>
                            <label for="registration_document" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $registrationDoc ? 'Upload New Document' : 'Upload Document' }}
                            </label>
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

                        @php $insuranceDoc = $vehicle->insurance_document; @endphp

                        @if($insuranceDoc)
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <svg class="w-8 h-8 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <p class="text-sm font-medium text-gray-900">Current Document</p>
                                    </div>
                                    <a href="{{ asset('storage/' . $insuranceDoc->document_path) }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 text-sm">View</a>
                                </div>
                                <label class="mt-2 flex items-center">
                                    <input type="checkbox" name="delete_insurance" value="1" class="rounded border-gray-300 text-red-600 focus:ring-red-500">
                                    <span class="ml-2 text-sm text-red-600">Remove this document</span>
                                </label>
                            </div>
                        @endif

                        <div>
                            <label for="insurance_document" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ $insuranceDoc ? 'Upload New Document' : 'Upload Document' }}
                            </label>
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

            <!-- Danger Zone -->
            @if($vehicle->canBeDeleted())
                <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                    <h2 class="text-lg font-semibold text-red-800 mb-2">Danger Zone</h2>
                    <p class="text-sm text-red-600 mb-4">Once you delete this vehicle, there is no going back. Please be certain.</p>
                    <button type="button" onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition">
                        Delete Vehicle
                    </button>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('guide.vehicles.show', $vehicle) }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition">
                    Update Vehicle
                </button>
            </div>
        </form>

        <!-- Delete Form (Hidden) -->
        @if($vehicle->canBeDeleted())
            <form id="delete-form" action="{{ route('guide.vehicles.destroy', $vehicle) }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
    // New photos preview
    const newPhotosInput = document.getElementById('new_photos');
    const newPhotosPreview = document.getElementById('new-photos-preview');
    const maxSlots = {{ $remainingSlots }};

    if (newPhotosInput) {
        newPhotosInput.addEventListener('change', function(e) {
            const files = Array.from(this.files);

            if (files.length > maxSlots) {
                alert(`You can only add ${maxSlots} more photos.`);
                this.value = '';
                return;
            }

            newPhotosPreview.innerHTML = '';

            if (files.length === 0) {
                newPhotosPreview.classList.add('hidden');
                return;
            }

            newPhotosPreview.classList.remove('hidden');

            files.forEach((file, index) => {
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative';

                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `
                        <img src="${e.target.result}" alt="New photo preview" class="w-full h-24 object-cover rounded-lg border-2 border-emerald-500">
                        <span class="absolute top-1 left-1 bg-emerald-500 text-white text-xs px-2 py-0.5 rounded">New</span>
                    `;
                };
                reader.readAsDataURL(file);

                newPhotosPreview.appendChild(previewDiv);
            });
        });
    }

    // Mark photos for deletion visually
    document.querySelectorAll('.delete-photo-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const photoContainer = this.closest('.relative');
            const img = photoContainer.querySelector('img');
            if (this.checked) {
                img.classList.add('opacity-50');
                photoContainer.classList.add('ring-2', 'ring-red-500', 'rounded-lg');
            } else {
                img.classList.remove('opacity-50');
                photoContainer.classList.remove('ring-2', 'ring-red-500', 'rounded-lg');
            }
        });
    });

    // Delete confirmation
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this vehicle? This action cannot be undone.')) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
@endsection
