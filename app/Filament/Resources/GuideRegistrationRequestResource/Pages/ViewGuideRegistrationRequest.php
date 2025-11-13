<?php

namespace App\Filament\Resources\GuideRegistrationRequestResource\Pages;

use App\Filament\Resources\GuideRegistrationRequestResource;
use App\Services\GuideApprovalService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewGuideRegistrationRequest extends ViewRecord
{
    protected static string $resource = GuideRegistrationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Approve Action
            Actions\Action::make('approve')
                ->label('Approve Guide')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Approve Guide Registration')
                ->modalDescription(fn ($record) => "You are about to approve {$record->full_name} as a guide. This will create their user account and guide profile.")
                ->modalSubmitActionLabel('Approve & Create Account')
                ->visible(fn ($record) => in_array($record->status, ['documents_pending', 'documents_verified', 'interview_scheduled']))
                ->action(function ($record) {
                    $service = new GuideApprovalService();

                    // Check if can approve
                    $validation = $service->canApprove($record);
                    if (!$validation['canApprove']) {
                        Notification::make()
                            ->title('Cannot Approve')
                            ->body($validation['reason'])
                            ->danger()
                            ->send();
                        return;
                    }

                    // Approve the guide
                    $result = $service->approveGuide($record, auth()->user()->admin->id);

                    if ($result['success']) {
                        $message = $result['message'];
                        if ($result['password_changed'] && $result['email_sent']) {
                            $message .= "\n\nAn email with login credentials has been sent to the guide's email address.";
                        }

                        Notification::make()
                            ->title('Guide Approved Successfully!')
                            ->body($message)
                            ->success()
                            ->duration(8000)
                            ->send();

                        return redirect()->route('filament.admin.resources.guide-registration-requests.view', ['record' => $record->id]);
                    } else {
                        Notification::make()
                            ->title('Approval Failed')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),

            // Reject Action
            Actions\Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Reject Guide Registration')
                ->modalDescription(fn ($record) => "You are about to reject {$record->full_name}'s guide registration. Please provide a reason.")
                ->form([
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Rejection Reason')
                        ->required()
                        ->rows(4)
                        ->placeholder('Please explain why this application is being rejected...')
                        ->helperText('This reason will be saved in admin notes and can be sent to the applicant.'),
                ])
                ->modalSubmitActionLabel('Reject Application')
                ->visible(fn ($record) => in_array($record->status, ['documents_pending', 'documents_verified', 'interview_scheduled']))
                ->action(function ($record, array $data) {
                    $service = new GuideApprovalService();

                    // Check if can reject
                    $validation = $service->canReject($record);
                    if (!$validation['canReject']) {
                        Notification::make()
                            ->title('Cannot Reject')
                            ->body($validation['reason'])
                            ->danger()
                            ->send();
                        return;
                    }

                    // Reject the guide
                    $result = $service->rejectGuide($record, auth()->user()->admin->id, $data['rejection_reason']);

                    if ($result['success']) {
                        Notification::make()
                            ->title('Application Rejected')
                            ->body($result['message'])
                            ->warning()
                            ->send();

                        return redirect()->route('filament.admin.resources.guide-registration-requests.view', ['record' => $record->id]);
                    } else {
                        Notification::make()
                            ->title('Rejection Failed')
                            ->body($result['message'])
                            ->danger()
                            ->send();
                    }
                }),

            // Update Status Action (for other status changes)
            Actions\EditAction::make()
                ->label('Update Status')
                ->icon('heroicon-o-pencil-square')
                ->modalHeading('Update Request Status')
                ->modalWidth('lg')
                ->visible(fn ($record) => !in_array($record->status, ['approved', 'rejected'])),

            // Delete Action
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->icon('heroicon-o-trash')
                ->visible(fn ($record) => $record->status !== 'approved'), // Can't delete approved requests
        ];
    }
}
