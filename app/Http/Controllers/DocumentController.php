<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Document;
use App\Services\VaultService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DocumentController extends Controller
{
    /**
     * Upload a new document to the application's vault.
     */
    public function store(Request $request, Application $application, VaultService $vaultService): RedirectResponse
    {
        Gate::authorize('create', [Document::class, $application]);

        $request->validate([
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'document_category_id' => ['nullable', 'exists:document_categories,id'],
            'custom_category' => ['nullable', 'string', 'max:100'],
        ]);

        if (empty($request->document_category_id) && empty($request->custom_category)) {
            return back()->withErrors(['document_category_id' => 'Please select a category or provide a custom category name.']);
        }

        try {
            $vaultService->uploadDocument(
                $application,
                $request->file('document'),
                $request->user(),
                $request->document_category_id ? (int) $request->document_category_id : null,
                $request->custom_category
            );

            return back()->with('success', 'Document uploaded successfully to the vault.');
        } catch (Exception $e) {
            return back()->withErrors(['document' => 'Upload failed: '.$e->getMessage()]);
        }
    }

    /**
     * Securely download a vault document.
     */
    public function download(Document $document, VaultService $vaultService): BinaryFileResponse
    {
        Gate::authorize('download', $document);

        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File not found in storage.');
        }

        $vaultService->logActivity($document, auth()->user(), 'downloaded', 'Document downloaded by '.auth()->user()->name);

        return response()->download(
            Storage::disk('local')->path($document->file_path),
            $document->file_name
        );
    }

    /**
     * Replace an existing document with a newer version.
     */
    public function replace(Request $request, Document $document, VaultService $vaultService): RedirectResponse
    {
        Gate::authorize('replace', $document);

        $request->validate([
            'document' => ['required', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        try {
            $vaultService->replaceDocument($document, $request->file('document'), $request->user());

            return back()->with('success', "Document replaced successfully. Now at version {$document->version}.");
        } catch (Exception $e) {
            return back()->withErrors(['replace_document' => $e->getMessage()]);
        }
    }

    /**
     * Delete an unlocked document from the vault.
     */
    public function destroy(Document $document, VaultService $vaultService): RedirectResponse
    {
        Gate::authorize('delete', $document);

        try {
            $vaultService->deleteDocument($document, auth()->user());

            return back()->with('success', 'Document deleted successfully.');
        } catch (Exception $e) {
            return back()->withErrors(['delete_error' => $e->getMessage()]);
        }
    }
}
