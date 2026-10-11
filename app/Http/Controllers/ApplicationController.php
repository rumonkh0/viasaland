<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\DocumentCategory;
use App\Models\User;
use App\Services\VaultService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApplicationController extends Controller
{
    /**
     * Store lead and application from the landing page.
     */
    public function storeLead(Request $request, VaultService $vaultService): RedirectResponse
    {
        if (! $request->has('fullName') && $request->has('name')) {
            $request->merge(['fullName' => $request->input('name')]);
        }
        if (! $request->has('visaType') && $request->has('visa_type')) {
            $request->merge(['visaType' => $request->input('visa_type')]);
        }

        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'country' => 'required|string|max:100',
            'visaType' => 'required|string|max:100',
            'message' => 'nullable|string',
            'document' => ['nullable', 'file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
            'documents' => ['nullable', 'array'],
            'documents.*' => ['file', 'max:10240', 'mimes:pdf,jpg,jpeg,png,doc,docx'],
        ]);

        $user = auth()->user();
        if (! $user && ! empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $validated['fullName'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? 'lead_'.Str::random(8).'@viasaland.com',
                'password' => Hash::make(Str::random(12)),
                'role' => 'client',
            ]);
        }

        $application = Application::create([
            'user_id' => $user->id,
            'visa_type' => $validated['visaType'],
            'target_country' => $validated['country'],
            'status' => 'draft',
            'notes' => $validated['message'] ?? null,
        ]);

        $application->syncRequirementsFromVisaType();

        // Process uploaded documents
        $targetFolderId = $request->input('target_folder_id') ?? $request->input('target_category_id');

        $files = [];
        if ($request->hasFile('documents')) {
            $files = $request->file('documents');
        } elseif ($request->hasFile('document')) {
            $files = [$request->file('document')];
        }

        $uploadedCount = 0;
        if (! empty($files)) {
            $requirements = $application->requirements()->with('documentCategory')->get();
            $categoriesBySlug = DocumentCategory::all()->keyBy('slug');
            $categoriesById = DocumentCategory::all()->keyBy('id');

            foreach ($files as $file) {
                $filename = strtolower($file->getClientOriginalName());
                $matchedCatId = null;
                $customCategoryName = null;

                // 1. If explicit destination folder is specified by user
                if (! empty($targetFolderId)) {
                    if ($targetFolderId === 'others' || $targetFolderId === 'general') {
                        $matchedCatId = null;
                        $customCategoryName = 'Others';
                    } elseif (is_numeric($targetFolderId) && $categoriesById->has((int) $targetFolderId)) {
                        $matchedCatId = (int) $targetFolderId;
                    } else {
                        $foundReq = $requirements->first(function ($r) use ($targetFolderId) {
                            return strtolower($r->documentCategory?->name ?? '') === strtolower($targetFolderId);
                        });
                        if ($foundReq) {
                            $matchedCatId = $foundReq->document_category_id;
                        } else {
                            $cat = DocumentCategory::where('name', $targetFolderId)->first();
                            if ($cat) {
                                $matchedCatId = $cat->id;
                            } else {
                                $customCategoryName = $targetFolderId;
                            }
                        }
                    }
                }

                // 2. Fallback heuristic if no destination folder was specified (e.g. from homepage consultation form)
                if (! $matchedCatId && ! $customCategoryName) {
                    foreach ($requirements as $req) {
                        $cat = $req->documentCategory;
                        if ($cat && str_contains($filename, strtolower($cat->name))) {
                            $matchedCatId = $cat->id;
                            break;
                        }
                    }

                    if (! $matchedCatId) {
                        if (str_contains($filename, 'passport')) {
                            $matchedCatId = $categoriesBySlug->get('passport')?->id;
                        } elseif (str_contains($filename, 'photo') || str_contains($filename, 'picture')) {
                            $matchedCatId = $categoriesBySlug->get('photograph')?->id;
                        } elseif (str_contains($filename, 'bank') || str_contains($filename, 'statement')) {
                            $matchedCatId = $categoriesBySlug->get('bank-statement')?->id;
                        } elseif (str_contains($filename, 'hotel') || str_contains($filename, 'booking')) {
                            $matchedCatId = $categoriesBySlug->get('hotel-booking')?->id;
                        } elseif (str_contains($filename, 'flight') || str_contains($filename, 'ticket')) {
                            $matchedCatId = $categoriesBySlug->get('flight-itinerary')?->id;
                        }
                    }

                    if (! $matchedCatId) {
                        $unfulfilled = $requirements->firstWhere('is_fulfilled', false);
                        $matchedCatId = $unfulfilled?->document_category_id;
                    }
                }

                $vaultService->uploadDocument(
                    $application,
                    $file,
                    $user,
                    $matchedCatId,
                    $matchedCatId ? null : ($customCategoryName ?: 'General Submission')
                );
                $uploadedCount++;
            }
        }

        if (! auth()->check()) {
            Auth::login($user);
        }

        $message = $uploadedCount > 0
            ? "Your visa application for {$application->target_country} ({$application->visa_type}) was created with {$uploadedCount} document(s) uploaded to your vault!"
            : "Your visa application for {$application->target_country} ({$application->visa_type}) was created! Check your document checklist below to upload required files.";

        return redirect()->route('dashboard', ['app' => $application->id, 'tab' => 'vault'])->with('success', $message);
    }

    /**
     * Download application vault documents as a ZIP archive.
     */
    public function downloadZip(Application $application, VaultService $vaultService): BinaryFileResponse|RedirectResponse
    {
        Gate::authorize('downloadZip', $application);

        try {
            $zipPath = $vaultService->createApplicationZip($application);

            return response()->download(
                $zipPath,
                'application_'.$application->id.'_'.Str::slug($application->visa_type).'_vault.zip'
            );
        } catch (Exception $e) {
            return back()->withErrors(['zip_error' => $e->getMessage()]);
        }
    }
}
