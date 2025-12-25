# Vehicle Management System - Requirements Document

**Document Version:** 1.0
**Date:** December 18, 2024
**Status:** Finalized - Ready for Implementation

---

## Table of Contents

1. [Executive Summary](#1-executive-summary)
2. [System Overview](#2-system-overview)
3. [User Roles & Permissions](#3-user-roles--permissions)
4. [Feature Requirements](#4-feature-requirements)
5. [Data Requirements](#5-data-requirements)
6. [Business Rules](#6-business-rules)
7. [User Interface Requirements](#7-user-interface-requirements)
8. [Admin Panel Requirements](#8-admin-panel-requirements)
9. [Validation Rules](#9-validation-rules)
10. [Notifications](#10-notifications)
11. [Future Considerations](#11-future-considerations)

---

## 1. Executive Summary

### 1.1 Purpose

This document defines the requirements for implementing a Vehicle Management System for the Tourism Platform. The system enables tour guides to manage their vehicles and assign them to bookings, ensuring tourists have reliable transportation for their tours.

### 1.2 Key Decisions

| Decision | Outcome |
|----------|---------|
| Vehicle in guide registration | No - Not required |
| Admin approval for vehicles | No - Documents stored for accountability |
| Vehicle assignment timing | After booking created, before 3 days of tour |
| Vehicle types | Saved (reusable) and Temporary (per-booking) |
| Lock after assignment | Yes - Guide cannot modify, admin can |
| Auto-cancel without vehicle | No - Warning in admin panel only |

### 1.3 Scope

**In Scope:**
- Guide vehicle management (add, edit, deactivate, delete)
- Saved vehicles in guide dashboard
- Temporary vehicles for specific bookings
- Vehicle assignment to bookings
- Vehicle photos and documents upload
- Admin vehicle oversight
- Capacity validation

**Out of Scope:**
- Vehicle tracking/GPS
- Vehicle maintenance scheduling
- Driver management (separate from guide)
- Vehicle rental marketplace
- Document expiry tracking

---

## 2. System Overview

### 2.1 High-Level Flow

```
┌─────────────────────────────────────────────────────────────────────────┐
│                         VEHICLE MANAGEMENT FLOW                          │
└─────────────────────────────────────────────────────────────────────────┘

PHASE 1: TOUR PLAN CREATION
┌─────────────────────────────────────────────────────────────────────────┐
│ Guide creates tour plan                                                  │
│ ├── Specifies: Vehicle Type (car, van, bus, etc.)                       │
│ ├── Specifies: Has AC (yes/no)                                          │
│ ├── Specifies: Seating Capacity                                         │
│ └── NO actual vehicle assigned at this stage                            │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
PHASE 2: TOURIST BOOKING
┌─────────────────────────────────────────────────────────────────────────┐
│ Tourist views tour plan                                                  │
│ ├── Sees: "Air-conditioned Van, 8 seats"                                │
│ ├── Does NOT see actual vehicle details                                 │
│ └── Makes booking and payment                                           │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
PHASE 3: VEHICLE ASSIGNMENT (Guide)
┌─────────────────────────────────────────────────────────────────────────┐
│ Guide receives booking notification                                      │
│ ├── Must assign vehicle before 3 days of tour start                     │
│ │                                                                        │
│ ├── OPTION A: Select from Saved Vehicles                                │
│ │   └── Choose from pre-registered vehicles in dashboard                │
│ │                                                                        │
│ └── OPTION B: Add Temporary Vehicle                                     │
│     └── Enter vehicle details for this booking only                     │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
PHASE 4: POST-ASSIGNMENT
┌─────────────────────────────────────────────────────────────────────────┐
│ Vehicle Locked                                                           │
│ ├── Guide cannot modify assigned vehicle                                │
│ ├── Guide contacts admin for changes                                    │
│ ├── Tourist can view full vehicle details                               │
│ └── Admin can edit if needed                                            │
└─────────────────────────────────────────────────────────────────────────┘
                                    │
                                    ▼
PHASE 5: TOUR EXECUTION
┌─────────────────────────────────────────────────────────────────────────┐
│ 3 Days Before Tour                                                       │
│ ├── System checks: Vehicle assigned?                                    │
│ ├── If NO: Warning displayed in Admin Panel                             │
│ ├── Admin handles manually (contact guide, etc.)                        │
│ └── Tour NOT auto-cancelled                                             │
└─────────────────────────────────────────────────────────────────────────┘
```

### 2.2 Vehicle Types

| Type | Description | Storage | Reusable |
|------|-------------|---------|----------|
| **Saved Vehicle** | Guide's vehicle stored in dashboard | `vehicles` table | Yes |
| **Temporary Vehicle** | Added for specific booking only | `booking_vehicle_assignments` table (JSON) | No |

---

## 3. User Roles & Permissions

### 3.1 Guide Permissions

| Action | Saved Vehicles | Temporary Vehicles | Assigned Vehicles |
|--------|---------------|-------------------|-------------------|
| Create | Yes | Yes (per booking) | N/A |
| View | Yes | Yes | Yes |
| Edit | Yes (if not assigned) | No | No |
| Delete | Yes (if not assigned) | No | No |
| Deactivate | Yes (if not assigned) | N/A | No |
| Assign to booking | Yes | Yes | N/A |

### 3.2 Admin Permissions

| Action | Permission |
|--------|------------|
| View all vehicles | Yes |
| Edit any vehicle | Yes |
| Delete any vehicle | Yes |
| View bookings without vehicles | Yes |
| Assign vehicle on behalf of guide | Yes |
| Override locked assignments | Yes |

### 3.3 Tourist Permissions

| Action | Before Assignment | After Assignment |
|--------|------------------|------------------|
| View vehicle type | Yes (from tour plan) | Yes |
| View vehicle details | No | Yes |
| View vehicle photos | No | Yes |
| View license plate | No | Yes |

---

## 4. Feature Requirements

### 4.1 Guide Dashboard - Vehicle Management

#### FR-4.1.1: View Saved Vehicles
- Guide can view list of all their saved vehicles
- Display: Vehicle type, make, model, license plate, capacity, status
- Show assignment status (assigned to X upcoming bookings)

#### FR-4.1.2: Add New Saved Vehicle
- Guide can add new vehicle with required fields
- Upload minimum 1, maximum 5 photos
- Upload required documents (registration, insurance)
- Vehicle immediately available for use (no admin approval)

#### FR-4.1.3: Edit Saved Vehicle
- Guide can edit vehicle details
- **Restriction:** Cannot edit if vehicle assigned to upcoming booking
- Show warning: "This vehicle is assigned to X upcoming bookings"

#### FR-4.1.4: Deactivate Saved Vehicle
- Guide can toggle vehicle active/inactive status
- Inactive vehicles hidden from assignment dropdown
- **Restriction:** Cannot deactivate if assigned to upcoming booking

#### FR-4.1.5: Delete Saved Vehicle
- Guide can permanently delete vehicle
- **Restriction:** Cannot delete if assigned to upcoming booking
- Confirmation required: "Are you sure? This cannot be undone."

### 4.2 Booking Vehicle Assignment

#### FR-4.2.1: View Bookings Needing Vehicle
- Guide dashboard shows bookings without assigned vehicle
- Sort by tour start date (soonest first)
- Highlight bookings within 3 days of tour start

#### FR-4.2.2: Assign Saved Vehicle to Booking
- Guide selects booking
- Dropdown shows only:
  - Active saved vehicles
  - Vehicles with sufficient capacity
- One-click assignment

#### FR-4.2.3: Add Temporary Vehicle for Booking
- Guide selects booking
- Fills vehicle form (same fields as saved vehicle)
- Vehicle attached to this booking only
- Not saved to guide's vehicle list

#### FR-4.2.4: View Assigned Vehicle
- Guide can view assigned vehicle details
- Cannot edit or change
- Shows "Contact admin to change vehicle"

### 4.3 Tour Plan Vehicle Information

#### FR-4.3.1: Specify Vehicle Type in Tour Plan
- When creating/editing tour plan, guide specifies:
  - Vehicle Type (required)
  - Has AC (required)
  - Seating Capacity (required)
- This is descriptive only, not actual vehicle assignment

### 4.4 Tourist Vehicle Visibility

#### FR-4.4.1: Before Booking
- Tourist sees tour plan vehicle info:
  - "Air-conditioned Van"
  - "Seats up to 8 passengers"

#### FR-4.4.2: After Booking & Payment (Before Assignment)
- Tourist sees same as above
- Message: "Vehicle details will be provided before your tour"

#### FR-4.4.3: After Vehicle Assignment
- Tourist sees full vehicle details:
  - Type, Make, Model
  - License Plate
  - Has AC, Capacity
  - Photos (all uploaded photos)
- Location: Booking details page

---

## 5. Data Requirements

### 5.1 Saved Vehicle Data (vehicles table)

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | bigint | Auto | Primary key |
| guide_id | bigint | Yes | Foreign key to guides |
| vehicle_type | enum | Yes | car, van, suv, minibus, bus, tuk_tuk, motorcycle |
| make | string(100) | Yes | Toyota, Nissan, etc. |
| model | string(100) | Yes | Hiace, Prado, etc. |
| license_plate | string(20) | Yes | Unique per guide |
| seating_capacity | integer | Yes | Number of passenger seats |
| has_ac | boolean | Yes | Air conditioning |
| description | text | No | Optional description |
| is_active | boolean | Yes | Default true |
| created_at | timestamp | Auto | |
| updated_at | timestamp | Auto | |

### 5.2 Vehicle Photos Data (vehicle_photos table)

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | bigint | Auto | Primary key |
| vehicle_id | bigint | Yes | Foreign key to vehicles |
| photo_path | string | Yes | Storage path |
| is_primary | boolean | Yes | Primary display photo |
| sort_order | integer | Yes | Display order |
| created_at | timestamp | Auto | |

### 5.3 Vehicle Documents Data (vehicle_documents table)

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | bigint | Auto | Primary key |
| vehicle_id | bigint | Yes | Foreign key to vehicles |
| document_type | enum | Yes | registration, insurance |
| document_path | string | Yes | Storage path |
| created_at | timestamp | Auto | |

### 5.4 Booking Vehicle Assignment Data (booking_vehicle_assignments table)

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| id | bigint | Auto | Primary key |
| booking_id | bigint | Yes | Foreign key to bookings (unique) |
| vehicle_id | bigint | No | Foreign key to vehicles (NULL if temporary) |
| is_temporary | boolean | Yes | True if temporary vehicle |
| temporary_vehicle_data | json | No | JSON data if is_temporary=true |
| assigned_at | timestamp | Yes | When vehicle was assigned |
| assigned_by | bigint | No | User who assigned (admin override) |
| created_at | timestamp | Auto | |
| updated_at | timestamp | Auto | |

### 5.5 Temporary Vehicle JSON Structure

```json
{
  "vehicle_type": "van",
  "make": "Toyota",
  "model": "Hiace",
  "license_plate": "ABC-1234",
  "seating_capacity": 8,
  "has_ac": true,
  "description": "Rented van for this tour",
  "photos": [
    "path/to/photo1.jpg",
    "path/to/photo2.jpg"
  ],
  "documents": {
    "registration": "path/to/registration.pdf",
    "insurance": "path/to/insurance.pdf"
  }
}
```

---

## 6. Business Rules

### 6.1 Vehicle Creation Rules

| Rule ID | Rule | Error Message |
|---------|------|---------------|
| BR-6.1.1 | Guide must upload minimum 1 photo | "Please upload at least 1 vehicle photo" |
| BR-6.1.2 | Guide must upload maximum 5 photos | "Maximum 5 photos allowed" |
| BR-6.1.3 | Guide must upload registration document | "Vehicle registration document is required" |
| BR-6.1.4 | Guide must upload insurance document | "Vehicle insurance document is required" |
| BR-6.1.5 | License plate must be unique per guide | "You already have a vehicle with this license plate" |

### 6.2 Vehicle Modification Rules

| Rule ID | Rule | Error Message |
|---------|------|---------------|
| BR-6.2.1 | Cannot edit vehicle assigned to upcoming booking | "This vehicle is assigned to upcoming bookings and cannot be edited" |
| BR-6.2.2 | Cannot deactivate vehicle assigned to upcoming booking | "This vehicle is assigned to upcoming bookings and cannot be deactivated" |
| BR-6.2.3 | Cannot delete vehicle assigned to upcoming booking | "This vehicle is assigned to upcoming bookings and cannot be deleted" |

### 6.3 Vehicle Assignment Rules

| Rule ID | Rule | Error Message |
|---------|------|---------------|
| BR-6.3.1 | Vehicle capacity must be >= booking group size | "Vehicle seats X passengers, but booking has Y tourists" |
| BR-6.3.2 | Can only assign active vehicles | "This vehicle is inactive" |
| BR-6.3.3 | One vehicle per booking | N/A (system enforced) |
| BR-6.3.4 | Assignment is permanent (guide cannot change) | "Vehicle assignment is locked. Contact admin for changes" |
| BR-6.3.5 | Assignment deadline is 3 days before tour | Warning only, not blocked |

### 6.4 Booking Rules

| Rule ID | Rule | Action |
|---------|------|--------|
| BR-6.4.1 | Booking without vehicle 3 days before tour | Warning in admin panel |
| BR-6.4.2 | Tour does NOT auto-cancel without vehicle | Admin handles manually |

---

## 7. User Interface Requirements

### 7.1 Guide Dashboard - My Vehicles Page

```
┌─────────────────────────────────────────────────────────────────────────┐
│ My Vehicles                                            [+ Add Vehicle]  │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌─────────┐  Toyota Hiace                              ⚫ Active       │
│  │  Photo  │  Van • 8 seats • AC                        [Edit] [Delete] │
│  └─────────┘  License: ABC-1234                                         │
│               📅 Assigned to 2 upcoming bookings                        │
│                                                                         │
│  ─────────────────────────────────────────────────────────────────────  │
│                                                                         │
│  ┌─────────┐  Nissan Caravan                            ○ Inactive     │
│  │  Photo  │  Van • 10 seats • AC                       [Edit] [Delete] │
│  └─────────┘  License: XYZ-5678                                         │
│               No upcoming bookings                                      │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

### 7.2 Add/Edit Vehicle Form

```
┌─────────────────────────────────────────────────────────────────────────┐
│ Add New Vehicle                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  Vehicle Type *          Make *                Model *                  │
│  ┌─────────────────┐    ┌─────────────────┐   ┌─────────────────┐      │
│  │ Van           ▼│    │ Toyota          │   │ Hiace           │      │
│  └─────────────────┘    └─────────────────┘   └─────────────────┘      │
│                                                                         │
│  License Plate *         Seating Capacity *   Air Conditioning *       │
│  ┌─────────────────┐    ┌─────────────────┐   ┌─────────────────┐      │
│  │ ABC-1234        │    │ 8             ▼│   │ ☑ Yes           │      │
│  └─────────────────┘    └─────────────────┘   └─────────────────┘      │
│                                                                         │
│  Description (Optional)                                                 │
│  ┌─────────────────────────────────────────────────────────────────┐   │
│  │ Comfortable air-conditioned van, perfect for group tours...     │   │
│  └─────────────────────────────────────────────────────────────────┘   │
│                                                                         │
│  ─────────────────────────────────────────────────────────────────────  │
│                                                                         │
│  Vehicle Photos * (Minimum 1, Maximum 5)                                │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐ ┌─────────┐          │
│  │ + Add   │ │ Photo 1 │ │ Photo 2 │ │         │ │         │          │
│  └─────────┘ └─────────┘ └─────────┘ └─────────┘ └─────────┘          │
│                                                                         │
│  ─────────────────────────────────────────────────────────────────────  │
│                                                                         │
│  Documents *                                                            │
│                                                                         │
│  Vehicle Registration        Vehicle Insurance                          │
│  ┌─────────────────────┐    ┌─────────────────────┐                    │
│  │ 📄 registration.pdf │    │ 📄 insurance.pdf    │                    │
│  │     [Change]        │    │     [Change]        │                    │
│  └─────────────────────┘    └─────────────────────┘                    │
│                                                                         │
│                                              [Cancel]  [Save Vehicle]   │
└─────────────────────────────────────────────────────────────────────────┘
```

### 7.3 Booking Vehicle Assignment

```
┌─────────────────────────────────────────────────────────────────────────┐
│ Assign Vehicle to Booking #12345                                        │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  Booking Details:                                                       │
│  Tour: 5 Days Cultural Triangle Tour                                    │
│  Date: December 25, 2024                                                │
│  Tourists: 4 adults, 2 children (6 total)                              │
│  Required: Van with AC, minimum 6 seats                                 │
│                                                                         │
│  ─────────────────────────────────────────────────────────────────────  │
│                                                                         │
│  ○ Select from My Vehicles                                              │
│    ┌─────────────────────────────────────────────────────────────────┐ │
│    │ Toyota Hiace (8 seats, AC) - ABC-1234                         ▼│ │
│    └─────────────────────────────────────────────────────────────────┘ │
│                                                                         │
│  ○ Add Temporary Vehicle (for this booking only)                        │
│    [Opens temporary vehicle form when selected]                         │
│                                                                         │
│  ⚠️ Once assigned, vehicle cannot be changed. Contact admin if needed.  │
│                                                                         │
│                                              [Cancel]  [Assign Vehicle] │
└─────────────────────────────────────────────────────────────────────────┘
```

### 7.4 Tourist View - After Assignment

```
┌─────────────────────────────────────────────────────────────────────────┐
│ Your Vehicle                                                            │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌───────────────────────────────┐                                     │
│  │                               │  Toyota Hiace                        │
│  │       [Vehicle Photo]         │  Van • 8 Seats • Air Conditioned    │
│  │                               │                                      │
│  └───────────────────────────────┘  License Plate: ABC-1234            │
│                                                                         │
│  ┌─────────┐ ┌─────────┐ ┌─────────┐                                   │
│  │ Photo 1 │ │ Photo 2 │ │ Photo 3 │  [View All Photos]                │
│  └─────────┘ └─────────┘ └─────────┘                                   │
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 8. Admin Panel Requirements

### 8.1 Vehicle Management

#### FR-8.1.1: View All Vehicles
- List all vehicles from all guides
- Columns: Guide Name, Vehicle Type, Make/Model, Plate, Capacity, Status, Bookings
- Filters: By guide, by type, by status
- Search: By license plate, guide name

#### FR-8.1.2: View Vehicle Details
- Full vehicle information
- Photos gallery
- Documents viewer
- Assignment history

#### FR-8.1.3: Edit Any Vehicle
- Admin can edit any field
- Even if assigned to booking
- Audit log: "Edited by Admin on [date]"

#### FR-8.1.4: Delete Any Vehicle
- Admin can delete any vehicle
- Warning if assigned to bookings
- Option to reassign bookings first

### 8.2 Booking Vehicle Oversight

#### FR-8.2.1: Bookings Without Vehicle
- Dashboard widget showing count
- List view with:
  - Booking ID, Guide, Tourist, Tour Date
  - Days until tour
- Highlight urgent (within 3 days)

#### FR-8.2.2: Assign Vehicle on Behalf of Guide
- Admin can assign saved vehicle to booking
- Admin can add temporary vehicle for booking
- Notification sent to guide

#### FR-8.2.3: Override Locked Assignment
- Admin can change assigned vehicle
- Requires confirmation
- Notification sent to guide and tourist

### 8.3 Admin Panel Layout

```
┌─────────────────────────────────────────────────────────────────────────┐
│ ADMIN: Vehicle Management                                               │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│  ┌─────────────────┐  ┌─────────────────┐  ┌─────────────────┐         │
│  │ Total Vehicles  │  │ Active Vehicles │  │ ⚠️ Bookings     │         │
│  │      156        │  │      142        │  │ Without Vehicle │         │
│  │                 │  │                 │  │       8         │         │
│  └─────────────────┘  └─────────────────┘  └─────────────────┘         │
│                                                                         │
│  ─────────────────────────────────────────────────────────────────────  │
│                                                                         │
│  [All Vehicles] [Bookings Without Vehicle] [Assignment History]         │
│                                                                         │
│  Filter: [All Guides ▼] [All Types ▼] [All Status ▼]  🔍 Search...     │
│                                                                         │
│  ┌─────────────────────────────────────────────────────────────────────┐│
│  │ Guide        │ Vehicle          │ Plate    │ Capacity │ Status     ││
│  ├─────────────────────────────────────────────────────────────────────┤│
│  │ John Silva   │ Toyota Hiace     │ ABC-1234 │ 8        │ 🟢 Active  ││
│  │ Kumar Perera │ Nissan Caravan   │ XYZ-5678 │ 10       │ 🟢 Active  ││
│  │ Amal Fernando│ Suzuki Alto      │ DEF-9012 │ 4        │ ⚫ Inactive ││
│  └─────────────────────────────────────────────────────────────────────┘│
│                                                                         │
└─────────────────────────────────────────────────────────────────────────┘
```

---

## 9. Validation Rules

### 9.1 Vehicle Form Validation

| Field | Rules |
|-------|-------|
| vehicle_type | Required, must be valid enum value |
| make | Required, max 100 characters |
| model | Required, max 100 characters |
| license_plate | Required, max 20 characters, unique per guide |
| seating_capacity | Required, integer, min 1, max 50 |
| has_ac | Required, boolean |
| description | Optional, max 500 characters |
| photos | Required, min 1, max 5, image types (jpg, png, webp), max 5MB each |
| registration | Required, file types (pdf, jpg, png), max 10MB |
| insurance | Required, file types (pdf, jpg, png), max 10MB |

### 9.2 Assignment Validation

| Check | Error Message |
|-------|---------------|
| Vehicle capacity < booking size | "Vehicle seats X, but booking requires Y seats" |
| Vehicle inactive | "Cannot assign inactive vehicle" |
| Booking already has vehicle | "This booking already has an assigned vehicle" |
| Vehicle not owned by guide | "You can only assign your own vehicles" |

---

## 10. Notifications

### 10.1 Guide Notifications

| Trigger | Notification |
|---------|--------------|
| Booking created | "New booking! Assign a vehicle before [date]" |
| 5 days before tour, no vehicle | "Reminder: Assign vehicle for booking #X" |
| 3 days before tour, no vehicle | "URGENT: Booking #X needs vehicle assignment" |
| Admin assigns vehicle | "Admin assigned vehicle to your booking #X" |
| Admin changes assigned vehicle | "Admin changed vehicle for booking #X" |

### 10.2 Admin Notifications

| Trigger | Notification |
|---------|--------------|
| Booking enters 3-day window without vehicle | "Booking #X has no vehicle, tour in 3 days" |

### 10.3 Tourist Notifications

| Trigger | Notification |
|---------|--------------|
| Vehicle assigned to booking | "Vehicle details added to your booking" |
| Vehicle changed by admin | "Vehicle updated for your booking" |

---

## 11. Future Considerations

The following features are intentionally excluded from this version but may be considered for future releases:

### 11.1 Potential Future Features

1. **Admin Vehicle Approval**
   - Optional approval workflow for new vehicles
   - Quality verification before activation

2. **Document Expiry Tracking**
   - Track registration and insurance expiry dates
   - Auto-deactivate vehicles with expired documents
   - Reminder notifications

3. **Multiple Vehicles per Booking**
   - Support for large group tours
   - Fleet management

4. **Vehicle Availability Calendar**
   - Mark vehicles unavailable for specific dates
   - Prevent double-booking

5. **Vehicle Rating System**
   - Tourists rate vehicle condition
   - Guide sees vehicle performance

6. **Driver Management**
   - Separate driver from guide
   - Driver profiles and documents

7. **Vehicle Categories/Tiers**
   - Standard, Premium, Luxury tiers
   - Pricing based on vehicle tier

---

## Document Approval

| Role | Name | Date | Signature |
|------|------|------|-----------|
| Product Owner | | | |
| Developer | | | |
| Reviewer | | | |

---

## Revision History

| Version | Date | Author | Changes |
|---------|------|--------|---------|
| 1.0 | Dec 18, 2024 | Claude | Initial requirements document |

---

*End of Document*
