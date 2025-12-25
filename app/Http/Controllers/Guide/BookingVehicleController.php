<?php

namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingVehicleAssignment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BookingVehicleController extends Controller
{
    /**
     * Show the vehicle assignment form for a booking.
     */
    public function showAssignForm(Booking $booking)
    {
        $this->authorizeBooking($booking);

        // Check if vehicle is already assigned
        if ($booking->hasVehicleAssigned()) {
            return redirect()->route('guide.bookings.show', $booking)
                ->with('info', 'This booking already has a vehicle assigned.');
        }

        // Check if booking can have a vehicle assigned
        if (!$booking->needsVehicleAssignment()) {
            return redirect()->route('guide.bookings.show', $booking)
                ->with('error', 'Vehicle cannot be assigned to this booking.');
        }

        $guide = Auth::user()->guide;

        // Get guide's available vehicles with enough capacity
        $requiredCapacity = $booking->total_participants;
        $vehicles = Vehicle::where('guide_id', $guide->id)
            ->active()
            ->withMinCapacity($requiredCapacity)
            ->with('photos')
            ->get();

        // Also get all vehicles (even those without enough capacity) for display
        $allVehicles = Vehicle::where('guide_id', $guide->id)
            ->active()
            ->with('photos')
            ->get();

        // Get plan requirements if exists
        $planRequirements = null;
        if ($booking->guidePlan) {
            $planRequirements = [
                'vehicle_type' => $booking->guidePlan->vehicle_type,
                'has_ac' => $booking->guidePlan->vehicle_ac,
                'seating_capacity' => $booking->guidePlan->vehicle_capacity,
            ];
        }

        $booking->load(['tourist.user', 'guidePlan', 'touristRequest']);

        return view('guide.bookings.assign-vehicle', compact(
            'booking',
            'vehicles',
            'allVehicles',
            'requiredCapacity',
            'planRequirements'
        ));
    }

    /**
     * Assign a saved vehicle to the booking.
     */
    public function assignSavedVehicle(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        // Check if vehicle is already assigned
        if ($booking->hasVehicleAssigned()) {
            return redirect()->route('guide.bookings.show', $booking)
                ->with('error', 'This booking already has a vehicle assigned.');
        }

        $guide = Auth::user()->guide;

        $validated = $request->validate([
            'vehicle_id' => [
                'required',
                'exists:vehicles,id',
                function ($attribute, $value, $fail) use ($guide) {
                    $vehicle = Vehicle::find($value);
                    if (!$vehicle || $vehicle->guide_id !== $guide->id) {
                        $fail('The selected vehicle does not belong to you.');
                    }
                    if ($vehicle && !$vehicle->is_active) {
                        $fail('The selected vehicle is not active.');
                    }
                },
            ],
        ]);

        $vehicle = Vehicle::find($validated['vehicle_id']);

        // Warn if capacity is less than required (but still allow)
        $requiredCapacity = $booking->total_participants;
        if ($vehicle->seating_capacity < $requiredCapacity) {
            // Allow but note this in the response
        }

        BookingVehicleAssignment::create([
            'booking_id' => $booking->id,
            'vehicle_id' => $vehicle->id,
            'is_temporary' => false,
            'temporary_vehicle_data' => null,
            'assigned_at' => now(),
            'assigned_by' => Auth::id(),
        ]);

        return redirect()->route('guide.bookings.show', $booking)
            ->with('success', "Vehicle '{$vehicle->display_name}' has been assigned to this booking.");
    }

    /**
     * Assign a temporary (one-time) vehicle to the booking.
     */
    public function assignTemporaryVehicle(Request $request, Booking $booking)
    {
        $this->authorizeBooking($booking);

        // Check if vehicle is already assigned
        if ($booking->hasVehicleAssigned()) {
            return redirect()->route('guide.bookings.show', $booking)
                ->with('error', 'This booking already has a vehicle assigned.');
        }

        $validated = $request->validate([
            'vehicle_type' => ['required', Rule::in(array_keys(Vehicle::VEHICLE_TYPES))],
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'license_plate' => ['required', 'string', 'max:20'],
            'seating_capacity' => ['required', 'integer', 'min:1', 'max:50'],
            'has_ac' => ['boolean'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $temporaryVehicleData = [
            'vehicle_type' => $validated['vehicle_type'],
            'make' => $validated['make'],
            'model' => $validated['model'],
            'license_plate' => $validated['license_plate'],
            'seating_capacity' => $validated['seating_capacity'],
            'has_ac' => $request->boolean('has_ac'),
            'description' => $validated['description'] ?? null,
        ];

        BookingVehicleAssignment::create([
            'booking_id' => $booking->id,
            'vehicle_id' => null,
            'is_temporary' => true,
            'temporary_vehicle_data' => $temporaryVehicleData,
            'assigned_at' => now(),
            'assigned_by' => Auth::id(),
        ]);

        $displayName = "{$validated['make']} {$validated['model']}";

        return redirect()->route('guide.bookings.show', $booking)
            ->with('success', "Temporary vehicle '{$displayName}' has been assigned to this booking.");
    }

    /**
     * View the vehicle assignment for a booking.
     */
    public function viewAssignment(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if (!$booking->hasVehicleAssigned()) {
            return redirect()->route('guide.bookings.vehicle.assign', $booking)
                ->with('info', 'No vehicle has been assigned to this booking yet.');
        }

        $booking->load(['vehicleAssignment.vehicle.photos', 'vehicleAssignment.assignedByUser', 'tourist.user', 'guidePlan']);

        return view('guide.bookings.vehicle-assignment', compact('booking'));
    }

    /**
     * Remove vehicle assignment (if allowed).
     */
    public function removeAssignment(Booking $booking)
    {
        $this->authorizeBooking($booking);

        if (!$booking->hasVehicleAssigned()) {
            return redirect()->route('guide.bookings.show', $booking)
                ->with('error', 'No vehicle is assigned to this booking.');
        }

        // Check if booking has already started
        if ($booking->start_date <= now()) {
            return redirect()->route('guide.bookings.show', $booking)
                ->with('error', 'Cannot remove vehicle assignment for a booking that has already started.');
        }

        $booking->vehicleAssignment->delete();

        return redirect()->route('guide.bookings.show', $booking)
            ->with('success', 'Vehicle assignment has been removed. Please assign a new vehicle.');
    }

    /**
     * Authorize that the guide owns this booking.
     */
    private function authorizeBooking(Booking $booking): void
    {
        $guide = Auth::user()->guide;

        if (!$guide || $booking->guide_id !== $guide->id) {
            abort(403, 'Unauthorized access to this booking.');
        }
    }
}
