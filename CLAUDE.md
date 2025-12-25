# Tourism Platform - Structure Documentation

This document defines the target architecture for the tourism platform frontend.
Use this to compare against the current implementation and track migration progress.

---

## PART 1: ROUTE STRUCTURE

### 1.1 Public Pages (Accessible by Everyone - No Auth Required)

| Route | Name | Controller | View | Status |
|-------|------|------------|------|--------|
| `/` | welcome | Closure | welcome.blade.php | DONE |
| `/tour-packages` | tour-packages.index | PublicController@tourPackages | public/tour-packages/index.blade.php | DONE |
| `/tour-packages/{plan}` | tour-packages.show | PublicController@showTourPackage | public/tour-packages/show.blade.php | DONE |
| `/tour-requests` | tour-requests.index | PublicController@tourRequests | public/tour-requests/index.blade.php | DONE |
| `/tour-requests/{request}` | tour-requests.show | PublicController@showTourRequest | public/tour-requests/show.blade.php | DONE |
| `/about` | about | PublicController@about | public/about.blade.php | DONE |
| `/contact` | contact | PublicController@contact | public/contact.blade.php | DONE |
| `/privacy-policy` | privacy-policy | PublicController@privacyPolicy | public/privacy-policy.blade.php | DONE |
| `/terms` | terms | PublicController@terms | public/terms.blade.php | DONE |

### 1.2 Tourist Dashboard Pages (Auth Required - Tourist Only)

| Route | Name | Controller | View | Status |
|-------|------|------------|------|--------|
| `/tourist/dashboard` | tourist.dashboard | TouristController@dashboard | tourist/dashboard.blade.php | DONE |
| `/tourist/settings` | tourist.settings | TouristController@settings | tourist/settings.blade.php | DONE |
| `/tourist/requests` | tourist.requests.index | TouristRequestController@index | tourist/requests/index.blade.php | DONE |
| `/tourist/requests/create` | tourist.requests.create | TouristRequestController@create | tourist/requests/create.blade.php | DONE |
| `/tourist/requests/{id}` | tourist.requests.show | TouristRequestController@show | tourist/requests/show.blade.php | DONE |
| `/tourist/requests/{id}/edit` | tourist.requests.edit | TouristRequestController@edit | tourist/requests/edit.blade.php | DONE |
| `/tourist/requests/{id}/bids/{bid}` | tourist.bids.show | BidController@show | tourist/requests/bids/show.blade.php | DONE |
| `/tourist/bookings` | tourist.bookings.index | BookingController@index | tourist/bookings/index.blade.php | DONE |
| `/tourist/bookings/{id}` | tourist.bookings.show | BookingController@show | tourist/bookings/show.blade.php | DONE |
| `/tourist/bookings/create` | tourist.bookings.create | BookingController@create | tourist/bookings/create.blade.php | DONE |
| `/tourist/proposals` | tourist.proposals.index | PlanProposalController@touristIndex | tourist/proposals/index.blade.php | DONE |
| `/tourist/proposals/{id}` | tourist.proposals.show | PlanProposalController@touristShow | tourist/proposals/show.blade.php | DONE |
| `/tourist/complaints` | tourist.complaints.index | TouristComplaintController@index | tourist/complaints/index.blade.php | DONE |
| `/tourist/complaints/create` | tourist.complaints.create | TouristComplaintController@create | tourist/complaints/create.blade.php | DONE |
| `/tourist/complaints/{id}` | tourist.complaints.show | TouristComplaintController@show | tourist/complaints/show.blade.php | DONE |

### 1.3 Guide Dashboard Pages (Auth Required - Guide Only)

| Route | Name | Controller | View | Status |
|-------|------|------------|------|--------|
| `/guide/dashboard` | guide.dashboard | GuideController@dashboard | guide/dashboard.blade.php | DONE |
| `/guide/settings` | guide.settings | GuideController@settings | guide/settings.blade.php | DONE |
| `/guide/plans` | guide.plans.index | GuidePlanController@index | guide/plans/index.blade.php | DONE |
| `/guide/plans/create` | guide.plans.create | GuidePlanController@create | guide/plans/create.blade.php | DONE |
| `/guide/plans/{id}` | guide.plans.show | GuidePlanController@show | guide/plans/show.blade.php | DONE |
| `/guide/plans/{id}/edit` | guide.plans.edit | GuidePlanController@edit | guide/plans/edit.blade.php | DONE |
| `/guide/bookings` | guide.bookings.index | GuideController@bookings | guide/bookings/index.blade.php | DONE |
| `/guide/bookings/{id}` | guide.bookings.show | GuideController@showBooking | guide/bookings/show.blade.php | DONE |
| `/guide/proposals` | guide.proposals.index | GuideProposalController@index | guide/proposals/index.blade.php | DONE |
| `/guide/proposals/{id}` | guide.proposals.show | GuideProposalController@show | guide/proposals/show.blade.php | DONE |
| `/guide/payments` | guide.payments.index | GuidePaymentController@index | guide/payments/index.blade.php | DONE |
| `/guide/payments/{id}` | guide.payments.show | GuidePaymentController@show | guide/payments/show.blade.php | DONE |
| `/guide/vehicles` | guide.vehicles.index | VehicleController@index | guide/vehicles/index.blade.php | DONE |
| `/guide/vehicles/create` | guide.vehicles.create | VehicleController@create | guide/vehicles/create.blade.php | DONE |
| `/guide/vehicles/{id}` | guide.vehicles.show | VehicleController@show | guide/vehicles/show.blade.php | DONE |
| `/guide/vehicles/{id}/edit` | guide.vehicles.edit | VehicleController@edit | guide/vehicles/edit.blade.php | DONE |
| `/guide/complaints` | guide.complaints.index | GuideComplaintController@index | guide/complaints/index.blade.php | DONE |
| `/guide/complaints/create` | guide.complaints.create | GuideComplaintController@create | guide/complaints/create.blade.php | DONE |
| `/guide/complaints/{id}` | guide.complaints.show | GuideComplaintController@show | guide/complaints/show.blade.php | DONE |

---

## PART 2: NAVIGATION STRUCTURE

### 2.1 Public Navigation (Not Logged In)
```
+-----------------------------------------------------------------------------------+
| [Logo] TravelAgency | Home | Tour Packages | Tour Requests | About | Contact | [Login] [Sign Up] |
+-----------------------------------------------------------------------------------+
```

### 2.2 Tourist Navigation (Logged In)
```
+-----------------------------------------------------------------------------------+
| [Logo] TravelAgency | Home | Tour Packages | Tour Requests | About | Contact | [Profile v] |
|                                                                       +- Dashboard |
|                                                                       +- Settings  |
|                                                                       +- Logout    |
+-----------------------------------------------------------------------------------+
```

### 2.3 Guide Navigation (Logged In)
```
+-----------------------------------------------------------------------------------+
| [Logo] TravelAgency | Home | Tour Packages | Tour Requests | About | Contact | [Profile v] |
|                                                                       +- Dashboard |
|                                                                       +- Settings  |
|                                                                       +- Logout    |
+-----------------------------------------------------------------------------------+
```

### 2.4 Footer Structure
```
+-----------------------------------------------------------------------------------+
|                              TravelAgency                                          |
|                                                                                    |
|  Quick Links          Support              Legal              Become a Partner    |
|  -----------          -------              -----              ----------------    |
|  - Home               - Contact Us         - Privacy Policy   - Become a Guide    |
|  - Tour Packages      - FAQ                - Terms of Service                     |
|  - Tour Requests      - Help Center                                               |
|  - About Us                                                                        |
|                                                                                    |
|  ------------------------------------------------------------------------------   |
|  (c) 2025 TravelAgency. All rights reserved.                                      |
+-----------------------------------------------------------------------------------+
```

**Navigation Implementation Status:**
- [x] Public navbar - `layouts/partials/navbar.blade.php`
- [x] Footer - `layouts/partials/footer.blade.php`
- [x] Profile dropdown - `layouts/partials/profile-dropdown.blade.php`
- [x] Public layout - `layouts/public.blade.php`

---

## PART 3: FILE STRUCTURE

### 3.1 Current Structure (What Exists Now)
```
resources/views/
|-- welcome.blade.php               [OK] Homepage
|-- dashboard.blade.php             [OK] Redirect page
|
|-- layouts/
|   |-- app.blade.php               [OK] Main authenticated layout
|   |-- guest.blade.php             [OK] Auth pages layout
|   |-- navigation.blade.php        [OK] Old navigation (for app layout)
|   |-- public.blade.php            [OK] NEW - Public pages layout
|   +-- partials/
|       |-- navbar.blade.php        [OK] NEW - Public navigation
|       |-- footer.blade.php        [OK] NEW - Footer component
|       +-- profile-dropdown.blade.php [OK] NEW - Profile dropdown
|
|-- public/                         [OK] PUBLIC PAGES
|   |-- about.blade.php             [OK] About us
|   |-- contact.blade.php           [OK] Contact us
|   |-- privacy-policy.blade.php    [OK] Privacy policy
|   |-- terms.blade.php             [OK] Terms & conditions
|   |-- tour-packages/
|   |   |-- index.blade.php         [OK] All tour packages
|   |   +-- show.blade.php          [OK] Single package details
|   |-- tour-requests/
|   |   |-- index.blade.php         [OK] All tour requests
|   |   +-- show.blade.php          [OK] Single request details
|   +-- plans/                      [!!] LEGACY - Keep for backward compatibility
|       |-- index.blade.php
|       |-- show.blade.php
|       +-- partials/
|
|-- tourist/                        [OK] TOURIST DASHBOARD
|   |-- dashboard.blade.php         [OK] Overview with stats
|   |-- settings.blade.php          [OK] Account settings
|   +-- complaints/
|       |-- index.blade.php         [OK]
|       |-- create.blade.php        [OK]
|       +-- show.blade.php          [OK]
|
|-- tourist-requests/               [!!] NEEDS REORGANIZATION -> move to tourist/requests/
|   |-- index.blade.php             Tourist's own requests
|   |-- create.blade.php            Create new request
|   |-- show.blade.php              Request details with bids
|   +-- bids/
|       +-- show.blade.php          View single bid
|
|-- bookings/                       [!!] NEEDS REORGANIZATION -> move to tourist/bookings/
|   |-- index.blade.php             Tourist's bookings
|   |-- create.blade.php            Create booking
|   +-- show.blade.php              Booking details
|
|-- proposals/                      [!!] MIXED FILES - Needs cleanup
|   |-- create.blade.php            Guide creates proposal
|   |-- tourist-index.blade.php     Tourist views proposals
|   +-- tourist-show.blade.php      Tourist views single proposal
|
|-- guide/                          [OK] GUIDE DASHBOARD
|   |-- dashboard.blade.php         [OK] Overview with stats & earnings
|   |-- settings.blade.php          [OK] Account settings
|   |-- plans/
|   |   |-- index.blade.php         [OK] My tour packages
|   |   |-- create.blade.php        [OK]
|   |   |-- edit.blade.php          [OK]
|   |   |-- show.blade.php          [OK]
|   |   +-- partials/form.blade.php [OK]
|   |-- bookings/
|   |   |-- index.blade.php         [OK]
|   |   |-- show.blade.php          [OK]
|   |   |-- assign-vehicle.blade.php [OK]
|   |   +-- vehicle-assignment.blade.php [OK]
|   |-- proposals/
|   |   |-- index.blade.php         [OK] Proposals from tourists
|   |   +-- show.blade.php          [OK]
|   |-- payments/
|   |   |-- index.blade.php         [OK]
|   |   +-- show.blade.php          [OK]
|   |-- vehicles/
|   |   |-- index.blade.php         [OK]
|   |   |-- create.blade.php        [OK]
|   |   |-- edit.blade.php          [OK]
|   |   +-- show.blade.php          [OK]
|   |-- complaints/
|   |   |-- index.blade.php         [OK]
|   |   |-- create.blade.php        [OK]
|   |   +-- show.blade.php          [OK]
|   |-- requests/                   [OK] Guide workspace for browsing & bidding on tour requests
|   |   |-- index.blade.php         [OK] Browse requests with filters (guide's view with bid tracking)
|   |   +-- show.blade.php          [OK] View request details with guide's own bids displayed
|   +-- bids/
|       +-- create.blade.php        [OK] Guide creates bid
|
|-- auth/                           [OK] AUTHENTICATION PAGES
|   |-- login.blade.php             [OK]
|   |-- tourist-register.blade.php  [OK] Tourist registration
|   |-- forgot-password.blade.php   [OK]
|   |-- reset-password.blade.php    [OK]
|   |-- verify-email.blade.php      [OK]
|   +-- confirm-password.blade.php  [OK]
|
|-- guide-registration/             [OK] Guide application
|   |-- create.blade.php            [OK]
|   +-- success.blade.php           [OK]
|
|-- profile/                        [OK] USER PROFILE/SETTINGS
|   |-- edit.blade.php              [OK]
|   +-- partials/
|       |-- update-profile-information-form.blade.php [OK]
|       |-- update-password-form.blade.php [OK]
|       +-- delete-user-form.blade.php [OK]
|
|-- emails/                         [OK] EMAIL TEMPLATES
|   +-- (all email templates)
|
|-- pdfs/                           [OK] PDF TEMPLATES
|   |-- booking-agreement.blade.php
|   +-- custom-booking-agreement.blade.php
|
|-- payment/
|   +-- success.blade.php           [OK]
|
+-- components/                     [OK] REUSABLE COMPONENTS
    |-- application-logo.blade.php
    |-- auth-session-status.blade.php
    |-- dropdown.blade.php
    |-- dropdown-link.blade.php
    |-- input-error.blade.php
    |-- input-label.blade.php
    |-- modal.blade.php
    |-- nav-link.blade.php
    |-- primary-button.blade.php
    |-- secondary-button.blade.php
    |-- danger-button.blade.php
    |-- text-input.blade.php
    +-- responsive-nav-link.blade.php
```

### 3.2 Proposed Clean Structure (Target)
```
resources/views/
|
|-- layouts/
|   |-- app.blade.php               Main layout with navbar & footer
|   |-- guest.blade.php             Auth pages layout
|   |-- public.blade.php            Public pages layout
|   +-- partials/
|       |-- navbar.blade.php        Navigation component
|       |-- footer.blade.php        Footer component
|       +-- profile-dropdown.blade.php
|
|-- pages/                          PUBLIC PAGES - accessible by all
|   |-- home.blade.php              Homepage (currently welcome.blade.php)
|   |-- about.blade.php
|   |-- contact.blade.php
|   |-- privacy-policy.blade.php
|   |-- terms.blade.php
|   |-- tour-packages/
|   |   |-- index.blade.php
|   |   |-- show.blade.php
|   |   +-- partials/
|   |       |-- card.blade.php
|   |       |-- filters.blade.php
|   |       +-- calendar.blade.php
|   +-- tour-requests/
|       |-- index.blade.php
|       |-- show.blade.php
|       +-- partials/
|           |-- card.blade.php
|           +-- filters.blade.php
|
|-- tourist/                        TOURIST DASHBOARD - auth required
|   |-- dashboard.blade.php
|   |-- settings.blade.php
|   |-- bookings/
|   |   |-- index.blade.php
|   |   |-- show.blade.php
|   |   +-- create.blade.php
|   |-- requests/
|   |   |-- index.blade.php
|   |   |-- show.blade.php
|   |   |-- create.blade.php
|   |   +-- edit.blade.php
|   |-- proposals/
|   |   |-- index.blade.php
|   |   +-- show.blade.php
|   +-- complaints/
|       |-- index.blade.php
|       |-- create.blade.php
|       +-- show.blade.php
|
|-- guide/                          GUIDE DASHBOARD - auth required
|   |-- dashboard.blade.php
|   |-- settings.blade.php
|   |-- plans/
|   |   |-- index.blade.php
|   |   |-- show.blade.php
|   |   |-- create.blade.php
|   |   |-- edit.blade.php
|   |   +-- partials/form.blade.php
|   |-- bookings/
|   |   |-- index.blade.php
|   |   |-- show.blade.php
|   |   |-- assign-vehicle.blade.php
|   |   +-- vehicle-assignment.blade.php
|   |-- requests/                   Guide workspace for tour requests (shows own bids)
|   |   |-- index.blade.php         Browse requests with filters
|   |   +-- show.blade.php          View request with own bids & withdraw options
|   |-- bids/
|   |   +-- create.blade.php        Create bid on a request
|   |-- proposals/
|   |   |-- index.blade.php         Proposals from tourists on guide's plans
|   |   +-- show.blade.php
|   |-- payments/
|   |   |-- index.blade.php
|   |   +-- show.blade.php
|   |-- vehicles/
|   |   |-- index.blade.php
|   |   |-- create.blade.php
|   |   |-- edit.blade.php
|   |   +-- show.blade.php
|   +-- complaints/
|       |-- index.blade.php
|       |-- create.blade.php
|       +-- show.blade.php
|
|-- auth/                           AUTHENTICATION PAGES
|   |-- login.blade.php
|   |-- register.blade.php          Tourist registration
|   |-- guide-register.blade.php    Guide application
|   |-- guide-register-success.blade.php
|   |-- forgot-password.blade.php
|   |-- reset-password.blade.php
|   +-- verify-email.blade.php
|
|-- profile/                        USER PROFILE/SETTINGS
|   |-- edit.blade.php
|   +-- partials/
|
|-- emails/                         EMAIL TEMPLATES
|-- pdfs/                           PDF TEMPLATES
+-- components/                     REUSABLE COMPONENTS
```

---

## PART 4: MIGRATION CHECKLIST

### Phase 1: Public Pages (COMPLETED)
- [x] Create `layouts/partials/navbar.blade.php`
- [x] Create `layouts/partials/footer.blade.php`
- [x] Create `layouts/partials/profile-dropdown.blade.php`
- [x] Create `layouts/public.blade.php`
- [x] Create `public/about.blade.php`
- [x] Create `public/contact.blade.php`
- [x] Create `public/privacy-policy.blade.php`
- [x] Create `public/terms.blade.php`
- [x] Create `public/tour-packages/index.blade.php`
- [x] Create `public/tour-packages/show.blade.php`
- [x] Create `public/tour-requests/index.blade.php`
- [x] Create `public/tour-requests/show.blade.php`
- [x] Update `welcome.blade.php` (homepage)
- [x] Create `PublicController.php`
- [x] Update routes in `web.php`

### Phase 2: Tourist Dashboard Reorganization (COMPLETED)
- [x] Move `tourist-requests/` -> `tourist/requests/`
- [x] Move `bookings/` -> `tourist/bookings/`
- [x] Move `proposals/tourist-*.blade.php` -> `tourist/proposals/`
- [x] Update controllers and routes
- [x] Create `tourist/requests/edit.blade.php`

### Phase 3: Guide Dashboard Cleanup (COMPLETED - NO CHANGES NEEDED)
- [x] Evaluated `guide/requests/` - **KEPT** (serves different purpose than public view)
  - Public `/tour-requests` = Read-only browsing for everyone
  - Guide `/guide/requests` = Guide workspace showing own bids, withdrawal options, bid status
- [x] Guide dashboard navigation links already correct

### Phase 4: Legacy Cleanup (COMPLETED)
- [x] Evaluate `public/plans/` - **KEPT** (still used by `/plans` routes for backward compatibility)
- [x] Remove duplicate files:
  - Deleted `tourist-requests/` folder (4 files)
  - Deleted `bookings/` folder (3 files)
  - Deleted `proposals/tourist-*.blade.php` files (2 files)
- [x] Fixed route references in views and email templates

---

## PART 5: KEY CONTROLLERS

| Controller | Purpose | Location |
|------------|---------|----------|
| PublicController | Public pages (about, contact, tour-packages, tour-requests) | `app/Http/Controllers/PublicController.php` |
| TouristController | Tourist dashboard & settings | `app/Http/Controllers/TouristController.php` |
| GuideController | Guide dashboard, bookings, settings | `app/Http/Controllers/GuideController.php` |
| TouristRequestController | Tourist's own requests CRUD | `app/Http/Controllers/TouristRequestController.php` |
| GuidePlanController | Guide's tour packages CRUD | `app/Http/Controllers/Guide/GuidePlanController.php` |
| BookingController | Tourist bookings | `app/Http/Controllers/BookingController.php` |
| BidController | Guide bids on tourist requests | `app/Http/Controllers/BidController.php` |
| VehicleController | Guide vehicle management | `app/Http/Controllers/Guide/VehicleController.php` |

---

## PART 6: DATABASE MODELS REFERENCE

| Model | Table | Key Fields |
|-------|-------|------------|
| User | users | email, password, user_type (tourist/guide/admin), status |
| Tourist | tourists | user_id, full_name, phone, nationality |
| Guide | guides | user_id, full_name, guide_id_number, guide_type, is_active |
| GuidePlan | guide_plans | guide_id, title, description, price_per_adult, num_days, **status** (active/draft/inactive) |
| TouristRequest | tourist_requests | tourist_id, title, description, budget_min, budget_max, **status** (open/closed), expires_at |
| Booking | bookings | tourist_id, guide_id, guide_plan_id, status, start_date, end_date |
| Bid | bids | guide_id, tourist_request_id, proposed_price, status |
| Vehicle | vehicles | guide_id, make, model, year, is_active |
| Complaint | complaints | complainant_id, against_id, booking_id, status |

**Important Notes:**
- `GuidePlan` uses `status` field (not `is_active`) with values: 'active', 'draft', 'inactive'
- `TouristRequest` uses `status` field with values: 'open', 'closed', 'expired'
- `Guide` and `Vehicle` use `is_active` boolean field

---

## PART 7: COMMON ISSUES & SOLUTIONS

### Issue: Column 'is_active' not found for GuidePlan
**Cause:** Using `is_active` instead of `status`
**Solution:** Use `->where('status', 'active')` for GuidePlan queries

### Issue: Route not found
**Check:** Run `php artisan route:list` to see all registered routes

### Issue: View not found
**Check:** Verify the view path matches the controller's `return view('path.to.view')`

---

## PART 8: DEVELOPMENT COMMANDS

```bash
# Clear all caches
php artisan cache:clear && php artisan config:clear && php artisan view:clear && php artisan route:clear

# List all routes
php artisan route:list

# Check for syntax errors
php -l app/Http/Controllers/PublicController.php

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start development server
php artisan serve

# Build assets
npm run build
```

---

## PART 9: PHASE 2 COMPLETION SUMMARY

### Files CREATED (Phase 2):
| File | Status |
|------|--------|
| `tourist/requests/index.blade.php` | DONE |
| `tourist/requests/create.blade.php` | DONE |
| `tourist/requests/show.blade.php` | DONE |
| `tourist/requests/edit.blade.php` | DONE |
| `tourist/requests/bids/show.blade.php` | DONE |
| `tourist/bookings/index.blade.php` | DONE |
| `tourist/bookings/create.blade.php` | DONE |
| `tourist/bookings/show.blade.php` | DONE |
| `tourist/proposals/index.blade.php` | DONE |
| `tourist/proposals/show.blade.php` | DONE |

### Routes UPDATED:
| Old Route | New Route | Status |
|-----------|-----------|--------|
| `/tourist-requests` | `/tourist/requests` | DONE (with redirect) |
| `/bookings` | `/tourist/bookings` | DONE (with redirect) |

### Controllers UPDATED:
| Controller | Changes |
|------------|---------|
| `TouristRequestController` | View paths updated to `tourist.requests.*` |
| `BookingController` | View paths updated to `tourist.bookings.*` |
| `BidController` | View paths updated to `tourist.requests.bids.*` |
| `PlanProposalController` | View paths updated to `tourist.proposals.*` |

### Files DELETED (Phase 4 - Cleanup):
| File | Status |
|------|--------|
| `tourist-requests/` folder | DELETED |
| `bookings/` folder | DELETED |
| `proposals/tourist-*.blade.php` files | DELETED |
| `public/plans/` folder | KEPT (backward compatibility) |
| `guide/requests/` folder | PENDING (Phase 3) |

---

*Last Updated: December 2024*
