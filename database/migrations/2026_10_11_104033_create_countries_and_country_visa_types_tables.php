<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('countries')) {
            Schema::create('countries', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('slug')->unique();
                $table->string('code', 10)->nullable();
                $table->string('flag', 20)->nullable();
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('country_visa_types')) {
            Schema::create('country_visa_types', function (Blueprint $table) {
                $table->id();
                $table->foreignId('country_id')->constrained('countries')->cascadeOnDelete();
                $table->string('name');
                $table->string('display_name')->nullable();
                $table->string('slug');
                $table->integer('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['country_id', 'name']);
            });
        }

        if (Schema::hasTable('visa_type_requirements')) {
            Schema::table('visa_type_requirements', function (Blueprint $table) {
                if (! Schema::hasColumn('visa_type_requirements', 'country_visa_type_id')) {
                    $table->foreignId('country_visa_type_id')->nullable()->after('id')->constrained('country_visa_types')->cascadeOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('visa_type_requirements') && Schema::hasColumn('visa_type_requirements', 'country_visa_type_id')) {
            Schema::table('visa_type_requirements', function (Blueprint $table) {
                $table->dropForeign(['country_visa_type_id']);
                $table->dropColumn('country_visa_type_id');
            });
        }

        Schema::dropIfExists('country_visa_types');
        Schema::dropIfExists('countries');
    }
};
