<?php

namespace App\Filament\Resources\PlanProposalResource\Pages;

use App\Filament\Resources\PlanProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPlanProposal extends ViewRecord
{
    protected static string $resource = PlanProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Edit removed - plan proposals should not be modified by admins
        ];
    }
}
