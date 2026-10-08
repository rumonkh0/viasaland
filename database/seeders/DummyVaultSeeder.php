<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Document;
use App\Models\DocumentActivity;
use App\Models\DocumentCategory;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DummyVaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ensure Categories & Visa Type Requirements are seeded
        $this->call(DocumentCategorySeeder::class);
        $this->call(VisaTypeRequirementSeeder::class);

        // 2. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@viasaland.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1234567890',
                'email_verified_at' => now(),
            ]
        );

        // 3. Client Users
        $demoClient = User::firstOrCreate(
            ['email' => 'client@viasaland.com'],
            [
                'name' => 'Demo Client',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '+1987654321',
                'email_verified_at' => now(),
            ]
        );

        $sarah = User::firstOrCreate(
            ['email' => 'sarah.jenkins@example.com'],
            [
                'name' => 'Sarah Jenkins',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '+442079460912',
                'email_verified_at' => now(),
            ]
        );

        $ahmed = User::firstOrCreate(
            ['email' => 'ahmed.khan@example.com'],
            [
                'name' => 'Ahmed Khan',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '+971501234567',
                'email_verified_at' => now(),
            ]
        );

        $michael = User::firstOrCreate(
            ['email' => 'michael.brown@example.com'],
            [
                'name' => 'Michael Brown',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '+61298765432',
                'email_verified_at' => now(),
            ]
        );

        // Preload categories by slug
        $cats = DocumentCategory::pluck('id', 'slug');

        // Helper to generate a dummy PDF/Image file on disk
        $createDummyFile = function (int $userId, int $appId, string $filename, string $type = 'pdf') {
            $folder = "private/vaults/user_{$userId}/application_{$appId}";
            $path = "{$folder}/{$filename}";

            if ($type === 'pdf') {
                $content = "%PDF-1.4\n1 0 obj\n<< /Title ({$filename}) /Creator (Viasaland Vault) >>\nendobj\ntrailer\n<< /Root 1 0 R >>\n%%EOF";
            } else {
                // Minimal 1x1 GIF / image header bytes
                $content = "GIF89a\x01\x00\x01\x00\x80\x00\x00\xff\xff\xff\x00\x00\x00!\xf9\x04\x01\x00\x00\x00\x00,\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02D\x01\x00;";
            }

            Storage::disk('local')->put($path, $content);

            return [
                'path' => $path,
                'size' => strlen($content),
                'mime' => $type === 'pdf' ? 'application/pdf' : 'image/jpeg',
            ];
        };

        // =========================================================================
        // Application 1: Demo Client — Tourist Visa (Canada)
        // =========================================================================
        $app1 = Application::firstOrCreate(
            [
                'user_id' => $demoClient->id,
                'visa_type' => 'Tourist',
                'target_country' => 'Canada',
            ],
            [
                'status' => 'under_review',
                'notes' => 'Client submitted all core documents. Awaiting revised photograph.',
            ]
        );
        $app1->syncRequirementsFromVisaType();

        // Doc 1: Passport (Approved & Locked)
        if (! Document::where('application_id', $app1->id)->where('file_name', 'Passport_Bio_Page.pdf')->exists()) {
            $f = $createDummyFile($demoClient->id, $app1->id, 'Passport_Bio_Page.pdf', 'pdf');
            $doc = Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app1->id,
                'document_category_id' => $cats['passport'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Passport_Bio_Page.pdf',
                'file_size' => 524288,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now()->subDays(2),
                'uploaded_by' => $demoClient->id,
                'version' => 1,
            ]);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $demoClient->id, 'action' => 'uploaded', 'description' => 'Initial passport uploaded by client.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'approved', 'description' => 'Passport verified and approved by admin.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'locked', 'description' => 'Document locked to prevent user alteration.']);
        }

        // Doc 2: Bank Statement (Approved, Unlocked)
        if (! Document::where('application_id', $app1->id)->where('file_name', 'Bank_Statement_6M_Certified.pdf')->exists()) {
            $f = $createDummyFile($demoClient->id, $app1->id, 'Bank_Statement_6M_Certified.pdf', 'pdf');
            $doc = Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app1->id,
                'document_category_id' => $cats['bank-statement'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Bank_Statement_6M_Certified.pdf',
                'file_size' => 1048576,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => false,
                'uploaded_by' => $demoClient->id,
                'version' => 1,
            ]);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $demoClient->id, 'action' => 'uploaded', 'description' => 'Bank statement uploaded.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'approved', 'description' => 'Sufficient funds verified by admin.']);
        }

        // Doc 3: Medical Report 1 (Pending Review)
        if (! Document::where('application_id', $app1->id)->where('file_name', 'General_Medical_Checkup.pdf')->exists()) {
            $f = $createDummyFile($demoClient->id, $app1->id, 'General_Medical_Checkup.pdf', 'pdf');
            $doc = Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app1->id,
                'document_category_id' => $cats['medical-reports'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'General_Medical_Checkup.pdf',
                'file_size' => 450000,
                'mime_type' => $f['mime'],
                'status' => 'pending',
                'is_locked' => false,
                'uploaded_by' => $demoClient->id,
                'version' => 1,
            ]);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $demoClient->id, 'action' => 'uploaded', 'description' => 'Medical checkup uploaded.']);
        }

        // Doc 4: Medical Report 2 (Multiple files under same category!)
        if (! Document::where('application_id', $app1->id)->where('file_name', 'Chest_XRay_Clearance.pdf')->exists()) {
            $f = $createDummyFile($demoClient->id, $app1->id, 'Chest_XRay_Clearance.pdf', 'pdf');
            $doc = Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app1->id,
                'document_category_id' => $cats['medical-reports'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Chest_XRay_Clearance.pdf',
                'file_size' => 380000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => false,
                'uploaded_by' => $demoClient->id,
                'version' => 1,
            ]);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $demoClient->id, 'action' => 'uploaded', 'description' => 'Chest X-ray uploaded.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'approved', 'description' => 'Clearance verified.']);
        }

        // Doc 5: Photograph (Rejected with Admin Feedback)
        if (! Document::where('application_id', $app1->id)->where('file_name', 'Applicant_Photo_35x45.jpg')->exists()) {
            $f = $createDummyFile($demoClient->id, $app1->id, 'Applicant_Photo_35x45.jpg', 'jpg');
            $doc = Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app1->id,
                'document_category_id' => $cats['photograph'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Applicant_Photo_35x45.jpg',
                'file_size' => 150000,
                'mime_type' => $f['mime'],
                'status' => 'rejected',
                'admin_notes' => 'The photo background must be pure white with neutral expression and no glare on glasses. Please re-upload a compliant photo.',
                'is_locked' => false,
                'uploaded_by' => $demoClient->id,
                'version' => 1,
            ]);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $demoClient->id, 'action' => 'uploaded', 'description' => 'Photo uploaded.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'rejected', 'description' => 'Rejected due to incorrect background.']);
        }

        // Doc 6: Flight Itinerary (Version 2 with Version History!)
        if (! Document::where('application_id', $app1->id)->where('file_name', 'Confirmed_Flight_E_Ticket.pdf')->exists()) {
            // Archived version 1 file
            $v1Path = 'private/vaults/versions/doc_flight_v1.pdf';
            Storage::disk('local')->put($v1Path, "%PDF-1.4\nFlight Itinerary Draft v1\n%%EOF");

            // Current version 2 file
            $f = $createDummyFile($demoClient->id, $app1->id, 'Confirmed_Flight_E_Ticket.pdf', 'pdf');
            $doc = Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app1->id,
                'document_category_id' => $cats['flight-itinerary'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Confirmed_Flight_E_Ticket.pdf',
                'file_size' => 240000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now()->subDay(),
                'uploaded_by' => $demoClient->id,
                'version' => 2,
            ]);

            // Save version 1 to document_versions
            DocumentVersion::create([
                'document_id' => $doc->id,
                'version' => 1,
                'file_path' => $v1Path,
                'file_name' => 'Draft_Flight_Reservation.pdf',
                'file_size' => 180000,
                'mime_type' => 'application/pdf',
                'replaced_by' => $demoClient->id,
            ]);

            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $demoClient->id, 'action' => 'uploaded', 'description' => 'Draft reservation uploaded.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $demoClient->id, 'action' => 'replaced', 'description' => 'Replaced with confirmed e-ticket (v2).']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'approved', 'description' => 'Confirmed ticket verified.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'locked', 'description' => 'Locked final travel itinerary.']);
        }

        // Doc 7: Hotel Booking (Uploaded directly by Admin to client's vault!)
        if (! Document::where('application_id', $app1->id)->where('file_name', 'Sheraton_Toronto_Confirmation.pdf')->exists()) {
            $f = $createDummyFile($demoClient->id, $app1->id, 'Sheraton_Toronto_Confirmation.pdf', 'pdf');
            $doc = Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app1->id,
                'document_category_id' => $cats['hotel-booking'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Sheraton_Toronto_Confirmation.pdf',
                'file_size' => 310000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now(),
                'uploaded_by' => $admin->id,
                'admin_notes' => 'Booked and verified directly by your Adiba Global travel consultant.',
                'version' => 1,
            ]);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'uploaded', 'description' => 'Uploaded by consultant on client behalf.']);
            DocumentActivity::create(['document_id' => $doc->id, 'user_id' => $admin->id, 'action' => 'locked', 'description' => 'Locked by admin.']);
        }

        $app1->refreshRequirementStatuses();

        // =========================================================================
        // Application 2: Demo Client — Business Visa (Japan)
        // =========================================================================
        $app2 = Application::firstOrCreate(
            [
                'user_id' => $demoClient->id,
                'visa_type' => 'Business',
                'target_country' => 'Japan',
            ],
            [
                'status' => 'draft',
                'notes' => 'New business visa file created.',
            ]
        );
        $app2->syncRequirementsFromVisaType();

        if (! Document::where('application_id', $app2->id)->where('file_name', 'Tokyo_Conference_Invitation.pdf')->exists()) {
            $f = $createDummyFile($demoClient->id, $app2->id, 'Tokyo_Conference_Invitation.pdf', 'pdf');
            Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app2->id,
                'document_category_id' => $cats['invitation-letter'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Tokyo_Conference_Invitation.pdf',
                'file_size' => 290000,
                'mime_type' => $f['mime'],
                'status' => 'pending',
                'is_locked' => false,
                'uploaded_by' => $demoClient->id,
                'version' => 1,
            ]);
        }

        if (! Document::where('application_id', $app2->id)->where('file_name', 'Company_Trade_License_English.pdf')->exists()) {
            $f = $createDummyFile($demoClient->id, $app2->id, 'Company_Trade_License_English.pdf', 'pdf');
            Document::create([
                'user_id' => $demoClient->id,
                'application_id' => $app2->id,
                'document_category_id' => null,
                'custom_category' => 'Trade License (Notarized)',
                'file_path' => $f['path'],
                'file_name' => 'Company_Trade_License_English.pdf',
                'file_size' => 610000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now(),
                'uploaded_by' => $demoClient->id,
                'version' => 1,
            ]);
        }

        $app2->refreshRequirementStatuses();

        // =========================================================================
        // Application 3: Sarah Jenkins — Work Visa (United Kingdom)
        // =========================================================================
        $app3 = Application::firstOrCreate(
            [
                'user_id' => $sarah->id,
                'visa_type' => 'Work',
                'target_country' => 'United Kingdom',
            ],
            [
                'status' => 'verified',
                'notes' => 'All UK Skilled Worker requirements fulfilled. Ready for embassy appointment.',
            ]
        );
        $app3->syncRequirementsFromVisaType();

        if (! Document::where('application_id', $app3->id)->where('file_name', 'UK_Certificate_of_Sponsorship.pdf')->exists()) {
            $f = $createDummyFile($sarah->id, $app3->id, 'UK_Certificate_of_Sponsorship.pdf', 'pdf');
            Document::create([
                'user_id' => $sarah->id,
                'application_id' => $app3->id,
                'document_category_id' => $cats['sponsorship-letter'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'UK_Certificate_of_Sponsorship.pdf',
                'file_size' => 410000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now(),
                'uploaded_by' => $sarah->id,
                'version' => 1,
            ]);
        }

        if (! Document::where('application_id', $app3->id)->where('file_name', 'Employment_Contract_Signed.pdf')->exists()) {
            $f = $createDummyFile($sarah->id, $app3->id, 'Employment_Contract_Signed.pdf', 'pdf');
            Document::create([
                'user_id' => $sarah->id,
                'application_id' => $app3->id,
                'document_category_id' => $cats['employment-letter'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Employment_Contract_Signed.pdf',
                'file_size' => 850000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now(),
                'uploaded_by' => $sarah->id,
                'version' => 1,
            ]);
        }

        $app3->refreshRequirementStatuses();

        // =========================================================================
        // Application 4: Ahmed Khan — Student Visa (Germany)
        // =========================================================================
        $app4 = Application::firstOrCreate(
            [
                'user_id' => $ahmed->id,
                'visa_type' => 'Student',
                'target_country' => 'Germany',
            ],
            [
                'status' => 'submitted',
                'notes' => 'German blocked account confirmation submitted.',
            ]
        );
        $app4->syncRequirementsFromVisaType();

        if (! Document::where('application_id', $app4->id)->where('file_name', 'TU_Munich_Admission_Letter.pdf')->exists()) {
            $f = $createDummyFile($ahmed->id, $app4->id, 'TU_Munich_Admission_Letter.pdf', 'pdf');
            Document::create([
                'user_id' => $ahmed->id,
                'application_id' => $app4->id,
                'document_category_id' => $cats['educational-certificates'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'TU_Munich_Admission_Letter.pdf',
                'file_size' => 320000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now(),
                'uploaded_by' => $ahmed->id,
                'version' => 1,
            ]);
        }

        if (! Document::where('application_id', $app4->id)->where('file_name', 'Expatrio_Blocked_Account_Letter.pdf')->exists()) {
            $f = $createDummyFile($ahmed->id, $app4->id, 'Expatrio_Blocked_Account_Letter.pdf', 'pdf');
            Document::create([
                'user_id' => $ahmed->id,
                'application_id' => $app4->id,
                'document_category_id' => $cats['bank-statement'] ?? null,
                'file_path' => $f['path'],
                'file_name' => 'Expatrio_Blocked_Account_Letter.pdf',
                'file_size' => 490000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => false,
                'uploaded_by' => $ahmed->id,
                'version' => 1,
            ]);
        }

        $app4->refreshRequirementStatuses();

        // =========================================================================
        // Application 5: Michael Brown — Tourist Visa (Australia)
        // =========================================================================
        $app5 = Application::firstOrCreate(
            [
                'user_id' => $michael->id,
                'visa_type' => 'Tourist',
                'target_country' => 'Australia',
            ],
            [
                'status' => 'approved',
                'notes' => 'Visa grant notification received from Australian Department of Home Affairs.',
            ]
        );
        $app5->syncRequirementsFromVisaType();

        if (! Document::where('application_id', $app5->id)->where('file_name', 'Subclass_600_Visa_Grant_Letter.pdf')->exists()) {
            $f = $createDummyFile($michael->id, $app5->id, 'Subclass_600_Visa_Grant_Letter.pdf', 'pdf');
            Document::create([
                'user_id' => $michael->id,
                'application_id' => $app5->id,
                'document_category_id' => null,
                'custom_category' => 'Official Visa Grant Notice',
                'file_path' => $f['path'],
                'file_name' => 'Subclass_600_Visa_Grant_Letter.pdf',
                'file_size' => 190000,
                'mime_type' => $f['mime'],
                'status' => 'approved',
                'is_locked' => true,
                'locked_by' => $admin->id,
                'locked_at' => now(),
                'uploaded_by' => $admin->id,
                'version' => 1,
            ]);
        }

        $app5->refreshRequirementStatuses();
    }
}
