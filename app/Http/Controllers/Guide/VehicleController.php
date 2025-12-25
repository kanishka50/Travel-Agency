<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehiclePhoto;
use App\Models\VehicleDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    /**
     * Display a listing of the guide's vehicles.
     */
    public function index()
    {
        $guide = Auth::user()->guide;

        $vehicles = $guide->vehicles()
            ->with(['photos'])
            ->withCount(['assignments', 'upcomingAssignments'])
            ->orderBy('is_active', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $vehicles->count(),
            'active' => $vehicles->where('is_active', true)->count(),
            'inactive' => $vehicles->where('is_active', false)->count(),
            'total_capacity' => $vehicles->where('is_active', true)->sum('seating_capacity'),
        ];

        return view('guide.vehicles.index', compact('vehicles', 'stats'));
    }

    /**
     * Show the form for creating a new vehicle.
     */
    public function create()
    {
        $vehicleTypes = Vehicle::VEHICLE_TYPES;
        $documentTypes = VehicleDocument::DOCUMENT_TYPES;

        return view('guide.vehicles.create', compact('vehicleTypes', 'documentTypes'));
    }

    /**
     * Store a newly created vehicle in storage.
     */
    public function store(Request $request)
    {
        $guide = Auth::user()->guide;

        $validated = $request->validate([
            'vehicle_type' => ['required', 'string', Rule::in(array_keys(Vehicle::VEHICLE_TYPES))],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'license_plate' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles')->where(function ($query) use ($guide) {
                    return $query->where('guide_id', $guide->id);
                }),
            ],
            'seating_capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'has_ac' => ['boolean'],
            'description' => ['nullable', 'string', 'max:500'],
            // Multiple photos
            'photos' => ['nullable', 'array', 'max:5'],
            'photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'primary_photo' => ['nullable', 'integer', 'min:0'],
            // Documents
            'registration_document' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
            'insurance_document' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
        ]);

        DB::beginTransaction();
        try {
            // Create vehicle
            $vehicle = $guide->vehicles()->create([
                'vehicle_type' => $validated['vehicle_type'],
                'make' => $validated['make'],
                'model' => $validated['model'],
                'year' => $validated['year'] ?? null,
                'license_plate' => strtoupper($validated['license_plate']),
                'seating_capacity' => $validated['seating_capacity'],
                'has_ac' => $validated['has_ac'] ?? false,
                'description' => $validated['description'] ?? null,
                'is_active' => true,
            ]);

            // Handle multiple photos
            if ($request->hasFile('photos')) {
                $primaryIndex = $request->input('primary_photo', 0);
                foreach ($request->file('photos') as $index => $photo) {
                    $path = $photo->store('vehicles/' . $guide->id . '/photos', 'public');
                    $vehicle->photos()->create([
                        'photo_path' => $path,
                        'is_primary' => ($index == $primaryIndex),
                        'sort_order' => $index,
                    ]);
                }
            }

            // Handle registration document
            if ($request->hasFile('registration_document')) {
                $path = $request->file('registration_document')->store('vehicles/' . $guide->id . '/documents', 'public');
                $vehicle->documents()->create([
                    'document_type' => 'registration',
                    'document_path' => $path,
                ]);
            }

            // Handle insurance document
            if ($request->hasFile('insurance_document')) {
                $path = $request->file('insurance_document')->store('vehicles/' . $guide->id . '/documents', 'public');
                $vehicle->documents()->create([
                    'document_type' => 'insurance',
                    'document_path' => $path,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('guide.vehicles.index')
                ->with('success', "Vehicle '{$vehicle->make} {$vehicle->model}' added successfully!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to save vehicle. Please try again.');
        }
    }

    /**
     * Display the specified vehicle.
     */
    public function show(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        $vehicle->load([
            'photos' => fn($q) => $q->orderBy('is_primary', 'desc')->orderBy('sort_order'),
            'documents',
            'assignments' => function ($query) {
                $query->with('booking.tourist', 'booking.guidePlan')
                    ->orderBy('assigned_at', 'desc')
                    ->limit(10);
            },
        ]);

        $stats = [
            'total_assignments' => $vehicle->assignments()->count(),
            'upcoming_assignments' => $vehicle->upcomingAssignments()->count(),
            'completed_tours' => $vehicle->assignments()
                ->whereHas('booking', fn($q) => $q->where('status', 'completed'))
                ->count(),
        ];

        return view('guide.vehicles.show', compact('vehicle', 'stats'));
    }

    /**
     * Show the form for editing the specified vehicle.
     */
    public function edit(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        if (!$vehicle->canBeEdited()) {
            return redirect()
                ->route('guide.vehicles.show', $vehicle)
                ->with('error', 'This vehicle cannot be edited because it has upcoming assignments.');
        }

        $vehicle->load(['photos' => fn($q) => $q->orderBy('is_primary', 'desc')->orderBy('sort_order'), 'documents']);
        $vehicleTypes = Vehicle::VEHICLE_TYPES;
        $documentTypes = VehicleDocument::DOCUMENT_TYPES;

        return view('guide.vehicles.edit', compact('vehicle', 'vehicleTypes', 'documentTypes'));
    }

    /**
     * Update the specified vehicle in storage.
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        if (!$vehicle->canBeEdited()) {
            return redirect()
                ->route('guide.vehicles.show', $vehicle)
                ->with('error', 'This vehicle cannot be edited because it has upcoming assignments.');
        }

        $guide = Auth::user()->guide;

        $validated = $request->validate([
            'vehicle_type' => ['required', 'string', Rule::in(array_keys(Vehicle::VEHICLE_TYPES))],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'license_plate' => [
                'required',
                'string',
                'max:20',
                Rule::unique('vehicles')->where(function ($query) use ($guide) {
                    return $query->where('guide_id', $guide->id);
                })->ignore($vehicle->id),
            ],
            'seating_capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'has_ac' => ['boolean'],
            'description' => ['nullable', 'string', 'max:500'],
            // Photos management
            'new_photos' => ['nullable', 'array', 'max:5'],
            'new_photos.*' => ['image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'delete_photos' => ['nullable', 'array'],
            'delete_photos.*' => ['integer', 'exists:vehicle_photos,id'],
            'primary_photo_id' => ['nullable', 'integer'],
            // Documents
            'registration_document' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
            'delete_registration' => ['boolean'],
            'insurance_document' => ['nullable', 'file', 'mimes:pdf,jpeg,png,jpg', 'max:5120'],
            'delete_insurance' => ['boolean'],
        ]);

        DB::beginTransaction();
        try {
            // Update vehicle details
            $vehicle->update([
                'vehicle_type' => $validated['vehicle_type'],
                'make' => $validated['make'],
                'model' => $validated['model'],
                'year' => $validated['year'] ?? null,
                'license_plate' => strtoupper($validated['license_plate']),
                'seating_capacity' => $validated['seating_capacity'],
                'has_ac' => $validated['has_ac'] ?? false,
                'description' => $validated['description'] ?? null,
            ]);

            // Delete selected photos
            if ($request->has('delete_photos')) {
                foreach ($request->input('delete_photos') as $photoId) {
                    $photo = $vehicle->photos()->find($photoId);
                    if ($photo) {
                        Storage::disk('public')->delete($photo->photo_path);
                        $photo->delete();
                    }
                }
            }

            // Add new photos
            if ($request->hasFile('new_photos')) {
                $currentCount = $vehicle->photos()->count();
                foreach ($request->file('new_photos') as $index => $photo) {
                    if ($currentCount + $index >= 5) break; // Max 5 photos
                    $path = $photo->store('vehicles/' . $guide->id . '/photos', 'public');
                    $vehicle->photos()->create([
                        'photo_path' => $path,
                        'is_primary' => false,
                        'sort_order' => $currentCount + $index,
                    ]);
                }
            }

            // Set primary photo
            if ($request->filled('primary_photo_id')) {
                $vehicle->photos()->update(['is_primary' => false]);
                $vehicle->photos()->where('id', $request->input('primary_photo_id'))->update(['is_primary' => true]);
            }

            // Handle registration document
            if ($request->boolean('delete_registration')) {
                $doc = $vehicle->documents()->where('document_type', 'registration')->first();
                if ($doc) {
                    Storage::disk('public')->delete($doc->document_path);
                    $doc->delete();
                }
            }
            if ($request->hasFile('registration_document')) {
                // Delete old if exists
                $oldDoc = $vehicle->documents()->where('document_type', 'registration')->first();
                if ($oldDoc) {
                    Storage::disk('public')->delete($oldDoc->document_path);
                    $oldDoc->delete();
                }
                $path = $request->file('registration_document')->store('vehicles/' . $guide->id . '/documents', 'public');
                $vehicle->documents()->create([
                    'document_type' => 'registration',
                    'document_path' => $path,
                ]);
            }

            // Handle insurance document
            if ($request->boolean('delete_insurance')) {
                $doc = $vehicle->documents()->where('document_type', 'insurance')->first();
                if ($doc) {
                    Storage::disk('public')->delete($doc->document_path);
                    $doc->delete();
                }
            }
            if ($request->hasFile('insurance_document')) {
                // Delete old if exists
                $oldDoc = $vehicle->documents()->where('document_type', 'insurance')->first();
                if ($oldDoc) {
                    Storage::disk('public')->delete($oldDoc->document_path);
                    $oldDoc->delete();
                }
                $path = $request->file('insurance_document')->store('vehicles/' . $guide->id . '/documents', 'public');
                $vehicle->documents()->create([
                    'document_type' => 'insurance',
                    'document_path' => $path,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('guide.vehicles.show', $vehicle)
                ->with('success', 'Vehicle updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Failed to update vehicle. Please try again.');
        }
    }

    /**
     * Toggle vehicle active status.
     */
    public function toggleStatus(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        // Cannot deactivate if has upcoming assignments
        if ($vehicle->is_active && $vehicle->hasUpcomingAssignments()) {
            return redirect()
                ->back()
                ->with('error', 'Cannot deactivate this vehicle because it has upcoming tour assignments.');
        }

        $vehicle->update(['is_active' => !$vehicle->is_active]);

        $status = $vehicle->is_active ? 'activated' : 'deactivated';

        return redirect()
            ->back()
            ->with('success', "Vehicle {$status} successfully!");
    }

    /**
     * Remove the specified vehicle from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        $this->authorizeVehicle($vehicle);

        if (!$vehicle->canBeDeleted()) {
            return redirect()
                ->back()
                ->with('error', 'This vehicle cannot be deleted because it has upcoming assignments or assignment history.');
        }

        $guide = Auth::user()->guide;

        // Delete all photos
        foreach ($vehicle->photos as $photo) {
            Storage::disk('public')->delete($photo->photo_path);
        }

        // Delete all documents
        foreach ($vehicle->documents as $doc) {
            Storage::disk('public')->delete($doc->document_path);
        }

        $vehicleName = "{$vehicle->make} {$vehicle->model}";
        $vehicle->delete();

        return redirect()
            ->route('guide.vehicles.index')
            ->with('success', "Vehicle '{$vehicleName}' deleted successfully!");
    }

    /**
     * Authorize that the vehicle belongs to the current guide.
     */
    private function authorizeVehicle(Vehicle $vehicle): void
    {
        $guide = Auth::user()->guide;

        if ($vehicle->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to this vehicle.');
        }
    }
}
