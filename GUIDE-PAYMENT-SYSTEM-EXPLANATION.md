# 💰 GUIDE PAYMENT SYSTEM - Complete Explanation & Upgrade Plan

## 📊 CURRENT SYSTEM OVERVIEW

### **How It Works Now:**

#### **1. Payment Record Creation (Automatic)**
When a tourist pays for a booking via Stripe:

**Location:** `PaymentController.php` - Webhook handler

```php
BookingPayment::create([
    'booking_id' => $booking->id,
    'total_amount' => $booking->total_amount,    // e.g., $500
    'platform_fee' => $booking->platform_fee,    // e.g., $50 (10%)
    'guide_payout' => $booking->guide_payout,    // e.g., $450 (90%)
    'guide_paid' => false,                        // Guide NOT paid yet
]);
```

**Example:**
- Tourist pays: **$500**
- Platform keeps: **$50** (10% commission)
- Guide should receive: **$450**
- Status: **Unpaid** ❌

---

#### **2. Database Structure (Current)**

**Table:** `booking_payments`

| Column | Type | Description | Example |
|--------|------|-------------|---------|
| `id` | bigint | Primary key | 1 |
| `booking_id` | bigint | Foreign key to bookings | 123 |
| `total_amount` | decimal(10,2) | Tourist paid amount | 500.00 |
| `platform_fee` | decimal(10,2) | Platform commission | 50.00 |
| `guide_payout` | decimal(10,2) | **Total owed to guide** | 450.00 |
| `guide_paid` | boolean | **Is fully paid?** | false |
| `guide_paid_at` | timestamp | When marked as paid | null |
| `paid_by_admin` | bigint | Admin who paid | null |
| `payment_method` | string | How paid | null |
| `payment_notes` | text | Admin notes | null |
| `created_at` | timestamp | When created | 2025-12-02 |

---

#### **3. Admin Payment Recording (Current - All or Nothing)**

**Location:** `BookingPaymentResource.php` - Admin panel action

**Current Flow:**
1. Admin clicks "Record Payment"
2. Enters:
   - Payment date
   - Payment method (Bank Transfer, Cash, PayPal, Check)
   - Optional notes
3. System updates:
   ```php
   $record->update([
       'guide_paid' => true,           // ✅ Fully paid
       'guide_paid_at' => now(),
       'paid_by_admin' => auth()->user()->admin->id,
       'payment_method' => 'bank_transfer',
       'payment_notes' => 'Ref: TX12345',
   ]);
   ```

**⚠️ LIMITATION: This marks the ENTIRE amount as paid in ONE transaction!**

---

## ❌ CURRENT SYSTEM PROBLEMS

### **Problem 1: No Partial Payments**
- Admin can only mark 100% paid or 0% paid
- If guide needs $450 but admin pays $200 first, there's no way to record this

### **Problem 2: No Payment History**
- Only stores ONE payment record per booking
- If admin pays in multiple installments, older records are lost

### **Problem 3: No Remaining Balance Tracking**
- Can't see how much is still owed
- No way to track: "Paid $200 out of $450, $250 remaining"

### **Problem 4: No Transaction Audit Trail**
- Can't see individual payment transactions
- If guide disputes payment, no detailed history

---

## ✅ RECOMMENDED UPGRADED SYSTEM

### **Solution: Add Payment Transactions Table**

Create a new **many-to-many relationship** between BookingPayments and actual payment transactions.

---

### **NEW DATABASE STRUCTURE**

#### **1. Keep existing `booking_payments` table (Master Record)**

**Purpose:** Tracks TOTAL amounts and overall status

Add new columns:
```sql
ALTER TABLE booking_payments
ADD COLUMN amount_paid DECIMAL(10, 2) DEFAULT 0.00,
ADD COLUMN amount_remaining DECIMAL(10, 2) DEFAULT 0.00;
```

| Column | Description | Example |
|--------|-------------|---------|
| `guide_payout` | Total owed to guide | 450.00 |
| `amount_paid` | ✨ **NEW:** Total paid so far | 200.00 |
| `amount_remaining` | ✨ **NEW:** Still owed | 250.00 |
| `guide_paid` | Fully paid? (amount_remaining = 0) | false |

---

#### **2. Create NEW `guide_payment_transactions` table**

**Purpose:** Tracks INDIVIDUAL payment installments

```sql
CREATE TABLE guide_payment_transactions (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    booking_payment_id BIGINT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATETIME NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    transaction_reference VARCHAR(255) NULL,
    notes TEXT NULL,
    paid_by_admin BIGINT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (booking_payment_id) REFERENCES booking_payments(id) ON DELETE CASCADE,
    FOREIGN KEY (paid_by_admin) REFERENCES admins(id) ON DELETE SET NULL,
    INDEX (booking_payment_id),
    INDEX (payment_date)
);
```

---

### **HOW THE NEW SYSTEM WORKS**

#### **Example Scenario:**

**Initial State:**
- Guide is owed: **$450**
- Paid: **$0**
- Remaining: **$450**

---

#### **Payment 1: Admin pays $200**

**Admin Action:**
1. Goes to BookingPayment detail page
2. Clicks "Add Payment Transaction"
3. Enters:
   - Amount: $200
   - Date: 2025-12-01
   - Method: Bank Transfer
   - Reference: TX-001
   - Notes: "First installment"

**Database Updates:**

**`guide_payment_transactions` (NEW RECORD):**
```
id: 1
booking_payment_id: 123
amount: 200.00
payment_date: 2025-12-01
payment_method: bank_transfer
transaction_reference: TX-001
paid_by_admin: 5
```

**`booking_payments` (UPDATED):**
```
amount_paid: 200.00 (was 0.00)
amount_remaining: 250.00 (was 450.00)
guide_paid: false (not fully paid yet)
```

---

#### **Payment 2: Admin pays remaining $250**

**Admin Action:**
1. Clicks "Add Payment Transaction" again
2. Enters:
   - Amount: $250
   - Date: 2025-12-15
   - Method: Cash
   - Reference: CASH-002
   - Notes: "Final payment"

**Database Updates:**

**`guide_payment_transactions` (NEW RECORD):**
```
id: 2
booking_payment_id: 123
amount: 250.00
payment_date: 2025-12-15
payment_method: cash
transaction_reference: CASH-002
paid_by_admin: 5
```

**`booking_payments` (UPDATED):**
```
amount_paid: 450.00 (200 + 250)
amount_remaining: 0.00
guide_paid: true ✅ (automatically set when remaining = 0)
guide_paid_at: 2025-12-15 (date of final payment)
```

---

## 🎯 BENEFITS OF NEW SYSTEM

### **1. Flexible Payment Schedules**
- Pay guides in installments
- Handle partial payments easily
- Adjust for deductions (taxes, disputes, etc.)

### **2. Complete Audit Trail**
- Every payment is recorded separately
- Track who paid, when, and how
- Transaction references for accounting

### **3. Accurate Balance Tracking**
- Always know how much is owed
- See payment progress at a glance
- Prevent overpayment or underpayment

### **4. Better Reporting**
- Export payment history
- Track payment methods used
- Admin accountability (who paid what)

### **5. Dispute Resolution**
- Guide says "I only got $200" - show transaction history
- Proof of all payments with dates and references

---

## 💻 IMPLEMENTATION PLAN

### **Phase 1: Database Migration (1 hour)**

**Step 1:** Create migration for new columns
```bash
php artisan make:migration add_payment_tracking_to_booking_payments
```

**Migration File:**
```php
public function up()
{
    Schema::table('booking_payments', function (Blueprint $table) {
        $table->decimal('amount_paid', 10, 2)->default(0)->after('guide_payout');
        $table->decimal('amount_remaining', 10, 2)->default(0)->after('amount_paid');
    });

    // Backfill existing records
    DB::statement('UPDATE booking_payments SET amount_remaining = guide_payout WHERE guide_paid = false');
    DB::statement('UPDATE booking_payments SET amount_paid = guide_payout, amount_remaining = 0 WHERE guide_paid = true');
}
```

**Step 2:** Create payment transactions table
```bash
php artisan make:migration create_guide_payment_transactions_table
```

---

### **Phase 2: Create Model (30 minutes)**

**File:** `app/Models/GuidePaymentTransaction.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuidePaymentTransaction extends Model
{
    protected $fillable = [
        'booking_payment_id',
        'amount',
        'payment_date',
        'payment_method',
        'transaction_reference',
        'notes',
        'paid_by_admin',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function bookingPayment(): BelongsTo
    {
        return $this->belongsTo(BookingPayment::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'paid_by_admin');
    }
}
```

**Update:** `app/Models/BookingPayment.php`

```php
use Illuminate\Database\Eloquent\Relations\HasMany;

protected $fillable = [
    // ... existing fields ...
    'amount_paid',
    'amount_remaining',
];

public function transactions(): HasMany
{
    return $this->hasMany(GuidePaymentTransaction::class);
}

// Helper: Check if fully paid
public function isFullyPaid(): bool
{
    return $this->amount_remaining <= 0;
}

// Helper: Get payment progress percentage
public function getPaymentProgressAttribute(): int
{
    if ($this->guide_payout <= 0) return 0;
    return (int) (($this->amount_paid / $this->guide_payout) * 100);
}
```

---

### **Phase 3: Update Admin Panel (2 hours)**

#### **3.1: Update BookingPaymentResource Table View**

**File:** `app/Filament/Resources/BookingPaymentResource.php`

**Add columns:**
```php
Tables\Columns\TextColumn::make('amount_paid')
    ->label('Paid')
    ->money('usd')
    ->color('success'),

Tables\Columns\TextColumn::make('amount_remaining')
    ->label('Remaining')
    ->money('usd')
    ->color(fn ($state) => $state > 0 ? 'warning' : 'success'),

Tables\Columns\ProgressBarColumn::make('payment_progress')
    ->label('Progress')
    ->getStateUsing(fn ($record) => ($record->amount_paid / $record->guide_payout) * 100),
```

---

#### **3.2: Create Payment Transaction Action**

**Replace the old "Record Payment" action with "Add Payment":**

```php
Tables\Actions\Action::make('add_payment')
    ->label('Add Payment')
    ->icon('heroicon-o-plus-circle')
    ->color('success')
    ->visible(fn (BookingPayment $record) => $record->amount_remaining > 0)
    ->form([
        Forms\Components\TextInput::make('amount')
            ->label('Payment Amount')
            ->numeric()
            ->prefix('$')
            ->required()
            ->maxValue(fn ($record) => $record->amount_remaining)
            ->helperText(fn ($record) => "Remaining: $" . number_format($record->amount_remaining, 2)),

        Forms\Components\DateTimePicker::make('payment_date')
            ->label('Payment Date')
            ->default(now())
            ->required()
            ->maxDate(now()),

        Forms\Components\Select::make('payment_method')
            ->label('Payment Method')
            ->options([
                'bank_transfer' => 'Bank Transfer',
                'cash' => 'Cash',
                'paypal' => 'PayPal',
                'check' => 'Check',
                'other' => 'Other',
            ])
            ->required(),

        Forms\Components\TextInput::make('transaction_reference')
            ->label('Transaction Reference')
            ->placeholder('TX-12345, Check #789, etc.')
            ->maxLength(255),

        Forms\Components\Textarea::make('notes')
            ->label('Notes')
            ->placeholder('Additional details...')
            ->rows(3)
            ->maxLength(1000),
    ])
    ->action(function (BookingPayment $record, array $data) {
        // Create transaction record
        $transaction = GuidePaymentTransaction::create([
            'booking_payment_id' => $record->id,
            'amount' => $data['amount'],
            'payment_date' => $data['payment_date'],
            'payment_method' => $data['payment_method'],
            'transaction_reference' => $data['transaction_reference'] ?? null,
            'notes' => $data['notes'] ?? null,
            'paid_by_admin' => auth()->user()->admin->id,
        ]);

        // Update totals
        $newAmountPaid = $record->amount_paid + $data['amount'];
        $newAmountRemaining = $record->guide_payout - $newAmountPaid;

        $record->update([
            'amount_paid' => $newAmountPaid,
            'amount_remaining' => max(0, $newAmountRemaining),
        ]);

        // If fully paid, mark as complete
        if ($newAmountRemaining <= 0) {
            $record->update([
                'guide_paid' => true,
                'guide_paid_at' => $data['payment_date'],
                'payment_method' => $data['payment_method'], // Last payment method
            ]);
        }

        Notification::make()
            ->title('Payment Recorded')
            ->body("Payment of $" . number_format($data['amount'], 2) . " recorded successfully. Remaining: $" . number_format(max(0, $newAmountRemaining), 2))
            ->success()
            ->send();
    }),
```

---

#### **3.3: Add Payment History View**

**Create a relation manager to show transaction history:**

```bash
php artisan make:filament-relation-manager BookingPaymentResource transactions amount
```

**Or add to view page directly:**

```php
// In BookingPaymentResource view page
Infolists\Components\Section::make('Payment History')
    ->schema([
        Infolists\Components\RepeatableEntry::make('transactions')
            ->label('')
            ->schema([
                Infolists\Components\TextEntry::make('amount')
                    ->money('usd')
                    ->weight('bold'),

                Infolists\Components\TextEntry::make('payment_date')
                    ->dateTime('M j, Y g:i A'),

                Infolists\Components\TextEntry::make('payment_method')
                    ->badge(),

                Infolists\Components\TextEntry::make('transaction_reference')
                    ->placeholder('N/A'),

                Infolists\Components\TextEntry::make('paidBy.full_name')
                    ->label('Paid By'),

                Infolists\Components\TextEntry::make('notes')
                    ->placeholder('No notes'),
            ])
            ->columns(3),
    ])
    ->collapsible()
    ->collapsed(false),
```

---

## 📈 ADMIN PANEL UI IMPROVEMENTS

### **Payment Dashboard Widget:**

```php
class GuidePaymentStatsWidget extends Widget
{
    protected function getStats(): array
    {
        $totalOwed = BookingPayment::where('guide_paid', false)->sum('amount_remaining');
        $totalPaid = BookingPayment::sum('amount_paid');
        $pendingCount = BookingPayment::where('amount_remaining', '>', 0)->count();

        return [
            Stat::make('Total Owed to Guides', '$' . number_format($totalOwed, 2))
                ->description('Pending payments')
                ->color('warning'),

            Stat::make('Total Paid', '$' . number_format($totalPaid, 2))
                ->description('All-time payments')
                ->color('success'),

            Stat::make('Pending Payments', $pendingCount)
                ->description('Bookings with balance')
                ->color('info'),
        ];
    }
}
```

---

## 🔒 SECURITY & VALIDATION

### **1. Prevent Overpayment:**
```php
->maxValue(fn ($record) => $record->amount_remaining)
->validate([
    'amount' => [
        'required',
        'numeric',
        'min:0.01',
        fn ($record) => 'max:' . $record->amount_remaining,
    ],
])
```

### **2. Audit Logging:**
```php
// After creating transaction
\Log::info('Guide payment recorded', [
    'booking_id' => $record->booking_id,
    'amount' => $data['amount'],
    'admin_id' => auth()->user()->admin->id,
    'transaction_id' => $transaction->id,
]);
```

### **3. Email Notifications:**
```php
// Send to guide when payment is made
Mail::to($record->booking->guide->user->email)->send(
    new GuidePaymentReceived($transaction)
);
```

---

## 📊 COMPARISON: OLD vs NEW

| Feature | Old System | New System |
|---------|-----------|------------|
| **Partial Payments** | ❌ No | ✅ Yes |
| **Payment History** | ❌ Single record | ✅ Full transaction log |
| **Balance Tracking** | ❌ No | ✅ Real-time remaining balance |
| **Transaction Details** | ❌ Limited | ✅ Reference, notes, method |
| **Audit Trail** | ⚠️ Basic | ✅ Complete with admin info |
| **Payment Progress** | ❌ Binary (paid/unpaid) | ✅ Percentage progress bar |
| **Overpayment Prevention** | ❌ No validation | ✅ Automatic validation |
| **Reporting** | ⚠️ Limited | ✅ Detailed per transaction |

---

## 🚀 IMPLEMENTATION TIME ESTIMATE

| Task | Time |
|------|------|
| Database migrations | 1 hour |
| Model creation & relationships | 30 minutes |
| Update BookingPaymentResource | 2 hours |
| Add payment transaction action | 1 hour |
| Create payment history view | 1 hour |
| Testing & debugging | 2 hours |
| **TOTAL** | **7.5 hours** |

---

## ✅ TESTING CHECKLIST

- [ ] Create payment transaction for $200 (partial)
- [ ] Verify amount_paid = $200, amount_remaining updated
- [ ] Add second payment for remaining balance
- [ ] Verify guide_paid = true when fully paid
- [ ] Try to overpay (should be prevented)
- [ ] View payment history in detail page
- [ ] Export payment transactions to CSV
- [ ] Test with different payment methods
- [ ] Verify admin info is recorded correctly
- [ ] Check transaction references save properly

---

## 📝 NOTES

1. **Backward Compatibility:** Existing records will work after migration
2. **Data Migration:** Run backfill to populate new columns from existing data
3. **Old Action:** Remove or hide old "Record Payment" action after deployment
4. **Guide Portal:** Optionally show payment history to guides in their dashboard

---

**Status:** Ready to implement
**Recommended:** Start with Phase 1 (Database) and test thoroughly before Phase 2
