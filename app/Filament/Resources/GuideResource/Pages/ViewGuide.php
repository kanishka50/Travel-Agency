<?php

namespace App\Filament\Resources\GuideResource\Pages;

use App\Filament\Resources\GuideResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewGuide extends ViewRecord
{
    protected static string $resource = GuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Edit Action
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square'),

            // Toggle Status Action
            Actions\Action::make('toggle_status')
                ->label(fn () => $this->record->user->status === 'active' ? 'Suspend Guide' : 'Activate Guide')
                ->icon(fn () => $this->record->user->status === 'active' ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                ->color(fn () => $this->record->user->status === 'active' ? 'warning' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn () => $this->record->user->status === 'active' ? 'Suspend Guide Account' : 'Activate Guide Account')
                ->modalDescription(fn () => $this->record->user->status === 'active'
                    ? "Are you sure you want to suspend {$this->record->full_name}'s guide account? They will not be able to receive bookings."
                    : "Are you sure you want to activate {$this->record->full_name}'s guide account? They will be able to receive bookings again."
                )
                ->action(function () {
                    $newStatus = $this->record->user->status === 'active' ? 'suspended' : 'active';
                    $this->record->user->update(['status' => $newStatus]);

                    Notification::make()
                        ->title('Status Updated')
                        ->body("Guide account has been " . ($newStatus === 'active' ? 'activated' : 'suspended'))
                        ->success()
                        ->send();

                    // Refresh the page to show updated status
                    return redirect()->route('filament.admin.resources.guides.view', ['record' => $this->record->id]);
                }),

            // Delete Action
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->icon('heroicon-o-trash')
                ->modalHeading('Delete Guide Account')
                ->modalDescription('Are you sure you want to delete this guide account? This action cannot be undone.')
                ->successRedirectUrl(route('filament.admin.resources.guides.index')),
        ];
    }
}
