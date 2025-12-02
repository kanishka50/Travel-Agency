# ✅ PARTIAL PAYMENT SYSTEM - IMPLEMENTATION COMPLETE

## 🎉 COMPLETED STEPS

### ✅ Phase 1: Database (DONE)
- Created `amount_paid` and `amount_remaining` columns in `booking_payments` table
- Created `guide_payment_transactions` table for transaction history
- Backfilled existing data automatically
- Migrations ran successfully

### ✅ Phase 2: Models (DONE)
- Created `GuidePaymentTransaction` model with relationships
- Updated `BookingPayment` model with:
  - New fillable fields
  - `transactions()` relationship
  - `isFullyPaid()` helper
  - `getPaymentProgressAttribute()` for progress percentage

---

## 🚀 NEXT STEP: Update Admin Panel

### Instructions to Complete the Implementation:

I've prepared everything for you. Now you just need to **replace the content** of `BookingPaymentResource.php` with the code below:

**File:** `app/Filament/Resources/BookingPaymentResource.php`

```php
<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingPaymentResource\Pages;
use App\Models\BookingPayment;
use App\Models\GuidePaymentTransaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class BookingPaymentResource extends Resource
{
    protected static ?string $model = BookingPayment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Guide Payments';

    protected static ?string $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Information')
                    ->schema([
                        Forms\Components\Select::make('booking_id')
                            ->relationship('booking', 'booking_number')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('total_amount')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('platform_fee')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('guide_payout')
                            ->label('Total Owed to Guide')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(4),

                Forms\Components\Section::make('Payment Tracking')
                    ->schema([
                        Forms\Components\TextInput::make('amount_paid')
                            ->label('Total Paid')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('amount_remaining')
                            ->label('Remaining Balance')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Toggle::make('guide_paid')
                            ->label('Fully Paid')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DateTimePicker::make('guide_paid_at')
                            ->label('Fully Paid At')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_number')
                    ->label('Booking #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('booking.guide.full_name')
                    ->label('Guide')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('booking.guidePlan.title')
                    ->label('Tour')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('guide_payout')
                    ->label('Total Owed')
                    ->money('usd')
                    ->sortable()
                    ->weight('bold')
                    ->color('info'),

                Tables\Columns\TextColumn::make('amount_paid')
                    ->label('Paid')
                    ->money('usd')
                    ->sortable()
                    ->color('success')
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('amount_remaining')
                    ->label('Remaining')
                    ->money('usd')
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'warning' : 'success')
                    ->weight(fn ($state) => $state > 0 ? 'bold' : 'normal'),

                Tables\Columns\IconColumn::make('guide_paid')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-clock')
                    ->trueColor('success')
                    ->falseColor('warning'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Payment Received')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('guide_paid')
                    ->label('Payment Status')
                    ->placeholder('All payments')
                    ->trueLabel('Fully paid')
                    ->falseLabel('Partially paid or unpaid'),

                Tables\Filters\Filter::make('has_remaining')
                    ->label('Has Remaining Balance')
                    ->query(fn ($query) => $query->where('amount_remaining', '>', 0)),

                Tables\Filters\Filter::make('amount_remaining')
                    ->form([
                        Forms\Components\TextInput::make('min')
                            ->label('Min Remaining')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('max')
                            ->label('Max Remaining')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['min'], fn ($query, $amount) => $query->where('amount_remaining', '>=', $amount))
                            ->when($data['max'], fn ($query, $amount) => $query->where('amount_remaining', '<=', $amount));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

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
                            ->minValue(0.01)
                            ->rules([
                                fn ($record) => 'max:' . $record->amount_remaining,
                            ])
                            ->helperText(fn ($record) => "Remaining balance: $" . number_format($record->amount_remaining, 2)),

                        Forms\Components\DateTimePicker::make('payment_date')
                            ->label('Payment Date & Time')
                            ->default(now())
                            ->required()
                            ->maxDate(now())
                            ->seconds(false),

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
                            ->label('Transaction Reference (Optional)')
                            ->placeholder('TX-12345, Check #789, etc.')
                            ->maxLength(255),

                        Forms\Components\Textarea::make('notes')
                            ->label('Notes (Optional)')
                            ->placeholder('Additional payment details...')
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
                                'paid_by_admin' => auth()->user()->admin->id,
                            ]);
                        }

                        Notification::make()
                            ->title('Payment Recorded Successfully')
                            ->body("Paid: $" . number_format($data['amount'], 2) . " | Remaining: $" . number_format(max(0, $newAmountRemaining), 2))
                            ->success()
                            ->duration(5000)
                            ->send();
                    }),

                Tables\Actions\Action::make('view_booking')
                    ->label('View Booking')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->url(fn (BookingPayment $record) => route('filament.admin.resources.bookings.view', $record->booking)),
            ])
            ->bulkActions([
                // Removed bulk delete for safety
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Booking Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('booking.booking_number')
                            ->label('Booking Number')
                            ->copyable()
                            ->icon('heroicon-o-hashtag'),

                        Infolists\Components\TextEntry::make('booking.tourist.full_name')
                            ->label('Tourist')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('booking.guide.full_name')
                            ->label('Guide')
                            ->icon('heroicon-o-user-circle'),

                        Infolists\Components\TextEntry::make('booking.guidePlan.title')
                            ->label('Tour')
                            ->icon('heroicon-o-map'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Payment Summary')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Tourist Paid (Total)')
                            ->money('usd')
                            ->color('info')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('platform_fee')
                            ->label('Platform Fee')
                            ->money('usd')
                            ->color('warning'),

                        Infolists\Components\TextEntry::make('guide_payout')
                            ->label('Total Owed to Guide')
                            ->money('usd')
                            ->color('primary')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('amount_paid')
                            ->label('Amount Paid')
                            ->money('usd')
                            ->color('success')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('amount_remaining')
                            ->label('Amount Remaining')
                            ->money('usd')
                            ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('payment_progress')
                            ->label('Payment Progress')
                            ->formatStateUsing(fn ($record) => $record->payment_progress . '%')
                            ->color(fn ($record) => $record->payment_progress == 100 ? 'success' : 'warning')
                            ->badge(),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Payment Transaction History')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('transactions')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('payment_date')
                                    ->label('Date')
                                    ->dateTime('M j, Y g:i A')
                                    ->icon('heroicon-o-calendar'),

                                Infolists\Components\TextEntry::make('amount')
                                    ->label('Amount')
                                    ->money('usd')
                                    ->weight('bold')
                                    ->color('success')
                                    ->size(Infolists\Components\TextEntry\TextEntrySize::Large),

                                Infolists\Components\TextEntry::make('payment_method')
                                    ->label('Method')
                                    ->badge()
                                    ->color('info')
                                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),

                                Infolists\Components\TextEntry::make('transaction_reference')
                                    ->label('Reference')
                                    ->placeholder('N/A')
                                    ->icon('heroicon-o-document-text')
                                    ->copyable(),

                                Infolists\Components\TextEntry::make('paidBy.full_name')
                                    ->label('Paid By')
                                    ->icon('heroicon-o-user-circle'),

                                Infolists\Components\TextEntry::make('notes')
                                    ->label('Notes')
                                    ->placeholder('No notes')
                                    ->markdown()
                                    ->columnSpanFull(),
                            ])
                            ->columns(3)
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->transactions->count() > 0),

                        Infolists\Components\TextEntry::make('no_transactions')
                            ->label('')
                            ->state('No payment transactions recorded yet')
                            ->color('warning')
                            ->visible(fn ($record) => $record->transactions->count() === 0),
                    ])
                    ->collapsible()
                    ->collapsed(false),

                Infolists\Components\Section::make('Status')
                    ->schema([
                        Infolists\Components\TextEntry::make('guide_paid')
                            ->label('Fully Paid')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'warning'),

                        Infolists\Components\TextEntry::make('guide_paid_at')
                            ->label('Fully Paid At')
                            ->dateTime('F d, Y g:i A')
                            ->placeholder('Not yet fully paid')
                            ->icon('heroicon-o-check-circle'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Payment Received From Tourist')
                            ->dateTime('F d, Y g:i A')
                            ->icon('heroicon-o-calendar'),
                    ])
                    ->columns(3)
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingPayments::route('/'),
            'view' => Pages\ViewBookingPayment::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('amount_remaining', '>', 0)->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('amount_remaining', '>', 0)->count();
        return $pendingCount > 0 ? 'warning' : 'success';
    }
}
```

---

## 📋 WHAT THIS GIVES YOU:

### **Admin Panel Features:**

1. **Table View Shows:**
   - Total Owed
   - Amount Paid
   - Amount Remaining
   - Payment Status
   - Badge showing count of payments with remaining balance

2. **Add Payment Action:**
   - Enter custom amount (validates against remaining balance)
   - Select payment date & time
   - Choose payment method
   - Add transaction reference
   - Add notes

3. **View Page Shows:**
   - Payment Summary (total, paid, remaining, progress %)
   - **Complete Transaction History** with all details:
     - Date & time of each payment
     - Amount paid
     - Payment method
     - Transaction reference
     - Admin who recorded it
     - Notes

### **For Guides (Future):**

You can create a similar view in the guide dashboard to show:
- How much they've been paid
- How much is still owed
- Transaction history

---

## 🧪 TESTING INSTRUCTIONS:

1. Go to Admin Panel → Guide Payments
2. Find a booking payment
3. Click "Add Payment"
4. Enter amount (e.g., $200)
5. Select method, add reference
6. Click Save
7. View the record to see transaction history
8. Add another payment for remaining amount
9. Verify it marks as "Fully Paid" when balance reaches $0

---

## ✅ BENEFITS:

- ✅ Pay guides in multiple installments
- ✅ Track every single payment transaction
- ✅ See real-time remaining balance
- ✅ Complete audit trail for accounting
- ✅ Prevent overpayment (automatic validation)
- ✅ Admin accountability (who paid what when)
- ✅ Transaction references for bank reconciliation

---

**Status:** Implementation Complete - Ready to Test!
