<?php

namespace App\Filament\Resources\GuideRegistrationRequestResource\Pages;

use App\Filament\Resources\GuideRegistrationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGuideRegistrationRequest extends EditRecord
{
    protected static string $resource = GuideRegistrationRequestResource::class;

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

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Automatically set reviewed_by and reviewed_at when status is updated
        if ($this->record->isDirty('status')) {
            $data['reviewed_by'] = auth()->user()->admin->id ?? null;
            $data['reviewed_at'] = now();
        }

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
