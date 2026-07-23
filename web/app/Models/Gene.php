<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Gene extends Model
{
    use HasFactory;

    protected $fillable = [
        'ensembl_id',
        'hgnc_symbol',
        'chromosome',
        'start_position',
        'end_position',
        'strand',
    ];

    protected $casts = [
        'start_position' => 'integer',
        'end_position' => 'integer',
    ];

    /**
     * All expression data points recorded for this gene across samples.
     */
    public function expressionMeasurements(): HasMany
    {
        return $this->hasMany(ExpressionMeasurement::class);
    }

    /**
     * All genomic variants mapped to this gene.
     */
    public function genomicVariants(): HasMany
    {
        return $this->hasMany(GenomicVariant::class);
    }
}
