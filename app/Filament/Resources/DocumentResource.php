<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Application;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Services\VaultService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static ?string $navigationGroup = 'Vault Management';

    protected static ?string $navigationLabel = 'All Vault Files';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Document Details')
                    ->schema([
                        Forms\Components\Select::make('application_id')
                            ->relationship('application', 'id')
                            ->getOptionLabelFromRecordUsing(fn (Application $record) => "#{$record->id} - {$record->user?->name} ({$record->visa_type} - {$record->target_country})")
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function (Forms\Set $set, ?int $state) {
                                if ($state) {
                                    $app = Application::find($state);
                                    if ($app) {
                                        $set('user_id', $app->user_id);
                                    }
                                }
                            }),

                        Forms\Components\Hidden::make('user_id'),

                        Forms\Components\Select::make('document_category_id')
                            ->relationship('documentCategory', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable()
                            ->label('Document Category'),

                        Forms\Components\TextInput::make('custom_category')
                            ->label('Custom Category (if not in list)')
                            ->maxLength(255)
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
                            ->label('🔒 Lock file (Prevents client from deleting/replacing)')
                            ->default(false),

                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Reviewer Notes / Feedback to Client')
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
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Document $record) => $record->user?->email),

                Tables\Columns\TextColumn::make('application.visa_type')
                    ->label('Application')
                    ->formatStateUsing(fn ($state, Document $record) => "#{$record->application_id} {$state} ({$record->application?->target_country})")
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->state(fn (Document $record) => $record->categoryName())
                    ->badge()
                    ->color('info')
                    ->sortable(),

                Tables\Columns\TextColumn::make('file_name')
                    ->label('File')
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

                Tables\Columns\TextColumn::make('uploadedBy.name')
                    ->label('Uploaded By')
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Uploaded At')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending Review',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),

                Tables\Filters\SelectFilter::make('document_category_id')
                    ->label('Category')
                    ->relationship('documentCategory', 'name'),

                Tables\Filters\TernaryFilter::make('is_locked')
                    ->label('Locked Status'),
            ])
            ->headerActions([
                Tables\Actions\Action::make('admin_upload')
                    ->label('Upload to Vault')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('application_id')
                            ->label('Target Application Vault')
                            ->options(fn () => Application::with('user')->get()->mapWithKeys(function ($app) {
                                return [$app->id => "#{$app->id} - {$app->user?->name} ({$app->visa_type} - {$app->target_country})"];
                            }))
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('document_category_id')
                            ->label('Category')
                            ->options(fn () => DocumentCategory::active()->ordered()->pluck('name', 'id'))
                            ->searchable()
                            ->nullable(),

                        Forms\Components\TextInput::make('custom_category')
                            ->label('Or Custom Category')
                            ->placeholder('e.g., Medical Certificate, Embassy Receipt')
                            ->visible(fn (Forms\Get $get) => empty($get('document_category_id'))),

                        Forms\Components\FileUpload::make('file')
                            ->label('File')
                            ->required()
                            ->disk('local')
                            ->directory('temp_uploads'),

                        Forms\Components\Toggle::make('lock_immediately')
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
                        $app = Application::findOrFail($data['application_id']);
                        $uploadedPath = Storage::disk('local')->path($data['file']);

                        $originalName = basename($data['file']);
                        $mime = mime_content_type($uploadedPath) ?: 'application/octet-stream';
                        $size = filesize($uploadedPath);

                        $uploadedFile = new UploadedFile(
                            $uploadedPath,
                            $originalName,
                            $mime,
                            null,
                            true
                        );

                        $doc = $vaultService->uploadDocument(
                            $app,
                            $uploadedFile,
                            auth()->user(),
                            $data['document_category_id'] ?? null,
                            $data['custom_category'] ?? null
                        );

                        if (! empty($data['lock_immediately'])) {
                            $vaultService->lockDocument($doc, auth()->user());
                        }

                        if ($data['status'] === 'approved') {
                            $vaultService->updateStatus($doc, 'approved', 'Uploaded and verified by Administrator.', auth()->user());
                        }

                        Notification::make()
                            ->success()
                            ->title('Uploaded to Vault')
                            ->body("Document saved to Application #{$app->id} vault.")
                            ->send();
                    }),
            ])
            ->actions([
                // Review (Approve / Reject) Action
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
                            ->label('Admin Notes / Feedback')
                            ->default(fn (Document $record) => $record->admin_notes),
                    ])
                    ->action(function (Document $record, array $data, VaultService $vaultService) {
                        $vaultService->updateStatus($record, $data['status'], $data['admin_notes'], auth()->user());

                        Notification::make()
                            ->success()
                            ->title('Document Reviewed')
                            ->body("Status updated to '{$data['status']}'.")
                            ->send();
                    }),

                // Lock / Unlock Toggle Action
                Tables\Actions\Action::make('toggle_lock')
                    ->label(fn (Document $record) => $record->is_locked ? 'Unlock' : 'Lock')
                    ->icon(fn (Document $record) => $record->is_locked ? 'heroicon-o-lock-open' : 'heroicon-s-lock-closed')
                    ->color(fn (Document $record) => $record->is_locked ? 'gray' : 'danger')
                    ->requiresConfirmation()
                    ->modalHeading(fn (Document $record) => $record->is_locked ? 'Unlock Document' : 'Lock Document')
                    ->modalDescription(fn (Document $record) => $record->is_locked
                        ? 'Unlocking this document will allow the client to delete or replace this file.'
                        : 'Locking this document will PREVENT the client from deleting or replacing it.')
                    ->action(function (Document $record, VaultService $vaultService) {
                        if ($record->is_locked) {
                            $vaultService->unlockDocument($record, auth()->user());
                            Notification::make()->success()->title('Document Unlocked')->send();
                        } else {
                            $vaultService->lockDocument($record, auth()->user());
                            Notification::make()->success()->title('Document Locked')->send();
                        }
                    }),

                // Download File Action
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

                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('bulk_approve')
                        ->label('Approve Selected')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function ($records, VaultService $vaultService) {
                            foreach ($records as $record) {
                                $vaultService->updateStatus($record, 'approved', 'Bulk approved by admin.', auth()->user());
                            }
                            Notification::make()->success()->title('Selected documents approved')->send();
                        }),

                    Tables\Actions\BulkAction::make('bulk_lock')
                        ->label('Lock Selected')
                        ->icon('heroicon-s-lock-closed')
                        ->color('danger')
                        ->action(function ($records, VaultService $vaultService) {
                            foreach ($records as $record) {
                                $vaultService->lockDocument($record, auth()->user());
                            }
                            Notification::make()->success()->title('Selected documents locked')->send();
                        }),

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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
