<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuideResource\Pages;
use App\Models\Guide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class GuideResource extends Resource
{
    protected static ?string $model = Guide::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationLabel = 'Guides';

    protected static ?string $modelLabel = 'Guide';

    protected static ?string $pluralModelLabel = 'Guides';

    protected static ?string $navigationGroup = 'Guide Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\FileUpload::make('profile_photo')
                            ->label('Profile Photo')
                            ->image()
                            ->disk('public')
                            ->directory('guide-photos')
                            ->imageEditor()
                            ->circleCropper()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('guide_id_number')
                            ->label('Guide ID')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Auto-generated ID'),

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

                        Forms\Components\Select::make('guide_type')
                            ->label('Guide Type')
                            ->options(Guide::GUIDE_TYPES)
                            ->required()
                            ->native(false)
                            ->default('not_specified'),

                        Forms\Components\TextInput::make('phone')
                            ->label('Phone Number')
                            ->tel()
                            ->required()
                            ->maxLength(50),

                        Forms\Components\TextInput::make('national_id')
                            ->label('National ID')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('years_experience')
                            ->label('Years of Experience')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(50)
                            ->default(0),

                        Forms\Components\Textarea::make('bio')
                            ->label('Biography')
                            ->rows(4)
                            ->columnSpanFull()
                            ->maxLength(1000),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Skills & Expertise')
                    ->schema([
                        Forms\Components\TagsInput::make('languages')
                            ->label('Languages')
                            ->placeholder('Add language (e.g., English, Sinhala)')
                            ->helperText('Press Enter after each language')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('expertise_areas')
                            ->label('Expertise Areas')
                            ->placeholder('Add expertise (e.g., Wildlife, Culture, Adventure)')
                            ->helperText('Press Enter after each area')
                            ->columnSpanFull(),

                        Forms\Components\TagsInput::make('regions_can_guide')
                            ->label('Regions Can Guide')
                            ->placeholder('Add region (e.g., Kandy, Galle, Colombo)')
                            ->helperText('Press Enter after each region')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('License & Insurance')
                    ->schema([
                        Forms\Components\TextInput::make('license_number')
                            ->label('Guide License Number')
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('license_expiry')
                            ->label('License Expiry Date')
                            ->native(false)
                            ->displayFormat('Y-m-d'),

                        Forms\Components\TextInput::make('insurance_policy_number')
                            ->label('Insurance Policy Number')
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('insurance_expiry')
                            ->label('Insurance Expiry Date')
                            ->native(false)
                            ->displayFormat('Y-m-d'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->description('Note: Vehicles are managed separately from the guide\'s dashboard.'),

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

                Forms\Components\Section::make('Banking Information')
                    ->schema([
                        Forms\Components\TextInput::make('bank_name')
                            ->label('Bank Name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('bank_account_number')
                            ->label('Bank Account Number')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('bank_account_holder')
                            ->label('Account Holder Name')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('commission_rate')
                            ->label('Commission Rate (%)')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(90.00)
                            ->suffix('%')
                            ->helperText('Percentage the guide receives from bookings'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Forms\Components\Section::make('Statistics')
                    ->schema([
                        Forms\Components\TextInput::make('average_rating')
                            ->label('Average Rating')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->default(0.00)
                            ->suffix('/ 5.0'),

                        Forms\Components\TextInput::make('total_reviews')
                            ->label('Total Reviews')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->default(0),

                        Forms\Components\TextInput::make('total_bookings')
                            ->label('Total Bookings')
                            ->numeric()
                            ->disabled()
                            ->dehydrated(false)
                            ->default(0),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),

                Forms\Components\Section::make('Admin Notes')
                    ->schema([
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Private Admin Notes')
                            ->rows(4)
                            ->columnSpanFull()
                            ->maxLength(5000)
                            ->helperText('These notes are only visible to admins. Guides and tourists cannot see them.'),
                    ])
                    ->icon('heroicon-o-lock-closed')
                    ->iconColor('warning')
                    ->description('Internal notes for admin reference only - not visible to guides or tourists')
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('profile_photo')
                    ->label('Photo')
                    ->circular()
                    ->defaultImageUrl(asset('images/default-avatar.png'))
                    ->disk('public'),

                Tables\Columns\TextColumn::make('guide_id_number')
                    ->label('Guide ID')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('guide_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Guide::GUIDE_TYPES[$state] ?? 'Not Specified')
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-phone'),

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

                Tables\Columns\TextColumn::make('years_experience')
                    ->label('Experience')
                    ->suffix(' years')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('average_rating')
                    ->label('Rating')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . ' / 5.0')
                    ->sortable()
                    ->alignCenter()
                    ->color('warning')
                    ->icon('heroicon-o-star'),

                Tables\Columns\TextColumn::make('total_bookings')
                    ->label('Bookings')
                    ->sortable()
                    ->alignCenter()
                    ->icon('heroicon-o-calendar'),

                Tables\Columns\TextColumn::make('total_reviews')
                    ->label('Reviews')
                    ->sortable()
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('commission_rate')
                    ->label('Commission')
                    ->suffix('%')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('languages')
                    ->label('Languages')
                    ->badge()
                    ->color('info')
                    ->limit(3)
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'suspended' => 'Suspended',
                    ])
                    ->native(false),

                Tables\Filters\SelectFilter::make('guide_type')
                    ->label('Guide Type')
                    ->options(Guide::GUIDE_TYPES)
                    ->native(false),

                Tables\Filters\Filter::make('high_rated')
                    ->label('High Rated (4.0+)')
                    ->query(fn (Builder $query): Builder => $query->where('average_rating', '>=', 4.0)),

                Tables\Filters\Filter::make('experienced')
                    ->label('Experienced (5+ years)')
                    ->query(fn (Builder $query): Builder => $query->where('years_experience', '>=', 5)),

                Tables\Filters\Filter::make('license_expiring')
                    ->label('License Expiring Soon')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereNotNull('license_expiry')
                              ->whereBetween('license_expiry', [now(), now()->addMonths(3)])
                    ),

                Tables\Filters\Filter::make('insurance_expiring')
                    ->label('Insurance Expiring Soon')
                    ->query(fn (Builder $query): Builder =>
                        $query->whereNotNull('insurance_expiry')
                              ->whereBetween('insurance_expiry', [now(), now()->addMonths(3)])
                    ),
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

                        Notification::make()
                            ->title('Status Updated')
                            ->body("Guide account has been " . ($newStatus === 'active' ? 'activated' : 'suspended'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\Action::make('reset_password')
                    ->label('Reset Password')
                    ->icon('heroicon-o-key')
                    ->color('danger')
                    ->modalHeading('Reset Guide Password')
                    ->modalDescription(fn ($record) => "Set a new password for {$record->full_name} ({$record->user->email}). You will need to communicate this password to the guide directly.")
                    ->modalSubmitActionLabel('Update Password')
                    ->form([
                        Forms\Components\TextInput::make('new_password')
                            ->label('New Password')
                            ->password()
                            ->required()
                            ->minLength(8)
                            ->maxLength(50)
                            ->helperText('Minimum 8 characters. You must communicate this password to the guide.'),

                        Forms\Components\TextInput::make('new_password_confirmation')
                            ->label('Confirm Password')
                            ->password()
                            ->required()
                            ->same('new_password')
                            ->helperText('Re-enter the password to confirm.'),
                    ])
                    ->action(function ($record, array $data) {
                        // Update user password
                        $record->user->update([
                            'password' => Hash::make($data['new_password']),
                        ]);

                        Notification::make()
                            ->title('Password Updated')
                            ->body("Password has been updated for {$record->full_name}. Please communicate the new password to the guide.")
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
            ->emptyStateHeading('No guides yet')
            ->emptyStateDescription('Approved guides will appear here.')
            ->emptyStateIcon('heroicon-o-user-circle');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Guide Profile')
                    ->schema([
                        Infolists\Components\ImageEntry::make('profile_photo')
                            ->label('Profile Photo')
                            ->circular()
                            ->defaultImageUrl(asset('images/default-avatar.png'))
                            ->disk('public')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('guide_id_number')
                            ->label('Guide ID')
                            ->icon('heroicon-o-identification')
                            ->copyable()
                            ->weight('bold')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('full_name')
                            ->label('Full Name')
                            ->icon('heroicon-o-user')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('guide_type')
                            ->label('Guide Type')
                            ->badge()
                            ->formatStateUsing(fn ($state) => Guide::GUIDE_TYPES[$state] ?? 'Not Specified')
                            ->color('info')
                            ->icon('heroicon-o-identification'),

                        Infolists\Components\TextEntry::make('phone')
                            ->label('Phone')
                            ->icon('heroicon-o-phone')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('national_id')
                            ->label('National ID')
                            ->icon('heroicon-o-identification'),

                        Infolists\Components\TextEntry::make('years_experience')
                            ->label('Years of Experience')
                            ->suffix(' years')
                            ->icon('heroicon-o-briefcase'),

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

                        Infolists\Components\TextEntry::make('bio')
                            ->label('Biography')
                            ->markdown()
                            ->columnSpanFull()
                            ->placeholder('No biography provided'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Skills & Expertise')
                    ->schema([
                        Infolists\Components\TextEntry::make('languages')
                            ->label('Languages')
                            ->badge()
                            ->color('info')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('expertise_areas')
                            ->label('Expertise Areas')
                            ->badge()
                            ->color('success')
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('regions_can_guide')
                            ->label('Regions Can Guide')
                            ->badge()
                            ->color('primary')
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Performance Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('average_rating')
                            ->label('Average Rating')
                            ->formatStateUsing(fn ($state) => number_format($state, 2) . ' / 5.0')
                            ->icon('heroicon-o-star')
                            ->color('warning'),

                        Infolists\Components\TextEntry::make('total_reviews')
                            ->label('Total Reviews')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->color('info'),

                        Infolists\Components\TextEntry::make('total_bookings')
                            ->label('Total Bookings')
                            ->icon('heroicon-o-calendar')
                            ->color('success'),

                        Infolists\Components\TextEntry::make('commission_rate')
                            ->label('Commission Rate')
                            ->suffix('%')
                            ->icon('heroicon-o-banknotes')
                            ->color('primary'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('License & Insurance')
                    ->schema([
                        Infolists\Components\TextEntry::make('license_number')
                            ->label('Guide License Number')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-document-text'),

                        Infolists\Components\TextEntry::make('license_expiry')
                            ->label('License Expiry')
                            ->date('F d, Y')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-calendar')
                            ->color(fn ($state) => $state && $state->isPast() ? 'danger' : 'success'),

                        Infolists\Components\TextEntry::make('insurance_policy_number')
                            ->label('Insurance Policy')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-shield-check'),

                        Infolists\Components\TextEntry::make('insurance_expiry')
                            ->label('Insurance Expiry')
                            ->date('F d, Y')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-calendar')
                            ->color(fn ($state) => $state && $state->isPast() ? 'danger' : 'success'),

                        Infolists\Components\TextEntry::make('vehicles_count')
                            ->label('Registered Vehicles')
                            ->state(fn ($record) => $record->vehicles()->count())
                            ->icon('heroicon-o-truck')
                            ->suffix(' vehicle(s)')
                            ->color('info'),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->description('Vehicles are managed from the guide\'s dashboard or the Vehicle Management section.'),

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

                Infolists\Components\Section::make('Banking Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('bank_name')
                            ->label('Bank Name')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-building-library'),

                        Infolists\Components\TextEntry::make('bank_account_number')
                            ->label('Account Number')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-credit-card')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('bank_account_holder')
                            ->label('Account Holder')
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-user'),
                    ])
                    ->columns(3)
                    ->collapsible(),

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

                Infolists\Components\Section::make('Admin Notes')
                    ->schema([
                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label('Private Admin Notes')
                            ->markdown()
                            ->columnSpanFull()
                            ->placeholder('No admin notes recorded')
                            ->icon('heroicon-o-lock-closed'),
                    ])
                    ->icon('heroicon-o-lock-closed')
                    ->iconColor('warning')
                    ->description('Internal notes for admin reference only - not visible to guides or tourists')
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
            'index' => Pages\ListGuides::route('/'),
            'view' => Pages\ViewGuide::route('/{record}'),
            'edit' => Pages\EditGuide::route('/{record}/edit'),
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
