<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function storeLead(Request $request)
    {
        $validated = $request->validate([
            'fullName' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'country' => 'required|string|max:100',
            'visaType' => 'required|string|max:100',
            'message' => 'nullable|string',
        ]);

        // Find or create user
        $user = null;
        if (!empty($validated['email'])) {
            $user = User::where('email', $validated['email'])->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $validated['fullName'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? 'lead_' . Str::random(8) . '@viasaland.com',
                'password' => Hash::make(Str::random(12)),
                'role' => 'client',
            ]);
        }

        // Create application
        Application::create([
            'user_id' => $user->id,
            'visa_type' => $validated['visaType'],
            'target_country' => $validated['country'],
            'status' => 'draft',
        ]);

        return redirect()->back()->with('success', 'Thank you! Your inquiry has been submitted. Our visa expert will call you shortly.');
    }
}
