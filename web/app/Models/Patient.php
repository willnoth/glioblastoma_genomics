<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_code',
        'age_at_diagnosis',
        'gender',
        'vital_status',
        'days_to_death',
        'clinical_metadata',
    ];

    protected $casts = [
        'clinical_metadata' => 'array', // Automatically handles Postgres JSONB <-> PHP Array
        'age_at_diagnosis' => 'integer',
        'days_to_death' => 'integer',
    ];

    /**
     * A patient has many biospecimen samples.
     */
    public function samples(): HasMany
    {
        return $this->hasMany(Sample::class);
    }

    /**
     * Direct access to all expression measurements for this patient across all samples.
     */
    public function expressionMeasurements(): HasManyThrough
    {
        return $this->hasManyThrough(ExpressionMeasurement::class, Sample::class);
    }

    /**
     * Direct access to all genomic variants/mutations for this patient.
     */
    public function genomicVariants(): HasManyThrough
    {
        return $this->hasManyThrough(GenomicVariant::class, Sample::class);
    }
}
