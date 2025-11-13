<?php

namespace App\Filament\Resources\TouristResource\Pages;

use App\Filament\Resources\TouristResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListTourists extends ListRecords
{
    protected static string $resource = TouristResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - tourists register through public form
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

            'unverified' => Tab::make('Unverified')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('user', fn ($q) => $q->whereNull('email_verified_at')))
                ->badge(fn () => static::getResource()::getModel()::whereHas('user', fn ($q) => $q->whereNull('email_verified_at'))->count())
                ->badgeColor('warning'),

            'has_bookings' => Tab::make('With Bookings')
                ->modifyQueryUsing(fn (Builder $query) => $query->has('bookings'))
                ->badge(fn () => static::getResource()::getModel()::has('bookings')->count())
                ->badgeColor('info'),
        ];
    }
}
