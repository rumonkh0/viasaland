<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
use App\Models\VisaTypeRequirement;
use App\Services\VaultService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class VaultTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
    }

    public function test_client_can_view_dashboard_with_applications(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Japan',
            'status' => 'under_review',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Tourist Visa — Japan');
        $response->assertSee($user->email);
    }

    public function test_client_can_upload_multiple_files_at_once_to_folder(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Schengen Area',
        ]);

        $bankCat = DocumentCategory::create([
            'name' => 'Bank Statement',
            'slug' => 'bank-statement',
            'is_predefined' => true,
        ]);

        $fileJan = UploadedFile::fake()->create('bank_statement_jan.pdf', 300, 'application/pdf');
        $fileFeb = UploadedFile::fake()->create('bank_statement_feb.pdf', 350, 'application/pdf');

        $response = $this->actingAs($user)->post("/applications/{$app->id}/documents", [
            'documents' => [$fileJan, $fileFeb],
            'document_category_id' => $bankCat->id,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertSame(2, Document::where('application_id', $app->id)->where('document_category_id', $bankCat->id)->count());
        $this->assertDatabaseHas('documents', ['file_name' => 'bank_statement_jan.pdf']);
        $this->assertDatabaseHas('documents', ['file_name' => 'bank_statement_feb.pdf']);
    }

    public function test_client_can_upload_multiple_documents_under_same_category(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Work',
            'target_country' => 'Canada',
        ]);

        $category = DocumentCategory::create([
            'name' => 'Medical Reports',
            'slug' => 'medical-reports',
            'is_predefined' => true,
        ]);

        // Upload first report
        $file1 = UploadedFile::fake()->create('blood_test.pdf', 300, 'application/pdf');
        $this->actingAs($user)->post("/applications/{$app->id}/documents", [
            'document' => $file1,
            'document_category_id' => $category->id,
        ]);

        // Upload second report under same category
        $file2 = UploadedFile::fake()->create('xray_scan.jpg', 400, 'image/jpeg');
        $this->actingAs($user)->post("/applications/{$app->id}/documents", [
            'document' => $file2,
            'document_category_id' => $category->id,
        ]);

        $this->assertSame(2, Document::where('application_id', $app->id)->where('document_category_id', $category->id)->count());
    }

    public function test_client_can_upload_with_custom_category(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'UK',
        ]);

        $file = UploadedFile::fake()->create('deed.pdf', 200, 'application/pdf');

        $response = $this->actingAs($user)->post("/applications/{$app->id}/documents", [
            'document' => $file,
            'custom_category' => 'Property Deeds',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('documents', [
            'application_id' => $app->id,
            'custom_category' => 'Property Deeds',
            'file_name' => 'deed.pdf',
        ]);
    }

    public function test_client_can_download_their_document(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'France',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('photo.jpg', 150, 'image/jpeg');
        $doc = $vaultService->uploadDocument($app, $file, $user, null, 'Photograph');

        $response = $this->actingAs($user)->get("/documents/{$doc->id}/download");

        $response->assertOk();
    }

    public function test_client_cannot_download_other_user_document(): void
    {
        $owner = User::factory()->create(['role' => 'client']);
        $otherUser = User::factory()->create(['role' => 'client']);

        $app = Application::create([
            'user_id' => $owner->id,
            'visa_type' => 'Tourist',
            'target_country' => 'France',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('confidential.pdf', 150, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $owner, null, 'Tax');

        $response = $this->actingAs($otherUser)->get("/documents/{$doc->id}/download");

        $response->assertForbidden();
    }

    public function test_client_can_view_their_document_inline(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'France',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('sample.pdf', 150, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $user, null, 'Passport');

        $response = $this->actingAs($user)->get("/documents/{$doc->id}/view");

        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'inline; filename="sample.pdf"');
    }

    public function test_client_cannot_view_other_user_document(): void
    {
        $owner = User::factory()->create(['role' => 'client']);
        $otherUser = User::factory()->create(['role' => 'client']);

        $app = Application::create([
            'user_id' => $owner->id,
            'visa_type' => 'Tourist',
            'target_country' => 'France',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('secret.pdf', 150, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $owner, null, 'Passport');

        $response = $this->actingAs($otherUser)->get("/documents/{$doc->id}/view");

        $response->assertForbidden();
    }

    public function test_admin_can_view_any_client_document(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $app = Application::create([
            'user_id' => $client->id,
            'visa_type' => 'Tourist',
            'target_country' => 'France',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('client_passport.pdf', 150, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $client, null, 'Passport');

        $response = $this->actingAs($admin)->get("/documents/{$doc->id}/view");

        $response->assertOk();
        $response->assertHeader('Content-Disposition', 'inline; filename="client_passport.pdf"');
    }

    public function test_client_can_replace_unlocked_document_creating_version(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'France',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('bank_v1.pdf', 100, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $user, null, 'Bank Statement');

        $newFile = UploadedFile::fake()->create('bank_v2.pdf', 200, 'application/pdf');

        $response = $this->actingAs($user)->post("/documents/{$doc->id}/replace", [
            'document' => $newFile,
        ]);

        $response->assertSessionHasNoErrors();
        $doc->refresh();

        $this->assertSame(2, $doc->version);
        $this->assertSame('bank_v2.pdf', $doc->file_name);
        $this->assertDatabaseHas('document_versions', [
            'document_id' => $doc->id,
            'version' => 1,
            'file_name' => 'bank_v1.pdf',
        ]);
    }

    public function test_client_can_delete_unlocked_document(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Germany',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('hotel.pdf', 100, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $user, null, 'Hotel');

        $response = $this->actingAs($user)->delete("/documents/{$doc->id}");

        $response->assertRedirect();
        $this->assertSoftDeleted('documents', ['id' => $doc->id]);
    }

    public function test_client_cannot_delete_locked_document(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);

        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Germany',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('passport_locked.pdf', 100, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $user, null, 'Passport');

        // Admin locks document
        $vaultService->lockDocument($doc, $admin);

        // Client attempts to delete
        $response = $this->actingAs($user)->delete("/documents/{$doc->id}");

        $response->assertForbidden();
        $this->assertDatabaseHas('documents', [
            'id' => $doc->id,
            'is_locked' => true,
            'deleted_at' => null,
        ]);
    }

    public function test_client_cannot_replace_locked_document(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);

        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Germany',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('passport_locked.pdf', 100, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $user, null, 'Passport');

        $vaultService->lockDocument($doc, $admin);

        $newFile = UploadedFile::fake()->create('hacked.pdf', 100, 'application/pdf');
        $response = $this->actingAs($user)->post("/documents/{$doc->id}/replace", [
            'document' => $newFile,
        ]);

        $response->assertForbidden();
    }

    public function test_admin_can_lock_and_unlock_document(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $admin = User::factory()->create(['role' => 'admin']);

        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Spain',
        ]);

        $vaultService = app(VaultService::class);
        $file = UploadedFile::fake()->create('visa_form.pdf', 100, 'application/pdf');
        $doc = $vaultService->uploadDocument($app, $file, $user, null, 'Form');

        $vaultService->lockDocument($doc, $admin);
        $doc->refresh();
        $this->assertTrue($doc->isLocked());
        $this->assertSame($admin->id, $doc->locked_by);

        $vaultService->unlockDocument($doc, $admin);
        $doc->refresh();
        $this->assertFalse($doc->isLocked());
    }

    public function test_user_can_download_vault_zip(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Italy',
        ]);

        // Put a real file in storage for the zip to read
        Storage::disk('local')->put("private/vaults/user_{$user->id}/application_{$app->id}/doc1.pdf", 'file content');

        Document::create([
            'user_id' => $user->id,
            'application_id' => $app->id,
            'custom_category' => 'Test Cat',
            'file_path' => "private/vaults/user_{$user->id}/application_{$app->id}/doc1.pdf",
            'file_name' => 'doc1.pdf',
            'file_size' => 12,
            'mime_type' => 'application/pdf',
            'status' => 'approved',
            'uploaded_by' => $user->id,
        ]);

        $response = $this->actingAs($user)->get("/applications/{$app->id}/download-zip");

        $response->assertOk();
    }

    public function test_admin_can_access_filament_vault_files_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/documents');

        $response->assertOk();
    }

    public function test_document_and_application_policy_checks(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $this->assertTrue($admin->can('viewAny', Document::class));
        $this->assertTrue($admin->can('create', Document::class));
        $this->assertTrue($admin->can('viewAny', Application::class));
        $this->assertTrue($admin->can('deleteAny', Application::class));

        $this->assertFalse($client->can('viewAny', Document::class));
        $this->assertFalse($client->can('viewAny', Application::class));
    }

    public function test_landing_page_renders_visa_requirements_data(): void
    {
        $cat = DocumentCategory::create([
            'name' => 'Valid Passport',
            'slug' => 'passport',
            'is_predefined' => true,
        ]);

        VisaTypeRequirement::create([
            'visa_type' => 'Tourist',
            'document_category_id' => $cat->id,
            'is_mandatory' => true,
        ]);

        $response = $this->get('/');
        $response->assertOk();
        $response->assertSee('Valid Passport');
    }

    public function test_client_can_apply_and_upload_files_from_landing_page(): void
    {
        $cat = DocumentCategory::create([
            'name' => 'Valid Passport',
            'slug' => 'passport',
            'is_predefined' => true,
        ]);

        VisaTypeRequirement::create([
            'visa_type' => 'Tourist',
            'document_category_id' => $cat->id,
            'is_mandatory' => true,
        ]);

        $passportFile = UploadedFile::fake()->create('my_passport.pdf', 300, 'application/pdf');

        $response = $this->post('/apply', [
            'name' => 'John Traveler',
            'email' => 'john.traveler@example.com',
            'phone' => '+1234567890',
            'visa_type' => 'Tourist',
            'country' => 'France',
            'notes' => 'Looking for tourist visa for holiday',
            'documents' => [$passportFile],
        ]);

        $user = User::where('email', 'john.traveler@example.com')->first();
        $this->assertNotNull($user);

        $application = Application::where('user_id', $user->id)->first();
        $this->assertNotNull($application);
        $this->assertEquals('Tourist', $application->visa_type);
        $this->assertEquals('France', $application->target_country);

        $response->assertRedirect(route('dashboard', ['app' => $application->id, 'tab' => 'vault']));
        $this->assertAuthenticated();

        // Check document stored in vault
        $document = Document::where('application_id', $application->id)->first();
        $this->assertNotNull($document);
        $this->assertEquals('my_passport.pdf', $document->file_name);
        Storage::disk('local')->assertExists($document->file_path);
    }

    public function test_client_can_upload_custom_category_document(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Canada',
        ]);

        $file = UploadedFile::fake()->create('custom_letter.pdf', 200, 'application/pdf');

        $response = $this->actingAs($user)->post("/applications/{$app->id}/documents", [
            'document_category_id' => 'custom',
            'custom_category' => 'Invitation Letter',
            'file' => $file,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $document = Document::where('application_id', $app->id)
            ->where('custom_category', 'Invitation Letter')
            ->first();

        $this->assertNotNull($document);
        $this->assertNull($document->document_category_id);
        Storage::disk('local')->assertExists($document->file_path);
    }

    public function test_dashboard_displays_requirements_checklist(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $cat = DocumentCategory::create([
            'name' => 'National ID / NID',
            'slug' => 'national-id',
            'is_predefined' => true,
        ]);

        VisaTypeRequirement::create([
            'visa_type' => 'Student',
            'document_category_id' => $cat->id,
            'is_mandatory' => true,
        ]);

        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Student',
            'target_country' => 'Germany',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Visa Document Requirements Checklist');
        $response->assertSee('National ID / NID');
    }

    public function test_visa_requirements_can_be_country_specific(): void
    {
        $passportCat = DocumentCategory::create(['name' => 'Passport', 'slug' => 'passport', 'is_predefined' => true]);
        $nidCat = DocumentCategory::create(['name' => 'National ID', 'slug' => 'nid', 'is_predefined' => true]);
        $insuranceCat = DocumentCategory::create(['name' => 'Travel Insurance', 'slug' => 'travel-insurance', 'is_predefined' => true]);
        $eduCat = DocumentCategory::create(['name' => 'Educational Certificates', 'slug' => 'educational-certificates', 'is_predefined' => true]);

        // India Tourist Visa -> Passport & National ID
        VisaTypeRequirement::create([
            'country' => 'India',
            'visa_type' => 'Tourist',
            'document_category_id' => $passportCat->id,
            'is_mandatory' => true,
        ]);
        VisaTypeRequirement::create([
            'country' => 'India',
            'visa_type' => 'Tourist',
            'document_category_id' => $nidCat->id,
            'is_mandatory' => true,
        ]);

        // Spain Student Visa -> Passport & Travel Insurance & Educational Certificates
        VisaTypeRequirement::create([
            'country' => 'Spain',
            'visa_type' => 'Student',
            'document_category_id' => $passportCat->id,
            'is_mandatory' => true,
        ]);
        VisaTypeRequirement::create([
            'country' => 'Spain',
            'visa_type' => 'Student',
            'document_category_id' => $insuranceCat->id,
            'is_mandatory' => true,
        ]);
        VisaTypeRequirement::create([
            'country' => 'Spain',
            'visa_type' => 'Student',
            'document_category_id' => $eduCat->id,
            'is_mandatory' => true,
        ]);

        $user = User::factory()->create(['role' => 'client']);

        // Test India Application
        $indiaApp = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'India',
        ]);
        $indiaApp->syncRequirementsFromVisaType();

        $this->assertEquals(2, $indiaApp->requirements()->count());
        $indiaCatIds = $indiaApp->requirements()->pluck('document_category_id')->toArray();
        $this->assertContains($passportCat->id, $indiaCatIds);
        $this->assertContains($nidCat->id, $indiaCatIds);
        $this->assertNotContains($insuranceCat->id, $indiaCatIds);

        // Test Spain Application
        $spainApp = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Student',
            'target_country' => 'Spain',
        ]);
        $spainApp->syncRequirementsFromVisaType();

        $this->assertEquals(3, $spainApp->requirements()->count());
        $spainCatIds = $spainApp->requirements()->pluck('document_category_id')->toArray();
        $this->assertContains($passportCat->id, $spainCatIds);
        $this->assertContains($insuranceCat->id, $spainCatIds);
        $this->assertContains($eduCat->id, $spainCatIds);
        $this->assertNotContains($nidCat->id, $spainCatIds);
    }

    public function test_every_application_vault_always_has_others_folder(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Spain',
        ]);

        $folders = $app->getVaultFolders();

        $this->assertTrue($folders->contains(function ($f) {
            return $f['name'] === 'Others';
        }));

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Others');
        $response->assertSee('Others • General Vault');
    }

    public function test_client_sees_all_their_applications_on_dashboard_directory(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app1 = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'India',
            'status' => 'draft',
        ]);
        $app2 = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Business',
            'target_country' => 'Spain',
            'status' => 'approved',
        ]);

        $response = $this->actingAs($user)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('My Applications (2)');
        $response->assertSee('Tourist Visa');
        $response->assertSee('India');
        $response->assertSee('Business Visa');
        $response->assertSee('Spain');
        $response->assertSee('Open Vault');
    }

    public function test_client_can_apply_for_new_visa_and_redirects_to_new_vault(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($user)->post(route('applications.storeLead'), [
            'fullName' => $user->name,
            'phone' => '+1234567890',
            'email' => $user->email,
            'country' => 'Japan',
            'visaType' => 'Tourist',
            'message' => 'Applying for holiday trip',
        ]);

        $newApp = Application::where('user_id', $user->id)->where('target_country', 'Japan')->first();
        $this->assertNotNull($newApp);
        $this->assertEquals('Tourist', $newApp->visa_type);

        $response->assertRedirect(route('dashboard', ['app' => $newApp->id, 'tab' => 'vault']));
    }

    public function test_dashboard_only_shows_authenticated_user_personal_applications(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create(['role' => 'client']);

        $adminApp = Application::create([
            'user_id' => $admin->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Australia',
        ]);
        $clientApp = Application::create([
            'user_id' => $client->id,
            'visa_type' => 'Student',
            'target_country' => 'Canada',
        ]);

        // Dashboard is strictly personal for all users
        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertOk();
        $response->assertSee('Australia');
        $response->assertDontSee('Canada');
        $response->assertDontSee('ADMIN CONTROL PANEL');

        // Admin manages all applications via the Filament admin panel
        $adminPanelResponse = $this->actingAs($admin)->get('/admin/applications');
        $adminPanelResponse->assertOk();
    }
}
