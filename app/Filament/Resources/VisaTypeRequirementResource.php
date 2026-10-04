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
        return $form
            ->schema([
                Forms\Components\Select::make('visa_type')
                    ->options([
                        'Tourist' => 'Tourist Visa',
                        'Work' => 'Work Visa',
                        'Student' => 'Student Visa',
                        'Business' => 'Business Visa',
                        'Family' => 'Family / Spouse Visa',
                        'Transit' => 'Transit Visa',
                    ])
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('document_category_id')
                    ->relationship('documentCategory', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Toggle::make('is_mandatory')
                    ->label('Mandatory Requirement')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('visa_type')
                    ->badge()
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('documentCategory.name')
                    ->label('Document Category')
                    ->sortable()
                    ->searchable()
                    ->weight('medium'),
                Tables\Columns\IconColumn::make('is_mandatory')
                    ->label('Mandatory')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('visa_type')
                    ->options([
                        'Tourist' => 'Tourist Visa',
                        'Work' => 'Work Visa',
                        'Student' => 'Student Visa',
                        'Business' => 'Business Visa',
                        'Family' => 'Family / Spouse Visa',
                        'Transit' => 'Transit Visa',
                    ]),
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
