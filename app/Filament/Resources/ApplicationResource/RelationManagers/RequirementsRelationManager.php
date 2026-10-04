<?php

namespace App\Filament\Resources\ApplicationResource\RelationManagers;

use App\Models\DocumentCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RequirementsRelationManager extends RelationManager
{
    protected static string $relationship = 'requirements';

    protected static ?string $title = 'Application Requirements';

    protected static ?string $icon = 'heroicon-o-check-circle';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_category_id')
                    ->label('Document Category')
                    ->options(fn () => DocumentCategory::active()->ordered()->pluck('name', 'id'))
                    ->required()
                    ->searchable(),
                Forms\Components\Toggle::make('is_mandatory')
                    ->label('Mandatory Requirement')
                    ->default(true),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('documentCategory.name')
                    ->label('Required Category')
                    ->weight('medium')
                    ->searchable(),

                Tables\Columns\IconColumn::make('is_mandatory')
                    ->label('Mandatory')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_fulfilled')
                    ->label('Fulfilled')
                    ->boolean()
                    ->trueIcon('heroicon-s-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('warning'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add Required Document'),

                Tables\Actions\Action::make('sync_defaults')
                    ->label('Sync Defaults from Visa Type')
                    ->icon('heroicon-o-arrow-path')
                    ->color('info')
                    ->action(function () {
                        $application = $this->getOwnerRecord();
                        $application->syncRequirementsFromVisaType();
                        $application->refreshRequirementStatuses();

                        Notification::make()
                            ->success()
                            ->title('Requirements Synced')
                            ->body('Synced default required documents for '.$application->visa_type.' visa.')
                            ->send();
                    }),
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
}
