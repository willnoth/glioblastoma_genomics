<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'sample_code',
        'sample_type',
        'sequencing_platform',
    ];

    /**
     * Get the patient that owns this sample.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get all RNA-Seq expression measurements for this sample.
     */
    public function expressionMeasurements(): HasMany
    {
        return $this->hasMany(ExpressionMeasurement::class);
    }

    /**
     * Get all DNA genomic variants associated with this sample.
     */
    public function genomicVariants(): HasMany
    {
        return $this->hasMany(GenomicVariant::class);
    }
}
