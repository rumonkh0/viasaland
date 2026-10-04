<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed categories
        $this->call(DocumentCategorySeeder::class);

        // 2. Seed visa requirements defaults
        $this->call(VisaTypeRequirementSeeder::class);

        // 3. Admin user
        User::firstOrCreate(
            ['email' => 'admin@viasaland.com'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1234567890',
                'email_verified_at' => now(),
            ]
        );

        // 4. Demo client user
        User::firstOrCreate(
            ['email' => 'client@viasaland.com'],
            [
                'name' => 'Demo Client',
                'password' => Hash::make('password'),
                'role' => 'client',
                'phone' => '+1987654321',
                'email_verified_at' => now(),
            ]
        );
    }
}
