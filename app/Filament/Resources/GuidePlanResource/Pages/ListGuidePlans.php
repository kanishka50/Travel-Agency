<?php

namespace App\Filament\Resources\GuidePlanResource\Pages;

use App\Filament\Resources\GuidePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGuidePlans extends ListRecords
{
    protected static string $resource = GuidePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Plans')
                ->badge(fn () => \App\Models\GuidePlan::count()),

            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'active'))
                ->badge(fn () => \App\Models\GuidePlan::where('status', 'active')->count())
                ->badgeColor('success'),

            'draft' => Tab::make('Drafts')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'draft'))
                ->badge(fn () => \App\Models\GuidePlan::where('status', 'draft')->count())
                ->badgeColor('gray'),

            'inactive' => Tab::make('Inactive')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'inactive'))
                ->badge(fn () => \App\Models\GuidePlan::where('status', 'inactive')->count())
                ->badgeColor('danger'),

            'seasonal' => Tab::make('Seasonal')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('availability_type', 'date_range'))
                ->badge(fn () => \App\Models\GuidePlan::where('availability_type', 'date_range')->count())
                ->badgeColor('warning'),

            'popular' => Tab::make('Popular')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('booking_count', '>', 5)->orderBy('booking_count', 'desc'))
                ->badge(fn () => \App\Models\GuidePlan::where('booking_count', '>', 5)->count())
                ->badgeColor('info'),
        ];
    }
}
