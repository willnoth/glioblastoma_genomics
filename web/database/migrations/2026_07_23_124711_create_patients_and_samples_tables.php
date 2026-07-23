<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_code')->unique(); // e.g., "TCGA-06-0125"
            $table->integer('age_at_diagnosis')->nullable();
            $table->string('gender')->nullable();
            $table->string('vital_status')->nullable(); // Living, Deceased
            $table->integer('days_to_death')->nullable();
            $table->jsonb('clinical_metadata')->nullable(); // Extensible clinical traits
            $table->timestamps();
        });

        Schema::create('samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->cascadeOnDelete();
            $table->string('sample_code')->unique(); // e.g., "TCGA-06-0125-01A"
            $table->string('sample_type'); // Primary Tumor, Recurrent Tumor, Normal
            $table->string('sequencing_platform')->default('Illumina');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('samples');
        Schema::dropIfExists('patients');
    }
};
