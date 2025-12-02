<?php

namespace App\Filament\Resources\TouristRequestResource\Pages;

use App\Filament\Resources\TouristRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTouristRequest extends EditRecord
{
    protected static string $resource = TouristRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
