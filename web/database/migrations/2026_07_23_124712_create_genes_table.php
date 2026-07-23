<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('genes', function (Blueprint $table) {
            $table->id();
            $table->string('ensembl_id')->unique(); // e.g., "ENSG00000171862"
            $table->string('hgnc_symbol')->index(); // e.g., "PTEN", "EGFR"
            $table->string('chromosome', 10);
            $table->unsignedBigInteger('start_position');
            $table->unsignedBigInteger('end_position');
            $table->string('strand', 1)->default('+');
            $table->timestamps();
            
            $table->index(['chromosome', 'start_position', 'end_position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('genes');
    }
};
