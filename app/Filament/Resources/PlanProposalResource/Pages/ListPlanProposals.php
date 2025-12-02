<?php

namespace App\Filament\Resources\PlanProposalResource\Pages;

use App\Filament\Resources\PlanProposalResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPlanProposals extends ListRecords
{
    protected static string $resource = PlanProposalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Create removed - plan proposals are created by tourists, not admins
        ];
    }
}
