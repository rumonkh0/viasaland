<?php

namespace App\Providers;

use App\Models\VisaTypeRequirement;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['welcome', 'layouts.public'], function ($view) {
            if (Schema::hasTable('visa_type_requirements')) {
                $requirements = VisaTypeRequirement::with('documentCategory')
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

                $view->with('visaRequirements', $requirements);
            }
        });
    }
}
