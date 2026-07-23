<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('genomic_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sample_id')->constrained()->cascadeOnDelete();
            $table->foreignId('gene_id')->nullable()->constrained()->nullOnDelete();
            $table->string('chromosome', 10);
            $table->unsignedBigInteger('position');
            $table->string('reference_allele');
            $table->string('alternate_allele');
            $table->string('variant_type')->nullable(); // SNP, Insertion, Deletion
            $table->string('variant_classification')->nullable(); // Missense_Mutation, Frame_Shift_Del
            $table->double('allele_frequency')->nullable();
            $table->timestamps();

            $table->index(['sample_id', 'chromosome', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genomic_variants');
    }
};
