<?php

namespace App\Filament\Resources\TouristRequestResource\Pages;

use App\Filament\Resources\TouristRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTouristRequests extends ListRecords
{
    protected static string $resource = TouristRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create removed - tourist requests are created by tourists, not admins
        ];
    }
}
