<?php

namespace Database\Seeders;

use App\Models\ExpressionMeasurement;
use App\Models\Gene;
use App\Models\GenomicVariant;
use App\Models\Patient;
use App\Models\Sample;
use Illuminate\Database\Seeder;

class GenomicDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed well-known Glioblastoma driver genes explicitly
        $keyGbmGenes = collect([
            ['hgnc_symbol' => 'EGFR',  'ensembl_id' => 'ENSG00000146648', 'chromosome' => '7',  'start_position' => 55019017, 'end_position' => 55211628],
            ['hgnc_symbol' => 'PTEN',  'ensembl_id' => 'ENSG00000171862', 'chromosome' => '10', 'start_position' => 87863113, 'end_position' => 87971383],
            ['hgnc_symbol' => 'TP53',  'ensembl_id' => 'ENSG00000141510', 'chromosome' => '17', 'start_position' => 7668402,  'end_position' => 7687550],
            ['hgnc_symbol' => 'IDH1',  'ensembl_id' => 'ENSG00000138413', 'chromosome' => '2',  'start_position' => 208236229,'end_position' => 208255073],
            ['hgnc_symbol' => 'MGMT',  'ensembl_id' => 'ENSG00000170430', 'chromosome' => '10', 'start_position' => 129467190,'end_position' => 129768045],
            ['hgnc_symbol' => 'RB1',   'ensembl_id' => 'ENSG00000139687', 'chromosome' => '13', 'start_position' => 48303722, 'end_position' => 48481811],
        ])->map(fn ($geneData) => Gene::firstOrCreate(['ensembl_id' => $geneData['ensembl_id']], $geneData));

        // Add 20 random reference genes
        $extraGenes = Gene::factory()->count(20)->create();
        $allGenes = $keyGbmGenes->concat($extraGenes);

        // 2. Create 15 Patients, each with 1-2 biospecimen samples
        Patient::factory()
            ->count(15)
            ->has(Sample::factory()->count(rand(1, 2)))
            ->create()
            ->each(function (Patient $patient) use ($allGenes) {
                foreach ($patient->samples as $sample) {

                    // Generate expression levels across all genes for this sample
                    foreach ($allGenes as $gene) {
                        ExpressionMeasurement::factory()->create([
                            'sample_id' => $sample->id,
                            'gene_id' => $gene->id,
                        ]);
                    }

                    // Generate 1 to 4 random mutations per sample
                    $mutatedGenes = $allGenes->random(rand(1, 4));
                    foreach ($mutatedGenes as $gene) {
                        GenomicVariant::factory()->create([
                            'sample_id' => $sample->id,
                            'gene_id' => $gene->id,
                            'chromosome' => $gene->chromosome,
                        ]);
                    }
                }
            });
    }
}
