<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
use App\Models\VisaTypeRequirement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the client dashboard and application vaults.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $applications = $user->applications()
            ->with([
                'documents' => fn ($query) => $query->with(['documentCategory', 'uploadedBy', 'versions'])->latest(),
                'requirements.documentCategory',
            ])
            ->latest()
            ->get();

        $syncedAny = false;
        foreach ($applications as $app) {
            if ($app->requirements->isEmpty()) {
                $app->syncRequirementsFromVisaType();
                $syncedAny = true;
            }
        }
        if ($syncedAny) {
            $applications->load('requirements.documentCategory');
        }

        $categories = DocumentCategory::active()->ordered()->get();

        $visaRequirements = VisaTypeRequirement::with('documentCategory')
            ->get()
            ->map(function ($r) {
                return [
                    'id' => $r->document_category_id,
                    'name' => $r->documentCategory?->name,
                    'country' => $r->country,
                    'visa_type' => $r->visa_type,
                    'is_mandatory' => (bool) $r->is_mandatory,
                ];
            })->values();

        return view('dashboard', compact('applications', 'categories', 'visaRequirements'));
    }
}
