<?php

namespace Database\Seeders;

use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DocumentCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Passport',
            'NID / National ID',
            'Photograph',
            'Bank Statement',
            'Travel Insurance',
            'Visa Application Form',
            'Employment Letter',
            'Invitation Letter',
            'Hotel Booking',
            'Flight Itinerary',
            'Birth Certificate',
            'Marriage Certificate',
            'Income Tax Return',
            'Sponsorship Letter',
            'Medical Reports',
            'Police Clearance',
            'Educational Certificates',
            'Other',
        ];

        foreach ($categories as $index => $name) {
            DocumentCategory::firstOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'name' => $name,
                    'is_predefined' => true,
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
