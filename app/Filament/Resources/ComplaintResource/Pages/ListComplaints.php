<?php

namespace App\Filament\Resources\ComplaintResource\Pages;

use App\Filament\Resources\ComplaintResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListComplaints extends ListRecords
{
    protected static string $resource = ComplaintResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Removed create action - complaints are filed by users, not created by admins
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Complaints'),

            'open' => Tab::make('Open')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'open'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'open')->count())
                ->badgeColor('warning'),

            'under_review' => Tab::make('Under Review')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'under_review'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'under_review')->count())
                ->badgeColor('info'),

            'urgent' => Tab::make('Urgent')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('priority', 'urgent'))
                ->badge(fn () => static::getResource()::getModel()::where('priority', 'urgent')->count())
                ->badgeColor('danger'),

            'high_priority' => Tab::make('High Priority')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('priority', 'high'))
                ->badge(fn () => static::getResource()::getModel()::where('priority', 'high')->count())
                ->badgeColor('warning'),

            'unassigned' => Tab::make('Unassigned')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('assigned_to'))
                ->badge(fn () => static::getResource()::getModel()::whereNull('assigned_to')->count())
                ->badgeColor('gray'),

            'my_complaints' => Tab::make('Assigned to Me')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('assigned_to', auth()->id()))
                ->badge(fn () => static::getResource()::getModel()::where('assigned_to', auth()->id())->count())
                ->badgeColor('success'),

            'resolved' => Tab::make('Resolved')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'resolved'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'resolved')->count())
                ->badgeColor('success'),

            'closed' => Tab::make('Closed')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'closed'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'closed')->count())
                ->badgeColor('gray'),
        ];
    }
}
