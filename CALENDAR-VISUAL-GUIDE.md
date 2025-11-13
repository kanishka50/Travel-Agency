# 📅 Availability Calendar - Visual Guide

## What The User Sees

### Initial View (Calendar Loads)
```
┌──────────────────────────────────────────────────────────────┐
│ 📅 Check Availability                                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│        ◄      December 2025      ►          [Today]         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ Mon  Tue  Wed  Thu  Fri  Sat  Sun                     │   │
│  │  1    2    3    4    5    6    7                      │   │
│  │ 🟢   🟢   🟢   🟢   🟢   🟢   🟢                    │   │
│  │                                                        │   │
│  │  8    9   10   11   12   13   14                      │   │
│  │ 🟢   🔴   🔴   🔴   🟢   🟢   🟢                    │   │
│  │                                                        │   │
│  │ 15   16   17   18   19   20   21                      │   │
│  │ 🟢   🟢   🟢   🟢   🟢   🔴   🔴                    │   │
│  │                                                        │   │
│  │ 22   23   24   25   26   27   28                      │   │
│  │ 🔴   🟢   🟢   🟢   🟢   🟢   🟢                    │   │
│  │                                                        │   │
│  │ 29   30   31                                           │   │
│  │ 🟢   🟢   🟢                                          │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                              │
│  ℹ️  Tour Duration: 5 days / 4 nights                       │
│     Click on an available date to select your start date    │
│                                                              │
│  [          Select dates to continue          ] (disabled)  │
│                                                              │
│  Legend:                                                     │
│  🟢 Available   🔴 Booked   ⚪ Past/Unavailable   🔵 Selected│
└──────────────────────────────────────────────────────────────┘
```

### After User Clicks Dec 15 (5-Day Tour)
```
┌──────────────────────────────────────────────────────────────┐
│ 📅 Check Availability                                        │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│        ◄      December 2025      ►          [Today]         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │ Mon  Tue  Wed  Thu  Fri  Sat  Sun                     │   │
│  │  1    2    3    4    5    6    7                      │   │
│  │ 🟢   🟢   🟢   🟢   🟢   🟢   🟢                    │   │
│  │                                                        │   │
│  │  8    9   10   11   12   13   14                      │   │
│  │ 🟢   🔴   🔴   🔴   🟢   🟢   🟢                    │   │
│  │                                                        │   │
│  │ 15   16   17   18   19   20   21   ← Selected Range   │   │
│  │ 🔵   🔵   🔵   🔵   🔵   🔴   🔴                    │   │
│  │                                                        │   │
│  │ 22   23   24   25   26   27   28                      │   │
│  │ 🔴   🟢   🟢   🟢   🟢   🟢   🟢                    │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                              │
│  ℹ️  Tour Duration: 5 days / 4 nights                       │
│     Click on an available date to select your start date    │
│                                                              │
│  ┌────────────────────────────────────────────────────┐     │
│  │ ✅ Dates are available!                            │     │
│  │ Selected dates: Mon, Dec 15 - Fri, Dec 19, 2025   │     │
│  └────────────────────────────────────────────────────┘     │
│                                                              │
│  [             Book This Tour             ] (enabled) 🎯    │
│                                                              │
│  Legend:                                                     │
│  🟢 Available   🔴 Booked   ⚪ Past/Unavailable   🔵 Selected│
└──────────────────────────────────────────────────────────────┘
```

### If User Clicks a Booked Date (e.g., Dec 9)
```
┌──────────────────────────────────────────────────────────────┐
│ 📅 Check Availability                                        │
├──────────────────────────────────────────────────────────────┤
│        ◄      December 2025      ►          [Today]         │
│  ┌──────────────────────────────────────────────────────┐   │
│  │  8    9   10   11   12   13   14                      │   │
│  │ 🟢   🔴   🔴   🔴   🟢   🟢   🟢   ← Tried to click  │   │
│  │      ⬆️                                                 │   │
│  │   (clicked)                                            │   │
│  └──────────────────────────────────────────────────────┘   │
│                                                              │
│  ┌────────────────────────────────────────────────────┐     │
│  │ ❌ This date is already booked.                    │     │
│  └────────────────────────────────────────────────────┘     │
│                                                              │
│  [          Select available dates         ] (disabled)     │
└──────────────────────────────────────────────────────────────┘
```

---

## 🎨 Color Meanings

### 🟢 Green (Available)
```
┌────────┐
│   15   │  ← Bright green background
│        │  ← Green border (2px)
└────────┘  ← Hover: scales up 1.05x
            ← Hover: shadow appears
            ← Cursor: pointer
            ← Tooltip: "Click to select this date"
```
**Meaning:** Guide is free, you can book these dates

### 🔴 Red (Booked)
```
┌────────┐
│   20   │  ← Light red background
│        │  ← Red border (2px)
└────────┘  ← Cursor: not-allowed
            ← Tooltip: "This date is already booked"
```
**Meaning:** Another tourist has already booked this date

### ⚪ Gray (Unavailable)
```
┌────────┐
│   3    │  ← Gray background
│        │  ← No border
└────────┘  ← Cursor: not-allowed
            ← Tooltip: "Past date" or "Outside season"
```
**Meaning:** Past dates or dates outside seasonal availability

### 🔵 Blue (Selected)
```
┌────────┐
│   15   │  ← Light blue background
│        │  ← Blue border (2px)
└────────┘  ← Bold font weight
            ← Your current selection
```
**Meaning:** The dates you've selected for booking

---

## 📱 Mobile View

```
┌────────────────────┐
│ 📅 Check Availab...│
├────────────────────┤
│   ◄  Dec 2025  ►   │
│      [Today]       │
│                    │
│ M  T  W  T  F  S  S│
│ 1  2  3  4  5  6  7│
│ 🟢 🟢 🟢 🟢 🟢 🟢 🟢│
│                    │
│ 8  9 10 11 12 13 14│
│ 🟢 🔴 🔴 🔴 🟢 🟢 🟢│
│                    │
│ 15 16 17 18 19 20 21│
│ 🔵 🔵 🔵 🔵 🔵 🔴 🔴│
│                    │
│ ℹ️  5 days / 4 nights│
│                    │
│ ✅ Available!      │
│ Dec 15 - Dec 19    │
│                    │
│ [Book This Tour]   │
│                    │
│ Legend:            │
│ 🟢 Available       │
│ 🔴 Booked          │
│ 🔵 Selected        │
└────────────────────┘
```

---

## 🎭 User Interaction Flow

### Scenario 1: Successful Booking
```
1. User lands on plan detail page
   └─→ Calendar loads with current month
   └─→ API fetches booked dates
   └─→ Calendar shows green/red dates

2. User clicks on Dec 15 (green date)
   └─→ JavaScript validates: ✓ Not past ✓ Not booked
   └─→ API call: POST /api/plans/1/check-dates
   └─→ Server checks: ✓ No conflicts for Dec 15-19
   └─→ Returns: { available: true, start: "2025-12-15", end: "2025-12-19" }

3. Calendar updates
   └─→ Dec 15-19 turn blue (selected)
   └─→ Success message: "✅ Dates are available!"
   └─→ Shows: "Mon, Dec 15 - Fri, Dec 19, 2025"
   └─→ Button enables: "Book This Tour"

4. User clicks "Book This Tour"
   └─→ Redirects: /bookings/create?plan_id=1&start_date=2025-12-15
   └─→ Booking form opens with dates pre-filled
```

### Scenario 2: Conflict Detected
```
1. User clicks on Dec 16 (green date)
   └─→ API call: POST /api/plans/1/check-dates { start_date: "2025-12-16" }
   └─→ Server checks: Dec 16-20 needed, but Dec 20-21 already booked
   └─→ Returns: { available: false, message: "Guide not available..." }

2. Calendar shows error
   └─→ No dates turn blue
   └─→ Error message: "❌ Guide is not available for selected dates"
   └─→ Button stays disabled
   └─→ User must choose different date
```

---

## 🔄 Month Navigation

### User clicks "Next Month" →
```
Before:                          After:
┌──────────────┐                ┌──────────────┐
│ December 2025│   [Next →]     │ January 2026 │
│              │   ========>    │              │
│ Bookings:    │                │ Bookings:    │
│ Dec 9-11 🔴  │                │ Jan 5-7 🔴   │
│ Dec 20-22 🔴 │                │ Jan 15-18 🔴 │
└──────────────┘                └──────────────┘

API Call: GET /api/plans/1/availability?month=2026-01-15
Response: { booked_dates: [...], availability_type: "..." }
Calendar re-renders with new month's data
```

---

## 💻 Technical View (Behind the Scenes)

### When User Clicks Dec 15
```javascript
// 1. Click Handler
handleDateClick('2025-12-15')
  └─→ Validate: Not past? ✓
  └─→ Validate: Not booked? ✓
  └─→ Call checkAvailability('2025-12-15')

// 2. API Request
fetch('/api/plans/1/check-dates', {
  method: 'POST',
  body: { start_date: '2025-12-15' }
})

// 3. Server Processing
AvailabilityService::checkPlanAvailability()
  └─→ Calculate end date: 2025-12-15 + 5 days = 2025-12-19
  └─→ Query bookings for guide where dates overlap
  └─→ Check: Any booking from 2025-12-15 to 2025-12-19?
  └─→ Result: No conflicts ✓

// 4. Response
{
  available: true,
  message: "Dates are available!",
  start_date: "2025-12-15",
  end_date: "2025-12-19"
}

// 5. UI Update
- selectedStartDate = "2025-12-15"
- selectedEndDate = "2025-12-19"
- calendar.render() → Dec 15-19 turn blue
- Show success message
- Enable button
```

---

## 🎯 Visual States Comparison

### State 1: Initial (No Selection)
```
Calendar: All dates show availability status
Message:  (hidden)
Button:   [Select dates to continue] (disabled, gray)
```

### State 2: Loading (After Click)
```
Calendar: Unchanged
Message:  🔄 Checking availability... (gray box)
Button:   [Checking availability...] (disabled)
```

### State 3: Success (Available)
```
Calendar: Selected range turns blue (5 dates)
Message:  ✅ Dates are available! (green box)
          Selected: Mon, Dec 15 - Fri, Dec 19
Button:   [Book This Tour] (enabled, blue, clickable)
```

### State 4: Error (Conflict)
```
Calendar: No change (no blue dates)
Message:  ❌ Guide not available... (red box)
Button:   [Select available dates] (disabled, gray)
```

---

## 🎨 CSS Visual Effects

### Available Date Hover
```
Normal State:
┌────────────┐
│     15     │ background: #f0fdf4 (light green)
│            │ border: 2px solid #22c55e (green)
└────────────┘

Hover State:
┌────────────┐
│  ╭────╮    │ background: #dcfce7 (darker green)
│  │ 15 │    │ transform: scale(1.05)
│  ╰────╯    │ box-shadow: 0 4px 6px rgba(34,197,94,0.2)
└────────────┘ cursor: pointer
```

### Booked Date (No Hover)
```
┌────────────┐
│     20     │ background: #fee2e2 (light red)
│            │ border: 2px solid #ef4444 (red)
└────────────┘ cursor: not-allowed
               pointer-events: limited
```

---

## 📊 Information Density

### Desktop View
```
Information visible at once:
- Full month (28-31 days)
- All date statuses (color-coded)
- Legend with 4 states
- Tour duration info
- Current selection (if any)
- Success/error message
- Book button
Total: ~30-35 UI elements
```

### Mobile View (Optimized)
```
Information visible at once:
- Full month (smaller cells)
- Date statuses (color-coded)
- Essential legend (4 items)
- Tour duration
- Selection status
Total: ~25-30 UI elements
(Slightly reduced but all critical info present)
```

---

## 🎬 Animation Sequence

### When User Selects Date
```
Frame 1 (0ms):    User clicks Dec 15
                  ↓

Frame 2 (50ms):   Calendar cell shows "pressed" state
                  Button shows: "Checking..."
                  ↓

Frame 3 (100ms):  Message box fades in (gray)
                  "🔄 Checking availability..."
                  ↓

Frame 4 (200ms):  API response received
                  ↓

Frame 5 (250ms):  Selected dates (Dec 15-19) transition to blue
                  (Smooth color change over 200ms)
                  ↓

Frame 6 (300ms):  Message box changes to green
                  "✅ Dates are available!"
                  ↓

Frame 7 (350ms):  Date range text fades in
                  "Selected: Mon, Dec 15..."
                  ↓

Frame 8 (400ms):  Button changes from gray to blue
                  Text changes to "Book This Tour"
                  ↓

Frame 9 (450ms):  Smooth scroll to results section
                  (if not fully visible)

Total animation: ~450ms (feels instant)
```

---

## 🎨 Real-World Example

### Scenario: Tourist wants to book "5-Day Beach & Culture Tour"

```
Step 1: View Tour Details
┌──────────────────────────────────────────┐
│ 🏝️ 5-Day Beach & Culture Tour           │
│                                          │
│ Price: $450/adult                        │
│ Duration: 5 days / 4 nights              │
│ Guide: John Silva (4.8 ⭐, 120 reviews)  │
│                                          │
│ [Scroll down to see availability...]    │
└──────────────────────────────────────────┘

Step 2: Check Calendar
┌──────────────────────────────────────────┐
│ 📅 Check Availability                    │
│                                          │
│ December 2025                            │
│ Mon Tue Wed Thu Fri Sat Sun              │
│  1   2   3   4   5   6   7               │
│ 🟢  🟢  🟢  🟢  🟢  🟢  🟢             │
│                                          │
│  8   9  10  11  12   13  14              │
│ 🟢  🔴  🔴  🔴  🟢  🟢  🟢             │
│     └─Already booked by someone else     │
│                                          │
│ "I want Dec 15-19 for my vacation"      │
│           ↓ (clicks Dec 15)              │
└──────────────────────────────────────────┘

Step 3: Confirmation
┌──────────────────────────────────────────┐
│ December 2025                            │
│ 15  16  17  18  19  20  21               │
│ 🔵  🔵  🔵  🔵  🔵  🔴  🔴             │
│ └─My selected dates!                     │
│                                          │
│ ✅ Dates are available!                  │
│ Selected: Mon, Dec 15 - Fri, Dec 19      │
│                                          │
│ [     Book This Tour     ] 🎯            │
│           ↓ (clicks)                     │
└──────────────────────────────────────────┘

Step 4: Redirect to Booking
┌──────────────────────────────────────────┐
│ 📝 Complete Your Booking                 │
│                                          │
│ Tour: 5-Day Beach & Culture Tour         │
│ Dates: Dec 15-19, 2025 ✓ (pre-filled)   │
│                                          │
│ Number of Adults: [__]                   │
│ Number of Children: [__]                 │
│ ...                                      │
└──────────────────────────────────────────┘
```

---

## 🏆 Summary: What Makes This Calendar Great

### User Benefits
✅ **Visual** - See availability at a glance
✅ **Interactive** - Click and select dates easily
✅ **Preventive** - Can't select conflicting dates
✅ **Clear** - Color-coded for instant understanding
✅ **Fast** - Real-time validation in <250ms
✅ **Mobile-Friendly** - Works on any device
✅ **Intuitive** - No learning curve required

### Technical Excellence
✅ **Modern** - FullCalendar.js v6 (latest)
✅ **Performant** - Optimized API calls
✅ **Secure** - CSRF protection + validation
✅ **Responsive** - Mobile-first design
✅ **Accessible** - ARIA labels + keyboard nav
✅ **Maintainable** - Clean, documented code

---

🎉 **The calendar transforms availability checking from a guessing game into a visual, intuitive experience!** 🎉
