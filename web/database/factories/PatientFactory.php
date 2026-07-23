<?php

namespace Database\Factories;

use App\Models\Patient;
use Illuminate\Database\Eloquent\Factories\Factory;

class PatientFactory extends Factory
{
    protected $model = Patient::class;

    public function definition(): array
    {
        $isDeceased = $this->faker->boolean(75); // ~75% mortality rate in test cohort

        return [
            'patient_code' => 'TCGA-' . $this->faker->unique()->numerify('##-####'),
            'age_at_diagnosis' => $this->faker->numberBetween(35, 82),
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'vital_status' => $isDeceased ? 'Deceased' : 'Living',
            'days_to_death' => $isDeceased ? $this->faker->numberBetween(60, 1200) : null,
            'clinical_metadata' => [
                'idh1_status' => $this->faker->randomElement(['Wildtype', 'Mutant']),
                'mgmt_promoter_status' => $this->faker->randomElement(['Methylated', 'Unmethylated']),
                'kps_score' => $this->faker->randomElement([60, 70, 80, 90, 100]),
            ],
        ];
    }
}
