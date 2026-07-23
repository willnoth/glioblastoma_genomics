<?php

namespace Database\Factories;

use App\Models\Gene;
use Illuminate\Database\Eloquent\Factories\Factory;

class GeneFactory extends Factory
{
    protected $model = Gene::class;

    public function definition(): array
    {
        $chromosomes = array_merge(range(1, 22), ['X', 'Y']);
        $start = $this->faker->numberBetween(100000, 150000000);

        return [
            'ensembl_id' => 'ENSG' . $this->faker->unique()->numerify('###########'),
            'hgnc_symbol' => strtoupper($this->faker->unique()->bothify('GBM-???')),
            'chromosome' => (string) $this->faker->randomElement($chromosomes),
            'start_position' => $start,
            'end_position' => $start + $this->faker->numberBetween(1000, 250000),
            'strand' => $this->faker->randomElement(['+', '-']),
        ];
    }
}
