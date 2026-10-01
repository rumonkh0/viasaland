<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|max:10240', // 10MB max
            'document_type' => 'required|string|max:100',
            'application_id' => 'nullable|exists:applications,id',
        ]);

        $file = $request->file('document');
        $originalName = $file->getClientOriginalName();
        $path = $file->store('private/client_documents/' . auth()->id());

        Document::create([
            'user_id' => auth()->id(),
            'application_id' => $request->application_id,
            'document_type' => $request->document_type,
            'file_path' => $path,
            'original_name' => $originalName,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Document uploaded successfully.');
    }

    public function download(Document $document)
    {
        if (auth()->id() !== $document->user_id && auth()->user()->role !== 'admin' && auth()->user()->role !== 'staff') {
            abort(403, 'Unauthorized action.');
        }

        if (!Storage::exists($document->file_path)) {
            abort(404, 'File not found.');
        }

        return Storage::download($document->file_path, $document->original_name);
    }
}
