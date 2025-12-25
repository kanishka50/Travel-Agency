<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\Guide;
use App\Models\Vehicle;
use App\Models\VehicleDocument;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\HtmlString;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationLabel = 'Vehicles';

    protected static ?string $modelLabel = 'Vehicle';

    protected static ?string $pluralModelLabel = 'Vehicles';

    protected static ?string $navigationGroup = 'Guide Management';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Vehicle Information')
                    ->schema([
                        Forms\Components\Select::make('guide_id')
                            ->label('Guide')
                            ->relationship('guide', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(fn ($record) => $record !== null),

                        Forms\Components\Select::make('vehicle_type')
                            ->label('Vehicle Type')
                            ->options(Vehicle::VEHICLE_TYPES)
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('make')
                            ->label('Make')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., Toyota, Nissan'),

                        Forms\Components\TextInput::make('model')
                            ->label('Model')
                            ->required()
                            ->maxLength(100)
                            ->placeholder('e.g., Hiace, Caravan'),

                        Forms\Components\TextInput::make('license_plate')
                            ->label('License Plate')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('e.g., ABC-1234'),

                        Forms\Components\TextInput::make('seating_capacity')
                            ->label('Seating Capacity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(50)
                            ->suffix('passengers'),

                        Forms\Components\Toggle::make('has_ac')
                            ->label('Air Conditioning')
                            ->default(true)
                            ->inline(false),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->inline(false)
                            ->helperText('Inactive vehicles cannot be assigned to bookings'),

                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->placeholder('Optional description of the vehicle...'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('guide.full_name')
                    ->label('Guide')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('vehicle_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => Vehicle::VEHICLE_TYPES[$state] ?? $state)
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('make')
                    ->label('Make')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('license_plate')
                    ->label('Plate')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('seating_capacity')
                    ->label('Seats')
                    ->sortable()
                    ->alignCenter()
                    ->suffix(' pax'),

                Tables\Columns\IconColumn::make('has_ac')
                    ->label('AC')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('photos_count')
                    ->label('Photos')
                    ->counts('photos')
                    ->alignCenter()
                    ->color('info'),

                Tables\Columns\TextColumn::make('documents_count')
                    ->label('Docs')
                    ->counts('documents')
                    ->alignCenter()
                    ->color('info'),

                Tables\Columns\TextColumn::make('bookingAssignments_count')
                    ->label('Bookings')
                    ->counts('bookingAssignments')
                    ->alignCenter()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Added')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('guide_id')
                    ->label('Guide')
                    ->relationship('guide', 'full_name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('vehicle_type')
                    ->label('Vehicle Type')
                    ->options(Vehicle::VEHICLE_TYPES)
                    ->native(false),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->placeholder('All'),

                Tables\Filters\TernaryFilter::make('has_ac')
                    ->label('Air Conditioning')
                    ->trueLabel('With AC')
                    ->falseLabel('Without AC')
                    ->placeholder('All'),

                Tables\Filters\Filter::make('high_capacity')
                    ->label('High Capacity (8+ seats)')
                    ->query(fn (Builder $query): Builder => $query->where('seating_capacity', '>=', 8)),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye'),

                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil-square'),

                Tables\Actions\Action::make('toggle_status')
                    ->label(fn ($record) => $record->is_active ? 'Deactivate' : 'Activate')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-pause-circle' : 'heroicon-o-play-circle')
                    ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                    ->requiresConfirmation()
                    ->modalDescription(fn ($record) => $record->is_active
                        ? 'This will prevent the vehicle from being assigned to new bookings.'
                        : 'This will allow the vehicle to be assigned to bookings.')
                    ->action(function ($record) {
                        if ($record->is_active && $record->hasUpcomingAssignments()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Cannot Deactivate')
                                ->body('This vehicle is assigned to upcoming bookings.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $record->update(['is_active' => !$record->is_active]);

                        \Filament\Notifications\Notification::make()
                            ->title('Status Updated')
                            ->body('Vehicle has been ' . ($record->is_active ? 'activated' : 'deactivated'))
                            ->success()
                            ->send();
                    }),

                Tables\Actions\DeleteAction::make()
                    ->before(function ($record, Tables\Actions\DeleteAction $action) {
                        if ($record->hasUpcomingAssignments()) {
                            \Filament\Notifications\Notification::make()
                                ->title('Cannot Delete')
                                ->body('This vehicle is assigned to ' . $record->upcomingAssignmentsCount() . ' upcoming booking(s).')
                                ->danger()
                                ->send();
                            $action->cancel();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->emptyStateHeading('No vehicles registered')
            ->emptyStateDescription('Guides can add vehicles from their dashboard.')
            ->emptyStateIcon('heroicon-o-truck');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Vehicle Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('guide.full_name')
                            ->label('Guide')
                            ->icon('heroicon-o-user')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('guide.guide_id_number')
                            ->label('Guide ID')
                            ->icon('heroicon-o-identification')
                            ->copyable(),

                        Infolists\Components\TextEntry::make('vehicle_type')
                            ->label('Vehicle Type')
                            ->badge()
                            ->formatStateUsing(fn ($state) => Vehicle::VEHICLE_TYPES[$state] ?? $state)
                            ->color('info'),

                        Infolists\Components\TextEntry::make('display_name')
                            ->label('Vehicle')
                            ->icon('heroicon-o-truck')
                            ->weight('bold'),

                        Infolists\Components\TextEntry::make('license_plate')
                            ->label('License Plate')
                            ->icon('heroicon-o-identification')
                            ->copyable()
                            ->weight('bold')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('seating_capacity')
                            ->label('Seating Capacity')
                            ->suffix(' passengers')
                            ->icon('heroicon-o-users'),

                        Infolists\Components\IconEntry::make('has_ac')
                            ->label('Air Conditioning')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),

                        Infolists\Components\IconEntry::make('is_active')
                            ->label('Status')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),

                        Infolists\Components\TextEntry::make('description')
                            ->label('Description')
                            ->placeholder('No description')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Photos')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('photos')
                            ->schema([
                                Infolists\Components\ImageEntry::make('photo_path')
                                    ->label('')
                                    ->disk('public')
                                    ->height(150)
                                    ->width(200),
                            ])
                            ->columns(4)
                            ->grid(4),
                    ])
                    ->collapsible()
                    ->visible(fn ($record) => $record->photos()->count() > 0),

                Infolists\Components\Section::make('Documents')
                    ->schema([
                        Infolists\Components\TextEntry::make('registration_document.document_path')
                            ->label('Registration Document')
                            ->formatStateUsing(fn ($state) => $state ? 'View Document' : 'Not uploaded')
                            ->url(fn ($record) => $record->registration_document?->document_path
                                ? Storage::disk('public')->url($record->registration_document->document_path)
                                : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-document')
                            ->color('primary'),

                        Infolists\Components\TextEntry::make('insurance_document.document_path')
                            ->label('Insurance Document')
                            ->formatStateUsing(fn ($state) => $state ? 'View Document' : 'Not uploaded')
                            ->url(fn ($record) => $record->insurance_document?->document_path
                                ? Storage::disk('public')->url($record->insurance_document->document_path)
                                : null)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-document')
                            ->color('primary'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Infolists\Components\Section::make('Usage Statistics')
                    ->schema([
                        Infolists\Components\TextEntry::make('bookingAssignments_count')
                            ->label('Total Assignments')
                            ->state(fn ($record) => $record->bookingAssignments()->count())
                            ->icon('heroicon-o-calendar')
                            ->color('info'),

                        Infolists\Components\TextEntry::make('upcoming_assignments')
                            ->label('Upcoming Bookings')
                            ->state(fn ($record) => $record->upcomingAssignmentsCount())
                            ->icon('heroicon-o-clock')
                            ->color(fn ($record) => $record->upcomingAssignmentsCount() > 0 ? 'warning' : 'success'),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Added On')
                            ->dateTime('F d, Y')
                            ->icon('heroicon-o-calendar'),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('F d, Y')
                            ->icon('heroicon-o-arrow-path'),
                    ])
                    ->columns(4),
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
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'view' => Pages\ViewVehicle::route('/{record}'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
