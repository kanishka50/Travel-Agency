<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\BookingVehicleAssignment;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
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

                Tables\Columns\TextColumn::make('vehicleAssignment.vehicle_display_name')
                    ->label('Vehicle')
                    ->placeholder('Not Assigned')
                    ->icon(fn ($record) => $record->vehicleAssignment ? 'heroicon-o-truck' : 'heroicon-o-exclamation-triangle')
                    ->color(fn ($record) => $record->vehicleAssignment ? 'success' : 'warning')
                    ->toggleable(),

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

                Tables\Filters\Filter::make('no_vehicle')
                    ->label('No Vehicle Assigned')
                    ->query(fn ($query) => $query->whereDoesntHave('vehicleAssignment')),

                Tables\Filters\Filter::make('has_vehicle')
                    ->label('Vehicle Assigned')
                    ->query(fn ($query) => $query->whereHas('vehicleAssignment')),

                Tables\Filters\Filter::make('vehicle_urgent')
                    ->label('Vehicle Urgent (3 days)')
                    ->query(fn ($query) => $query
                        ->whereDoesntHave('vehicleAssignment')
                        ->where('start_date', '>=', now())
                        ->where('start_date', '<=', now()->addDays(3))
                        ->whereNotIn('status', ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin', 'completed', 'pending_payment', 'payment_failed'])
                    ),

                Tables\Filters\Filter::make('upcoming_no_vehicle')
                    ->label('Upcoming - No Vehicle')
                    ->query(fn ($query) => $query
                        ->whereDoesntHave('vehicleAssignment')
                        ->where('start_date', '>=', now())
                        ->whereNotIn('status', ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin', 'completed', 'pending_payment', 'payment_failed'])
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('assign_vehicle')
                    ->label('Assign Vehicle')
                    ->icon('heroicon-o-truck')
                    ->color('warning')
                    ->visible(fn (Booking $record) =>
                        !$record->vehicleAssignment &&
                        $record->start_date >= now() &&
                        !in_array($record->status, ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin', 'completed', 'pending_payment', 'payment_failed'])
                    )
                    ->modalHeading('Assign Vehicle to Booking')
                    ->modalDescription(fn (Booking $record) => "Assign a vehicle to booking {$record->booking_number} (Guide: {$record->guide->full_name})")
                    ->form([
                        Forms\Components\Toggle::make('is_temporary')
                            ->label('Use Temporary Vehicle')
                            ->default(false)
                            ->reactive()
                            ->helperText('Check if using a rented/temporary vehicle not in the guide\'s saved vehicles'),

                        Forms\Components\Select::make('vehicle_id')
                            ->label('Select Vehicle')
                            ->options(fn (Booking $record) =>
                                Vehicle::where('guide_id', $record->guide_id)
                                    ->where('is_active', true)
                                    ->get()
                                    ->mapWithKeys(fn ($v) => [
                                        $v->id => "{$v->make} {$v->model} ({$v->license_plate}) - {$v->seating_capacity} seats" . ($v->has_ac ? ' [AC]' : '')
                                    ])
                            )
                            ->searchable()
                            ->visible(fn (callable $get) => !$get('is_temporary'))
                            ->required(fn (callable $get) => !$get('is_temporary'))
                            ->helperText(fn (Booking $record) => "Participants: {$record->total_participants} people"),

                        Forms\Components\Section::make('Temporary Vehicle Details')
                            ->schema([
                                Forms\Components\Select::make('vehicle_type')
                                    ->label('Vehicle Type')
                                    ->options(Vehicle::VEHICLE_TYPES)
                                    ->required(),

                                Forms\Components\TextInput::make('make')
                                    ->label('Make')
                                    ->required()
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('model')
                                    ->label('Model')
                                    ->required()
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('license_plate')
                                    ->label('License Plate')
                                    ->required()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('seating_capacity')
                                    ->label('Seating Capacity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->maxValue(50),

                                Forms\Components\Toggle::make('has_ac')
                                    ->label('Air Conditioning')
                                    ->default(true),
                            ])
                            ->columns(2)
                            ->visible(fn (callable $get) => $get('is_temporary')),
                    ])
                    ->action(function (Booking $record, array $data) {
                        if ($data['is_temporary']) {
                            BookingVehicleAssignment::create([
                                'booking_id' => $record->id,
                                'vehicle_id' => null,
                                'is_temporary' => true,
                                'temporary_vehicle_data' => [
                                    'vehicle_type' => $data['vehicle_type'],
                                    'make' => $data['make'],
                                    'model' => $data['model'],
                                    'license_plate' => $data['license_plate'],
                                    'seating_capacity' => $data['seating_capacity'],
                                    'has_ac' => $data['has_ac'] ?? false,
                                ],
                                'assigned_at' => now(),
                                'assigned_by' => Auth::id(),
                            ]);

                            Notification::make()
                                ->title('Temporary Vehicle Assigned')
                                ->body("Temporary vehicle {$data['make']} {$data['model']} assigned to booking {$record->booking_number}")
                                ->success()
                                ->send();
                        } else {
                            $vehicle = Vehicle::find($data['vehicle_id']);

                            BookingVehicleAssignment::create([
                                'booking_id' => $record->id,
                                'vehicle_id' => $vehicle->id,
                                'is_temporary' => false,
                                'temporary_vehicle_data' => null,
                                'assigned_at' => now(),
                                'assigned_by' => Auth::id(),
                            ]);

                            Notification::make()
                                ->title('Vehicle Assigned')
                                ->body("Vehicle {$vehicle->display_name} assigned to booking {$record->booking_number}")
                                ->success()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('remove_vehicle')
                    ->label('Remove Vehicle')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Booking $record) =>
                        $record->vehicleAssignment &&
                        $record->start_date >= now()
                    )
                    ->requiresConfirmation()
                    ->modalHeading('Remove Vehicle Assignment')
                    ->modalDescription('This will remove the current vehicle assignment. The guide will need to assign a new vehicle.')
                    ->action(function (Booking $record) {
                        $record->vehicleAssignment->delete();

                        Notification::make()
                            ->title('Vehicle Assignment Removed')
                            ->body("Vehicle assignment removed from booking {$record->booking_number}")
                            ->success()
                            ->send();
                    }),

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Booking Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('booking_number')
                            ->label('Booking Number')
                            ->icon('heroicon-o-ticket')
                            ->copyable()
                            ->weight('bold')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
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

                        Infolists\Components\TextEntry::make('booking_type')
                            ->label('Type')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state === 'guide_plan' ? 'Guide Plan' : 'Custom Request'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Participants')
                    ->schema([
                        Infolists\Components\TextEntry::make('tourist.full_name')
                            ->label('Tourist')
                            ->icon('heroicon-o-user-circle'),

                        Infolists\Components\TextEntry::make('guide.full_name')
                            ->label('Guide')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('guidePlan.title')
                            ->label('Tour/Plan')
                            ->icon('heroicon-o-map'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Dates & Travelers')
                    ->schema([
                        Infolists\Components\TextEntry::make('start_date')
                            ->label('Start Date')
                            ->date('F d, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('end_date')
                            ->label('End Date')
                            ->date('F d, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('num_adults')
                            ->label('Adults')
                            ->icon('heroicon-o-users'),

                        Infolists\Components\TextEntry::make('num_children')
                            ->label('Children')
                            ->icon('heroicon-o-user-group'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Vehicle Assignment')
                    ->schema([
                        Infolists\Components\TextEntry::make('vehicleAssignment')
                            ->label('Status')
                            ->getStateUsing(fn ($record) => $record->vehicleAssignment ? 'Assigned' : 'Not Assigned')
                            ->badge()
                            ->color(fn ($record) => $record->vehicleAssignment ? 'success' : 'warning')
                            ->icon(fn ($record) => $record->vehicleAssignment ? 'heroicon-o-check-circle' : 'heroicon-o-exclamation-triangle'),

                        Infolists\Components\TextEntry::make('vehicleAssignment.vehicle_display_name')
                            ->label('Vehicle')
                            ->icon('heroicon-o-truck')
                            ->placeholder('No vehicle assigned')
                            ->visible(fn ($record) => $record->vehicleAssignment !== null),

                        Infolists\Components\TextEntry::make('vehicleAssignment.license_plate')
                            ->label('License Plate')
                            ->copyable()
                            ->icon('heroicon-o-identification')
                            ->visible(fn ($record) => $record->vehicleAssignment !== null),

                        Infolists\Components\TextEntry::make('vehicleAssignment.is_temporary')
                            ->label('Type')
                            ->formatStateUsing(fn ($state) => $state ? 'Temporary/Rented' : 'Saved Vehicle')
                            ->badge()
                            ->color(fn ($state) => $state ? 'warning' : 'success')
                            ->visible(fn ($record) => $record->vehicleAssignment !== null),

                        Infolists\Components\TextEntry::make('vehicleAssignment.seating_capacity')
                            ->label('Capacity')
                            ->suffix(' passengers')
                            ->icon('heroicon-o-users')
                            ->visible(fn ($record) => $record->vehicleAssignment !== null),

                        Infolists\Components\TextEntry::make('vehicleAssignment.assigned_at')
                            ->label('Assigned At')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-clock')
                            ->visible(fn ($record) => $record->vehicleAssignment !== null),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Infolists\Components\Section::make('Pricing')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_amount')
                            ->label('Total Amount')
                            ->money('usd')
                            ->icon('heroicon-o-currency-dollar'),

                        Infolists\Components\TextEntry::make('platform_fee')
                            ->label('Platform Fee')
                            ->money('usd'),

                        Infolists\Components\TextEntry::make('guide_payout')
                            ->label('Guide Payout')
                            ->money('usd'),

                        Infolists\Components\TextEntry::make('paid_at')
                            ->label('Payment')
                            ->formatStateUsing(fn ($state) => $state ? 'Paid on ' . $state->format('M d, Y') : 'Unpaid')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('tourist_notes')
                            ->label('Tourist Special Requests')
                            ->placeholder('No special requests')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('cancellation_reason')
                            ->label('Cancellation Reason')
                            ->visible(fn ($record) => str_contains($record?->status ?? '', 'cancelled'))
                            ->columnSpanFull(),
                    ])
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
            'index' => Pages\ListBookings::route('/'),
            'view' => Pages\ViewBooking::route('/{record}'),
        ];
    }
}
