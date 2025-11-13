<?php

namespace App\Filament\Resources\GuidePlanResource\Pages;

use App\Filament\Resources\GuidePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuidePlan extends EditRecord
{
    protected static string $resource = GuidePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
