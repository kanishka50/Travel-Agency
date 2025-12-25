<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingVehicleAssignmentResource\Pages;
use App\Models\BookingVehicleAssignment;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BookingVehicleAssignmentResource extends Resource
{
    protected static ?string $model = BookingVehicleAssignment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationLabel = 'Vehicle Assignments';

    protected static ?string $modelLabel = 'Vehicle Assignment';

    protected static ?string $pluralModelLabel = 'Vehicle Assignments';

    protected static ?string $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Assignment Details')
                    ->schema([
                        Forms\Components\Select::make('booking_id')
                            ->label('Booking')
                            ->relationship('booking', 'booking_number')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($record) => $record !== null),

                        Forms\Components\Toggle::make('is_temporary')
                            ->label('Temporary Vehicle')
                            ->default(false)
                            ->reactive()
                            ->helperText('Check if using a rented/temporary vehicle not in the guide\'s saved vehicles'),

                        Forms\Components\Select::make('vehicle_id')
                            ->label('Saved Vehicle')
                            ->options(function (callable $get) {
                                $bookingId = $get('booking_id');
                                if (!$bookingId) return [];

                                $booking = \App\Models\Booking::find($bookingId);
                                if (!$booking) return [];

                                return Vehicle::where('guide_id', $booking->guide_id)
                                    ->where('is_active', true)
                                    ->get()
                                    ->mapWithKeys(fn ($v) => [
                                        $v->id => "{$v->make} {$v->model} ({$v->license_plate}) - {$v->seating_capacity} seats"
                                    ]);
                            })
                            ->searchable()
                            ->visible(fn (callable $get) => !$get('is_temporary'))
                            ->required(fn (callable $get) => !$get('is_temporary')),

                        Forms\Components\Section::make('Temporary Vehicle Details')
                            ->schema([
                                Forms\Components\Select::make('temporary_vehicle_data.vehicle_type')
                                    ->label('Vehicle Type')
                                    ->options(Vehicle::VEHICLE_TYPES)
                                    ->required(),

                                Forms\Components\TextInput::make('temporary_vehicle_data.make')
                                    ->label('Make')
                                    ->required()
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('temporary_vehicle_data.model')
                                    ->label('Model')
                                    ->required()
                                    ->maxLength(100),

                                Forms\Components\TextInput::make('temporary_vehicle_data.license_plate')
                                    ->label('License Plate')
                                    ->required()
                                    ->maxLength(20),

                                Forms\Components\TextInput::make('temporary_vehicle_data.seating_capacity')
                                    ->label('Seating Capacity')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->maxValue(50),

                                Forms\Components\Toggle::make('temporary_vehicle_data.has_ac')
                                    ->label('Air Conditioning')
                                    ->default(true),

                                Forms\Components\Textarea::make('temporary_vehicle_data.description')
                                    ->label('Description')
                                    ->rows(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2)
                            ->visible(fn (callable $get) => $get('is_temporary')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('booking.booking_number')
                    ->label('Booking')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->color('primary')
                    ->copyable(),

                Tables\Columns\TextColumn::make('booking.guide.full_name')
                    ->label('Guide')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('booking.tourist.full_name')
                    ->label('Tourist')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('booking.start_date')
                    ->label('Tour Date')
                    ->date('M d, Y')
                    ->sortable()
                    ->color(fn ($record) => $record->booking->start_date < now() ? 'gray' : 'success'),

                Tables\Columns\TextColumn::make('vehicle_display_name')
                    ->label('Vehicle')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('vehicle', function ($q) use ($search) {
                            $q->where('make', 'like', "%{$search}%")
                              ->orWhere('model', 'like', "%{$search}%");
                        });
                    })
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Plate')
                    ->copyable()
                    ->color('info'),

                Tables\Columns\IconColumn::make('is_temporary')
                    ->label('Temp')
                    ->boolean()
                    ->trueIcon('heroicon-o-clock')
                    ->falseIcon('heroicon-o-check-badge')
                    ->trueColor('warning')
                    ->falseColor('success')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('assigned_at')
                    ->label('Assigned')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('assignedByUser.email')
                    ->label('Assigned By')
                    ->placeholder('Guide')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('assigned_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_temporary')
                    ->label('Vehicle Type')
                    ->trueLabel('Temporary Only')
                    ->falseLabel('Saved Only')
                    ->placeholder('All'),

                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming Tours')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereHas('booking', fn ($q) => $q->where('start_date', '>=', now()))
                    ),

                Tables\Filters\Filter::make('past')
                    ->label('Past Tours')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereHas('booking', fn ($q) => $q->where('start_date', '<', now()))
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square')
                    ->visible(fn ($record) => $record->booking->start_date >= now()),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->booking->start_date >= now())
                    ->modalDescription('This will remove the vehicle assignment. The guide will need to assign a new vehicle.'),
            ])
            ->bulkActions([])
            ->emptyStateHeading('No vehicle assignments')
            ->emptyStateDescription('Vehicle assignments will appear here when guides assign vehicles to bookings.')
            ->emptyStateIcon('heroicon-o-clipboard-document-check');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Booking Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('booking.booking_number')
                            ->label('Booking Number')
                            ->icon('heroicon-o-ticket')
                            ->copyable()
                            ->weight('bold')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('booking.guide.full_name')
                            ->label('Guide')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('booking.tourist.full_name')
                            ->label('Tourist')
                            ->icon('heroicon-o-user-circle'),

                        Infolists\Components\TextEntry::make('booking.start_date')
                            ->label('Tour Date')
                            ->date('F d, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('booking.total_participants')
                            ->label('Participants')
                            ->suffix(' people')
                            ->icon('heroicon-o-users'),

                        Infolists\Components\TextEntry::make('booking.status')
                            ->label('Booking Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'confirmed', 'upcoming' => 'success',
                                'ongoing' => 'info',
                                'completed' => 'gray',
                                default => 'warning',
                            }),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Vehicle Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('is_temporary')
                            ->label('Vehicle Type')
                            ->formatStateUsing(fn ($state) => $state ? 'Temporary/Rented' : 'Saved Vehicle')
                            ->badge()
                            ->color(fn ($state) => $state ? 'warning' : 'success'),

                        Infolists\Components\TextEntry::make('vehicle_display_name')
                            ->label('Vehicle')
                            ->icon('heroicon-o-truck')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('license_plate')
                            ->label('License Plate')
                            ->icon('heroicon-o-identification')
                            ->copyable()
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('seating_capacity')
                            ->label('Seating Capacity')
                            ->suffix(' passengers')
                            ->icon('heroicon-o-users'),

                        Infolists\Components\TextEntry::make('vehicle_details.has_ac')
                            ->label('Air Conditioning')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                            ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                            ->color(fn ($state) => $state ? 'success' : 'danger'),

                        Infolists\Components\TextEntry::make('vehicle_details.description')
                            ->label('Description')
                            ->placeholder('No description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Assignment Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('assigned_at')
                            ->label('Assigned At')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-clock'),

                        Infolists\Components\TextEntry::make('assignedByUser.email')
                            ->label('Assigned By')
                            ->placeholder('Guide (self-assigned)')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Record Created')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-arrow-path'),
                    ])
                    ->columns(2),
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
            'index' => Pages\ListBookingVehicleAssignments::route('/'),
            'create' => Pages\CreateBookingVehicleAssignment::route('/create'),
            'view' => Pages\ViewBookingVehicleAssignment::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Count upcoming bookings with assignments
        return static::getModel()::whereHas('booking', function ($q) {
            $q->where('start_date', '>=', now());
        })->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }
}
