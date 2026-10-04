<?php

namespace App\Filament\Resources\ApplicationResource\RelationManagers;

use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\VaultService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentsRelationManager extends RelationManager
{
    protected static string $relationship = 'documents';

    protected static ?string $title = 'Vault Documents';

    protected static ?string $icon = 'heroicon-o-folder-open';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('document_category_id')
                    ->label('Category')
                    ->options(fn () => DocumentCategory::active()->ordered()->pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),

                Forms\Components\TextInput::make('custom_category')
                    ->label('Custom Category (if not in list)')
                    ->visible(fn (Forms\Get $get) => empty($get('document_category_id'))),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->default('pending')
                    ->required(),

                Forms\Components\Toggle::make('is_locked')
                    ->label('🔒 Lock file (Prevents client from deleting/replacing)'),

                Forms\Components\Textarea::make('admin_notes')
                    ->label('Admin Notes')
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('file_name')
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->state(fn (Document $record) => $record->categoryName())
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('file_name')
                    ->label('File Name')
                    ->searchable()
                    ->weight('medium')
                    ->description(fn (Document $record) => $record->fileSizeFormatted().' • v'.$record->version),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),

                Tables\Columns\IconColumn::make('is_locked')
                    ->label('Locked')
                    ->boolean()
                    ->trueIcon('heroicon-s-lock-closed')
                    ->falseIcon('heroicon-o-lock-open')
                    ->trueColor('danger')
                    ->falseColor('gray'),

                Tables\Columns\TextColumn::make('admin_notes')
                    ->label('Notes')
                    ->limit(30)
                    ->tooltip(fn (Document $record) => $record->admin_notes),

                Tables\Columns\TextColumn::make('uploadedBy.name')
                    ->label('Uploaded By')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->headerActions([
                // Upload to this application vault
                Tables\Actions\Action::make('upload')
                    ->label('Upload Document')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('document_category_id')
                            ->label('Category')
                            ->options(fn () => DocumentCategory::active()->ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Forms\Components\TextInput::make('custom_category')
                            ->label('Or Custom Category')
                            ->placeholder('e.g., Police Verification, Sponsor ID')
                            ->visible(fn (Forms\Get $get) => empty($get('document_category_id'))),

                        Forms\Components\FileUpload::make('file')
                            ->label('Select File')
                            ->required()
                            ->disk('local')
                            ->directory('temp_uploads'),

                        Forms\Components\Toggle::make('lock_file')
                            ->label('🔒 Lock file immediately for client')
                            ->default(true),

                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                            ])
                            ->default('approved')
                            ->required(),
                    ])
                    ->action(function (array $data, VaultService $vaultService) {
                        $application = $this->getOwnerRecord();
                        $uploadedPath = Storage::disk('local')->path($data['file']);

                        $originalName = basename($data['file']);
                        $mime = mime_content_type($uploadedPath) ?: 'application/octet-stream';

                        $uploadedFile = new UploadedFile(
                            $uploadedPath,
                            $originalName,
                            $mime,
                            null,
                            true
                        );

                        $doc = $vaultService->uploadDocument(
                            $application,
                            $uploadedFile,
                            auth()->user(),
                            $data['document_category_id'] ?? null,
                            $data['custom_category'] ?? null
                        );

                        if (! empty($data['lock_file'])) {
                            $vaultService->lockDocument($doc, auth()->user());
                        }

                        if ($data['status'] === 'approved') {
                            $vaultService->updateStatus($doc, 'approved', 'Uploaded and verified by Administrator.', auth()->user());
                        }

                        Notification::make()
                            ->success()
                            ->title('Uploaded to Vault')
                            ->send();
                    }),

                // Download all as ZIP
                Tables\Actions\Action::make('download_zip')
                    ->label('Download All (ZIP)')
                    ->icon('heroicon-o-archive-box-arrow-down')
                    ->color('success')
                    ->action(function (VaultService $vaultService) {
                        $application = $this->getOwnerRecord();

                        try {
                            $zipPath = $vaultService->createApplicationZip($application);

                            return response()->download($zipPath, "vault_application_{$application->id}.zip");
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('ZIP Generation Failed')
                                ->body($e->getMessage())
                                ->send();

                            return null;
                        }
                    }),
            ])
            ->actions([
                // Review Action
                Tables\Actions\Action::make('review')
                    ->label('Review')
                    ->icon('heroicon-o-check-badge')
                    ->color('warning')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending Review',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required()
                            ->default(fn (Document $record) => $record->status),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->default(fn (Document $record) => $record->admin_notes),
                    ])
                    ->action(function (Document $record, array $data, VaultService $vaultService) {
                        $vaultService->updateStatus($record, $data['status'], $data['admin_notes'], auth()->user());
                        Notification::make()->success()->title('Document status updated')->send();
                    }),

                // Lock / Unlock Action
                Tables\Actions\Action::make('toggle_lock')
                    ->label(fn (Document $record) => $record->is_locked ? 'Unlock' : 'Lock')
                    ->icon(fn (Document $record) => $record->is_locked ? 'heroicon-o-lock-open' : 'heroicon-s-lock-closed')
                    ->color(fn (Document $record) => $record->is_locked ? 'gray' : 'danger')
                    ->requiresConfirmation()
                    ->action(function (Document $record, VaultService $vaultService) {
                        if ($record->is_locked) {
                            $vaultService->unlockDocument($record, auth()->user());
                            Notification::make()->success()->title('Document Unlocked')->send();
                        } else {
                            $vaultService->lockDocument($record, auth()->user());
                            Notification::make()->success()->title('Document Locked (Client cannot delete)')->send();
                        }
                    }),

                // Download Action
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function (Document $record) {
                        if (! Storage::disk('local')->exists($record->file_path)) {
                            Notification::make()->danger()->title('File Not Found')->send();

                            return null;
                        }

                        return response()->download(
                            Storage::disk('local')->path($record->file_path),
                            $record->file_name
                        );
                    }),

                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
