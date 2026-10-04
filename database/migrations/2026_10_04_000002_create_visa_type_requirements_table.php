<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visa_type_requirements', function (Blueprint $table) {
            $table->id();
            $table->string('visa_type');
            $table->foreignId('document_category_id')->constrained('document_categories')->cascadeOnDelete();
            $table->boolean('is_mandatory')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visa_type_requirements');
    }
};
