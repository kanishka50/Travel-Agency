<?php

namespace App\Filament\Resources\PlanProposalResource\Pages;

use App\Filament\Resources\PlanProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPlanProposal extends EditRecord
{
    protected static string $resource = PlanProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
