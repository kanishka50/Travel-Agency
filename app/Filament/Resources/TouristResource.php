<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TouristResource\Pages;
use App\Models\Tourist;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TouristResource extends Resource
{
    protected static ?string $model = Tourist::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Tourists';

    protected static ?string $modelLabel = 'Tourist';

    protected static ?string $pluralModelLabel = 'Tourists';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User Account')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Associated user account'),

                        Forms\Components\TextInput::make('full_name')
                            ->label('Full Name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('country')
                            ->label('Country')
                            ->required()
                            ->maxLength(100),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Emergency Contact')
                    ->schema([
                        Forms\Components\TextInput::make('emergency_contact_name')
                            ->label('Emergency Contact Name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('emergency_contact_phone')
                            ->label('Emergency Contact Phone')
                            ->tel()
                            ->maxLength(50),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\Placeholder::make('bookings_count')
                            ->label('Total Bookings')
                            ->content(fn ($record) => $record ? $record->bookings()->count() : 0),

                        Forms\Components\Placeholder::make('requests_count')
                            ->label('Total Requests')
                            ->content(fn ($record) => $record ? $record->requests()->count() : 0),

                        Forms\Components\Placeholder::make('reviews_count')
                            ->label('Reviews Written')
                            ->content(fn ($record) => $record ? $record->reviews()->count() : 0),

                        Forms\Components\Placeholder::make('favorites_count')
                            ->label('Favorites')
                            ->content(fn ($record) => $record ? $record->favorites()->count() : 0),
                    ])
                    ->columns(4)
                    ->collapsible()
                    ->collapsed()
                    ->hidden(fn ($record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('country')
                    ->label('Country')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-globe-alt'),

                Tables\Columns\TextColumn::make('user.status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),

                Tables\Columns\TextColumn::make('bookings_count')
                    ->label('Bookings')
                    ->counts('bookings')
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-o-calendar')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('requests_count')
                    ->label('Requests')
                    ->counts('requests')
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-o-document-text')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('reviews_count')
                    ->label('Reviews')
                    ->counts('reviews')
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-o-star')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('user.email_verified_at')
                    ->label('Verified')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Verified' : 'Unverified')
                    ->color(fn ($state) => $state ? 'success' : 'warning')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user.status')
                    ->label('Status')
                    ->relationship('user', 'status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('country')
                    ->options(function () {
                        return Tourist::query()
                            ->select('country')
                            ->distinct()
                            ->pluck('country', 'country')
                            ->toArray();
                    })
                    ->searchable()
                    ->native(false),

                Tables\Filters\Filter::make('verified')
                    ->label('Email Verified')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereHas('user', fn ($q) => $q->whereNotNull('email_verified_at'))
                    ),

                Tables\Filters\Filter::make('unverified')
                    ->label('Email Unverified')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereHas('user', fn ($q) => $q->whereNull('email_verified_at'))
                    ),

                Tables\Filters\Filter::make('has_bookings')
                    ->label('Has Bookings')
                    ->query(fn (Builder $query): Builder => $query->has('bookings')),

                Tables\Filters\Filter::make('recent')
                    ->label('Recent (Last 30 days)')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\Action::make('toggle_status')
                    ->label(fn ($record) => $record->user->status === 'active' ? 'Suspend' : 'Activate')
                    ->icon(fn ($record) => $record->user->status === 'active' ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                    ->color(fn ($record) => $record->user->status === 'active' ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $newStatus = $record->user->status === 'active' ? 'suspended' : 'active';
                        $record->user->update(['status' => $newStatus]);

                        \Filament\Notifications\Notification::make()
                            ->title('Status Updated')
                            ->body("Tourist account has been " . ($newStatus === 'active' ? 'activated' : 'suspended'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('resend_verification')
                    ->label('Resend Verification')
                    ->icon('heroicon-o-envelope')
                    ->color('info')
                    ->visible(fn ($record) => $record->user->email_verified_at === null)
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        $record->user->sendEmailVerificationNotification();

                        \Filament\Notifications\Notification::make()
                            ->title('Verification Email Sent')
                            ->body('Verification email has been sent to the tourist.')
                            ->success()
                            ->send();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No tourists yet')
            ->emptyStateDescription('Tourist registrations will appear here.')
            ->emptyStateIcon('heroicon-o-users');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Tourist Profile')
                    ->schema([
                        Infolists\Components\TextEntry::make('full_name')
                            ->label('Full Name')
                            ->icon('heroicon-o-user')
                            ->weight('bold')
                            ->size('lg'),

                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone')
                            ->icon('heroicon-o-phone')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('country')
                            ->label('Country')
                            ->icon('heroicon-o-globe-alt'),

                        Infolists\Components\TextEntry::make('user.status')
                            ->label('Account Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'success',
                                'inactive' => 'warning',
                                'suspended' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                        Infolists\Components\TextEntry::make('user.email_verified_at')
                            ->label('Email Verification')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'Verified on ' . $state->format('M d, Y') : 'Not Verified')
                            ->color(fn ($state) => $state ? 'success' : 'warning')
                            ->icon(fn ($state) => $state ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Emergency Contact')
                    ->schema([
                        Infolists\Components\TextEntry::make('emergency_contact_name')
                            ->label('Contact Name')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('emergency_contact_phone')
                            ->label('Contact Phone')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-phone')
                            ->copyable(),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Infolists\Components\Section::make('Activity Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('bookings_count')
                            ->label('Total Bookings')
                            ->state(fn ($record) => $record->bookings()->count())
                            ->icon('heroicon-o-calendar')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('requests_count')
                            ->label('Total Requests')
                            ->state(fn ($record) => $record->requests()->count())
                            ->icon('heroicon-o-document-text')
                            ->color('info'),

                        Infolists\Components\TextEntry::make('reviews_count')
                            ->label('Reviews Written')
                            ->state(fn ($record) => $record->reviews()->count())
                            ->icon('heroicon-o-star')
                            ->color('warning'),

                        Infolists\Components\TextEntry::make('favorites_count')
                            ->label('Favorites')
                            ->state(fn ($record) => $record->favorites()->count())
                            ->icon('heroicon-o-heart')
                            ->color('danger'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Account Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Joined On')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-arrow-path'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed(),
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
            'index' => Pages\ListTourists::route('/'),
            'view' => Pages\ViewTourist::route('/{record}'),
            'edit' => Pages\EditTourist::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $activeCount = static::getModel()::whereHas('user', function ($query) {
            $query->where('status', 'active');
        })->count();

        return $activeCount > 0 ? (string) $activeCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
