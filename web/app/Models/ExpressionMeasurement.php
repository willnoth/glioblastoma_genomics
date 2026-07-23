<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpressionMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id',
        'gene_id',
        'raw_count',
        'tpm',
        'fpkm',
        'pipeline_run_id',
    ];

    protected $casts = [
        'raw_count' => 'integer',
        'tpm' => 'float',
        'fpkm' => 'float',
    ];

    /**
     * Get the sample this expression value belongs to.
     */
    public function sample(): BelongsTo
    {
        return $this->belongsTo(Sample::class);
    }

    /**
     * Get the reference gene for this measurement.
     */
    public function gene(): BelongsTo
    {
        return $this->belongsTo(Gene::class);
    }
}
