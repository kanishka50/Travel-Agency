<?php

namespace App\Filament\Resources\TouristRequestResource\Pages;

use App\Filament\Resources\TouristRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTouristRequest extends ViewRecord
{
    protected static string $resource = TouristRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Edit removed - tourist requests should not be modified by admins
        ];
    }
}
