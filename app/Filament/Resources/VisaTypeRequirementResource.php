<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VisaTypeRequirementResource\Pages;
use App\Models\VisaTypeRequirement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VisaTypeRequirementResource extends Resource
{
    protected static ?string $model = VisaTypeRequirement::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Vault Management';

    protected static ?string $navigationLabel = 'Visa Requirements';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        $countryOptions = [
            'India' => 'India',
            'Spain' => 'Spain',
            'USA' => 'United States (USA)',
            'UK' => 'United Kingdom (UK)',
            'Canada' => 'Canada',
            'Australia' => 'Australia',
            'Germany' => 'Germany',
            'France' => 'France',
            'Italy' => 'Italy',
            'Japan' => 'Japan',
            'Saudi Arabia' => 'Saudi Arabia',
            'UAE' => 'United Arab Emirates (UAE)',
            'Singapore' => 'Singapore',
            'Malaysia' => 'Malaysia',
            'Turkey' => 'Turkey',
            'Qatar' => 'Qatar',
            'Portugal' => 'Portugal',
            'Sweden' => 'Sweden',
            'South Korea' => 'South Korea',
            'New Zealand' => 'New Zealand',
        ];

        return $form
            ->schema([
                Forms\Components\Section::make('Requirement Details')
                    ->description('Configure required documents per country and visa category.')
                    ->schema([
                        Forms\Components\Select::make('country')
                            ->label('Country')
                            ->options($countryOptions)
                            ->searchable()
                            ->createOptionUsing(fn (string $search): string => $search)
                            ->nullable()
                            ->placeholder('All Countries (Global Default)')
                            ->helperText('Select or type a country (e.g. India, Spain). Leave blank for Global Default across all countries.'),

                        Forms\Components\Select::make('visa_type')
                            ->label('Visa Type')
                            ->options([
                                'Tourist' => 'Tourist Visa',
                                'Business' => 'Business Visa',
                                'Student' => 'Student Visa',
                                'Work' => 'Work Visa',
                                'Medical' => 'Medical Visa',
                                'Family' => 'Family / Spouse Visa',
                                'Transit' => 'Transit Visa',
                                'Umrah' => 'Umrah Visa',
                                'PR' => 'Permanent Residency / Investor',
                                'Double Entry' => 'Double Entry Visa',
                                'Appeal' => 'Visa Appeal / Refusal Review',
                            ])
                            ->required()
                            ->searchable(),

                        Forms\Components\Select::make('document_category_id')
                            ->label('Required Document Category')
                            ->relationship('documentCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Toggle::make('is_mandatory')
                            ->label('Mandatory Requirement')
                            ->default(true)
                            ->helperText('Clients must upload this document before their visa application can be approved.'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        $countriesInDb = VisaTypeRequirement::whereNotNull('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country', 'country')
            ->toArray();

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('country')
                    ->label('Country')
                    ->badge()
                    ->color(fn (?string $state) => $state ? 'info' : 'gray')
                    ->formatStateUsing(fn (?string $state) => $state ?: 'Unassigned')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('visa_type')
                    ->label('Visa Type')
                    ->badge()
                    ->color('warning')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('documentCategory.name')
                    ->label('Required Document')
                    ->sortable()
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\IconColumn::make('is_mandatory')
                    ->label('Mandatory')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('country')
            ->groups([
                Tables\Grouping\Group::make('country')
                    ->label('Country')
                    ->getTitleFromRecordUsing(fn (VisaTypeRequirement $record): string => $record->country ?: 'Unassigned')
                    ->collapsible(),
                Tables\Grouping\Group::make('visa_type')
                    ->label('Visa Type')
                    ->collapsible(),
            ])
            ->defaultGroup('country')
            ->filters([
                Tables\Filters\SelectFilter::make('country')
                    ->label('Country')
                    ->options($countriesInDb)
                    ->placeholder('All Countries & Defaults'),

                Tables\Filters\SelectFilter::make('visa_type')
                    ->label('Visa Type')
                    ->options([
                        'Tourist' => 'Tourist Visa',
                        'Business' => 'Business Visa',
                        'Student' => 'Student Visa',
                        'Work' => 'Work Visa',
                        'Medical' => 'Medical Visa',
                        'Family' => 'Family / Spouse Visa',
                        'Transit' => 'Transit Visa',
                        'Umrah' => 'Umrah Visa',
                        'PR' => 'Permanent Residency / Investor',
                        'Double Entry' => 'Double Entry Visa',
                        'Appeal' => 'Visa Appeal / Refusal Review',
                    ]),

                Tables\Filters\TernaryFilter::make('is_mandatory')
                    ->label('Mandatory Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVisaTypeRequirements::route('/'),
            'create' => Pages\CreateVisaTypeRequirement::route('/create'),
            'edit' => Pages\EditVisaTypeRequirement::route('/{record}/edit'),
        ];
    }
}
