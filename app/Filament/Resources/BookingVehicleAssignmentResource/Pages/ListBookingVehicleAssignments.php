<?php

namespace App\Filament\Resources\BookingVehicleAssignmentResource\Pages;

use App\Filament\Resources\BookingVehicleAssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookingVehicleAssignments extends ListRecords
{
    protected static string $resource = BookingVehicleAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Assign Vehicle')
                ->icon('heroicon-o-plus-circle'),
        ];
    }
}
