<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Document;
use App\Models\DocumentActivity;
use App\Models\DocumentVersion;
use App\Models\User;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class VaultService
{
    /**
     * Upload a new document to an application's vault.
     *
     * @throws Exception
     */
    public function uploadDocument(
        Application $application,
        UploadedFile $file,
        User $uploadedBy,
        ?int $categoryId = null,
        ?string $customCategory = null
    ): Document {
        $owner = $application->user;
        $originalName = $file->getClientOriginalName();
        $safeName = time().'_'.Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'.'.$file->getClientOriginalExtension();
        $folder = "private/vaults/user_{$owner->id}/application_{$application->id}";

        $path = $file->storeAs($folder, $safeName, 'local');

        $document = Document::create([
            'user_id' => $owner->id,
            'application_id' => $application->id,
            'document_category_id' => $categoryId,
            'custom_category' => $categoryId ? null : $customCategory,
            'file_path' => $path,
            'file_name' => $originalName,
            'file_size' => $file->getSize() ?: 0,
            'mime_type' => $file->getClientMimeType() ?: $file->getMimeType(),
            'status' => 'pending',
            'is_locked' => false,
            'uploaded_by' => $uploadedBy->id,
            'version' => 1,
        ]);

        $this->logActivity($document, $uploadedBy, 'uploaded', "Document '{$originalName}' uploaded to vault.");
        $application->refreshRequirementStatuses();

        return $document;
    }

    /**
     * Replace an existing document file with a new version.
     *
     * @throws Exception
     */
    public function replaceDocument(Document $document, UploadedFile $newFile, User $replacedBy): Document
    {
        if (! $document->canBeReplacedBy($replacedBy)) {
            throw new Exception('Document is locked and cannot be replaced.');
        }

        // Archive current file into document_versions
        DocumentVersion::create([
            'document_id' => $document->id,
            'version' => $document->version,
            'file_path' => $document->file_path,
            'file_name' => $document->file_name,
            'file_size' => $document->file_size,
            'mime_type' => $document->mime_type,
            'replaced_by' => $replacedBy->id,
        ]);

        // Upload new file
        $owner = $document->user;
        $originalName = $newFile->getClientOriginalName();
        $safeName = time().'_v'.($document->version + 1).'_'.Str::slug(pathinfo($originalName, PATHINFO_FILENAME)).'.'.$newFile->getClientOriginalExtension();
        $folder = "private/vaults/user_{$owner->id}/application_{$document->application_id}";

        $newPath = $newFile->storeAs($folder, $safeName, 'local');

        $document->update([
            'file_path' => $newPath,
            'file_name' => $originalName,
            'file_size' => $newFile->getSize() ?: 0,
            'mime_type' => $newFile->getClientMimeType() ?: $newFile->getMimeType(),
            'version' => $document->version + 1,
            'status' => 'pending',
        ]);

        $this->logActivity(
            $document,
            $replacedBy,
            'replaced',
            "Replaced with version {$document->version} ('{$originalName}')."
        );

        $document->application->refreshRequirementStatuses();

        return $document;
    }

    /**
     * Delete a document from the vault.
     *
     * @throws Exception
     */
    public function deleteDocument(Document $document, User $deletedBy): bool
    {
        if (! $document->canBeDeletedBy($deletedBy)) {
            throw new Exception('Document is locked and cannot be deleted.');
        }

        $this->logActivity($document, $deletedBy, 'deleted', "Document '{$document->file_name}' deleted.");

        // Soft delete the document record
        $document->delete();

        $document->application->refreshRequirementStatuses();

        return true;
    }

    /**
     * Lock a document (Admin only).
     */
    public function lockDocument(Document $document, User $admin): void
    {
        $document->update([
            'is_locked' => true,
            'locked_by' => $admin->id,
            'locked_at' => now(),
        ]);

        $this->logActivity($document, $admin, 'locked', "Document locked by {$admin->name}.");
    }

    /**
     * Unlock a document (Admin only).
     */
    public function unlockDocument(Document $document, User $admin): void
    {
        $document->update([
            'is_locked' => false,
            'locked_by' => null,
            'locked_at' => null,
        ]);

        $this->logActivity($document, $admin, 'unlocked', "Document unlocked by {$admin->name}.");
    }

    /**
     * Update review status and admin notes (Admin only).
     */
    public function updateStatus(Document $document, string $status, ?string $adminNotes, User $admin): void
    {
        $document->update([
            'status' => $status,
            'admin_notes' => $adminNotes,
        ]);

        $this->logActivity(
            $document,
            $admin,
            $status,
            "Status updated to {$status}. Notes: ".($adminNotes ?: 'None')
        );

        $document->application->refreshRequirementStatuses();
    }

    /**
     * Create a ZIP file for an application containing all its documents organized into folders.
     *
     * @throws Exception
     */
    public function createApplicationZip(Application $application): string
    {
        $documents = $application->documents()->with('documentCategory')->get();

        if ($documents->isEmpty()) {
            throw new Exception('No documents found in this application vault.');
        }

        $zipFileName = 'vault_app_'.$application->id.'_'.Str::slug($application->visa_type).'_'.time().'.zip';
        $tempDir = storage_path('app/temp');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir.'/'.$zipFileName;
        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new Exception('Failed to create ZIP archive.');
        }

        $usedNames = [];

        foreach ($documents as $doc) {
            if (! Storage::disk('local')->exists($doc->file_path)) {
                continue;
            }

            $folder = Str::slug($doc->categoryName());
            $fileName = $doc->file_name;

            // Ensure unique names within the folder inside ZIP
            $entryPath = $folder.'/'.$fileName;
            if (isset($usedNames[$entryPath])) {
                $usedNames[$entryPath]++;
                $ext = pathinfo($fileName, PATHINFO_EXTENSION);
                $base = pathinfo($fileName, PATHINFO_FILENAME);
                $entryPath = $folder.'/'.$base.'_'.$usedNames[$entryPath].($ext ? '.'.$ext : '');
            } else {
                $usedNames[$entryPath] = 1;
            }

            $absoluteFilePath = Storage::disk('local')->path($doc->file_path);
            $zip->addFile($absoluteFilePath, $entryPath);
        }

        $zip->close();

        return $zipPath;
    }

    /**
     * Log document activity.
     */
    public function logActivity(Document $document, User $user, string $action, ?string $description = null): DocumentActivity
    {
        return DocumentActivity::create([
            'document_id' => $document->id,
            'user_id' => $user->id,
            'action' => $action,
            'description' => $description,
        ]);
    }
}
