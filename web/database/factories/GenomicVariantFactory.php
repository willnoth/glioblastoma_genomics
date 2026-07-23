<?php

namespace Database\Factories;

use App\Models\Gene;
use App\Models\GenomicVariant;
use App\Models\Sample;
use Illuminate\Database\Eloquent\Factories\Factory;

class GenomicVariantFactory extends Factory
{
    protected $model = GenomicVariant::class;

    public function definition(): array
    {
        return [
            'sample_id' => Sample::factory(),
            'gene_id' => Gene::factory(),
            'chromosome' => (string) $this->faker->numberBetween(1, 22),
            'position' => $this->faker->numberBetween(100000, 150000000),
            'reference_allele' => $this->faker->randomElement(['A', 'C', 'G', 'T']),
            'alternate_allele' => $this->faker->randomElement(['A', 'C', 'G', 'T']),
            'variant_type' => $this->faker->randomElement(['SNP', 'Insertion', 'Deletion']),
            'variant_classification' => $this->faker->randomElement(['Missense_Mutation', 'Nonsense_Mutation', 'Frame_Shift_Del', 'Silent']),
            'allele_frequency' => round($this->faker->randomFloat(4, 0.01, 0.99), 4),
        ];
    }
}
