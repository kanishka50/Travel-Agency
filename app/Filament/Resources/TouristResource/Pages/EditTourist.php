<?php

namespace App\Filament\Resources\TouristResource\Pages;

use App\Filament\Resources\TouristResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTourist extends EditRecord
{
    protected static string $resource = TouristResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->icon('heroicon-o-eye'),

            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->icon('heroicon-o-trash'),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
