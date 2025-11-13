<?php

namespace App\Filament\Resources\GuideResource\Pages;

use App\Filament\Resources\GuideResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGuides extends ListRecords
{
    protected static string $resource = GuideResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - guides are created through approval workflow
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn () => static::getResource()::getModel()::count()),

            'active' => Tab::make('Active')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('user', fn ($q) => $q->where('status', 'active')))
                ->badge(fn () => static::getResource()::getModel()::whereHas('user', fn ($q) => $q->where('status', 'active'))->count())
                ->badgeColor('success'),

            'inactive' => Tab::make('Inactive')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('user', fn ($q) => $q->where('status', 'inactive')))
                ->badge(fn () => static::getResource()::getModel()::whereHas('user', fn ($q) => $q->where('status', 'inactive'))->count())
                ->badgeColor('warning'),

            'suspended' => Tab::make('Suspended')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('user', fn ($q) => $q->where('status', 'suspended')))
                ->badge(fn () => static::getResource()::getModel()::whereHas('user', fn ($q) => $q->where('status', 'suspended'))->count())
                ->badgeColor('danger'),

            'high_rated' => Tab::make('High Rated')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('average_rating', '>=', 4.0))
                ->badge(fn () => static::getResource()::getModel()::where('average_rating', '>=', 4.0)->count())
                ->badgeColor('warning'),
        ];
    }
}
