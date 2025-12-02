<?php

namespace App\Filament\Resources\TouristRequestResource\RelationManagers;

use App\Models\Bid;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class BidsRelationManager extends RelationManager
{
    protected static string $relationship = 'bids';

    protected static ?string $title = 'Submitted Bids';

    protected static ?string $recordTitleAttribute = 'bid_number';

    public function form(Form $form): Form
    {
        // Read-only form - admins should not create/edit bids
        return $form
            ->schema([
                Forms\Components\Placeholder::make('note')
                    ->content('Bids are submitted by guides and cannot be edited here.')
                    ->columnSpanFull(),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Bid Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('bid_number')
                            ->label('Bid Number')
                            ->badge()
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('guide.user.name')
                            ->label('Guide Name')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'pending' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                'withdrawn' => 'gray',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('total_price')
                            ->label('Total Price')
                            ->money('usd')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),

                        Infolists\Components\TextEntry::make('estimated_days')
                            ->label('Estimated Days')
                            ->suffix(' days')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('submitted_at')
                            ->label('Submitted At')
                            ->dateTime('F j, Y g:i A')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Proposal Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('proposal_message')
                            ->label('Proposal Message')
                            ->markdown()
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('day_by_day_plan')
                            ->label('Day-by-Day Itinerary')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Trip Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('destinations_covered')
                            ->label('Destinations Covered')
                            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                            ->icon('heroicon-o-map-pin')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('price_breakdown')
                            ->label('Price Breakdown')
                            ->markdown(),

                        Infolists\Components\TextEntry::make('accommodation_details')
                            ->label('Accommodation Details')
                            ->markdown(),

                        Infolists\Components\TextEntry::make('transport_details')
                            ->label('Transport Details')
                            ->markdown(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Infolists\Components\Section::make('Services')
                    ->schema([
                        Infolists\Components\TextEntry::make('included_services')
                            ->label('Included Services')
                            ->markdown(),

                        Infolists\Components\TextEntry::make('excluded_services')
                            ->label('Excluded Services')
                            ->markdown(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Infolists\Components\Section::make('Response Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('responded_at')
                            ->label('Responded At')
                            ->dateTime('F j, Y g:i A')
                            ->icon('heroicon-o-clock')
                            ->placeholder('Not responded yet'),

                        Infolists\Components\TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($record) => $record->status === 'rejected')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record->responded_at !== null)
                    ->collapsible(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('bid_number')
            ->columns([
                Tables\Columns\TextColumn::make('bid_number')
                    ->label('Bid #')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('guide.user.name')
                    ->label('Guide')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guide.user.email')
                    ->label('Guide Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Bid Amount')
                    ->money('usd')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\TextColumn::make('estimated_days')
                    ->label('Days')
                    ->suffix(' days')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                        'gray' => 'withdrawn',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'accepted',
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-minus-circle' => 'withdrawn',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('submitted_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('responded_at')
                    ->label('Responded')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->placeholder('Pending')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('submitted_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'withdrawn' => 'Withdrawn',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('min_price')
                            ->label('Min Price')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('max_price')
                            ->label('Max Price')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['min_price'], fn (Builder $query, $price) => $query->where('total_price', '>=', $price))
                            ->when($data['max_price'], fn (Builder $query, $price) => $query->where('total_price', '<=', $price));
                    }),
            ])
            ->headerActions([
                // Remove create action - bids are created by guides only
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Remove edit and delete actions - admins should not modify bids
            ])
            ->bulkActions([
                // Remove bulk actions - admins should not delete bids
            ])
            ->emptyStateHeading('No Bids Yet')
            ->emptyStateDescription('This request has not received any bids from guides yet.')
            ->emptyStateIcon('heroicon-o-chat-bubble-left-ellipsis');
    }
}
