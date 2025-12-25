<?php

namespace App\Filament\Resources\BookingVehicleAssignmentResource\Pages;

use App\Filament\Resources\BookingVehicleAssignmentResource;
use App\Models\Booking;
use App\Models\BookingVehicleAssignment;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateBookingVehicleAssignment extends CreateRecord
{
    protected static string $resource = BookingVehicleAssignmentResource::class;

    protected static ?string $title = 'Assign Vehicle to Booking';

    public function mount(): void
    {
        parent::mount();

        // Pre-fill booking_id if provided in URL
        if (request()->has('booking_id')) {
            $this->form->fill([
                'booking_id' => request('booking_id'),
            ]);
        }
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Booking Selection')
                    ->schema([
                        Forms\Components\Select::make('booking_id')
                            ->label('Booking')
                            ->options(function () {
                                return Booking::query()
                                    ->whereDoesntHave('vehicleAssignment')
                                    ->where('start_date', '>=', now())
                                    ->whereNotIn('status', ['cancelled_by_tourist', 'cancelled_by_guide', 'cancelled_by_admin', 'completed', 'pending_payment', 'payment_failed'])
                                    ->with(['guide', 'tourist'])
                                    ->get()
                                    ->mapWithKeys(fn ($b) => [
                                        $b->id => "{$b->booking_number} - {$b->guide->full_name} ({$b->start_date->format('M d, Y')}) - {$b->total_participants} pax"
                                    ]);
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn (callable $set) => $set('vehicle_id', null)),

                        Forms\Components\Placeholder::make('booking_info')
                            ->label('Booking Details')
                            ->content(function (callable $get) {
                                $bookingId = $get('booking_id');
                                if (!$bookingId) return 'Select a booking to see details';

                                $booking = Booking::with(['guide', 'tourist', 'guidePlan'])->find($bookingId);
                                if (!$booking) return 'Booking not found';

                                $tour = $booking->guidePlan?->title ?? ($booking->touristRequest?->title ?? 'Custom Tour');
                                $daysLeft = now()->diffInDays($booking->start_date, false);
                                $urgency = $daysLeft <= 3 ? '⚠️ URGENT' : '';

                                return new \Illuminate\Support\HtmlString("
                                    <div class='text-sm space-y-1'>
                                        <div><strong>Tour:</strong> {$tour}</div>
                                        <div><strong>Tourist:</strong> {$booking->tourist->full_name}</div>
                                        <div><strong>Date:</strong> {$booking->start_date->format('F d, Y')} ({$daysLeft} days) {$urgency}</div>
                                        <div><strong>Participants:</strong> {$booking->total_participants} people</div>
                                    </div>
                                ");
                            })
                            ->visible(fn (callable $get) => $get('booking_id') !== null),
                    ]),

                Forms\Components\Section::make('Vehicle Assignment')
                    ->schema([
                        Forms\Components\Toggle::make('is_temporary')
                            ->label('Use Temporary Vehicle')
                            ->default(false)
                            ->reactive()
                            ->helperText('Check if using a rented/temporary vehicle not in the guide\'s saved fleet'),

                        Forms\Components\Select::make('vehicle_id')
                            ->label('Select Vehicle from Guide\'s Fleet')
                            ->options(function (callable $get) {
                                $bookingId = $get('booking_id');
                                if (!$bookingId) return [];

                                $booking = Booking::find($bookingId);
                                if (!$booking) return [];

                                return Vehicle::where('guide_id', $booking->guide_id)
                                    ->where('is_active', true)
                                    ->get()
                                    ->mapWithKeys(fn ($v) => [
                                        $v->id => "{$v->make} {$v->model} ({$v->license_plate}) - {$v->seating_capacity} seats" . ($v->has_ac ? ' [AC]' : '')
                                    ]);
                            })
                            ->searchable()
                            ->visible(fn (callable $get) => !$get('is_temporary') && $get('booking_id'))
                            ->required(fn (callable $get) => !$get('is_temporary'))
                            ->helperText('Only showing active vehicles from the guide\'s fleet'),

                        Forms\Components\Placeholder::make('no_vehicles')
                            ->label('')
                            ->content(new \Illuminate\Support\HtmlString('<div class="text-warning-600 font-medium">⚠️ This guide has no saved vehicles. Use a temporary vehicle instead.</div>'))
                            ->visible(function (callable $get) {
                                $bookingId = $get('booking_id');
                                if (!$bookingId || $get('is_temporary')) return false;

                                $booking = Booking::find($bookingId);
                                if (!$booking) return false;

                                return Vehicle::where('guide_id', $booking->guide_id)->where('is_active', true)->count() === 0;
                            }),
                    ])
                    ->visible(fn (callable $get) => $get('booking_id') !== null),

                Forms\Components\Section::make('Temporary Vehicle Details')
                    ->schema([
                        Forms\Components\Select::make('temporary_vehicle_data.vehicle_type')
                            ->label('Vehicle Type')
                            ->options(Vehicle::VEHICLE_TYPES)
                            ->required(),

                        Forms\Components\TextInput::make('temporary_vehicle_data.make')
                            ->label('Make')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., Toyota'),

                        Forms\Components\TextInput::make('temporary_vehicle_data.model')
                            ->label('Model')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., HiAce'),

                        Forms\Components\TextInput::make('temporary_vehicle_data.license_plate')
                            ->label('License Plate')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('e.g., ABC-1234'),

                        Forms\Components\TextInput::make('temporary_vehicle_data.seating_capacity')
                            ->label('Seating Capacity')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('passengers'),

                        Forms\Components\Toggle::make('temporary_vehicle_data.has_ac')
                            ->label('Air Conditioning')
                            ->default(true),

                        Forms\Components\Textarea::make('temporary_vehicle_data.description')
                            ->label('Description')
                            ->rows(2)
                            ->maxLength(500)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn (callable $get) => $get('is_temporary') && $get('booking_id')),
            ]);
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['assigned_at'] = now();
        $data['assigned_by'] = Auth::id();

        // If temporary, clear vehicle_id
        if ($data['is_temporary'] ?? false) {
            $data['vehicle_id'] = null;
        } else {
            $data['temporary_vehicle_data'] = null;
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Vehicle assigned successfully';
    }
}
