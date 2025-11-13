<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GuidePlanResource\Pages;
use App\Models\GuidePlan;
use App\Models\Guide;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

class GuidePlanResource extends Resource
{
    protected static ?string $model = GuidePlan::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Guide Management';

    protected static ?string $navigationLabel = 'Guide Plans';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Basic Information Section
                Section::make('Basic Information')
                    ->description('Core details about the tour plan')
                    ->schema([
                        Forms\Components\Select::make('guide_id')
                            ->label('Guide')
                            ->relationship('guide', 'full_name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Select the guide who will lead this tour'),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., "7-Day Cultural Tour of Sri Lanka"')
                            ->columnSpan('full'),

                        Forms\Components\RichEditor::make('description')
                            ->required()
                            ->placeholder('Provide a detailed description of the tour...')
                            ->columnSpan('full')
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'bulletList',
                                'orderedList',
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('num_days')
                                    ->label('Number of Days')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(30)
                                    ->default(1),

                                Forms\Components\TextInput::make('num_nights')
                                    ->label('Number of Nights')
                                    ->required()
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(30)
                                    ->default(0),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Locations & Destinations Section
                Section::make('Locations & Destinations')
                    ->description('Pickup, dropoff, and tour destinations')
                    ->schema([
                        Forms\Components\TextInput::make('pickup_location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Colombo International Airport'),

                        Forms\Components\TextInput::make('dropoff_location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Colombo International Airport'),

                        Forms\Components\TagsInput::make('destinations')
                            ->placeholder('Add destinations (press Enter after each)')
                            ->helperText('e.g., Kandy, Nuwara Eliya, Ella, Galle')
                            ->required()
                            ->columnSpan('full'),

                        Forms\Components\TagsInput::make('trip_focus_tags')
                            ->label('Trip Focus/Tags')
                            ->placeholder('Add tags (press Enter after each)')
                            ->helperText('e.g., Culture, Adventure, Wildlife, Beach, Food')
                            ->required()
                            ->columnSpan('full'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Pricing & Group Size Section
                Section::make('Pricing & Group Size')
                    ->description('Tour pricing and capacity')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('price_per_adult')
                                    ->label('Price per Adult (USD)')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->step(0.01),

                                Forms\Components\TextInput::make('price_per_child')
                                    ->label('Price per Child (USD)')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->minValue(0)
                                    ->step(0.01)
                                    ->helperText('Children under 12 years'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('min_group_size')
                                    ->label('Minimum Group Size')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(50)
                                    ->default(1),

                                Forms\Components\TextInput::make('max_group_size')
                                    ->label('Maximum Group Size')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(50),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Availability Section
                Section::make('Availability')
                    ->description('When is this tour available?')
                    ->schema([
                        Forms\Components\Select::make('availability_type')
                            ->label('Availability Type')
                            ->options([
                                'always_available' => 'Always Available',
                                'date_range' => 'Specific Date Range',
                            ])
                            ->required()
                            ->reactive()
                            ->helperText('Choose if this tour is available year-round or only during specific dates'),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('available_start_date')
                                    ->label('Start Date')
                                    ->required(fn ($get) => $get('availability_type') === 'date_range')
                                    ->visible(fn ($get) => $get('availability_type') === 'date_range'),

                                Forms\Components\DatePicker::make('available_end_date')
                                    ->label('End Date')
                                    ->required(fn ($get) => $get('availability_type') === 'date_range')
                                    ->visible(fn ($get) => $get('availability_type') === 'date_range')
                                    ->after('available_start_date'),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Vehicle Information Section
                Section::make('Vehicle Information')
                    ->description('Details about transportation')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                Forms\Components\Select::make('vehicle_type')
                                    ->label('Vehicle Type')
                                    ->options([
                                        'Car' => 'Car',
                                        'Van' => 'Van',
                                        'Mini Bus' => 'Mini Bus',
                                        'Bus' => 'Bus',
                                        'SUV' => 'SUV',
                                        '4WD' => '4WD',
                                    ])
                                    ->searchable(),

                                Forms\Components\Select::make('vehicle_category')
                                    ->label('Vehicle Category')
                                    ->options([
                                        'Economy' => 'Economy',
                                        'Standard' => 'Standard',
                                        'Luxury' => 'Luxury',
                                        'Premium' => 'Premium',
                                    ])
                                    ->searchable(),

                                Forms\Components\TextInput::make('vehicle_capacity')
                                    ->label('Vehicle Capacity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(50)
                                    ->helperText('Number of passengers'),
                            ]),

                        Forms\Components\Toggle::make('vehicle_ac')
                            ->label('Air Conditioning Available')
                            ->default(true)
                            ->inline(false),

                        Forms\Components\Textarea::make('vehicle_description')
                            ->label('Vehicle Description')
                            ->rows(3)
                            ->placeholder('Describe the vehicle features and comfort...')
                            ->columnSpan('full'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Dietary & Accessibility Section
                Section::make('Dietary & Accessibility')
                    ->description('Special requirements and accommodations')
                    ->schema([
                        Forms\Components\CheckboxList::make('dietary_options')
                            ->label('Dietary Options Available')
                            ->options([
                                'vegetarian' => 'Vegetarian',
                                'vegan' => 'Vegan',
                                'halal' => 'Halal',
                                'kosher' => 'Kosher',
                                'gluten_free' => 'Gluten-Free',
                                'lactose_free' => 'Lactose-Free',
                                'nut_free' => 'Nut-Free',
                            ])
                            ->columns(3)
                            ->columnSpan('full'),

                        Forms\Components\Textarea::make('accessibility_info')
                            ->label('Accessibility Information')
                            ->rows(3)
                            ->placeholder('Describe accessibility features (wheelchair access, mobility assistance, etc.)...')
                            ->columnSpan('full'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Inclusions & Exclusions Section
                Section::make('Inclusions & Exclusions')
                    ->description('What is included and excluded in the tour price')
                    ->schema([
                        Forms\Components\Textarea::make('inclusions')
                            ->label('Inclusions')
                            ->required()
                            ->rows(5)
                            ->placeholder("List what's included (one per line):\n- Accommodation\n- Meals\n- Transportation\n- Entrance fees")
                            ->helperText('What is included in the tour price'),

                        Forms\Components\Textarea::make('exclusions')
                            ->label('Exclusions')
                            ->required()
                            ->rows(5)
                            ->placeholder("List what's NOT included (one per line):\n- International flights\n- Personal expenses\n- Travel insurance\n- Tips")
                            ->helperText('What is NOT included in the tour price'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Policies Section
                Section::make('Policies')
                    ->description('Cancellation and other policies')
                    ->schema([
                        Forms\Components\Textarea::make('cancellation_policy')
                            ->label('Cancellation Policy')
                            ->rows(4)
                            ->placeholder('Describe your cancellation policy and refund terms...')
                            ->columnSpan('full'),
                    ])
                    ->columns(1)
                    ->collapsible(),

                // Cover Photo & Status Section
                Section::make('Media & Status')
                    ->description('Cover photo and plan status')
                    ->schema([
                        Forms\Components\FileUpload::make('cover_photo')
                            ->label('Cover Photo')
                            ->image()
                            ->directory('guide-plans/covers')
                            ->imageEditor()
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                            ])
                            ->maxSize(5120)
                            ->helperText('Max size: 5MB. Recommended: 1920x1080px'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'inactive' => 'Inactive',
                            ])
                            ->required()
                            ->default('draft')
                            ->helperText('Draft plans are not visible to tourists'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                // Statistics Section (Read-only for edit mode)
                Section::make('Statistics')
                    ->description('Plan performance metrics')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('view_count')
                                    ->label('View Count')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),

                                Forms\Components\TextInput::make('booking_count')
                                    ->label('Booking Count')
                                    ->numeric()
                                    ->default(0)
                                    ->disabled()
                                    ->dehydrated(false),
                            ]),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->collapsed()
                    ->hidden(fn ($record) => $record === null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('cover_photo')
                    ->label('Cover')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-tour.png'))
                    ->size(50),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->description(fn (GuidePlan $record): string =>
                        $record->num_days . ' days / ' . $record->num_nights . ' nights'
                    )
                    ->wrap(),

                Tables\Columns\TextColumn::make('guide.full_name')
                    ->label('Guide')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price_per_adult')
                    ->label('Price (Adult)')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('price_per_child')
                    ->label('Price (Child)')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('max_group_size')
                    ->label('Group Size')
                    ->badge()
                    ->sortable()
                    ->description(fn (GuidePlan $record): string =>
                        'Min: ' . $record->min_group_size
                    ),

                Tables\Columns\TextColumn::make('availability_type')
                    ->label('Availability')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'always_available' => 'success',
                        'date_range' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'always_available' => 'Always',
                        'date_range' => 'Seasonal',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('view_count')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('booking_count')
                    ->label('Bookings')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('success')
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated')
                    ->dateTime('M j, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->label('Status'),

                SelectFilter::make('guide')
                    ->relationship('guide', 'full_name')
                    ->searchable()
                    ->preload()
                    ->label('Guide'),

                SelectFilter::make('availability_type')
                    ->options([
                        'always_available' => 'Always Available',
                        'date_range' => 'Seasonal',
                    ])
                    ->label('Availability'),

                Tables\Filters\Filter::make('price_range')
                    ->form([
                        Forms\Components\TextInput::make('price_from')
                            ->numeric()
                            ->prefix('$'),
                        Forms\Components\TextInput::make('price_to')
                            ->numeric()
                            ->prefix('$'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['price_from'], fn ($query, $price) =>
                                $query->where('price_per_adult', '>=', $price)
                            )
                            ->when($data['price_to'], fn ($query, $price) =>
                                $query->where('price_per_adult', '<=', $price)
                            );
                    }),

                Tables\Filters\Filter::make('duration')
                    ->form([
                        Forms\Components\TextInput::make('days_min')
                            ->numeric()
                            ->label('Min Days'),
                        Forms\Components\TextInput::make('days_max')
                            ->numeric()
                            ->label('Max Days'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['days_min'], fn ($query, $days) =>
                                $query->where('num_days', '>=', $days)
                            )
                            ->when($data['days_max'], fn ($query, $days) =>
                                $query->where('num_days', '<=', $days)
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),

                Action::make('publish')
                    ->label('Publish')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (GuidePlan $record) => $record->update(['status' => 'active']))
                    ->visible(fn (GuidePlan $record) => $record->status === 'draft'),

                Action::make('deactivate')
                    ->label('Deactivate')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (GuidePlan $record) => $record->update(['status' => 'inactive']))
                    ->visible(fn (GuidePlan $record) => $record->status === 'active'),

                Action::make('activate')
                    ->label('Activate')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (GuidePlan $record) => $record->update(['status' => 'active']))
                    ->visible(fn (GuidePlan $record) => $record->status === 'inactive'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->requiresConfirmation(),

                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'active'])),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate Selected')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'inactive'])),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListGuidePlans::route('/'),
            'create' => Pages\CreateGuidePlan::route('/create'),
            'view' => Pages\ViewGuidePlan::route('/{record}'),
            'edit' => Pages\EditGuidePlan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'active')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
