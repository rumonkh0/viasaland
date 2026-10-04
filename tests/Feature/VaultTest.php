<?php

namespace Tests\Feature;

use App\Models\Application;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\User;
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
    }

    public function test_client_can_upload_document_to_application_vault(): void
    {
        $user = User::factory()->create(['role' => 'client']);
        $app = Application::create([
            'user_id' => $user->id,
            'visa_type' => 'Tourist',
            'target_country' => 'Japan',
        ]);

        $category = DocumentCategory::create([
            'name' => 'Passport',
            'slug' => 'passport',
            'is_predefined' => true,
        ]);

        $file = UploadedFile::fake()->create('my_passport.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)->post("/applications/{$app->id}/documents", [
            'document' => $file,
            'document_category_id' => $category->id,
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->assertDatabaseHas('documents', [
            'application_id' => $app->id,
            'user_id' => $user->id,
            'document_category_id' => $category->id,
            'file_name' => 'my_passport.pdf',
            'status' => 'pending',
            'is_locked' => false,
            'version' => 1,
        ]);
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
}
