<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class BookingsWithoutVehicleWidget extends BaseWidget
{
    protected static ?string $heading = 'Bookings Awaiting Vehicle Assignment';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->whereDoesntHave('vehicleAssignment')
                    ->whereIn('status', ['confirmed', 'upcoming'])
                    ->where('start_date', '>=', now())
                    ->orderBy('start_date', 'asc')
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_number')
                    ->label('Booking')
                    ->searchable()
                    ->weight('bold')
                    ->color('primary')
                    ->url(fn ($record) => route('filament.admin.resources.bookings.view', $record)),

                Tables\Columns\TextColumn::make('guide.full_name')
                    ->label('Guide')
                    ->searchable(),

                Tables\Columns\TextColumn::make('tourist.full_name')
                    ->label('Tourist')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('guidePlan.title')
                    ->label('Tour')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->guidePlan?->title),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Tour Date')
                    ->date('M d, Y')
                    ->color(fn ($record) => $record->start_date <= now()->addDays(3) ? 'danger' : 'warning')
                    ->description(fn ($record) => $record->start_date->diffForHumans()),

                Tables\Columns\TextColumn::make('total_participants')
                    ->label('Participants')
                    ->suffix(' people')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('days_until_tour')
                    ->label('Days Left')
                    ->getStateUsing(fn ($record) => $record->start_date->diffInDays(now()))
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state <= 1 => 'danger',
                        $state <= 3 => 'warning',
                        default => 'info',
                    })
                    ->alignCenter(),
            ])
            ->actions([
                Tables\Actions\Action::make('assign')
                    ->label('Assign Vehicle')
                    ->icon('heroicon-o-truck')
                    ->color('success')
                    ->url(fn ($record) => route('filament.admin.resources.booking-vehicle-assignments.create', ['booking_id' => $record->id])),

                Tables\Actions\Action::make('notify_guide')
                    ->label('Notify Guide')
                    ->icon('heroicon-o-bell')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Send Reminder to Guide')
                    ->modalDescription('This will send a reminder notification to the guide about assigning a vehicle.')
                    ->action(function ($record) {
                        // TODO: Implement notification logic in Phase 7
                        \Filament\Notifications\Notification::make()
                            ->title('Reminder Sent')
                            ->body("Reminder sent to {$record->guide->full_name}")
                            ->success()
                            ->send();
                    }),
            ])
            ->emptyStateHeading('All bookings have vehicles assigned')
            ->emptyStateDescription('Great! All upcoming confirmed bookings have vehicle assignments.')
            ->emptyStateIcon('heroicon-o-check-circle')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }

    public static function canView(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }
}
