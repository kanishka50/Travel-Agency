<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TouristRequestResource\Pages;
use App\Filament\Resources\TouristRequestResource\RelationManagers;
use App\Models\TouristRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class TouristRequestResource extends Resource
{
    protected static ?string $model = TouristRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationLabel = 'Tourist Requests';

    protected static ?string $navigationGroup = 'Booking Management';

    protected static ?int $navigationSort = 3;

    // Form is view-only for admins
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Request Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('description')
                            ->disabled()
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Tourist & Status')
                    ->schema([
                        Forms\Components\Select::make('tourist_id')
                            ->relationship('tourist', 'full_name')
                            ->disabled(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'bids_received' => 'Bids Received',
                                'bid_accepted' => 'Bid Accepted',
                                'closed' => 'Closed',
                                'expired' => 'Expired',
                                'booked' => 'Booked',
                            ])
                            ->required(),
                        Forms\Components\TextInput::make('bid_count')
                            ->disabled()
                            ->numeric(),
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

                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->limit(40)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    }),

                Tables\Columns\TextColumn::make('preferred_destinations')
                    ->label('Destinations')
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                    ->limit(30)
                    ->tooltip(function ($state): ?string {
                        if (is_array($state)) {
                            $destinations = implode(', ', $state);
                            return strlen($destinations) > 30 ? $destinations : null;
                        }
                        return null;
                    }),

                Tables\Columns\TextColumn::make('budget_range')
                    ->label('Budget')
                    ->state(fn (TouristRequest $record) => '$' . number_format($record->budget_min, 0) . ' - $' . number_format($record->budget_max, 0))
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->orderBy('budget_min', $direction);
                    }),

                Tables\Columns\TextColumn::make('duration_days')
                    ->label('Duration')
                    ->suffix(' days')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Date')
                    ->date('M j, Y')
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'success' => 'open',
                        'info' => 'bids_received',
                        'warning' => 'bid_accepted',
                        'danger' => 'closed',
                        'gray' => 'expired',
                        'primary' => 'booked',
                    ])
                    ->icons([
                        'heroicon-o-clock' => 'open',
                        'heroicon-o-envelope' => 'bids_received',
                        'heroicon-o-hand-thumb-up' => 'bid_accepted',
                        'heroicon-o-x-circle' => 'closed',
                        'heroicon-o-calendar-x' => 'expired',
                        'heroicon-o-check-circle' => 'booked',
                    ]),

                Tables\Columns\TextColumn::make('bid_count')
                    ->label('Bids')
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray')
                    ->sortable(),

                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expires')
                    ->date('M j, Y')
                    ->sortable()
                    ->color(fn ($state) => $state->isPast() ? 'danger' : 'success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'bids_received' => 'Bids Received',
                        'bid_accepted' => 'Bid Accepted',
                        'closed' => 'Closed',
                        'expired' => 'Expired',
                        'booked' => 'Booked',
                    ])
                    ->default('open'),

                Tables\Filters\Filter::make('has_bids')
                    ->label('Has Bids')
                    ->query(fn (Builder $query) => $query->where('bid_count', '>', 0)),

                Tables\Filters\Filter::make('no_bids')
                    ->label('No Bids')
                    ->query(fn (Builder $query) => $query->where('bid_count', 0)),

                Tables\Filters\Filter::make('expired')
                    ->label('Expired')
                    ->query(fn (Builder $query) => $query->where('expires_at', '<=', now())),

                Tables\Filters\Filter::make('budget')
                    ->form([
                        Forms\Components\TextInput::make('min_budget')
                            ->label('Min Budget')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('max_budget')
                            ->label('Max Budget')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['min_budget'], fn (Builder $query, $amount) => $query->where('budget_min', '>=', $amount))
                            ->when($data['max_budget'], fn (Builder $query, $amount) => $query->where('budget_max', '<=', $amount));
                    }),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Created From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Created Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['created_from'], fn (Builder $query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn (Builder $query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('close_request')
                    ->label('Close')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (TouristRequest $record) => $record->status === 'open')
                    ->requiresConfirmation()
                    ->modalHeading('Close Request')
                    ->modalDescription('Are you sure you want to close this request? It will no longer accept bids.')
                    ->action(function (TouristRequest $record) {
                        $record->update(['status' => 'closed']);
                    }),

                Tables\Actions\Action::make('reopen_request')
                    ->label('Reopen')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->visible(fn (TouristRequest $record) => $record->status === 'closed' && !$record->hasExpired())
                    ->requiresConfirmation()
                    ->action(function (TouristRequest $record) {
                        $record->update(['status' => 'open']);
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
                Infolists\Components\Section::make('Request Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label('Title')
                            ->weight('bold')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->markdown()
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('tourist.full_name')
                            ->label('Tourist')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('tourist.email')
                            ->label('Tourist Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'open' => 'success',
                                'bids_received' => 'info',
                                'bid_accepted' => 'warning',
                                'closed' => 'danger',
                                'expired' => 'gray',
                                'booked' => 'primary',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('bid_count')
                            ->label('Total Bids')
                            ->badge()
                            ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Trip Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('preferred_destinations')
                            ->label('Preferred Destinations')
                            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state)
                            ->icon('heroicon-o-map-pin'),

                        Infolists\Components\TextEntry::make('must_visit_places')
                            ->label('Must-Visit Places')
                            ->placeholder('Not specified'),

                        Infolists\Components\TextEntry::make('duration_days')
                            ->label('Duration')
                            ->suffix(' days')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('start_date')
                            ->label('Start Date')
                            ->date('F j, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('end_date')
                            ->label('End Date')
                            ->date('F j, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('dates_flexible')
                            ->label('Dates Flexible')
                            ->formatStateUsing(fn ($state) => $state ? 'Yes' : 'No')
                            ->badge()
                            ->color(fn ($state) => $state ? 'success' : 'gray'),

                        Infolists\Components\TextEntry::make('flexibility_range')
                            ->label('Flexibility Range')
                            ->placeholder('Not specified')
                            ->visible(fn ($record) => $record->dates_flexible),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Budget & Group')
                    ->schema([
                        Infolists\Components\TextEntry::make('budget_min')
                            ->label('Minimum Budget')
                            ->money('usd')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),

                        Infolists\Components\TextEntry::make('budget_max')
                            ->label('Maximum Budget')
                            ->money('usd')
                            ->size(Infolists\Components\TextEntry\TextEntrySize::Large)
                            ->weight('bold')
                            ->color('success'),

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
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Preferences & Requirements')
                    ->schema([
                        Infolists\Components\TextEntry::make('trip_focus')
                            ->label('Trip Focus')
                            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : $state),

                        Infolists\Components\TextEntry::make('transport_preference')
                            ->label('Transport Preference')
                            ->placeholder('Not specified'),

                        Infolists\Components\TextEntry::make('accommodation_preference')
                            ->label('Accommodation Preference')
                            ->placeholder('Not specified'),

                        Infolists\Components\TextEntry::make('dietary_requirements')
                            ->label('Dietary Requirements')
                            ->formatStateUsing(fn ($state) => is_array($state) ? implode(', ', $state) : 'None')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('accessibility_needs')
                            ->label('Accessibility Needs')
                            ->placeholder('None specified')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('special_requests')
                            ->label('Special Requests')
                            ->placeholder('None specified')
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Infolists\Components\Section::make('Request Timeline')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('F j, Y g:i A')
                            ->icon('heroicon-o-clock'),

                        Infolists\Components\TextEntry::make('expires_at')
                            ->label('Expires At')
                            ->date('F j, Y')
                            ->icon('heroicon-o-calendar')
                            ->color(fn ($state) => $state->isPast() ? 'danger' : 'success'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('F j, Y g:i A')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\BidsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTouristRequests::route('/'),
            'view' => Pages\ViewTouristRequest::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'open')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $openCount = static::getModel()::where('status', 'open')->count();
        return $openCount > 0 ? 'success' : 'gray';
    }
}
