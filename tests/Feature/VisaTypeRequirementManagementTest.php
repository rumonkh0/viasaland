<?php

namespace Tests\Feature;

use App\Filament\Resources\VisaTypeRequirementResource\Pages\ListVisaTypeRequirements;
use App\Models\Country;
use App\Models\CountryVisaType;
use App\Models\DocumentCategory;
use App\Models\User;
use App\Models\VisaTypeRequirement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class VisaTypeRequirementManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'email' => 'admin@viasaland.com',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_access_visa_type_requirements_page(): void
    {
        $this->actingAs($this->admin)
            ->get('/admin/visa-type-requirements')
            ->assertOk()
            ->assertSee('Visa Requirements')
            ->assertSee('Countries');
    }

    public function test_page_displays_countries_at_level_one(): void
    {
        Country::create([
            'name' => 'Spain',
            'code' => 'ES',
            'flag' => '🇪🇸',
        ]);

        Livewire::actingAs($this->admin)
            ->test(ListVisaTypeRequirements::class)
            ->assertSee('Spain')
            ->assertSee('ES')
            ->assertSee('1. Countries');
    }

    public function test_admin_can_add_a_new_country(): void
    {
        Livewire::actingAs($this->admin)
            ->test(ListVisaTypeRequirements::class)
            ->set('newCountryName', 'Switzerland')
            ->set('newCountryCode', 'CH')
            ->call('saveCountry')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('countries', [
            'name' => 'Switzerland',
            'code' => 'CH',
        ]);
    }

    public function test_selecting_country_shows_its_visa_types_at_level_two(): void
    {
        $spain = Country::create([
            'name' => 'Spain',
            'code' => 'ES',
            'flag' => '🇪🇸',
        ]);

        $tourist = CountryVisaType::create([
            'country_id' => $spain->id,
            'name' => 'Tourist',
            'display_name' => 'Tourist Visa',
        ]);

        Livewire::actingAs($this->admin)
            ->test(ListVisaTypeRequirements::class)
            ->call('selectCountry', $spain->id)
            ->assertSet('country_id', $spain->id)
            ->assertSee('Tourist Visa')
            ->assertSee('Back to Countries');
    }

    public function test_admin_can_add_visa_type_under_country(): void
    {
        $spain = Country::create([
            'name' => 'Spain',
            'code' => 'ES',
            'flag' => '🇪🇸',
        ]);

        Livewire::actingAs($this->admin)
            ->test(ListVisaTypeRequirements::class, ['country_id' => $spain->id])
            ->set('newVisaTypePreset', 'Student')
            ->call('saveVisaType')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('country_visa_types', [
            'country_id' => $spain->id,
            'name' => 'Student',
        ]);
    }

    public function test_selecting_visa_type_shows_checklist_at_level_three(): void
    {
        $spain = Country::create([
            'name' => 'Spain',
            'code' => 'ES',
            'flag' => '🇪🇸',
        ]);

        $tourist = CountryVisaType::create([
            'country_id' => $spain->id,
            'name' => 'Tourist',
            'display_name' => 'Tourist Visa',
        ]);

        $cat = DocumentCategory::create([
            'name' => 'Valid Passport',
            'slug' => 'passport',
            'is_predefined' => true,
        ]);

        VisaTypeRequirement::create([
            'country_visa_type_id' => $tourist->id,
            'country' => 'Spain',
            'visa_type' => 'Tourist',
            'document_category_id' => $cat->id,
            'is_mandatory' => true,
        ]);

        Livewire::actingAs($this->admin)
            ->test(ListVisaTypeRequirements::class, [
                'country_id' => $spain->id,
                'visa_type_id' => $tourist->id,
            ])
            ->assertSee('Active Required Documents')
            ->assertSee('Valid Passport')
            ->assertSee('Mandatory');
    }

    public function test_admin_can_toggle_requirement_and_mandatory_status(): void
    {
        $spain = Country::create([
            'name' => 'Spain',
            'code' => 'ES',
            'flag' => '🇪🇸',
        ]);

        $tourist = CountryVisaType::create([
            'country_id' => $spain->id,
            'name' => 'Tourist',
            'display_name' => 'Tourist Visa',
        ]);

        $cat = DocumentCategory::create([
            'name' => 'Bank Statement',
            'slug' => 'bank-statement',
            'is_predefined' => true,
        ]);

        // Toggle on
        $test = Livewire::actingAs($this->admin)
            ->test(ListVisaTypeRequirements::class, [
                'country_id' => $spain->id,
                'visa_type_id' => $tourist->id,
            ])
            ->call('toggleRequirement', $cat->id);

        $this->assertDatabaseHas('visa_type_requirements', [
            'country_visa_type_id' => $tourist->id,
            'document_category_id' => $cat->id,
            'is_mandatory' => true,
        ]);

        $req = VisaTypeRequirement::where('country_visa_type_id', $tourist->id)
            ->where('document_category_id', $cat->id)
            ->first();

        // Toggle mandatory to optional
        $test->call('toggleMandatory', $req->id);

        $this->assertDatabaseHas('visa_type_requirements', [
            'id' => $req->id,
            'is_mandatory' => false,
        ]);

        // Toggle off
        $test->call('toggleRequirement', $cat->id);

        $this->assertDatabaseMissing('visa_type_requirements', [
            'id' => $req->id,
        ]);
    }
}
