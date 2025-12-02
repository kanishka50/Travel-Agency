<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanProposalResource\Pages;
use App\Models\PlanProposal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class PlanProposalResource extends Resource
{
    protected static ?string $model = PlanProposal::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Plan Proposals';

    protected static ?string $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 4;

    // Read-only form - proposals are created by tourists
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Proposal Information')
                    ->schema([
                        Forms\Components\Select::make('tourist_id')
                            ->relationship('tourist', 'full_name')
                            ->disabled(),
                        Forms\Components\Select::make('guide_plan_id')
                            ->relationship('guidePlan', 'title')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'accepted' => 'Accepted',
                                'rejected' => 'Rejected',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('tourist.full_name')
                    ->label('Tourist')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guidePlan.guide.user.name')
                    ->label('Guide')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('guidePlan.title')
                    ->label('Plan')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 30 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('trip_dates')
                    ->label('Trip Dates')
                    ->state(fn (PlanProposal $record) => $record->start_date->format('M j') . ' - ' . $record->end_date->format('M j, Y'))
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('start_date', $direction);
                    }),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Duration')
                    ->suffix(' days')
                    ->sortable(false),

                Tables\Columns\TextColumn::make('total_travelers')
                    ->label('Travelers')
                    ->state(fn (PlanProposal $record) => $record->num_adults . 'A' . ($record->num_children > 0 ? ', ' . $record->num_children . 'C' : ''))
                    ->sortable(false),

                Tables\Columns\TextColumn::make('proposed_price')
                    ->label('Proposed Price')
                    ->money('usd')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'accepted',
                        'danger' => 'rejected',
                        'gray' => 'cancelled',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-check-circle' => 'accepted',
                        'heroicon-o-x-circle' => 'rejected',
                        'heroicon-o-minus-circle' => 'cancelled',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'accepted' => 'Accepted',
                        'rejected' => 'Rejected',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('pending')
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
                            ->when($data['min_price'], fn (Builder $query, $price) => $query->where('proposed_price', '>=', $price))
                            ->when($data['max_price'], fn (Builder $query, $price) => $query->where('proposed_price', '<=', $price));
                    }),

                Tables\Filters\Filter::make('trip_dates')
                    ->form([
                        Forms\Components\DatePicker::make('trip_from')
                            ->label('Trip From'),
                        Forms\Components\DatePicker::make('trip_until')
                            ->label('Trip Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['trip_from'], fn (Builder $query, $date) => $query->whereDate('start_date', '>=', $date))
                            ->when($data['trip_until'], fn (Builder $query, $date) => $query->whereDate('end_date', '<=', $date));
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('submitted_from')
                            ->label('Submitted From'),
                        Forms\Components\DatePicker::make('submitted_until')
                            ->label('Submitted Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['submitted_from'], fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['submitted_until'], fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('cancel_proposal')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (PlanProposal $record) => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Proposal')
                    ->modalDescription('Are you sure you want to cancel this proposal?')
                    ->action(function (PlanProposal $record) {
                        $record->update(['status' => 'cancelled']);
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Proposal Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Proposal ID')
                            ->badge()
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'pending' => 'warning',
                                'accepted' => 'success',
                                'rejected' => 'danger',
                                'cancelled' => 'gray',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Submitted At')
                            ->dateTime('F j, Y g:i A')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Tourist Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('tourist.full_name')
                            ->label('Tourist Name')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('tourist.email')
                            ->label('Tourist Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('tourist.phone_number')
                            ->label('Phone Number')
                            ->icon('heroicon-o-phone')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Guide & Plan Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('guidePlan.guide.user.name')
                            ->label('Guide Name')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('guidePlan.guide.user.email')
                            ->label('Guide Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('guidePlan.title')
                            ->label('Original Plan')
                            ->columnSpanFull()
                            ->icon('heroicon-o-map'),

                        Infolists\Components\TextEntry::make('guidePlan.price_per_adult')
                            ->label('Original Price (per adult)')
                            ->money('usd'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Infolists\Components\Section::make('Trip Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('start_date')
                            ->label('Start Date')
                            ->date('F j, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('end_date')
                            ->label('End Date')
                            ->date('F j, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('duration_days')
                            ->label('Duration')
                            ->suffix(' days')
                            ->icon('heroicon-o-clock'),

                        Infolists\Components\TextEntry::make('num_adults')
                            ->label('Number of Adults')
                            ->icon('heroicon-o-user-group'),

                        Infolists\Components\TextEntry::make('num_children')
                            ->label('Number of Children')
                            ->icon('heroicon-o-user-group'),

                        Infolists\Components\TextEntry::make('children_ages')
                            ->label('Children Ages')
                            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : 'N/A')
                            ->visible(fn ($record) => $record->num_children > 0),

                        Infolists\Components\TextEntry::make('total_travelers')
                            ->label('Total Travelers')
                            ->badge()
                            ->color('info'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Pricing')
                    ->schema([
                        Infolists\Components\TextEntry::make('proposed_price')
                            ->label('Proposed Price')
                            ->money('usd')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),

                        Infolists\Components\TextEntry::make('discount_percentage')
                            ->label('Discount from Original')
                            ->suffix('%')
                            ->visible(fn ($state) => $state > 0)
                            ->badge()
                            ->color('warning'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Customization Request')
                    ->schema([
                        Infolists\Components\TextEntry::make('modifications')
                            ->label('Requested Modifications')
                            ->markdown()
                            ->placeholder('No modifications requested')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('message')
                            ->label('Message to Guide')
                            ->markdown()
                            ->placeholder('No message provided')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),

                Infolists\Components\Section::make('Response')
                    ->schema([
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Responded At')
                            ->dateTime('F j, Y g:i A')
                            ->icon('heroicon-o-clock'),

                        Infolists\Components\TextEntry::make('rejection_reason')
                            ->label('Rejection Reason')
                            ->visible(fn ($record) => $record->status === 'rejected')
                            ->columnSpanFull()
                            ->color('danger'),

                        Infolists\Components\TextEntry::make('booking.booking_number')
                            ->label('Booking Number')
                            ->visible(fn ($record) => $record->status === 'accepted' && $record->booking_id)
                            ->badge()
                            ->color('success')
                            ->icon('heroicon-o-check-circle'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => in_array($record->status, ['accepted', 'rejected']))
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlanProposals::route('/'),
            'view' => Pages\ViewPlanProposal::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $pendingCount = static::getModel()::where('status', 'pending')->count();
        return $pendingCount > 0 ? 'warning' : 'gray';
    }
}
