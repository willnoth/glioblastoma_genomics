<?php

namespace Database\Factories;

use App\Models\ExpressionMeasurement;
use App\Models\Gene;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpressionMeasurementFactory extends Factory
{
    protected $model = ExpressionMeasurement::class;

    public function definition(): array
    {
        $rawCount = $this->faker->numberBetween(0, 50000);

        return [
            'sample_id' => Sample::factory(),
            'gene_id' => Gene::factory(),
            'raw_count' => $rawCount,
            'tpm' => round($rawCount / $this->faker->numberBetween(100, 500), 4),
            'fpkm' => round($rawCount / $this->faker->numberBetween(150, 600), 4),
            'pipeline_run_id' => 'RUN-' . date('Ymd') . '-01',
        ];
    }
}
