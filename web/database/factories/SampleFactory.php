<?php

namespace Database\Factories;

use App\Models\Patient;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Factories\Factory;

class SampleFactory extends Factory
{
    protected $model = Sample::class;

    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'sample_code' => 'TCGA-' . $this->faker->unique()->numerify('##-####-01A'),
            'sample_type' => $this->faker->randomElement(['Primary Tumor', 'Recurrent Tumor']),
            'sequencing_platform' => 'Illumina HiSeq 2500',
        ];
    }
}
