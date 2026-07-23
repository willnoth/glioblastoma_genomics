<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('expression_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gene_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('raw_count')->nullable();
            $table->double('tpm')->nullable();  // Transcripts Per Million
            $table->double('fpkm')->nullable(); // Fragments Per Kilobase Million
            $table->string('pipeline_run_id')->nullable()->index(); // Track Python script run
            $table->timestamps();

            // Compound index for ultra-fast query lookups by sample & gene
            $table->unique(['sample_id', 'gene_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expression_measurements');
    }
};
