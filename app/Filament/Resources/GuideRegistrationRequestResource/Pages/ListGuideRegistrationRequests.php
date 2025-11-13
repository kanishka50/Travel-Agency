<?php

namespace App\Filament\Resources\GuideRegistrationRequestResource\Pages;

use App\Filament\Resources\GuideRegistrationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListGuideRegistrationRequests extends ListRecords
{
    protected static string $resource = GuideRegistrationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - requests come from public form
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn () => static::getResource()::getModel()::count()),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'documents_pending'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'documents_pending')->count())
                ->badgeColor('warning'),

            'verified' => Tab::make('Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'documents_verified'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'documents_verified')->count())
                ->badgeColor('info'),

            'interview' => Tab::make('Interview')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'interview_scheduled'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'interview_scheduled')->count())
                ->badgeColor('primary'),

            'approved' => Tab::make('Approved')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'approved'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'approved')->count())
                ->badgeColor('success'),

            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'rejected'))
                ->badge(fn () => static::getResource()::getModel()::where('status', 'rejected')->count())
                ->badgeColor('danger'),
        ];
    }
}
