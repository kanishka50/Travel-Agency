<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationLabel = 'Bookings';

    protected static ?string $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Information')
                    ->schema([
                        Forms\Components\TextInput::make('booking_number')
                            ->label('Booking Number')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending_payment' => 'Pending Payment',
                                'payment_failed' => 'Payment Failed',
                                'confirmed' => 'Confirmed',
                                'upcoming' => 'Upcoming',
                                'ongoing' => 'Ongoing',
                                'completed' => 'Completed',
                                'cancelled_by_tourist' => 'Cancelled by Tourist',
                                'cancelled_by_guide' => 'Cancelled by Guide',
                                'cancelled_by_admin' => 'Cancelled by Admin',
                            ])
                            ->required()
                            ->disabled(),
                        Forms\Components\Select::make('booking_type')
                            ->options([
                                'guide_plan' => 'Guide Plan',
                                'custom_request' => 'Custom Request',
                            ])
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(3),

                Forms\Components\Section::make('Participants')
                    ->schema([
                        Forms\Components\Select::make('tourist_id')
                            ->relationship('tourist', 'full_name')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('guide_id')
                            ->relationship('guide', 'full_name')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Select::make('guide_plan_id')
                            ->relationship('guidePlan', 'title')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(3),

                Forms\Components\Section::make('Dates & Travelers')
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\DatePicker::make('end_date')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('num_adults')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('num_children')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(4),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\TextInput::make('total_amount')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('platform_fee')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('guide_payout')
                            ->prefix('$')
                            ->disabled()
                            ->dehydrated(false),
                    ])->columns(3),

                Forms\Components\Section::make('Notes')
                    ->schema([
                        Forms\Components\Textarea::make('tourist_notes')
                            ->label('Tourist Special Requests')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('Cancellation Reason')
                            ->visible(fn ($record) => str_contains($record?->status ?? '', 'cancelled'))
                            ->disabled()
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('Booking #')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('tourist.full_name')
                    ->label('Tourist')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guide.full_name')
                    ->label('Guide')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guidePlan.title')
                    ->label('Tour')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('M j, Y')
                    ->sortable(),

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
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucwords($state, '_'))),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Amount')
                    ->money('usd')
                    ->sortable(),

                Tables\Columns\IconColumn::make('paid_at')
                    ->label('Paid')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Booked On')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending_payment' => 'Pending Payment',
                        'payment_failed' => 'Payment Failed',
                        'confirmed' => 'Confirmed',
                        'upcoming' => 'Upcoming',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled_by_admin' => 'Cancelled by Admin',
                        'cancelled_by_tourist' => 'Cancelled by Tourist',
                        'cancelled_by_guide' => 'Cancelled by Guide',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('paid')
                    ->query(fn ($query) => $query->whereNotNull('paid_at'))
                    ->label('Paid Only'),

                Tables\Filters\Filter::make('unpaid')
                    ->query(fn ($query) => $query->whereNull('paid_at'))
                    ->label('Unpaid Only'),

                Tables\Filters\Filter::make('start_date')
                    ->form([
                        Forms\Components\DatePicker::make('from')->label('Start Date From'),
                        Forms\Components\DatePicker::make('until')->label('Start Date Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($query, $date) => $query->whereDate('start_date', '>=', $date))
                            ->when($data['until'], fn ($query, $date) => $query->whereDate('start_date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('download_agreement')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('info')
                    ->visible(fn (Booking $record) => $record->agreement_pdf_path && Storage::disk('public')->exists($record->agreement_pdf_path))
                    ->url(fn (Booking $record) => route('bookings.download-agreement', $record))
                    ->openUrlInNewTab(),

                Tables\Actions\Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Booking $record) => !in_array($record->status, ['completed', 'cancelled_by_admin', 'cancelled_by_tourist', 'cancelled_by_guide']))
                    ->modalHeading('Cancel Booking')
                    ->modalDescription(fn (Booking $record) => "Are you sure you want to cancel booking {$record->booking_number}? This action cannot be undone.")
                    ->form([
                        Forms\Components\Select::make('reason_category')
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
                        Forms\Components\Textarea::make('cancellation_reason')
                            ->label('Detailed Reason')
                            ->required()
                            ->minLength(20)
                            ->maxLength(1000)
                            ->placeholder('Provide a detailed explanation for the cancellation...'),
                    ])
                    ->action(function (Booking $record, array $data) {
                        $record->update([
                            'status' => 'cancelled_by_admin',
                            'cancellation_reason' => "[{$data['reason_category']}] " . $data['cancellation_reason'],
                            'cancelled_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Booking Cancelled')
                            ->body("Booking {$record->booking_number} has been cancelled successfully.")
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                // Removed bulk delete for safety
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
            'index' => Pages\ListBookings::route('/'),
            'view' => Pages\ViewBooking::route('/{record}'),
        ];
    }
}
