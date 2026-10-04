<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use App\Services\VaultService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ApplicationController extends Controller
{
    /**
     * Store lead from the landing page.
     */
    public function storeLead(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'country' => 'required|string|max:100',
            'visaType' => 'required|string|max:100',
            'message' => 'nullable|string',
        ]);

        $user = null;
        if (! empty($validated['email'])) {
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
        ]);

        $application->syncRequirementsFromVisaType();

        return redirect()->back()->with('success', 'Thank you! Your inquiry has been submitted. Our visa expert will call you shortly.');
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
