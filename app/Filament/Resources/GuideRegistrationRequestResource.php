<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuideRegistrationRequestResource\Pages;
use App\Models\Guide;
use App\Models\GuideRegistrationRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class GuideRegistrationRequestResource extends Resource
{
    protected static ?string $model = GuideRegistrationRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';

    protected static ?string $navigationLabel = 'Guide Registrations';

    protected static ?string $modelLabel = 'Guide Registration Request';

    protected static ?string $pluralModelLabel = 'Guide Registration Requests';

    protected static ?string $navigationGroup = 'Guide Management';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Admin Review')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'documents_pending' => 'Documents Pending',
                                'documents_verified' => 'Documents Verified',
                                'interview_scheduled' => 'Interview Scheduled',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\DatePicker::make('interview_date')
                            ->label('Interview Date')
                            ->native(false)
                            ->displayFormat('Y-m-d')
                            ->visible(fn ($get) => $get('status') === 'interview_scheduled'),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
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

                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('guide_type')
                    ->label('Guide Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Guide::GUIDE_TYPES[$state] ?? 'Not Specified')
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone')
                    ->searchable()
                    ->icon('heroicon-o-phone'),

                Tables\Columns\TextColumn::make('years_experience')
                    ->label('Experience')
                    ->suffix(' years')
                    ->sortable()
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'documents_pending' => 'warning',
                        'documents_verified' => 'info',
                        'interview_scheduled' => 'primary',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'documents_pending' => 'Pending',
                        'documents_verified' => 'Verified',
                        'interview_scheduled' => 'Interview',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        default => $state,
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('reviewer.full_name')
                    ->label('Reviewed By')
                    ->placeholder('Not reviewed')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'documents_pending' => 'Documents Pending',
                        'documents_verified' => 'Documents Verified',
                        'interview_scheduled' => 'Interview Scheduled',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->native(false)
                    ->multiple(),

                Tables\Filters\Filter::make('recent')
                    ->label('Recent (Last 30 days)')
                    ->query(fn (Builder $query): Builder => $query->where('created_at', '>=', now()->subDays(30))),

                Tables\Filters\Filter::make('has_interview')
                    ->label('Has Interview Date')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('interview_date')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('View')
                    ->icon('heroicon-o-eye'),

                Tables\Actions\Action::make('quick_approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => in_array($record->status, ['documents_pending', 'documents_verified', 'interview_scheduled']))
                    ->action(function ($record) {
                        $service = new \App\Services\GuideApprovalService();
                        $validation = $service->canApprove($record);

                        if (!$validation['canApprove']) {
                            \Filament\Notifications\Notification::make()
                                ->title('Cannot Approve')
                                ->body($validation['reason'])
                                ->danger()
                                ->send();
                            return;
                        }

                        $result = $service->approveGuide($record, auth()->user()->admin->id);

                        if ($result['success']) {
                            $message = "Guide ID: {$result['guide_id']}";
                            if ($result['password_changed'] && $result['email_sent']) {
                                $message .= "\n\nLogin credentials have been emailed to the guide.";
                            }

                            \Filament\Notifications\Notification::make()
                                ->title('Approved!')
                                ->body($message)
                                ->success()
                                ->duration(8000)
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed')
                                ->body($result['message'])
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\EditAction::make()
                    ->label('Update')
                    ->icon('heroicon-o-pencil-square')
                    ->modalHeading('Update Request Status')
                    ->modalWidth('lg')
                    ->visible(fn ($record) => !in_array($record->status, ['approved', 'rejected'])),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No registration requests yet')
            ->emptyStateDescription('Guide registration requests will appear here once submitted.')
            ->emptyStateIcon('heroicon-o-user-plus');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Applicant Information')
                    ->schema([
                        Infolists\Components\ImageEntry::make('profile_photo')
                            ->label('Profile Photo')
                            ->circular()
                            ->defaultImageUrl(asset('images/default-avatar.png'))
                            ->disk('public')
                            ->columnSpanFull(),

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

                        Infolists\Components\TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),

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

                        Infolists\Components\TextEntry::make('experience_description')
                            ->label('Experience Description')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Submitted Documents')
                    ->schema([
                        Infolists\Components\TextEntry::make('national_id_document')
                            ->label('National ID Document')
                            ->formatStateUsing(fn ($state) => $state ? 'View Document' : 'Not uploaded')
                            ->url(fn ($record) => $record->national_id_document ? Storage::disk('public')->url($record->national_id_document) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-document')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('driving_license')
                            ->label('Driving License')
                            ->formatStateUsing(fn ($state) => $state ? 'View Document' : 'Not uploaded')
                            ->url(fn ($record) => $record->driving_license ? Storage::disk('public')->url($record->driving_license) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-document')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('guide_certificate')
                            ->label('Guide Certificate')
                            ->formatStateUsing(fn ($state) => $state ? 'View Document' : 'Not uploaded')
                            ->url(fn ($record) => $record->guide_certificate ? Storage::disk('public')->url($record->guide_certificate) : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-document')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('language_certificates')
                            ->label('Language Certificates')
                            ->formatStateUsing(fn ($state) => $state && is_array($state) ? count($state) . ' file(s) uploaded' : 'Not uploaded')
                            ->icon('heroicon-o-document-text')
                            ->color('info')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->description('Note: Vehicle information can be added by guides from their dashboard after approval.'),

                Infolists\Components\Section::make('Review Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'documents_pending' => 'warning',
                                'documents_verified' => 'info',
                                'interview_scheduled' => 'primary',
                                'approved' => 'success',
                                'rejected' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'documents_pending' => 'Documents Pending',
                                'documents_verified' => 'Documents Verified',
                                'interview_scheduled' => 'Interview Scheduled',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                default => $state,
                            }),

                        Infolists\Components\TextEntry::make('interview_date')
                            ->label('Interview Date')
                            ->date('F d, Y')
                            ->placeholder('Not scheduled')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('reviewer.full_name')
                            ->label('Reviewed By')
                            ->placeholder('Not reviewed yet')
                            ->icon('heroicon-o-user-circle'),

                        Infolists\Components\TextEntry::make('reviewed_at')
                            ->label('Reviewed At')
                            ->dateTime('F d, Y h:i A')
                            ->placeholder('Not reviewed yet')
                            ->icon('heroicon-o-clock'),

                        Infolists\Components\TextEntry::make('admin_notes')
                            ->label('Admin Notes')
                            ->markdown()
                            ->placeholder('No notes added')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Submission Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Submitted At')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('F d, Y h:i A')
                            ->icon('heroicon-o-arrow-path'),
                    ])
                    ->columns(2)
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
            'index' => Pages\ListGuideRegistrationRequests::route('/'),
            'view' => Pages\ViewGuideRegistrationRequest::route('/{record}'),
            'edit' => Pages\EditGuideRegistrationRequest::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'documents_pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::where('status', 'documents_pending')->count() > 0 ? 'warning' : 'primary';
    }
}
