<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile - {{ $patient->patient_code }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 p-8">
    <div class="max-w-6xl mx-auto space-y-6">

        <!-- Header / Clinical Overview -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-sky-400">{{ $patient->patient_code }}</h1>
                    <p class="text-slate-400 text-sm mt-1">Glioblastoma Patient Profile</p>
                </div>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $patient->vital_status === 'Deceased' ? 'bg-red-900/50 text-red-300 border border-red-700' : 'bg-emerald-900/50 text-emerald-300 border border-emerald-700' }}">
                    {{ $patient->vital_status ?? 'Unknown' }}
                </span>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6 border-t border-slate-700/60 pt-4">
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wider block">Age at Diagnosis</span>
                    <span class="text-lg font-semibold">{{ $patient->age_at_diagnosis ?? 'N/A' }} yrs</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wider block">Gender</span>
                    <span class="text-lg font-semibold">{{ $patient->gender ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wider block">IDH1 Status</span>
                    <span class="text-lg font-semibold text-amber-400">
                        {{ $patient->clinical_metadata['idh1_status'] ?? 'N/A' }}
                    </span>
                </div>
                <div>
                    <span class="text-xs text-slate-400 uppercase tracking-wider block">MGMT Promoter</span>
                    <span class="text-lg font-semibold text-purple-400">
                        {{ $patient->clinical_metadata['mgmt_promoter_status'] ?? 'N/A' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Samples Section -->
        <div class="space-y-4">
            <h2 class="text-xl font-semibold text-slate-200">Biospecimen Samples ({{ $patient->samples->count() }})</h2>

            @foreach($patient->samples as $sample)
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-md space-y-4">
                    <div class="flex justify-between items-center border-b border-slate-700/60 pb-3">
                        <div>
                            <span class="text-lg font-bold text-slate-100">{{ $sample->sample_code }}</span>
                            <span class="ml-3 text-xs bg-slate-700 px-2.5 py-1 rounded text-slate-300">
                                {{ $sample->sample_type }}
                            </span>
                        </div>
                        <span class="text-xs text-slate-400">Platform: {{ $sample->sequencing_platform }}</span>
                    </div>

                    <!-- Variant Table -->
                    <div>
                        <h3 class="text-sm font-semibold text-slate-300 mb-3">Detected Genomic Variants</h3>
                        @if($sample->genomicVariants->isEmpty())
                            <p class="text-sm text-slate-500 italic">No variants recorded for this sample.</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-slate-900/60 text-slate-400 text-xs uppercase">
                                        <tr>
                                            <th class="p-2.5">Gene</th>
                                            <th class="p-2.5">Location</th>
                                            <th class="p-2.5">Ref / Alt</th>
                                            <th class="p-2.5">Classification</th>
                                            <th class="p-2.5 text-right">Allele Freq</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-700/40">
                                        @foreach($sample->genomicVariants as $variant)
                                            <tr class="hover:bg-slate-750">
                                                <td class="p-2.5 font-bold text-sky-400">
                                                    {{ $variant->gene->hgnc_symbol ?? 'N/A' }}
                                                </td>
                                                <td class="p-2.5 font-mono text-xs text-slate-300">
                                                    chr{{ $variant->chromosome }}:{{ number_format($variant->position) }}
                                                </td>
                                                <td class="p-2.5 font-mono text-xs">
                                                    <span class="text-red-400">{{ $variant->reference_allele }}</span> &rarr; 
                                                    <span class="text-emerald-400">{{ $variant->alternate_allele }}</span>
                                                </td>
                                                <td class="p-2.5 text-slate-300">
                                                    {{ $variant->variant_classification ?? $variant->variant_type }}
                                                </td>
                                                <td class="p-2.5 text-right font-mono text-xs text-slate-300">
                                                    {{ number_format(($variant->allele_frequency ?? 0) * 100, 1) }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</body>
</html>
