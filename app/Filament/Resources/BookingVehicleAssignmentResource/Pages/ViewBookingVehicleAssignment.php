<?php

namespace App\Filament\Resources\BookingVehicleAssignmentResource\Pages;

use App\Filament\Resources\BookingVehicleAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookingVehicleAssignment extends ViewRecord
{
    protected static string $resource = BookingVehicleAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn () => $this->record->booking->start_date >= now()),

            Actions\DeleteAction::make()
                ->visible(fn () => $this->record->booking->start_date >= now())
                ->modalDescription('This will remove the vehicle assignment. The guide will need to assign a new vehicle.'),
        ];
    }
}
