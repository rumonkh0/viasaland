<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\ApplicationResource\RelationManagers\RequirementsRelationManager;
use App\Models\Application;
use App\Services\VaultService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Visa Operations';

    protected static ?string $navigationLabel = 'Visa Applications';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Application Information')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('Client')
                            ->relationship('user', 'name', fn ($query) => $query->where('role', 'client'))
                            ->searchable()
                            ->preload()
                            ->required(),

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

                        Forms\Components\TextInput::make('target_country')
                            ->label('Destination Country')
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

                        Forms\Components\Textarea::make('notes')
                            ->label('Internal Notes')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Application $record) => $record->user?->phone ?? $record->user?->email),

                Tables\Columns\TextColumn::make('visa_type')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('target_country')
                    ->label('Destination')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'under_review', 'verified', 'embassy_booked' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('documents_count')
                    ->counts('documents')
                    ->label('Vault Files')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('completion')
                    ->label('Requirements')
                    ->state(fn (Application $record) => $record->completionPercentage().'%')
                    ->badge()
                    ->color(fn (string $state): string => (float) $state >= 100 ? 'success' : 'warning'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'submitted' => 'Submitted',
                        'under_review' => 'Under Review',
                        'verified' => 'Verified',
                        'embassy_booked' => 'Embassy Booked',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('visa_type')
                    ->options([
                        'Tourist' => 'Tourist Visa',
                        'Work' => 'Work Visa',
                        'Student' => 'Student Visa',
                        'Business' => 'Business Visa',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download_zip')
                    ->label('ZIP')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('success')
                    ->tooltip('Download application vault as ZIP archive')
                    ->action(function (Application $record, VaultService $vaultService) {
                        try {
                            $zipPath = $vaultService->createApplicationZip($record);

                            return response()->download($zipPath, "vault_application_{$record->id}_{$record->visa_type}.zip");
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('ZIP Generation Failed')
                                ->body($e->getMessage())
                                ->send();

                            return null;
                        }
                    }),

                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DocumentsRelationManager::class,
            RequirementsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
