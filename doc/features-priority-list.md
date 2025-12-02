# Tourism Platform - Features Priority & Implementation List

**Version:** 1.0  
**Date:** October 11, 2025  
**Total Implementation Time:** 20-22 weeks (5-5.5 months)

---

## 📋 How to Use This Document

- **Priority Levels:** P0 (Critical) → P1 (High) → P2 (Medium) → P3 (Low)
- **Status:** 🔴 Not Started | 🟡 In Progress | 🟢 Completed
- **Dependencies:** Some features require others to be completed first
- **Build Order:** Follow phases sequentially for best results

---

## 🎯 PHASE 1: FOUNDATION (Weeks 1-4)
**Goal:** Basic user system, guide registration, admin approval

### Feature 1.1: User Authentication System
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** None

**Includes:**
- ✅ User registration form (tourists)
- ✅ Email/password login
- ✅ Email verification
- ✅ Password reset functionality
- ✅ Session management
- ✅ Remember me feature
- ✅ Basic user profile page

**Technical Tasks:**
- Install Laravel Breeze or Jetstream
- Configure email settings (.env)
- Create registration validation rules
- Set up email templates
- Test with Mailtrap (development)

**Acceptance Criteria:**
- Tourist can register with email
- Verification email sent and works
- User can login/logout
- Password reset via email works
- Session persists across page loads

---

### Feature 1.2: Guide Registration Request System
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 5-6 days  
**Dependencies:** 1.1 (User Authentication)

**Includes:**
- ✅ Public guide registration request form
- ✅ Multi-step form with validation
- ✅ Document upload functionality (8 document types)
- ✅ Profile photo upload with validation
- ✅ Store data in `guide_registration_requests` table
- ✅ Email confirmation to guide
- ✅ Admin email notification

**Technical Tasks:**
- Create registration form (multi-step)
- Implement file upload with validation (size, type)
- Store files in `storage/app/guide-documents/`
- Create email templates (confirmation, admin notification)
- Validate national ID, phone format
- Test with various document formats

**Acceptance Criteria:**
- Guide can submit registration request
- All required fields validated
- Documents uploaded successfully (PDF, JPG, PNG)
- Maximum file sizes enforced (5MB photos, 10MB docs)
- Guide receives confirmation email
- Admin receives notification email
- Request saved with status "pending"

---

### Feature 1.3: Admin Panel - Guide Approval System
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 6-7 days  
**Dependencies:** 1.2 (Guide Registration)

**Includes:**
- ✅ Admin dashboard with guide requests list
- ✅ View registration request details
- ✅ View/download uploaded documents
- ✅ Update request status (pending, under review, interview scheduled, approved, rejected)
- ✅ Add interview notes
- ✅ Manual guide account creation after approval
- ✅ Generate guide ID number automatically
- ✅ Send credentials email to approved guide

**Technical Tasks:**
- Install Filament Admin Panel (recommended) or build custom
- Create GuideRegistrationResource in Filament
- Build document viewer/downloader
- Create guide account creation form
- Auto-generate unique guide ID (e.g., GD-2025-0001)
- Create guide credentials email template
- Implement role-based access (only Registration Manager can approve)

**Acceptance Criteria:**
- Admin can view all registration requests
- Admin can filter by status
- Admin can view/download all documents
- Admin can update status with notes
- Admin can create full guide account
- Guide ID auto-generated uniquely
- Guide receives login credentials email
- Guide can login after approval

---

### Feature 1.4: Role-Based Access Control (RBAC)
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** 1.1 (User Authentication)

**Includes:**
- ✅ Define roles: Tourist, Guide, Super Admin, Content Moderator, Finance Admin, Complaint Manager, Registration Manager
- ✅ Middleware to check user role
- ✅ Route protection based on roles
- ✅ Dashboard redirection based on user type

**Technical Tasks:**
- Install Spatie Laravel Permission package (optional) or build custom
- Create middleware: `CheckRole.php`
- Define role constants or enum
- Protect routes with role middleware
- Create separate dashboard routes per user type
- Test access restrictions

**Acceptance Criteria:**
- Tourist can only access tourist features
- Guide can only access guide features
- Admin roles have specific permissions
- Unauthorized access returns 403 error
- User redirected to correct dashboard after login

---

### Feature 1.5: Basic Dashboards (Empty Scaffolding)
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 2-3 days  
**Dependencies:** 1.4 (RBAC)

**Includes:**
- ✅ Tourist dashboard layout (no data yet)
- ✅ Guide dashboard layout (no data yet)
- ✅ Admin dashboard layout (basic welcome)
- ✅ Navigation menu structure
- ✅ Responsive design with Tailwind CSS

**Technical Tasks:**
- Create dashboard blade templates
- Setup navigation components
- Implement responsive sidebar/header
- Add placeholder sections for future features
- Style with Tailwind CSS

**Acceptance Criteria:**
- Each user type sees their dashboard after login
- Navigation menu shows role-appropriate links
- Responsive on mobile/tablet/desktop
- Empty state messages where applicable

---

## 📊 Phase 1 Deliverables Checklist
- [ ] Tourists can register and login
- [ ] Guides can submit registration requests with documents
- [ ] Admins can review and approve guide registrations
- [ ] Role-based access control working
- [ ] Basic dashboards for each user type
- [ ] Email notifications configured and working

---

## 🏗️ PHASE 2: CORE BOOKING FLOW (Weeks 5-10)
**Goal:** Guide plans functional, tourists can browse and book (without payment initially)

### Feature 2.1: Guide Tour Plan Creation
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 8-10 days  
**Dependencies:** Phase 1 complete

**Includes:**
- ✅ Multi-step tour plan creation form
- ✅ Basic information (title, description, days/nights, locations)
- ✅ Pricing (adult/child, group size)
- ✅ Availability type (date range or always available)
- ✅ Vehicle details with photos
- ✅ Day-by-day itinerary builder
- ✅ Accommodation details per day
- ✅ Meal inclusions per day
- ✅ Optional add-ons per day
- ✅ Cover photo and gallery uploads
- ✅ Inclusions/exclusions text
- ✅ Save as draft or publish
- ✅ Preview plan before publishing

**Technical Tasks:**
- Create multi-step form component (Alpine.js or Vue)
- Implement dynamic itinerary day addition/removal
- File upload for multiple photos (cover, gallery, vehicle)
- Validate pricing (child < adult)
- JSON storage for destinations, tags, dietary options
- Create itinerary sub-form with meal options
- Add-ons sub-form with participant limits
- Draft/publish status toggle
- Image optimization (resize/compress on upload)

**Acceptance Criteria:**
- Guide can create complete tour plan
- All fields validated properly
- Photos upload and display correctly
- Itinerary days can be added/removed dynamically
- Add-ons link to specific days
- Plan saves as draft without publishing
- Published plans appear in guide's plan list
- Preview shows exactly how tourists will see it

---

### Feature 2.2: Guide Plan Management
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** 2.1 (Plan Creation)

**Includes:**
- ✅ View all own plans (list view)
- ✅ Filter by status (draft, active, inactive)
- ✅ Edit existing plans
- ✅ Delete plans (only if no bookings)
- ✅ Duplicate plan feature
- ✅ Change plan status (active/inactive)
- ✅ View plan statistics (views, bookings)

**Technical Tasks:**
- Create plans list page with filters
- Implement edit form (same as create)
- Soft delete plans with bookings
- Clone plan functionality
- Toggle active/inactive status
- Display view count and booking count

**Acceptance Criteria:**
- Guide sees all their plans
- Can filter by status
- Can edit draft and active plans
- Cannot delete plans with confirmed bookings
- Duplicate creates editable copy
- Status changes take effect immediately

---

### Feature 2.3: Public Guide Plan Browsing (No Login Required)
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 6-7 days  
**Dependencies:** 2.1 (Plan Creation)

**Includes:**
- ✅ Public search page
- ✅ Search filters (destination, duration, price, dates, style, group size)
- ✅ Grid/list view toggle
- ✅ Sort options (price, duration, popularity, newest)
- ✅ Plan cards with key info
- ✅ Pagination
- ✅ No login required to browse

**Technical Tasks:**
- Create search page with filter sidebar
- Implement filter logic (multiple destinations, price range slider)
- Build plan card component
- Add pagination (20 results per page)
- Create sort dropdown
- View toggle (grid vs list)
- Optimize query performance with indexes
- Handle empty search results gracefully

**Acceptance Criteria:**
- Anyone can view search page without login
- Filters work correctly (single and multiple selections)
- Price slider filters results accurately
- Sort changes order as expected
- Pagination works smoothly
- Results load in < 1 second
- Empty state shows helpful message

---

### Feature 2.4: Guide Plan Detail Page
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 5-6 days  
**Dependencies:** 2.3 (Plan Browsing)

**Includes:**
- ✅ Complete plan information display
- ✅ Photo gallery with lightbox
- ✅ Day-by-day itinerary accordion
- ✅ Vehicle details and photos
- ✅ Optional add-ons display
- ✅ Pricing calculator (adults/children input)
- ✅ Guide information (nickname, experience, rating)
- ✅ Reviews section (empty for now)
- ✅ "Save to Favorites" button (requires login)
- ✅ "Book Now" button (requires login)
- ✅ Share buttons (social media, copy link)
- ✅ Availability calendar (implemented in 2.5)

**Technical Tasks:**
- Create detailed plan view template
- Implement photo gallery with JavaScript lightbox
- Build accordion for itinerary days
- Dynamic price calculator with JavaScript
- Add-ons checkboxes with price updates
- Favorite button (AJAX save)
- Social share functionality
- Responsive design for all sections

**Acceptance Criteria:**
- All plan details visible and readable
- Photos open in lightbox gallery
- Itinerary days expand/collapse smoothly
- Price calculator updates in real-time
- Guide info displayed (no contact details yet)
- Save to favorites works (if logged in)
- Book button prompts login if needed
- Page loads in < 2 seconds

---

### Feature 2.5: Guide Availability Calendar
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 7-8 days  
**Dependencies:** 2.4 (Plan Detail Page)

**Includes:**
- ✅ Monthly calendar view showing guide availability
- ✅ Color-coded dates (green=available, red=booked, gray=past/unavailable)
- ✅ Date range selection by tourist
- ✅ Validation: prevent selecting dates with conflicts
- ✅ For seasonal plans: only show available date range
- ✅ For always-available plans: check against guide's bookings
- ✅ Real-time availability checking
- ✅ Duration enforcement (must select exact plan duration)

**Technical Tasks:**
- Choose calendar library (FullCalendar.js or build custom)
- Query `bookings` table for guide's booked dates
- Implement conflict detection algorithm
- Color code dates based on availability
- Handle date range selection with validation
- AJAX call for real-time availability check
- Display error if conflict detected
- Auto-calculate end date based on plan duration

**Acceptance Criteria:**
- Calendar displays correctly with color coding
- Booked dates show as red/unavailable
- Tourist can only select available (green) dates
- Selecting dates with conflicts shows error message
- For 5-day plan, selecting start date highlights next 5 days
- Conflict in any selected day prevents booking
- Calendar refreshes when guide receives new booking
- Works on mobile (touch-friendly)

---

### Feature 2.6: Booking Form (Without Payment)
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 6-7 days  
**Dependencies:** 2.5 (Availability Calendar)

**Includes:**
- ✅ Tourist must be logged in
- ✅ Step 1: Traveler details (adults, children, ages, special requests)
- ✅ Step 2: Select optional add-ons with participant numbers
- ✅ Step 3: Review booking summary with price breakdown
- ✅ Final availability check before confirmation
- ✅ Generate agreement PDF
- ✅ Display agreement for review
- ✅ "I agree" checkbox
- ✅ Create booking record (status: pending - payment comes later)

**Technical Tasks:**
- Create multi-step booking form
- Validate traveler details (min 1 adult)
- Add-ons selection with dynamic price calculation
- Build booking summary component
- Calculate total: base + add-ons + 10% platform fee
- Implement final availability check (lock dates temporarily)
- Generate PDF using DomPDF or similar
- Store PDF in `storage/app/agreements/`
- Create booking record with all details
- Send confirmation email (no payment yet)

**Acceptance Criteria:**
- Only logged-in tourists can book
- All traveler details validated
- Add-ons selection works with participant limits
- Price calculation accurate (including 10% fee)
- Final availability check prevents double-booking
- Agreement PDF generates correctly with all details
- Tourist can download agreement
- Booking record created with status "pending"
- Confirmation email sent (mentions payment needed)

---

### Feature 2.7: Tourist Booking Dashboard
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 2.6 (Booking Form)

**Includes:**
- ✅ View all bookings (upcoming, past, pending payment)
- ✅ Booking details page
- ✅ Download agreement PDF
- ✅ View tour itinerary
- ✅ Contact admin button
- ✅ Pending payment notice (for Phase 4)

**Technical Tasks:**
- Create bookings list page with tabs
- Filter by booking status
- Display booking cards with key info
- Link to booking details page
- Show PDF download button
- Add "Contact Admin" link to complaint form

**Acceptance Criteria:**
- Tourist sees all their bookings
- Can filter by status (pending, upcoming, past)
- Booking details page shows complete information
- PDF downloads correctly
- Status badges clear (pending payment, confirmed, etc.)

---

### Feature 2.8: Guide Booking Dashboard
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 2.6 (Booking Form)

**Includes:**
- ✅ Calendar view of all booked dates
- ✅ List view of bookings (upcoming, past)
- ✅ Booking details page
- ✅ Tourist information (nickname only, until payment)
- ✅ View agreement PDF
- ✅ Earnings information (owed amount)
- ✅ Payment status tracking

**Technical Tasks:**
- Create calendar view of guide's bookings
- Build bookings list with filters
- Display booking details with tour info
- Show tourist nickname (hide real name pre-payment)
- Calculate guide earnings (90% of subtotal)
- Show payment status badge

**Acceptance Criteria:**
- Guide sees calendar with all booked dates
- Can view bookings in list format
- Booking details complete and clear
- Tourist contact hidden until payment
- Earnings correctly calculated
- Payment status visible (pending, partial, paid)

---

## 📊 Phase 2 Deliverables Checklist
- [ ] Guides can create and manage tour plans
- [ ] Public can browse and search guide plans
- [ ] Availability calendar shows guide's free/booked dates
- [ ] Tourists can book plans (creates pending booking)
- [ ] Agreement PDFs generate correctly
- [ ] Both dashboards show bookings appropriately
- [ ] No payment processing yet (comes in Phase 4)

---

## 🎯 PHASE 3: BIDDING SYSTEM (Weeks 11-13)
**Goal:** Tourist requests and guide bidding fully functional

### Feature 3.1: Tourist Request Posting
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 5-6 days  
**Dependencies:** Phase 1 complete

**Includes:**
- ✅ Create request form (tourist must be logged in)
- ✅ All fields: title, description, duration, destinations, travelers, dates, budget, preferences
- ✅ Dietary requirements checkboxes
- ✅ Accessibility needs textarea
- ✅ Date flexibility option
- ✅ Request saved with status "open"
- ✅ Confirmation email to tourist
- ✅ Request visible to guides immediately

**Technical Tasks:**
- Create comprehensive request form
- Validate all required fields
- Store destinations as JSON array
- Handle flexible dates with range
- Save trip focus as JSON array
- Create request confirmation email
- Query to show requests to guides (filtered by availability)

**Acceptance Criteria:**
- Tourist can create detailed request
- All fields validated properly
- Flexible dates option works
- Request saved with unique ID
- Tourist receives confirmation email
- Request appears in guides' feed immediately
- Can edit request if no bids received

---

### Feature 3.2: Guide View Available Requests
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 3.1 (Request Posting)

**Includes:**
- ✅ List all open tourist requests
- ✅ Filter by destination, duration, budget, dates
- ✅ Hide requests where guide has date conflicts
- ✅ Show badge if guide already bid
- ✅ Request detail page (full information)
- ✅ "Submit Bid" button

**Technical Tasks:**
- Create requests list page for guides
- Implement availability conflict checking
- Query: exclude requests overlapping guide's bookings
- Build filter sidebar
- Display request cards with key info
- Show "Already Bid" badge if applicable
- Create request detail view
- Disable bid button if 2 bids already submitted

**Acceptance Criteria:**
- Guide sees all open requests (no date conflicts)
- Requests with guide's date conflicts not shown
- Filters work correctly
- Can view full request details
- "Submit Bid" button visible if eligible
- "Already Bid" indicator shows if guide bid before
- "Bid Limit Reached" message if 2 bids submitted

---

### Feature 3.3: Guide Submit Bid
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 8-10 days  
**Dependencies:** 3.2 (View Requests)

**Includes:**
- ✅ Bid submission form (similar to guide plan creation)
- ✅ Proposed dates (can differ if tourist flexible)
- ✅ Day-by-day itinerary builder
- ✅ Pricing breakdown (accommodation, transport, meals, guide fee)
- ✅ Vehicle details
- ✅ Accommodation details
- ✅ Personal message to tourist
- ✅ Alternative suggestions field
- ✅ Validate guide availability (no conflicts)
- ✅ Enforce 2-bid limit per request
- ✅ Track bid number (1 or 2)
- ✅ Email notification to tourist

**Technical Tasks:**
- Create comprehensive bid form
- Reuse itinerary builder from guide plan creation
- Implement pricing breakdown calculator
- Final availability check before submission
- Enforce 2-bid limit (query existing bids)
- Store bid number (1 or 2)
- Store itinerary, pricing as JSON
- Send email to tourist with bid notification
- Link to view bid

**Acceptance Criteria:**
- Guide can submit detailed bid proposal
- Itinerary builder works smoothly
- Pricing breakdown clear and validated
- Cannot submit if dates conflict with existing bookings
- Cannot submit more than 2 bids per request
- Bid number correctly tracked
- Tourist receives email notification
- Bid saved with status "pending"

---

### Feature 3.4: Tourist View and Manage Bids
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 6-7 days  
**Dependencies:** 3.3 (Submit Bid)

**Includes:**
- ✅ View all bids for own requests
- ✅ Bid cards showing key info (guide experience, price, dates)
- ✅ "View Full Proposal" button
- ✅ Full bid detail page (complete itinerary, pricing)
- ✅ "Accept Bid" button
- ✅ "Reject Bid" button with reason selection
- ✅ Compare multiple bids side-by-side
- ✅ Acceptance confirmation modal

**Technical Tasks:**
- Create bids list page per request
- Display bid summary cards
- Build full bid detail page
- Implement accept/reject actions
- Create rejection reason modal (dropdown + text)
- Handle acceptance: close request, create booking
- Handle rejection: notify guide with reason
- Allow guide to submit bid #2 if bid #1 rejected
- Block guide from bidding if bid #2 rejected

**Acceptance Criteria:**
- Tourist sees all received bids
- Can view complete bid details
- Accept bid closes request to other bids
- Rejection requires reason selection
- Guide receives rejection email with reason
- Guide can submit revised bid (#2) after rejection
- Guide blocked after 2nd rejection
- Acceptance creates booking (pending payment)

---

### Feature 3.5: Bid Status Management
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** 3.4 (Manage Bids)

**Includes:**
- ✅ Bid statuses: pending, accepted, rejected, withdrawn
- ✅ Guide can withdraw pending bid
- ✅ Withdrawal counts toward 2-bid limit
- ✅ Tourist notified of withdrawal
- ✅ Guide dashboard shows bid statuses

**Technical Tasks:**
- Implement bid withdrawal functionality
- Update bid status on withdrawal
- Notify tourist via email
- Display bid status badges in guide dashboard
- Handle withdrawn bids in bid count

**Acceptance Criteria:**
- Guide can withdraw bid if status is "pending"
- Cannot withdraw after tourist accepts/rejects
- Withdrawal counts as one of the 2 allowed bids
- Tourist sees bid disappear after withdrawal
- Guide dashboard shows all bid statuses clearly

---

## 📊 Phase 3 Deliverables Checklist
- [ ] Tourists can post detailed custom requests
- [ ] Guides can view requests (filtered by availability)
- [ ] Guides can submit bids (max 2 per request)
- [ ] Tourists can view, accept, or reject bids with reasons
- [ ] Bid rejection allows guide to submit revised bid
- [ ] 2-bid limit enforced properly
- [ ] All email notifications working

---

## 💳 PHASE 4: PAYMENT INTEGRATION (Weeks 14-16)
**Goal:** Complete payment flow with Stripe

### Feature 4.1: Stripe Integration Setup
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** Phase 2 & 3 complete

**Includes:**
- ✅ Install Laravel Cashier (Stripe)
- ✅ Configure Stripe API keys (.env)
- ✅ Test mode setup with test cards
- ✅ Webhook endpoint configuration
- ✅ Stripe webhook verification

**Technical Tasks:**
- Install Laravel Cashier: `composer require laravel/cashier`
- Add Stripe keys to .env (test mode)
- Configure webhook URL in Stripe dashboard
- Create webhook controller
- Verify webhook signatures
- Test with Stripe CLI

**Acceptance Criteria:**
- Stripe SDK installed and configured
- Test API keys working
- Webhook endpoint accessible
- Webhook signature verification working
- Can create test checkout sessions

---

### Feature 4.2: Checkout & Payment Processing
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 6-7 days  
**Dependencies:** 4.1 (Stripe Setup)

**Includes:**
- ✅ "Proceed to Payment" button in booking flow
- ✅ Create Stripe Checkout session
- ✅ Redirect to Stripe hosted checkout
- ✅ Handle successful payment
- ✅ Handle failed payment
- ✅ Update booking status to "paid"
- ✅ Store Stripe payment ID
- ✅ Lock guide calendar dates

**Technical Tasks:**
- Create payment controller
- Build Checkout session with line items
- Include booking details in metadata
- Redirect user to Stripe Checkout
- Create success/cancel return URLs
- Handle webhook: `checkout.session.completed`
- Update booking status on success
- Block guide's calendar for paid bookings
- Send confirmation emails

**Acceptance Criteria:**
- Tourist redirected to Stripe Checkout
- Can pay with test cards (4242 4242 4242 4242)
- Successful payment updates booking to "paid"
- Failed payment shows error, allows retry
- Stripe payment ID stored in database
- Guide's calendar dates blocked immediately
- Both parties receive confirmation email

---

### Feature 4.3: Payment Confirmation & Contact Reveal
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 4.2 (Payment Processing)

**Includes:**
- ✅ Generate final agreement PDF after payment
- ✅ Reveal guide's real name, phone, email to tourist
- ✅ Reveal tourist's real name, phone, email to guide
- ✅ Send agreement PDF to both parties
- ✅ Create guide payment record (90% owed)
- ✅ Update booking status to "paid"

**Technical Tasks:**
- Regenerate agreement PDF with contact details
- Update booking details pages to show real names
- Create guide_payments record
- Calculate guide amount (90% of subtotal)
- Set payment status to "pending"
- Send confirmation emails with PDF attachments
- Display contact info in booking details

**Acceptance Criteria:**
- Agreement PDF includes full contact details
- Tourist sees guide's name, phone, email
- Guide sees tourist's name, phone, email
- Both receive PDF via email
- Guide payment record created correctly
- Guide dashboard shows amount owed
- All information accurate and complete

---

### Feature 4.4: Guide Payment Tracking (Admin)
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 6-7 days  
**Dependencies:** 4.3 (Payment Confirmation)

**Includes:**
- ✅ Admin view all bookings with payment status
- ✅ Filter by guide, status, date
- ✅ "Record Payment" button per booking
- ✅ Payment recording modal (amount, date, method, reference, notes)
- ✅ Support partial payments
- ✅ Update payment status (pending → partially_paid → paid)
- ✅ Create payment transaction records
- ✅ Send payment confirmation email to guide

**Technical Tasks:**
- Create admin payment management page (Filament)
- Build payment recording form
- Validate payment amount ≤ remaining amount
- Update guide_payments table
- Create guide_payment_transactions record
- Calculate remaining amount
- Update payment status based on total paid
- Send email to guide with payment details

**Acceptance Criteria:**
- Finance Admin can view all pending payments
- Can filter by guide or date range
- Can record full or partial payment
- Payment history visible for each booking
- Guide receives email notification
- Guide dashboard updates with paid amount
- Cannot record payment exceeding owed amount

---

### Feature 4.5: Guide Earnings Dashboard
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 4.4 (Payment Tracking)

**Includes:**
- ✅ Total pending earnings
- ✅ Total paid earnings
- ✅ This month earnings
- ✅ All-time earnings
- ✅ Earnings table with payment status
- ✅ Filter by status and date
- ✅ Payment history per booking

**Technical Tasks:**
- Create earnings dashboard page
- Calculate totals from guide_payments
- Build earnings table with pagination
- Display payment status badges
- Show payment history with dates/amounts
- Add export functionality (CSV)

**Acceptance Criteria:**
- Guide sees accurate earnings summary
- Table shows all bookings with payment status
- Can filter by status (pending, partial, paid)
- Payment history clearly displayed
- Can export earnings report

---

## 📊 Phase 4 Deliverables Checklist
- [ ] Stripe integration complete and tested
- [ ] Tourists can pay via Stripe Checkout
- [ ] Payment confirmation updates booking status
- [ ] Contact details revealed after payment
- [ ] Agreement PDFs sent to both parties
- [ ] Admin can record guide payments (full/partial)
- [ ] Guide sees earnings dashboard with payment status
- [ ] All payment emails working

---

## 🤖 PHASE 5: AUTOMATION (Weeks 17-18)
**Goal:** Automated status updates and email reminders

### Feature 5.1: Scheduled Tasks Setup
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 2-3 days  
**Dependencies:** Phase 4 complete

**Includes:**
- ✅ Setup Laravel Task Scheduler
- ✅ Configure cron job on server
- ✅ Create custom Artisan commands
- ✅ Test scheduler in development

**Technical Tasks:**
- Create command: `UpdateBookingStatuses.php`
- Register commands in `routes/console.php` (Laravel 11)
- Configure schedule (daily at midnight)
- Test with `php artisan schedule:run`
- Setup server cron: `* * * * * php artisan schedule:run`

**Email Configuration for Development:**
- Use Gmail SMTP for development (free)
- Configure in .env:
  ```
  MAIL_MAILER=smtp
  MAIL_HOST=smtp.gmail.com
  MAIL_PORT=587
  MAIL_USERNAME=your-email@gmail.com
  MAIL_PASSWORD=your-app-password
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=your-email@gmail.com
  MAIL_FROM_NAME="Tourism Platform"
  ```
- Generate App Password: Google Account → Security → 2-Step Verification → App Passwords
- For production: Consider Gmail (free tier: 500 emails/day) or upgrade to paid service

**Acceptance Criteria:**
- Task scheduler configured
- Commands registered correctly
- Cron job runs daily
- Can manually test commands
- Logs show execution history

---

### Feature 5.2: Automatic Booking Status Updates
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 5.1 (Scheduler Setup)

**Includes:**
- ✅ Auto-update: Paid → Upcoming (7 days before tour)
- ✅ Auto-update: Upcoming → In Progress (start date)
- ✅ Auto-update: In Progress → Completed (day after end date)
- ✅ Send reminder emails at each transition
- ✅ Log all status changes

**Technical Tasks:**
- Create status update logic in command
- Query bookings by date ranges
- Update booking_status field
- Send reminder emails for each transition
- Log status changes with timestamps
- Handle edge cases (cancelled bookings)

**Acceptance Criteria:**
- Bookings automatically move to "upcoming" 7 days before
- Bookings move to "in progress" on start date
- Bookings move to "completed" day after end date
- Reminder emails sent at each transition
- Status changes logged for audit
- Does not affect cancelled bookings

---

### Feature 5.3: Seasonal Plan Auto-Deactivation
**Priority:** P2 (Medium)  
**Status:** 🔴 Not Started  
**Estimated Time:** 2-3 days  
**Dependencies:** 5.1 (Scheduler Setup)

**Includes:**
- ✅ Daily check for expired seasonal plans
- ✅ Auto-change status from "active" to "inactive"
- ✅ Log deactivations
- ✅ Optional email to guide about expired plans

**Technical Tasks:**
- Create command: `DeactivateExpiredPlans.php`
- Query plans where `available_end_date < today`
- Update status to "inactive"
- Log deactivations
- Optional: Send email to guide

**Acceptance Criteria:**
- Seasonal plans deactivate day after end date
- Deactivated plans hidden from search
- Guide can still view inactive plans
- Log shows which plans were deactivated
- No impact on existing bookings

---

### Feature 5.4: Email Notification System Enhancement
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 5-6 days  
**Dependencies:** Phases 2, 3, 4 complete

**Includes:**
- ✅ All notification types configured
- ✅ Email templates designed and tested
- ✅ Notification logging in database
- ✅ Queue jobs for async email sending

**Notification Types:**
- Booking confirmed (tourist & guide)
- Payment received (guide)
- Bid received (tourist)
- Bid accepted/rejected (guide)
- Tour reminder (7 days before, tourist & guide)
- Tour starting today (tourist & guide)
- Tour completed - rate your experience (tourist)
- Payment processed (guide)
- Guide registration approved (guide)

**Technical Tasks:**
- Create Mailable classes for each notification
- Design email templates (responsive HTML)
- Configure queue driver (database or Redis)
- Test all email templates with real data (using Gmail SMTP)
- Log all sent notifications
- Handle failed email sending

**Gmail SMTP Configuration:**
- Already configured in Phase 5.1
- Test with `php artisan tinker` → `Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });`
- Monitor Gmail sending limits (500 emails/day for free accounts)
- For production with higher volume: Use Gmail Workspace or dedicated service

**Acceptance Criteria:**
- All email templates responsive and branded
- Emails sent asynchronously (no page delays)
- Failed emails logged for retry
- Unsubscribe link in all emails (optional)
- All notifications logged in database
- Test emails delivered to Mailtrap

---

## 📊 Phase 5 Deliverables Checklist
- [ ] Task scheduler configured and running
- [ ] Booking statuses update automatically
- [ ] Reminder emails sent at correct times
- [ ] Seasonal plans auto-deactivate
- [ ] All email notifications configured
- [ ] Emails sent asynchronously via queue
- [ ] Failed emails logged and retried

---

## 🔧 PHASE 6: ADMIN PANEL COMPLETION (Weeks 19-20)
**Goal:** Full admin functionality with Filament

### Feature 6.1: Filament Admin Panel Setup
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** Phase 1 complete

**Includes:**
- ✅ Install Filament v3
- ✅ Create admin resources for main entities
- ✅ Configure role-based access per resource
- ✅ Customize dashboard with widgets

**Technical Tasks:**
- Install Filament: `composer require filament/filament`
- Run `php artisan filament:install --panels`
- Create resources for: Bookings, Guides, Tourists, Plans, Requests, Payments
- Configure permissions per resource
- Create dashboard widgets (stats, charts)

**Acceptance Criteria:**
- Filament admin panel accessible at /admin
- All main entities have resources
- Role-based access working
- Dashboard shows key metrics
- Navigation organized logically

---

### Feature 6.2: Booking Management (Admin)
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 6.1 (Filament Setup)

**Includes:**
- ✅ View all bookings with filters
- ✅ Booking details modal
- ✅ View agreement PDF
- ✅ Cancel booking (admin only)
- ✅ Update booking status manually (if needed)
- ✅ View payment information
- ✅ Add internal notes

**Technical Tasks:**
- Create BookingResource in Filament
- Add filters: status, date range, guide, tourist
- Build detail view modal
- Implement cancellation with reason
- PDF viewer/download link
- Internal notes field (admin only)

**Acceptance Criteria:**
- Admin can view all bookings
- Filters work correctly
- Can view complete booking details
- Can cancel bookings with reason
- Cancellation releases guide's calendar
- Internal notes saved privately

---

### Feature 6.3: Content Moderation
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** 6.1 (Filament Setup)

**Includes:**
- ✅ View all guide plans
- ✅ View all tourist requests
- ✅ Delete inappropriate content
- ✅ View deletion reason
- ✅ Content stays visible in existing bookings

**Technical Tasks:**
- Create content moderation dashboard
- Soft delete with reason field
- Filter deleted content
- Ensure bookings unaffected by deletion

**Acceptance Criteria:**
- Content Moderator can view all plans/requests
- Can delete with mandatory reason
- Deletion logged with date/admin
- Deleted content hidden from public
- Existing bookings still access deleted content

---

### Feature 6.4: Analytics Dashboard
**Priority:** P2 (Medium)  
**Status:** 🔴 Not Started  
**Estimated Time:** 5-6 days  
**Dependencies:** 6.1 (Filament Setup)

**Includes:**
- ✅ Revenue metrics (total, this month, last month)
- ✅ Booking statistics (total, pending, completed)
- ✅ User counts (tourists, guides, active)
- ✅ Popular destinations chart
- ✅ Revenue trend chart (12 months)
- ✅ Top guides by bookings/revenue
- ✅ Export reports (CSV, Excel)

**Technical Tasks:**
- Create dashboard widgets in Filament
- Query database for metrics
- Build charts with Filament Chart widgets
- Calculate popular destinations from bookings
- Create export functionality
- Add date range filters

**Acceptance Criteria:**
- Dashboard shows accurate metrics
- Charts visualize trends clearly
- Can filter by date range
- Top guides list accurate
- Export generates complete reports
- Refreshes in real-time

---

## 📊 Phase 6 Deliverables Checklist
- [ ] Filament admin panel fully configured
- [ ] All main entities have admin resources
- [ ] Booking management complete
- [ ] Content moderation working
- [ ] Analytics dashboard with charts
- [ ] Export functionality working
- [ ] Role-based permissions enforced

---

## ⭐ PHASE 7: REVIEWS & POLISH (Weeks 21-22)
**Goal:** Reviews system, favorites, UI improvements

### Feature 7.1: Review & Rating System
**Priority:** P1 (High)  
**Status:** 🔴 Not Started  
**Estimated Time:** 6-7 days  
**Dependencies:** Phase 5 (Completed bookings)

**Includes:**
- ✅ Review submission form (after tour completion)
- ✅ Overall rating (1-5 stars)
- ✅ Category ratings (6 categories)
- ✅ Written review (optional)
- ✅ Photo uploads (up to 5)
- ✅ Anonymous option
- ✅ Display reviews on guide profile
- ✅ Display reviews on guide plans
- ✅ Calculate average ratings
- ✅ Update guide's average rating

**Technical Tasks:**
- Create review form with star ratings
- Validate ratings (1-5 range)
- Image upload for review photos
- Calculate category averages
- Update guide's cached average_rating
- Display reviews with pagination
- Filter reviews by rating
- Admin can hide inappropriate reviews

**Acceptance Criteria:**
- Tourist can submit review after completed tour
- All rating categories required
- Photos upload successfully
- Anonymous option hides tourist name
- Reviews appear on guide profile immediately
- Guide's average rating updates
- Admin can hide reviews with reason

---

### Feature 7.2: Favorites System
**Priority:** P2 (Medium)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** Phase 2 (Guide Plans)

**Includes:**
- ✅ Save plan to favorites (heart icon)
- ✅ Remove from favorites
- ✅ "My Favorites" page
- ✅ Show if plan no longer available

**Technical Tasks:**
- Add favorite button to plan cards/detail
- AJAX save/remove functionality
- Create favorites page
- Handle deleted plans (show as unavailable)

**Acceptance Criteria:**
- Logged-in tourists can save favorites
- Heart icon toggles on/off
- Favorites page shows all saved plans
- Deleted plans show "No longer available"
- Guest users prompted to login

---

### Feature 7.3: Recently Viewed Plans
**Priority:** P3 (Low)  
**Status:** 🔴 Not Started  
**Estimated Time:** 2-3 days  
**Dependencies:** Phase 2 (Guide Plans)

**Includes:**
- ✅ Track last 20 viewed plans per tourist
- ✅ "Recently Viewed" section in dashboard
- ✅ Link back to plan details

**Technical Tasks:**
- Insert record on plan detail page view
- Limit to 20 per user (delete oldest)
- Create recently viewed widget
- Link to plan detail pages

**Acceptance Criteria:**
- System tracks viewed plans
- Tourist sees last 20 viewed
- Links work correctly
- Old views automatically deleted

---

### Feature 7.4: UI/UX Polish
**Priority:** P2 (Medium)  
**Status:** 🔴 Not Started  
**Estimated Time:** 5-6 days  
**Dependencies:** All features complete

**Includes:**
- ✅ Consistent styling across all pages
- ✅ Loading states and spinners
- ✅ Error message improvements
- ✅ Success notifications (toasts)
- ✅ Empty state illustrations
- ✅ Form validation error display
- ✅ Mobile responsiveness review
- ✅ Accessibility improvements

**Technical Tasks:**
- Review all pages for consistency
- Add loading spinners to async actions
- Implement toast notification library
- Create empty state components
- Test mobile layout on real devices
- Add ARIA labels for screen readers
- Improve form error messages

**Acceptance Criteria:**
- All pages styled consistently
- Loading states prevent double-submissions
- Error messages helpful and clear
- Success toasts appear after actions
- Mobile layout works perfectly
- Keyboard navigation functional
- Screen reader compatible

---

## 📊 Phase 7 Deliverables Checklist
- [ ] Review system complete and functional
- [ ] Tourists can rate and review tours
- [ ] Reviews display on guide profiles
- [ ] Favorites and recently viewed working
- [ ] UI consistent and polished
- [ ] Loading states and notifications
- [ ] Mobile responsive throughout
- [ ] Accessibility standards met

---

## 🚀 PHASE 8: TESTING & LAUNCH (Weeks 23-24)
**Goal:** Comprehensive testing and production deployment

### Feature 8.1: Comprehensive Testing
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 5-6 days  
**Dependencies:** All features complete

**Includes:**
- ✅ Manual feature testing (all flows)
- ✅ Payment testing with Stripe test cards
- ✅ Email testing (all notification types)
- ✅ Role-based access testing
- ✅ Edge case testing (conflicts, errors)
- ✅ Browser compatibility testing
- ✅ Mobile device testing
- ✅ Performance testing (page load speeds)

**Testing Checklist:**
- [ ] Tourist registration and login
- [ ] Guide registration request and approval
- [ ] Guide plan creation (all variants)
- [ ] Search and filtering
- [ ] Availability calendar (conflict prevention)
- [ ] Booking flow end-to-end
- [ ] Tourist request posting
- [ ] Guide bidding (2-bid limit enforcement)
- [ ] Bid acceptance/rejection
- [ ] Payment processing (test cards)
- [ ] Agreement PDF generation
- [ ] Contact reveal after payment
- [ ] Status updates (automatic)
- [ ] Guide payment recording
- [ ] Review submission
- [ ] All email notifications
- [ ] Admin panel (all roles)

**Acceptance Criteria:**
- All features tested and working
- No critical bugs found
- Payment flow tested extensively
- All emails delivered correctly
- Mobile experience smooth
- Page loads < 2 seconds
- No console errors

---

### Feature 8.2: Security Audit
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 3-4 days  
**Dependencies:** 8.1 (Testing)

**Includes:**
- ✅ Authentication testing (bypass attempts)
- ✅ Authorization testing (role escalation)
- ✅ SQL injection testing
- ✅ XSS prevention verification
- ✅ CSRF token validation
- ✅ File upload security
- ✅ Payment webhook security
- ✅ Rate limiting configuration

**Technical Tasks:**
- Test unauthorized access to routes
- Attempt SQL injection on forms
- Test XSS in text fields
- Verify CSRF tokens on all forms
- Test malicious file uploads
- Verify webhook signature validation
- Configure rate limiting on login/API

**Acceptance Criteria:**
- No unauthorized access possible
- SQL injection attempts blocked
- XSS attempts sanitized
- CSRF protection working
- Only allowed file types uploadable
- Webhook signatures verified
- Rate limiting prevents abuse

---

### Feature 8.3: Production Deployment
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 4-5 days  
**Dependencies:** 8.2 (Security Audit)

**Includes:**
- ✅ Server setup (VPS or cloud hosting)
- ✅ Domain configuration and SSL
- ✅ Database migration to production
- ✅ Environment configuration (.env)
- ✅ Email service setup (SendGrid/Mailgun)
- ✅ Stripe live mode activation
- ✅ Storage configuration (local or S3)
- ✅ Backup automation setup
- ✅ Monitoring tools configuration

**Technical Tasks:**
- Choose hosting (DigitalOcean, Linode, AWS)
- Install PHP, MySQL, Nginx/Apache
- Configure domain and SSL (Let's Encrypt)
- Run migrations on production database
- Configure production .env (API keys, secrets)
- Setup email service:
  - **Option 1 (Free):** Continue with Gmail SMTP (limit: 500 emails/day)
  - **Option 2 (Paid):** Gmail Workspace (higher limits, more professional)
  - **Option 3 (Paid):** SendGrid, Mailgun, Amazon SES for high volume
- Activate Stripe live mode (update keys)
- Configure automatic daily backups
- Install monitoring (New Relic, Sentry)
- Setup Laravel Horizon for queues (optional)

**Acceptance Criteria:**
- Site accessible via HTTPS
- SSL certificate valid
- Database populated with admin user
- Emails sending via Gmail SMTP (or chosen production service)
- Stripe live payments working
- Backups running daily
- Error monitoring active
- Queue workers running

---

### Feature 8.4: Launch Preparation
**Priority:** P0 (Critical)  
**Status:** 🔴 Not Started  
**Estimated Time:** 2-3 days  
**Dependencies:** 8.3 (Deployment)

**Includes:**
- ✅ Create user documentation (guides, FAQs)
- ✅ Admin training materials
- ✅ Marketing landing page
- ✅ Social media announcement
- ✅ Press release (optional)
- ✅ Beta user outreach
- ✅ Support email monitoring
- ✅ Feedback collection system

**Technical Tasks:**
- Write guide documentation (How to book, How to create plan, etc.)
- Create FAQ page
- Design landing page
- Prepare social media content
- Invite beta users (5-10 guides, 20-30 tourists)
- Setup support@ email monitoring
- Create feedback form

**Acceptance Criteria:**
- Documentation clear and comprehensive
- Landing page live and attractive
- Beta users invited and trained
- Social media scheduled
- Support email monitored
- Feedback form accessible

---

## 📊 Phase 8 Deliverables Checklist
- [ ] All features tested thoroughly
- [ ] Security audit passed
- [ ] Production deployment complete
- [ ] SSL certificate installed
- [ ] Stripe live mode activated
- [ ] Email service configured
- [ ] Backups automated
- [ ] Monitoring tools active
- [ ] Documentation complete
- [ ] Beta users invited
- [ ] Ready for public launch! 🎉

---

## 📈 POST-LAUNCH (Ongoing)

### Immediate Priorities (First Month)
1. **Monitor Performance**
   - Page load times
   - Server resources
   - Database queries
   - Error rates

2. **User Support**
   - Respond to support emails < 24 hours
   - Track common issues
   - Create additional FAQs based on questions

3. **Bug Fixes**
   - Fix critical bugs immediately
   - Prioritize user-reported issues
   - Weekly bug fix deployments

4. **Data Collection**
   - User behavior analytics
   - Booking conversion rates
   - Most popular features
   - Drop-off points

---

## 🔮 FUTURE ENHANCEMENTS (Phase 9+)

### High Priority (Months 3-6)
- [ ] Mobile apps (iOS and Android)
- [ ] Live chat between tourists and guides (after booking)
- [ ] Multi-language support (Sinhala, Tamil, German, French)
- [ ] Travel insurance integration
- [ ] SMS notifications (critical alerts)
- [ ] Advanced search with AI recommendations

### Medium Priority (Months 6-12)
- [ ] Video content (guide introductions, destination videos)
- [ ] Blog/content marketing system
- [ ] Referral program for tourists
- [ ] Loyalty program (repeat bookings)
- [ ] Group tours (fixed-date events)
- [ ] Equipment rental (hiking gear, cameras)
- [ ] Multi-guide tours (requiring multiple guides)

### Low Priority (12+ months)
- [ ] Virtual tour previews (360° photos)
- [ ] Live tracking during tours
- [ ] Integration with hotel booking systems
- [ ] Integration with flight booking
- [ ] AR/VR destination experiences
- [ ] Cryptocurrency payment option
- [ ] Guide certification courses
- [ ] Tourist community forum

---

## 🎯 SUMMARY

**Total Features:** 54 major features across 8 phases  
**Estimated Timeline:** 20-24 weeks (5-6 months)  
**Critical Path:** Phases 1 → 2 → 4 (Authentication → Booking → Payment)  
**Optional Phases:** Phase 7 can be done post-launch if needed

**Phase Priorities:**
- **Must Complete:** Phases 1, 2, 4, 8 (Foundation, Booking, Payment, Launch)
- **Highly Recommended:** Phases 3, 5, 6 (Bidding, Automation, Admin)
- **Can Delay:** Phase 7 (Reviews, Polish) - can launch without this

**Key Success Metrics:**
- [ ] Tourists can successfully book and pay for tours
- [ ] Guides can create plans and receive bookings
- [ ] Payments process correctly (tourist pays, guide gets paid)
- [ ] Admin can manage the platform effectively
- [ ] All critical user flows work without errors
- [ ] Platform secure and stable

---

**Next Steps:**
1. ✅ Review this priority list
2. ✅ Confirm technology stack (Laravel 11 + MySQL)
3. ✅ Setup development environment
4. ✅ Start Phase 1, Feature 1.1 (User Authentication)
5. Track progress using this document

**Good Luck!** 🚀