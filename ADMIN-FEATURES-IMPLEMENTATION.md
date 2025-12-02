# 🛡️ ADMIN FEATURES IMPLEMENTATION GUIDE

**Created:** November 13, 2025
**Purpose:** Step-by-step guide to implement critical admin features

---

## ✅ COMPLETED SO FAR

1. ✅ Updated `BookingPayment` model with relationships
2. ✅ Generated `BookingResource` for Filament (needs customization)
3. ✅ Generated `BookingPaymentResource` for Filament (needs customization)

---

## 🎯 WHAT ADMINS NEED TO DO

### **Admin Booking Management:**
- View all bookings with search/filters
- See booking details (tourist, guide, tour info)
- View payment status
- Cancel problematic bookings
- Add internal admin notes
- Download booking agreement PDF
- Override booking status if needed

### **Admin Payment Management:**
- View all guide payments (paid/unpaid)
- Record payment to guide (mark as paid)
- Track payment method (bank transfer, cash, etc.)
- Add payment notes
- View payment history per guide
- Filter by payment status

---

## 📝 FILES CREATED

1. `app/Filament/Resources/BookingResource.php` - Manage all bookings
2. `app/Filament/Resources/BookingResource/Pages/` - List, View, Edit pages
3. `app/Filament/Resources/BookingPaymentResource.php` - Track guide payments
4. `app/Filament/Resources/BookingPaymentResource/Pages/` - List, View pages

---

## 🔧 REQUIRED CUSTOMIZATIONS

### **1. BookingResource Customization**

#### Navigation Setup
```php
protected static ?string $navigationIcon = 'heroicon-o-calendar';
protected static ?string $navigationLabel = 'Bookings';
protected static ?string $navigationGroup = 'Booking Management';
protected static ?int $navigationSort = 1;
```

#### Table Columns (Most Important Info)
Show in list view:
- Booking Number
- Tourist Name
- Guide Name
- Tour Title
- Start Date
- Status (with badge colors)
- Total Amount
- Payment Status
- Actions (View, Cancel)

#### Filters Needed
- By Status (confirmed, ongoing, completed, cancelled)
- By Date Range
- By Guide
- By Tourist
- By Payment Status

#### Actions Needed
1. **View Details** - Modal showing full booking info
2. **Cancel Booking** - With reason field (only if not completed)
3. **Download Agreement** - PDF download button
4. **View Payment** - Link to payment record

#### Important Fields for View/Edit
- Booking details (read-only mostly)
- Cancellation reason (if cancelled)
- Internal admin notes (admin only, not visible to users)
- Status override (with confirmation)

---

### **2. BookingPaymentResource Customization**

#### Navigation Setup
```php
protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
protected static ?string $navigationLabel = 'Guide Payments';
protected static ?string $navigationGroup = 'Booking Management';
protected static ?int $navigationSort = 2;
```

#### Table Columns
Show in list view:
- Booking Number
- Guide Name
- Tour Title
- Total Amount
- Guide Payout
- Paid Status (badge: paid/unpaid)
- Paid Date
- Actions (Record Payment, View)

#### Filters Needed
- By Payment Status (paid/unpaid)
- By Guide
- By Date Range

#### Actions Needed
1. **Record Payment** - Form to mark as paid
   - Payment Date
   - Payment Method (Bank Transfer, Cash, PayPal, etc.)
   - Notes
   - Auto-set paid_by_admin to current admin
2. **View Details** - Show booking and payment info
3. **Payment History** - Show if partial payments exist (future feature)

---

## 🚀 QUICK IMPLEMENTATION STEPS

### **Step 1: Access Filament Admin Panel**
Navigate to: `http://localhost/admin` (or your domain/admin)

### **Step 2: Verify Resources Appear in Menu**
You should see:
- Booking Management
  - Bookings
  - Guide Payments

### **Step 3: Test Basic Functionality**
1. Can you see the list of bookings?
2. Can you see the list of payments?
3. Do filters work?
4. Can you view booking details?

### **Step 4: Add Custom Actions**

The auto-generated resources need custom actions added. I'll provide the code for:

1. **Cancel Booking Action**
2. **Record Payment Action**
3. **Download Agreement Action**

---

## 💻 CODE SNIPPETS TO ADD

### **Cancel Booking Action** (Add to BookingResource table)

```php
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

// In BookingResource::table() method, in ->actions([...])
Action::make('cancel')
    ->label('Cancel Booking')
    ->icon('heroicon-o-x-circle')
    ->color('danger')
    ->requiresConfirmation()
    ->visible(fn (Booking $record) => !in_array($record->status, ['completed', 'cancelled_by_admin', 'cancelled_by_tourist', 'cancelled_by_guide']))
    ->form([
        Select::make('reason_category')
            ->label('Cancellation Category')
            ->options([
                'customer_request' => 'Customer Request',
                'guide_unavailable' => 'Guide Unavailable',
                'weather' => 'Weather/Safety Concerns',
                'fraud' => 'Suspected Fraud',
                'violation' => 'Terms Violation',
                'other' => 'Other',
            ])
            ->required(),
        Textarea::make('cancellation_reason')
            ->label('Detailed Reason')
            ->required()
            ->minLength(20)
            ->maxLength(1000),
    ])
    ->action(function (Booking $record, array $data) {
        $record->update([
            'status' => 'cancelled_by_admin',
            'cancellation_reason' => "[{$data['reason_category']}] " . $data['cancellation_reason'],
            'cancelled_at' => now(),
        ]);

        // TODO: Send cancellation emails to tourist and guide
        // Mail::to($record->tourist->user->email)->send(new BookingCancelledByAdmin($record));

        Notification::make()
            ->title('Booking Cancelled')
            ->success()
            ->send();
    })
```

### **Record Payment Action** (Add to BookingPaymentResource table)

```php
use Filament\Notifications\Notification;
use Filament\Forms\Components\DatePicker;

// In BookingPaymentResource::table() method, in ->actions([...])
Action::make('record_payment')
    ->label('Record Payment')
    ->icon('heroicon-o-check-circle')
    ->color('success')
    ->visible(fn (BookingPayment $record) => !$record->guide_paid)
    ->requiresConfirmation()
    ->modalHeading('Record Payment to Guide')
    ->modalDescription(fn (BookingPayment $record) => "Mark payment of $" . number_format($record->guide_payout, 2) . " to guide as completed")
    ->form([
        DatePicker::make('payment_date')
            ->label('Payment Date')
            ->default(now())
            ->required()
            ->maxDate(now()),
        Select::make('payment_method')
            ->label('Payment Method')
            ->options([
                'bank_transfer' => 'Bank Transfer',
                'cash' => 'Cash',
                'paypal' => 'PayPal',
                'stripe' => 'Stripe Transfer',
                'check' => 'Check',
                'other' => 'Other',
            ])
            ->required(),
        Textarea::make('payment_notes')
            ->label('Notes (Optional)')
            ->placeholder('Transaction ID, reference number, or other details...')
            ->maxLength(500),
    ])
    ->action(function (BookingPayment $record, array $data) {
        $record->update([
            'guide_paid' => true,
            'guide_paid_at' => $data['payment_date'],
            'paid_by_admin' => auth()->user()->admin->id,
            'payment_method' => $data['payment_method'],
            'payment_notes' => $data['payment_notes'],
        ]);

        // TODO: Send email to guide confirming payment
        // Mail::to($record->booking->guide->user->email)->send(new PaymentRecorded($record));

        Notification::make()
            ->title('Payment Recorded Successfully')
            ->body("Payment to guide marked as completed")
            ->success()
            ->send();
    })
```

### **Download Agreement Action** (Add to BookingResource table)

```php
use Illuminate\Support\Facades\Storage;

// In BookingResource::table() method, in ->actions([...])
Action::make('download_agreement')
    ->label('Download Agreement')
    ->icon('heroicon-o-document-arrow-down')
    ->color('info')
    ->url(fn (Booking $record) => route('bookings.download-agreement', $record))
    ->openUrlInNewTab()
```

---

## 🧪 TESTING CHECKLIST

### **Booking Management**
- [ ] View list of all bookings
- [ ] Filter by status (confirmed, ongoing, completed)
- [ ] Search by booking number
- [ ] View booking details modal
- [ ] Cancel a booking (test with non-completed booking)
- [ ] Verify cancellation reason is saved
- [ ] Download booking agreement PDF
- [ ] Verify cancelled bookings show in list

### **Payment Management**
- [ ] View list of all payments
- [ ] Filter by paid/unpaid status
- [ ] Filter by guide
- [ ] Record payment for unpaid booking
- [ ] Verify payment date is saved
- [ ] Verify payment method is saved
- [ ] Verify paid_by_admin is current admin
- [ ] Verify "Record Payment" button disappears after payment
- [ ] View payment details

---

## 🎨 UI IMPROVEMENTS (Optional but Recommended)

### **Status Badges with Colors**
```php
// In table columns
Tables\Columns\TextColumn::make('status')
    ->badge()
    ->color(fn (string $state): string => match ($state) {
        'pending_payment' => 'warning',
        'payment_failed' => 'danger',
        'confirmed' => 'success',
        'upcoming' => 'info',
        'ongoing' => 'primary',
        'completed' => 'success',
        'cancelled_by_admin', 'cancelled_by_tourist', 'cancelled_by_guide' => 'danger',
        default => 'gray',
    })
```

### **Payment Status Badge**
```php
// In BookingPaymentResource table
Tables\Columns\IconColumn::make('guide_paid')
    ->boolean()
    ->label('Paid to Guide')
```

---

## 📊 METRICS TO DISPLAY (Future Enhancement)

On admin dashboard, show:
- Total bookings this month
- Total revenue this month
- Pending payments to guides
- Active tours happening now
- Bookings requiring attention (complaints, issues)

---

## 🔒 SECURITY CONSIDERATIONS

1. **Authorization:** Only admin users can access these resources
2. **Audit Trail:** Log all cancellations and payment records
3. **Validation:** Ensure payment amounts match booking amounts
4. **Email Notifications:** Notify users when admin cancels booking
5. **Refunds:** Consider adding refund tracking (future feature)

---

## 🚀 NEXT STEPS

1. Customize BookingResource table columns
2. Add cancel booking action
3. Customize BookingPaymentResource table columns
4. Add record payment action
5. Test all functionality
6. Add email notifications for admin actions
7. Add audit logging

---

## 📝 NOTES FOR DEVELOPER

- Auto-generated resources are in: `app/Filament/Resources/`
- Pages are in: `app/Filament/Resources/{ResourceName}/Pages/`
- Filament docs: https://filamentphp.com/docs/3.x/panels/resources
- The resources use Livewire under the hood
- Custom actions can be added to both table and page levels
- Form validation is automatic based on model rules

---

**Status:** Resources created, customization needed
**Next:** Add custom actions and test functionality
**Time Estimate:** 2-3 hours to customize and test
