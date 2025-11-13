<?php

namespace App\Filament\Resources\TouristResource\Pages;

use App\Filament\Resources\TouristResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewTourist extends ViewRecord
{
    protected static string $resource = TouristResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Edit Action
            Actions\EditAction::make()
                ->icon('heroicon-o-pencil-square'),

            // Toggle Status Action
            Actions\Action::make('toggle_status')
                ->label(fn () => $this->record->user->status === 'active' ? 'Suspend Tourist' : 'Activate Tourist')
                ->icon(fn () => $this->record->user->status === 'active' ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                ->color(fn () => $this->record->user->status === 'active' ? 'warning' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn () => $this->record->user->status === 'active' ? 'Suspend Tourist Account' : 'Activate Tourist Account')
                ->modalDescription(fn () => $this->record->user->status === 'active'
                    ? "Are you sure you want to suspend {$this->record->full_name}'s account?"
                    : "Are you sure you want to activate {$this->record->full_name}'s account?"
                )
                ->action(function () {
                    $newStatus = $this->record->user->status === 'active' ? 'suspended' : 'active';
                    $this->record->user->update(['status' => $newStatus]);

                    Notification::make()
                        ->title('Status Updated')
                        ->body("Tourist account has been " . ($newStatus === 'active' ? 'activated' : 'suspended'))
                        ->success()
                        ->send();

                    // Refresh the page to show updated status
                    return redirect()->route('filament.admin.resources.tourists.view', ['record' => $this->record->id]);
                }),

            // Resend Verification Email
            Actions\Action::make('resend_verification')
                ->label('Resend Verification Email')
                ->icon('heroicon-o-envelope')
                ->color('info')
                ->visible(fn () => $this->record->user->email_verified_at === null)
                ->requiresConfirmation()
                ->modalHeading('Resend Verification Email')
                ->modalDescription('Send a new email verification link to this tourist.')
                ->action(function () {
                    $this->record->user->sendEmailVerificationNotification();

                    Notification::make()
                        ->title('Verification Email Sent')
                        ->body('A new verification email has been sent to the tourist.')
                        ->success()
                        ->send();
                }),

            // Delete Action
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->icon('heroicon-o-trash')
                ->modalHeading('Delete Tourist Account')
                ->modalDescription('Are you sure you want to delete this tourist account? This action cannot be undone.')
                ->successRedirectUrl(route('filament.admin.resources.tourists.index')),
        ];
    }
}
