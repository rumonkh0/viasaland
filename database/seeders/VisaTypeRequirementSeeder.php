<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\CountryVisaType;
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
        $countryMeta = [
            'India' => ['flag' => '🇮🇳', 'code' => 'IN', 'sort_order' => 1],
            'Spain' => ['flag' => '🇪🇸', 'code' => 'ES', 'sort_order' => 2],
            'USA' => ['flag' => '🇺🇸', 'code' => 'US', 'sort_order' => 3],
            'UK' => ['flag' => '🇬🇧', 'code' => 'GB', 'sort_order' => 4],
            'Canada' => ['flag' => '🇨🇦', 'code' => 'CA', 'sort_order' => 5],
            'Australia' => ['flag' => '🇦🇺', 'code' => 'AU', 'sort_order' => 6],
            'Germany' => ['flag' => '🇩🇪', 'code' => 'DE', 'sort_order' => 7],
            'France' => ['flag' => '🇫🇷', 'code' => 'FR', 'sort_order' => 8],
            'Italy' => ['flag' => '🇮🇹', 'code' => 'IT', 'sort_order' => 9],
            'Japan' => ['flag' => '🇯🇵', 'code' => 'JP', 'sort_order' => 10],
        ];

        // 1. Country-specific visa requirements (as requested: India, Spain, etc.)
        $countryRequirements = [
            'India' => [
                'Tourist' => ['Passport', 'Photograph', 'Bank Statement', 'NID / National ID', 'Flight Itinerary', 'Hotel Booking'],
                'Business' => ['Passport', 'Photograph', 'Bank Statement', 'Invitation Letter', 'Employment Letter', 'Income Tax Return'],
                'Medical' => ['Passport', 'Photograph', 'Medical Reports', 'Invitation Letter', 'Bank Statement', 'NID / National ID'],
                'Double Entry' => ['Passport', 'Photograph', 'Bank Statement', 'Flight Itinerary', 'Hotel Booking', 'NID / National ID'],
            ],
            'Spain' => [
                'Tourist' => ['Passport', 'Photograph', 'Bank Statement', 'Travel Insurance', 'Hotel Booking', 'Flight Itinerary'],
                'Student' => ['Passport', 'Photograph', 'Bank Statement', 'Educational Certificates', 'Medical Reports', 'Police Clearance', 'Travel Insurance', 'Invitation Letter'],
                'Business' => ['Passport', 'Photograph', 'Bank Statement', 'Travel Insurance', 'Invitation Letter', 'Employment Letter'],
                'Work' => ['Passport', 'Photograph', 'Employment Letter', 'Police Clearance', 'Medical Reports', 'Travel Insurance'],
            ],
            'USA' => [
                'Tourist' => ['Passport', 'Photograph', 'Bank Statement', 'Visa Application Form', 'Employment Letter'],
                'Student' => ['Passport', 'Photograph', 'Bank Statement', 'Educational Certificates', 'Sponsorship Letter', 'Visa Application Form'],
                'Business' => ['Passport', 'Photograph', 'Bank Statement', 'Invitation Letter', 'Employment Letter', 'Visa Application Form'],
            ],
            'UK' => [
                'Tourist' => ['Passport', 'Photograph', 'Bank Statement', 'Employment Letter', 'Hotel Booking'],
                'Student' => ['Passport', 'Photograph', 'Bank Statement', 'Educational Certificates', 'Police Clearance', 'Travel Insurance'],
                'Work' => ['Passport', 'Photograph', 'Employment Letter', 'Police Clearance', 'Medical Reports'],
            ],
            'Canada' => [
                'Tourist' => ['Passport', 'Photograph', 'Bank Statement', 'Employment Letter', 'Flight Itinerary'],
                'Student' => ['Passport', 'Photograph', 'Bank Statement', 'Educational Certificates', 'Police Clearance', 'Medical Reports'],
                'Work' => ['Passport', 'Photograph', 'Employment Letter', 'Police Clearance', 'Educational Certificates'],
            ],
        ];

        foreach ($countryRequirements as $countryName => $visaTypes) {
            $country = Country::firstOrCreate(
                ['name' => $countryName],
                [
                    'slug' => Str::slug($countryName),
                    'code' => $countryMeta[$countryName]['code'] ?? null,
                    'flag' => $countryMeta[$countryName]['flag'] ?? null,
                    'sort_order' => $countryMeta[$countryName]['sort_order'] ?? 0,
                    'is_active' => true,
                ]
            );

            foreach ($visaTypes as $visaType => $catNames) {
                $countryVisaType = CountryVisaType::firstOrCreate(
                    [
                        'country_id' => $country->id,
                        'name' => $visaType,
                    ],
                    [
                        'display_name' => Str::endsWith($visaType, 'Visa') ? $visaType : $visaType.' Visa',
                        'slug' => Str::slug($visaType),
                        'is_active' => true,
                    ]
                );

                foreach ($catNames as $catName) {
                    $category = DocumentCategory::where('slug', Str::slug($catName))->first();
                    if ($category) {
                        VisaTypeRequirement::updateOrCreate([
                            'country' => $countryName,
                            'visa_type' => $visaType,
                            'document_category_id' => $category->id,
                        ], [
                            'country_visa_type_id' => $countryVisaType->id,
                            'is_mandatory' => true,
                        ]);
                    }
                }
            }
        }

        // 2. Additional common destination countries

        // Seed additional common destination countries if not already created
        $otherCountries = [
            'Australia' => ['flag' => '🇦🇺', 'code' => 'AU', 'sort_order' => 6],
            'Germany' => ['flag' => '🇩🇪', 'code' => 'DE', 'sort_order' => 7],
            'France' => ['flag' => '🇫🇷', 'code' => 'FR', 'sort_order' => 8],
            'Italy' => ['flag' => '🇮🇹', 'code' => 'IT', 'sort_order' => 9],
            'Japan' => ['flag' => '🇯🇵', 'code' => 'JP', 'sort_order' => 10],
        ];

        foreach ($otherCountries as $cName => $meta) {
            Country::firstOrCreate(
                ['name' => $cName],
                [
                    'slug' => Str::slug($cName),
                    'code' => $meta['code'],
                    'flag' => $meta['flag'],
                    'sort_order' => $meta['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
