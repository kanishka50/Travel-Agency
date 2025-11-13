<?php

namespace App\Filament\Resources\GuidePlanResource\Pages;

use App\Filament\Resources\GuidePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewGuidePlan extends ViewRecord
{
    protected static string $resource = GuidePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('publish')
                ->label('Publish')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'active']))
                ->visible(fn () => $this->record->status === 'draft'),

            Actions\Action::make('deactivate')
                ->label('Deactivate')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'inactive']))
                ->visible(fn () => $this->record->status === 'active'),

            Actions\Action::make('activate')
                ->label('Activate')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn () => $this->record->update(['status' => 'active']))
                ->visible(fn () => $this->record->status === 'inactive'),

            Actions\DeleteAction::make(),
        ];
    }
}
