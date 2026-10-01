<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/apply', [\App\Http\Controllers\ApplicationController::class, 'storeLead'])->name('applications.storeLead');


Route::get('/dashboard', function () {
    $user = auth()->user();
    $applications = $user->applications;
    $documents = $user->documents;
    return view('dashboard', compact('applications', 'documents'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/documents/upload', [\App\Http\Controllers\DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/download/{document}', [\App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
