<?php

namespace App\Http\Controllers;

use App\Models\DocumentCategory;
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

        $categories = DocumentCategory::active()->ordered()->get();

        return view('dashboard', compact('applications', 'categories'));
    }
}
