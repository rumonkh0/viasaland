<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\ApplicationResource;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationsRelationManager extends RelationManager
{
    protected static string $relationship = 'applications';

    protected static ?string $title = 'Client Visa Applications';

    public function form(Form $form): Form
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
                    ])
                    ->required(),
                Forms\Components\TextInput::make('target_country')
                    ->required()
                    ->maxLength(100),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'verified' => 'Verified',
                        'embassy_booked' => 'Embassy Booked',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('draft')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('visa_type')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('visa_type')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_country')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('documents_count')
                    ->counts('documents')
                    ->label('Vault Files'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function (Application $record) {
                        $record->syncRequirementsFromVisaType();
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('manage_vault')
                    ->label('Open Vault')
                    ->icon('heroicon-o-folder-open')
                    ->url(fn (Application $record) => ApplicationResource::getUrl('edit', ['record' => $record])),

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }
}
