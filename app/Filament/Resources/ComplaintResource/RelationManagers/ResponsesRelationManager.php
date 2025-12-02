<?php

namespace App\Filament\Resources\ComplaintResource\RelationManagers;

use App\Models\ComplaintResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ResponsesRelationManager extends RelationManager
{
    protected static string $relationship = 'responses';

    protected static ?string $title = 'Communication Thread';

    protected static ?string $recordTitleAttribute = 'response_type';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Add Response')
                    ->schema([
                        Forms\Components\Select::make('response_type')
                            ->label('Response Type')
                            ->options([
                                'email' => 'Email Response',
                                'internal_note' => 'Internal Note (Admin Only)',
                                'status_update' => 'Status Update',
                                'public_note' => 'Public Note',
                                'request_info' => 'Request Information',
                            ])
                            ->required()
                            ->reactive()
                            ->default('public_note'),

                        Forms\Components\Textarea::make('response_text')
                            ->label('Response')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Forms\Components\FileUpload::make('attachments')
                            ->label('Attachments')
                            ->multiple()
                            ->directory('complaint-attachments')
                            ->maxFiles(5)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('internal_only')
                            ->label('Internal Only (Admin Only)')
                            ->helperText('This response will only be visible to admins')
                            ->default(fn ($get) => $get('response_type') === 'internal_note')
                            ->reactive()
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('visible_to_complainant')
                            ->label('Visible to Complainant')
                            ->helperText('Allow the person who filed the complaint to see this response')
                            ->default(true)
                            ->disabled(fn ($get) => $get('internal_only'))
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('visible_to_defendant')
                            ->label('Visible to Defendant')
                            ->helperText('Allow the person being complained about to see this response')
                            ->default(true)
                            ->disabled(fn ($get) => $get('internal_only'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('response_type')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),

                Tables\Columns\TextColumn::make('responder_type')
                    ->label('From')
                    ->formatStateUsing(fn ($record) => $record->getResponderName())
                    ->description(fn ($record) => ucfirst($record->responder_type ?? 'admin'))
                    ->icon('heroicon-o-user'),

                Tables\Columns\TextColumn::make('response_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => str_replace('_', ' ', ucwords($state, '_')))
                    ->color(fn ($state) => match($state) {
                        'internal_note' => 'gray',
                        'email' => 'info',
                        'status_update' => 'warning',
                        'public_note' => 'success',
                        'request_info' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('response_text')
                    ->label('Response')
                    ->limit(60)
                    ->wrap()
                    ->tooltip(fn ($record) => $record->response_text),

                Tables\Columns\IconColumn::make('internal_only')
                    ->label('Internal')
                    ->boolean()
                    ->trueIcon('heroicon-o-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('warning')
                    ->falseColor('success'),

                Tables\Columns\IconColumn::make('visible_to_complainant')
                    ->label('Complainant')
                    ->boolean()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('visible_to_defendant')
                    ->label('Defendant')
                    ->boolean()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('response_type')
                    ->options([
                        'email' => 'Email',
                        'internal_note' => 'Internal Note',
                        'status_update' => 'Status Update',
                        'public_note' => 'Public Note',
                        'request_info' => 'Request Info',
                    ])
                    ->multiple(),

                Tables\Filters\Filter::make('internal_only')
                    ->label('Internal Only')
                    ->query(fn ($query) => $query->where('internal_only', true)),

                Tables\Filters\Filter::make('public')
                    ->label('Public Responses')
                    ->query(fn ($query) => $query->where('internal_only', false)),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Response')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['admin_id'] = auth()->id();
                        $data['responder_type'] = 'admin';
                        $data['responder_id'] = auth()->id();

                        // If internal_only is true, hide from both parties
                        if ($data['internal_only'] ?? false) {
                            $data['visible_to_complainant'] = false;
                            $data['visible_to_defendant'] = false;
                        }

                        return $data;
                    })
                    ->successNotificationTitle('Response added to complaint'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make()
                    ->visible(fn ($record) => $record->isFromAdmin()),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn ($record) => $record->isFromAdmin()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Response Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Date & Time')
                            ->dateTime('F j, Y g:i A')
                            ->icon('heroicon-o-clock'),

                        Infolists\Components\TextEntry::make('responder_type')
                            ->label('From')
                            ->formatStateUsing(fn ($record) => $record->getResponderName() . ' (' . ucfirst($record->responder_type ?? 'admin') . ')')
                            ->icon('heroicon-o-user'),

                        Infolists\Components\TextEntry::make('response_type')
                            ->label('Response Type')
                            ->badge()
                            ->formatStateUsing(fn ($record) => $record->getResponseTypeLabel()),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Response Content')
                    ->schema([
                        Infolists\Components\TextEntry::make('response_text')
                            ->label('Message')
                            ->html()
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('attachments')
                            ->label('Attachments')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->formatStateUsing(fn ($state) => basename($state))
                            ->visible(fn ($record) => $record->attachments && count($record->attachments) > 0)
                            ->columnSpanFull(),
                    ]),

                Infolists\Components\Section::make('Visibility')
                    ->schema([
                        Infolists\Components\IconEntry::make('internal_only')
                            ->label('Internal Only')
                            ->boolean()
                            ->trueIcon('heroicon-o-lock-closed')
                            ->falseIcon('heroicon-o-lock-open')
                            ->trueColor('warning'),

                        Infolists\Components\IconEntry::make('visible_to_complainant')
                            ->label('Visible to Complainant')
                            ->boolean(),

                        Infolists\Components\IconEntry::make('visible_to_defendant')
                            ->label('Visible to Defendant')
                            ->boolean(),
                    ])
                    ->columns(3),
            ]);
    }
}
