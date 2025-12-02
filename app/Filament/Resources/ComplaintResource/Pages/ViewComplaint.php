<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewComplaint extends ViewRecord
{
    protected static string $resource = ComplaintResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure all relationships are loaded before displaying infolist
        $this->record->load([
            'filedByUser',
            'againstUser',
            'assignedAdmin',
            'booking',
            'complainant',  // Polymorphic - loads tourist/guide/admin
            'against'       // Polymorphic - loads tourist/guide/admin
        ]);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('assign_to_me')
                ->label('Assign to Me')
                ->icon('heroicon-o-user-plus')
                ->color('success')
                ->visible(fn () => $this->record->assigned_to !== auth()->id())
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'assigned_to' => auth()->id(),
                        'status' => 'under_review',
                    ]);
                })
                ->successNotificationTitle('Complaint assigned to you'),

            Actions\Action::make('mark_under_review')
                ->label('Mark Under Review')
                ->icon('heroicon-o-eye')
                ->color('info')
                ->visible(fn () => $this->record->status === 'open')
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 'under_review']);
                })
                ->successNotificationTitle('Complaint marked as under review'),

            Actions\Action::make('resolve')
                ->label('Mark as Resolved')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn () => !in_array($this->record->status, ['resolved', 'closed']))
                ->form([
                    \Filament\Forms\Components\Textarea::make('resolution_summary')
                        ->label('Resolution Summary')
                        ->required()
                        ->rows(4),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => 'resolved',
                        'resolution_summary' => $data['resolution_summary'],
                        'resolved_at' => now(),
                    ]);
                })
                ->successNotificationTitle('Complaint resolved successfully'),

            Actions\Action::make('close')
                ->label('Close Complaint')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn () => $this->record->status !== 'closed')
                ->requiresConfirmation()
                ->modalHeading('Close Complaint')
                ->modalDescription('Are you sure you want to close this complaint? This should only be done after it has been resolved.')
                ->action(function () {
                    $this->record->update(['status' => 'closed']);
                })
                ->successNotificationTitle('Complaint closed'),

            Actions\Action::make('reopen')
                ->label('Reopen Complaint')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn () => in_array($this->record->status, ['resolved', 'closed']))
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update([
                        'status' => 'open',
                        'resolved_at' => null,
                    ]);
                })
                ->successNotificationTitle('Complaint reopened'),
        ];
    }
}
