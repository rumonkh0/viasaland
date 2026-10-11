<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Models\VisaTypeRequirement;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

Route::get('/', function () {
    $visaRequirements = collect();

    if (Schema::hasTable('visa_type_requirements')) {
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
    }

    return view('welcome', compact('visaRequirements'));
});

Route::post('/apply', [ApplicationController::class, 'storeLead'])->name('applications.storeLead');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Document vault operations
    Route::post('/applications/{application}/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}/view', [DocumentController::class, 'show'])->name('documents.show');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{document}/replace', [DocumentController::class, 'replace'])->name('documents.replace');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

    // Application ZIP download
    Route::get('/applications/{application}/download-zip', [ApplicationController::class, 'downloadZip'])->name('applications.downloadZip');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
