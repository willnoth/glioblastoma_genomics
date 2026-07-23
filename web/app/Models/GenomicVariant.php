<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GenomicVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'gene_id',
        'chromosome',
        'position',
        'reference_allele',
        'alternate_allele',
        'variant_type',
        'variant_classification',
        'allele_frequency',
    ];

    protected $casts = [
        'position' => 'integer',
        'allele_frequency' => 'float',
    ];

    /**
     * Get the biospecimen sample where this mutation was detected.
     */
    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    /**
     * Get the annotated gene associated with this variant.
     */
    public function gene(): BelongsTo
    {
        return $this->belongsTo(Gene::class);
    }
}
