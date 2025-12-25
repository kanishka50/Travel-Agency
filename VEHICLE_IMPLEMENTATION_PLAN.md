# Vehicle Management Implementation Plan

**Created:** December 19, 2024
**Status:** Ready for Implementation

---

## Overview

This plan outlines all changes needed after the database cleanup (removed `guide_plan_vehicle_photos` table and `guides.vehicle_type`, `guides.vehicle_registration` columns) and the creation of new vehicle management tables.

---

## Phase 1: Cleanup - Remove Deprecated Vehicle References

### 1.1 Delete Unused Model
| File | Action |
|------|--------|
| `app/Models/GuidePlanVehiclePhoto.php` | **DELETE** - Table no longer exists |

### 1.2 Update GuideRegistrationRequest Model
| File | Changes |
|------|---------|
| `app/Models/GuideRegistrationRequest.php` | Remove `vehicle_photos` and `vehicle_license` from fillable and casts |

### 1.3 Update Guide Registration Form (Public)
| File | Changes |
|------|---------|
| `resources/views/guide-registration/create.blade.php` | Remove vehicle photo and vehicle license upload fields |

### 1.4 Update GuideRegistrationController
| File | Changes |
|------|---------|
| `app/Http/Controllers/GuideRegistrationController.php` | Remove vehicle_photos and vehicle_license validation and storage |

### 1.5 Update Admin - GuideRegistrationRequestResource
| File | Changes |
|------|---------|
| `app/Filament/Resources/GuideRegistrationRequestResource.php` | Remove vehicle fields from form/view |

### 1.6 Update Admin - GuideResource
| File | Changes |
|------|---------|
| `app/Filament/Resources/GuideResource.php` | Remove vehicle_type and vehicle_registration fields |

### 1.7 Update GuideApprovalService
| File | Changes |
|------|---------|
| `app/Services/GuideApprovalService.php` | Remove vehicle data transfer if any |

---

## Phase 2: Admin Panel - Vehicle Management

### 2.1 Create VehicleResource (Filament)
**New Files to Create:**
```
app/Filament/Resources/VehicleResource.php
app/Filament/Resources/VehicleResource/Pages/ListVehicles.php
app/Filament/Resources/VehicleResource/Pages/CreateVehicle.php
app/Filament/Resources/VehicleResource/Pages/EditVehicle.php
app/Filament/Resources/VehicleResource/Pages/ViewVehicle.php
```

**Features:**
- List all vehicles with filters (by guide, type, status)
- View vehicle details with photos and documents
- Edit any vehicle (admin override)
- Delete vehicle (with warning if assigned)
- See assignment history

### 2.2 Create BookingVehicleAssignmentResource (Filament)
**New Files to Create:**
```
app/Filament/Resources/BookingVehicleAssignmentResource.php
app/Filament/Resources/BookingVehicleAssignmentResource/Pages/ListBookingVehicleAssignments.php
app/Filament/Resources/BookingVehicleAssignmentResource/Pages/ViewBookingVehicleAssignment.php
```

**Features:**
- List all vehicle assignments
- View assignment details
- Override/change assigned vehicle
- See bookings without vehicles (urgent widget)

### 2.3 Update BookingResource
| File | Changes |
|------|---------|
| `app/Filament/Resources/BookingResource.php` | Add vehicle assignment section, show assigned vehicle, allow admin to assign |

### 2.4 Admin Dashboard Widget
**New File:**
```
app/Filament/Widgets/BookingsWithoutVehicleWidget.php
```
- Show count of upcoming bookings without vehicles
- Highlight urgent (within 3 days)
- Quick link to assign

---

## Phase 3: Guide Dashboard - Vehicle Management

### 3.1 Create Vehicle Controller
**New File:**
```
app/Http/Controllers/Guide/VehicleController.php
```

**Methods:**
- `index()` - List guide's vehicles
- `create()` - Show add vehicle form
- `store()` - Save new vehicle
- `edit($id)` - Show edit form
- `update($id)` - Update vehicle
- `destroy($id)` - Delete vehicle
- `toggleStatus($id)` - Activate/deactivate

### 3.2 Create Vehicle Views
**New Files:**
```
resources/views/guide/vehicles/index.blade.php
resources/views/guide/vehicles/create.blade.php
resources/views/guide/vehicles/edit.blade.php
resources/views/guide/vehicles/show.blade.php
resources/views/guide/vehicles/partials/form.blade.php
resources/views/guide/vehicles/partials/vehicle-card.blade.php
```

### 3.3 Add Routes
| File | Changes |
|------|---------|
| `routes/web.php` | Add vehicle management routes for guides |

### 3.4 Update Guide Dashboard
| File | Changes |
|------|---------|
| `resources/views/guide/dashboard.blade.php` | Add "My Vehicles" quick stats and link |

### 3.5 Update Guide Navigation
| File | Changes |
|------|---------|
| Navigation component/layout | Add "My Vehicles" menu item |

---

## Phase 4: Guide Plan Updates

### 4.1 Update GuidePlan Model
| File | Changes |
|------|---------|
| `app/Models/GuidePlan.php` | Remove `vehiclePhotos()` relationship if exists |

### 4.2 Update Guide Plan Form (Guide Dashboard)
| File | Changes |
|------|---------|
| `resources/views/guide/plans/partials/form.blade.php` | Keep vehicle_type, vehicle_capacity, vehicle_ac, vehicle_description (descriptive only) |
| `resources/views/guide/plans/create.blade.php` | Update labels to clarify these describe tour requirements |
| `resources/views/guide/plans/edit.blade.php` | Same updates |

### 4.3 Update GuidePlanController
| File | Changes |
|------|---------|
| `app/Http/Controllers/Guide/GuidePlanController.php` | Remove vehicle photo upload handling |

### 4.4 Update Admin GuidePlanResource
| File | Changes |
|------|---------|
| `app/Filament/Resources/GuidePlanResource.php` | Remove vehicle photos section, keep vehicle description fields |

### 4.5 Update Public Plan View
| File | Changes |
|------|---------|
| `resources/views/public/plans/show.blade.php` | Show vehicle requirements (type, AC, capacity) but NOT actual vehicle |

---

## Phase 5: Booking Vehicle Assignment (Guide)

### 5.1 Create BookingVehicleController
**New File:**
```
app/Http/Controllers/Guide/BookingVehicleController.php
```

**Methods:**
- `showAssignForm($bookingId)` - Show vehicle assignment form
- `assignSavedVehicle($bookingId)` - Assign from saved vehicles
- `assignTemporaryVehicle($bookingId)` - Assign temporary vehicle
- `viewAssignment($bookingId)` - View assigned vehicle

### 5.2 Create Assignment Views
**New Files:**
```
resources/views/guide/bookings/assign-vehicle.blade.php
resources/views/guide/bookings/partials/vehicle-assignment.blade.php
resources/views/guide/bookings/partials/temporary-vehicle-form.blade.php
```

### 5.3 Update Guide Bookings Index
| File | Changes |
|------|---------|
| `resources/views/guide/bookings/index.blade.php` | Show vehicle assignment status, highlight bookings needing vehicle |

### 5.4 Update Guide Booking Show
| File | Changes |
|------|---------|
| `resources/views/guide/bookings/show.blade.php` | Show assigned vehicle details or "Assign Vehicle" button |

### 5.5 Add Routes
| File | Changes |
|------|---------|
| `routes/web.php` | Add vehicle assignment routes |

---

## Phase 6: Tourist Vehicle Visibility

### 6.1 Update Tourist Booking View
| File | Changes |
|------|---------|
| `resources/views/bookings/show.blade.php` | Show vehicle details after assignment |

### 6.2 Create Vehicle Display Component
**New File:**
```
resources/views/components/vehicle-details.blade.php
```
- Reusable component to display vehicle info
- Shows photos, make/model, plate, capacity, AC

---

## Phase 7: Notifications

### 7.1 Create Mail Classes
**New Files:**
```
app/Mail/VehicleAssignmentReminder.php
app/Mail/VehicleAssigned.php
app/Mail/VehicleAssignmentUrgent.php
```

### 7.2 Create Email Templates
**New Files:**
```
resources/views/emails/vehicle-assignment-reminder.blade.php
resources/views/emails/vehicle-assigned.blade.php
resources/views/emails/vehicle-assignment-urgent.blade.php
```

### 7.3 Create Scheduled Command
**New File:**
```
app/Console/Commands/SendVehicleAssignmentReminders.php
```
- Run daily
- Send reminders at 5 days and 3 days before tour
- Notify admin for urgent cases

### 7.4 Register Command
| File | Changes |
|------|---------|
| `app/Console/Kernel.php` | Schedule the reminder command |

---

## Phase 8: Database Migration for Cleanup

### 8.1 Remove Vehicle Columns from guide_registration_requests
**New Migration:**
```
database/migrations/2025_12_19_000002_remove_vehicle_fields_from_registration_requests.php
```

Remove:
- `vehicle_photos` (json)
- `vehicle_license` (string)

---

## Implementation Order

| Step | Phase | Priority | Estimated Effort |
|------|-------|----------|------------------|
| 1 | Phase 1 | HIGH | 1-2 hours |
| 2 | Phase 8 | HIGH | 30 mins |
| 3 | Phase 3 | HIGH | 3-4 hours |
| 4 | Phase 2 | MEDIUM | 2-3 hours |
| 5 | Phase 4 | MEDIUM | 1-2 hours |
| 6 | Phase 5 | HIGH | 3-4 hours |
| 7 | Phase 6 | MEDIUM | 1-2 hours |
| 8 | Phase 7 | LOW | 2-3 hours |

**Total Estimated:** 14-20 hours

---

## Files Summary

### Files to DELETE:
1. `app/Models/GuidePlanVehiclePhoto.php`

### Files to CREATE:
1. `app/Filament/Resources/VehicleResource.php` + Pages
2. `app/Filament/Resources/BookingVehicleAssignmentResource.php` + Pages
3. `app/Filament/Widgets/BookingsWithoutVehicleWidget.php`
4. `app/Http/Controllers/Guide/VehicleController.php`
5. `app/Http/Controllers/Guide/BookingVehicleController.php`
6. `resources/views/guide/vehicles/*.blade.php` (6 files)
7. `resources/views/guide/bookings/assign-vehicle.blade.php`
8. `resources/views/guide/bookings/partials/vehicle-assignment.blade.php`
9. `resources/views/guide/bookings/partials/temporary-vehicle-form.blade.php`
10. `resources/views/components/vehicle-details.blade.php`
11. `app/Mail/VehicleAssignmentReminder.php`
12. `app/Mail/VehicleAssigned.php`
13. `app/Mail/VehicleAssignmentUrgent.php`
14. `resources/views/emails/vehicle-*.blade.php` (3 files)
15. `app/Console/Commands/SendVehicleAssignmentReminders.php`
16. Migration for removing vehicle fields from registration_requests

### Files to MODIFY:
1. `app/Models/GuideRegistrationRequest.php`
2. `app/Models/GuidePlan.php`
3. `app/Http/Controllers/GuideRegistrationController.php`
4. `app/Http/Controllers/Guide/GuidePlanController.php`
5. `app/Services/GuideApprovalService.php`
6. `app/Filament/Resources/GuideRegistrationRequestResource.php`
7. `app/Filament/Resources/GuideResource.php`
8. `app/Filament/Resources/GuidePlanResource.php`
9. `app/Filament/Resources/BookingResource.php`
10. `resources/views/guide-registration/create.blade.php`
11. `resources/views/guide/plans/partials/form.blade.php`
12. `resources/views/guide/bookings/index.blade.php`
13. `resources/views/guide/bookings/show.blade.php`
14. `resources/views/bookings/show.blade.php`
15. `resources/views/guide/dashboard.blade.php`
16. `resources/views/public/plans/show.blade.php`
17. `routes/web.php`
18. `app/Console/Kernel.php`

---

## Testing Checklist

- [ ] Guide can register without vehicle fields
- [ ] Admin can approve guide without vehicle data
- [ ] Guide can add/edit/delete saved vehicles
- [ ] Guide can assign saved vehicle to booking
- [ ] Guide can add temporary vehicle for booking
- [ ] Vehicle assignment is locked after assignment
- [ ] Admin can override vehicle assignment
- [ ] Tourist can see vehicle after assignment
- [ ] Admin sees bookings without vehicles widget
- [ ] Reminder notifications work correctly
- [ ] Vehicle capacity validation works

---

*Ready for implementation. Start with Phase 1 (Cleanup) first.*
