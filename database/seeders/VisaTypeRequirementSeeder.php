<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use App\Models\VisaTypeRequirement;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VisaTypeRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $requirements = [
            'Tourist' => ['Passport', 'Photograph', 'Bank Statement', 'Hotel Booking', 'Flight Itinerary'],
            'Work' => ['Passport', 'Photograph', 'Employment Letter', 'Medical Reports', 'Police Clearance'],
            'Student' => ['Passport', 'Photograph', 'Educational Certificates', 'Bank Statement', 'Sponsorship Letter'],
            'Business' => ['Passport', 'Photograph', 'Invitation Letter', 'Bank Statement', 'Income Tax Return'],
        ];

        foreach ($requirements as $visaType => $catNames) {
            foreach ($catNames as $catName) {
                $category = DocumentCategory::where('slug', Str::slug($catName))->first();
                if ($category) {
                    VisaTypeRequirement::firstOrCreate([
                        'visa_type' => $visaType,
                        'document_category_id' => $category->id,
                    ], [
                        'is_mandatory' => true,
                    ]);
                }
            }
        }
    }
}
