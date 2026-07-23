<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\GenomicVariant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a paginated list of patients with filtering options & mutation summary.
     */
    public function index(Request $request): View
    {
        $query = Patient::query()->withCount('samples');

        // Search & Filter rules
        if ($request->filled('search')) {
            $query->where('patient_code', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('vital_status')) {
            $query->where('vital_status', $request->vital_status);
        }

        if ($request->filled('idh1_status')) {
            $query->where('clinical_metadata->idh1_status', $request->idh1_status);
        }

        $patients = $query->orderBy('patient_code')->paginate(10)->withQueryString();

        // Query top mutated genes across all samples for Chart.js
        $geneMutationCounts = GenomicVariant::query()
            ->join('genes', 'genomic_variants.gene_id', '=', 'genes.id')
            ->select('genes.hgnc_symbol', DB::raw('count(distinct genomic_variants.sample_id) as mutated_sample_count'))
            ->groupBy('genes.hgnc_symbol')
            ->orderByDesc('mutated_sample_count')
            ->limit(10)
            ->get();

        $chartLabels = $geneMutationCounts->pluck('hgnc_symbol');
        $chartData = $geneMutationCounts->pluck('mutated_sample_count');

        return view('patients.index', compact('patients', 'chartLabels', 'chartData'));
    }

    /**
     * Return JSON for API consumers (or frontend JS frameworks).
     */
    public function showApi(string $patientCode): JsonResponse
    {
        $patient = Patient::where('patient_code', $patientCode)
            ->with(['samples.genomicVariants.gene', 'samples.expressionMeasurements.gene'])
            ->firstOrFail();

        return response()->json($patient);
    }

    /**
     * Render the Blade UI dashboard view.
     */
    public function showView(string $patientCode): View
    {
        $patient = Patient::where('patient_code', $patientCode)
            ->with([
                'samples' => function ($query) {
                    $query->withCount(['genomicVariants', 'expressionMeasurements']);
                },
                'samples.genomicVariants.gene',
            ])
            ->firstOrFail();

        return view('patients.show', compact('patient'));
    }
}
