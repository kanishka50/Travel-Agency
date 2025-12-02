<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComplaintResource\Pages;
use App\Filament\Resources\ComplaintResource\RelationManagers\ResponsesRelationManager;
use App\Models\Complaint;
use App\Models\Admin;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;

class ComplaintResource extends Resource
{
    protected static ?string $model = Complaint::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationGroup = 'Support & Moderation';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereIn('status', ['open', 'under_review'])->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Complaint Information')
                    ->schema([
                        Forms\Components\TextInput::make('complaint_number')
                            ->disabled()
                            ->dehydrated(false),

                        Forms\Components\Select::make('status')
                            ->options([
                                'open' => 'Open',
                                'under_review' => 'Under Review',
                                'resolved' => 'Resolved',
                                'closed' => 'Closed',
                            ])
                            ->required()
                            ->reactive(),

                        Forms\Components\Select::make('priority')
                            ->options([
                                'low' => 'Low',
                                'medium' => 'Medium',
                                'high' => 'High',
                                'urgent' => 'Urgent',
                            ])
                            ->required(),

                        Forms\Components\Select::make('assigned_to')
                            ->label('Assign To Admin')
                            ->options(Admin::all()->mapWithKeys(function ($admin) {
                                return [$admin->id => $admin->full_name ?? 'Admin #' . $admin->id];
                            }))
                            ->searchable()
                            ->nullable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Admin Notes & Resolution')
                    ->schema([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Internal Admin Notes')
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('resolution_summary')
                            ->label('Resolution Summary')
                            ->rows(3)
                            ->visible(fn ($get) => in_array($get('status'), ['resolved', 'closed']))
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Visibility Settings')
                    ->schema([
                        Forms\Components\Toggle::make('visible_to_defendant')
                            ->label('Visible to Defendant')
                            ->helperText('Allow the person being complained about to view this complaint')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('complaint_number')
                    ->label('Number')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('filed_by_type')
                    ->label('Filed By')
                    ->formatStateUsing(fn ($record) => $record->getComplainantName())
                    ->description(fn ($record) => ucfirst($record->filed_by_type))
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('filedByUser', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),

                Tables\Columns\TextColumn::make('against_user_id')
                    ->label('Against')
                    ->formatStateUsing(fn ($record) => $record->getDefendantName())
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('againstUser', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    }),

                Tables\Columns\TextColumn::make('complaint_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_')))
                    ->color(fn ($state) => match($state) {
                        'safety_concern' => 'danger',
                        'payment_issue' => 'warning',
                        'unprofessional_behavior' => 'warning',
                        'cancellation_dispute' => 'info',
                        'service_quality' => 'gray',
                        'other' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject')
                    ->limit(30)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->subject),

                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'open',
                        'info' => 'under_review',
                        'success' => 'resolved',
                        'gray' => 'closed',
                    ])
                    ->icons([
                        'heroicon-o-exclamation-circle' => 'open',
                        'heroicon-o-eye' => 'under_review',
                        'heroicon-o-check-circle' => 'resolved',
                        'heroicon-o-x-circle' => 'closed',
                    ])
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('priority')
                    ->colors([
                        'gray' => 'low',
                        'info' => 'medium',
                        'warning' => 'high',
                        'danger' => 'urgent',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('assignedAdmin.full_name')
                    ->label('Assigned To')
                    ->default('Unassigned')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Filed')
                    ->dateTime('M j, Y g:i A')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('resolved_at')
                    ->label('Resolved')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'under_review' => 'Under Review',
                        'resolved' => 'Resolved',
                        'closed' => 'Closed',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('complaint_type')
                    ->label('Type')
                    ->options([
                        'service_quality' => 'Service Quality',
                        'safety_concern' => 'Safety Concern',
                        'unprofessional_behavior' => 'Unprofessional Behavior',
                        'payment_issue' => 'Payment Issue',
                        'cancellation_dispute' => 'Cancellation Dispute',
                        'other' => 'Other',
                    ])
                    ->multiple(),

                Tables\Filters\SelectFilter::make('assigned_to')
                    ->label('Assigned Admin')
                    ->options(Admin::all()->mapWithKeys(function ($admin) {
                        return [$admin->id => $admin->name ?? 'Admin #' . $admin->id];
                    }))
                    ->multiple(),

                Tables\Filters\SelectFilter::make('filed_by_type')
                    ->label('Filed By Type')
                    ->options([
                        'tourist' => 'Tourist',
                        'guide' => 'Guide',
                    ]),

                Tables\Filters\Filter::make('unassigned')
                    ->label('Unassigned Only')
                    ->query(fn (Builder $query): Builder => $query->whereNull('assigned_to')),

                Tables\Filters\Filter::make('has_booking')
                    ->label('Has Booking')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('booking_id')),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('filed_from')
                            ->label('Filed From'),
                        Forms\Components\DatePicker::make('filed_until')
                            ->label('Filed Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['filed_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['filed_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Tables\Actions\Action::make('assign_to_me')
                    ->label('Assign to Me')
                    ->icon('heroicon-o-user-plus')
                    ->color('success')
                    ->visible(fn ($record) => $record->assigned_to === null)
                    ->requiresConfirmation()
                    ->action(function (Complaint $record) {
                        $record->update([
                            'assigned_to' => auth()->id(),
                            'status' => 'under_review',
                        ]);
                    })
                    ->successNotificationTitle('Complaint assigned to you'),

                Tables\Actions\Action::make('resolve')
                    ->label('Resolve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn ($record) => !in_array($record->status, ['resolved', 'closed']))
                    ->form([
                        Forms\Components\Textarea::make('resolution_summary')
                            ->label('Resolution Summary')
                            ->required()
                            ->rows(4),
                    ])
                    ->action(function (Complaint $record, array $data) {
                        $record->update([
                            'status' => 'resolved',
                            'resolution_summary' => $data['resolution_summary'],
                            'resolved_at' => now(),
                        ]);
                    })
                    ->successNotificationTitle('Complaint resolved'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assign')
                        ->label('Assign to Admin')
                        ->icon('heroicon-o-user-plus')
                        ->form([
                            Forms\Components\Select::make('admin_id')
                                ->label('Assign to')
                                ->options(Admin::all()->mapWithKeys(function ($admin) {
                                    return [$admin->id => $admin->name ?? 'Admin #' . $admin->id];
                                }))
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->update([
                                    'assigned_to' => $data['admin_id'],
                                    'status' => 'under_review',
                                ]);
                            }
                        }),

                    Tables\Actions\BulkAction::make('change_priority')
                        ->label('Change Priority')
                        ->icon('heroicon-o-flag')
                        ->form([
                            Forms\Components\Select::make('priority')
                                ->options([
                                    'low' => 'Low',
                                    'medium' => 'Medium',
                                    'high' => 'High',
                                    'urgent' => 'Urgent',
                                ])
                                ->required(),
                        ])
                        ->action(function ($records, array $data) {
                            foreach ($records as $record) {
                                $record->update(['priority' => $data['priority']]);
                            }
                        }),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Complaint Overview')
                    ->schema([
                        Infolists\Components\TextEntry::make('complaint_number')
                            ->label('Complaint Number')
                            ->badge()
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'open' => 'warning',
                                'under_review' => 'info',
                                'resolved' => 'success',
                                'closed' => 'gray',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('priority')
                            ->badge()
                            ->color(fn ($state) => match($state) {
                                'low' => 'gray',
                                'medium' => 'info',
                                'high' => 'warning',
                                'urgent' => 'danger',
                                default => 'gray',
                            }),

                        Infolists\Components\TextEntry::make('assignedAdmin.full_name')
                            ->label('Assigned To')
                            ->default('Not assigned')
                            ->icon('heroicon-o-user'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Parties Involved')
                    ->schema([
                        Infolists\Components\TextEntry::make('filed_by_type')
                            ->label('Complainant')
                            ->formatStateUsing(fn ($record) => $record->getComplainantName() . ' (' . ucfirst($record->filed_by_type) . ')')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('against_user_id')
                            ->label('Defendant')
                            ->formatStateUsing(fn ($record) => $record->getDefendantName())
                            ->icon('heroicon-o-user-minus'),

                        Infolists\Components\TextEntry::make('booking.booking_number')
                            ->label('Related Booking')
                            ->default('No booking associated')
                            ->icon('heroicon-o-ticket')
                            ->url(fn ($record) => $record->booking ? route('filament.admin.resources.bookings.view', $record->booking) : null),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Complaint Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('complaint_type')
                            ->label('Type')
                            ->badge()
                            ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_'))),

                        Infolists\Components\TextEntry::make('subject')
                            ->label('Subject')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->html()
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('evidence_files')
                            ->label('Evidence Files')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->formatStateUsing(fn ($state) => basename($state))
                            ->visible(fn ($record) => $record->evidence_files && count($record->evidence_files) > 0)
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Admin Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label('Internal Notes')
                            ->default('No admin notes')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('resolution_summary')
                            ->label('Resolution Summary')
                            ->visible(fn ($record) => $record->resolution_summary !== null)
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Visibility Settings')
                    ->schema([
                        Infolists\Components\IconEntry::make('visible_to_defendant')
                            ->label('Visible to Defendant')
                            ->boolean(),
                    ]),

                Infolists\Components\Section::make('Timestamps')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Filed At')
                            ->dateTime('M j, Y g:i A'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('M j, Y g:i A'),

                        Infolists\Components\TextEntry::make('resolved_at')
                            ->label('Resolved At')
                            ->dateTime('M j, Y g:i A')
                            ->visible(fn ($record) => $record->resolved_at !== null),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            ResponsesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComplaints::route('/'),
            'create' => Pages\CreateComplaint::route('/create'),
            'view' => Pages\ViewComplaint::route('/{record}'),
            'edit' => Pages\EditComplaint::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'filedByUser',
                'againstUser',
                'assignedAdmin',
                'booking',
                'complainant',  // Load polymorphic complainant (tourist/guide/admin)
                'against'       // Load polymorphic against (tourist/guide/admin)
            ]);
    }
}
