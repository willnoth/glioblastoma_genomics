<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Glioblastoma Patient Directory</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen p-8">
    <div class="max-w-7xl mx-auto space-y-6">

        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-sky-400">Cohort Explorer</h1>
                <p class="text-slate-400 text-sm mt-1">Glioblastoma clinical directory & variant analysis</p>
            </div>
            <div class="text-slate-400 text-sm">
                Showing <span class="font-semibold text-slate-200">{{ $patients->firstItem() ?? 0 }}</span> to 
                <span class="font-semibold text-slate-200">{{ $patients->lastItem() ?? 0 }}</span> of 
                <span class="font-semibold text-slate-200">{{ $patients->total() }}</span> patients
            </div>
        </div>

        <!-- Mutation Frequency Chart Card -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 shadow-lg">
            <h2 class="text-lg font-semibold text-slate-200 mb-4">Top Mutated Driver Genes Across Cohort</h2>
            <div class="relative h-64 w-full">
                <canvas id="mutationChart"></canvas>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-5 shadow-lg">
            <form method="GET" action="{{ route('patients.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                
                <!-- Search input -->
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1">Search ID</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="TCGA-..." 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-sky-500">
                </div>

                <!-- Gender filter -->
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1">Gender</label>
                    <select name="gender" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-sky-500">
                        <option value="">All Genders</option>
                        <option value="Male" {{ request('gender') === 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ request('gender') === 'Female' ? 'selected' : '' }}>Female</option>
                    </select>
                </div>

                <!-- Vital Status filter -->
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1">Vital Status</label>
                    <select name="vital_status" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-sky-500">
                        <option value="">All Statuses</option>
                        <option value="Living" {{ request('vital_status') === 'Living' ? 'selected' : '' }}>Living</option>
                        <option value="Deceased" {{ request('vital_status') === 'Deceased' ? 'selected' : '' }}>Deceased</option>
                    </select>
                </div>

                <!-- IDH1 Status filter -->
                <div>
                    <label class="block text-xs text-slate-400 uppercase tracking-wider mb-1">IDH1 Status</label>
                    <select name="idh1_status" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-100 focus:outline-none focus:border-sky-500">
                        <option value="">All Types</option>
                        <option value="Mutant" {{ request('idh1_status') === 'Mutant' ? 'selected' : '' }}>Mutant</option>
                        <option value="Wildtype" {{ request('idh1_status') === 'Wildtype' ? 'selected' : '' }}>Wildtype</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-sky-600 hover:bg-sky-500 text-white font-medium py-2 px-4 rounded-lg text-sm transition">
                        Filter
                    </button>
                    @if(request()->anyFilled(['search', 'gender', 'vital_status', 'idh1_status']))
                        <a href="{{ route('patients.index') }}" class="bg-slate-700 hover:bg-slate-600 text-slate-300 font-medium py-2 px-3 rounded-lg text-sm transition">
                            Reset
                        </a>
                    @endif
                </div>

            </form>
        </div>

        <!-- Patients Table -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden shadow-lg">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-900/80 text-slate-400 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-4">Patient Code</th>
                        <th class="p-4">Age</th>
                        <th class="p-4">Gender</th>
                        <th class="p-4">IDH1 Status</th>
                        <th class="p-4">MGMT Status</th>
                        <th class="p-4 text-center">Samples</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($patients as $patient)
                        <tr class="hover:bg-slate-750 transition">
                            <td class="p-4 font-bold text-sky-400 font-mono">
                                {{ $patient->patient_code }}
                            </td>
                            <td class="p-4 text-slate-200">
                                {{ $patient->age_at_diagnosis ?? '—' }} yrs
                            </td>
                            <td class="p-4 text-slate-300">
                                {{ $patient->gender ?? '—' }}
                            </td>
                            <td class="p-4 font-medium {{ ($patient->clinical_metadata['idh1_status'] ?? '') === 'Mutant' ? 'text-amber-400' : 'text-slate-400' }}">
                                {{ $patient->clinical_metadata['idh1_status'] ?? 'N/A' }}
                            </td>
                            <td class="p-4 font-medium text-purple-400">
                                {{ $patient->clinical_metadata['mgmt_promoter_status'] ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-center">
                                <span class="bg-slate-700 text-slate-200 text-xs px-2.5 py-1 rounded-full font-semibold">
                                    {{ $patient->samples_count }}
                                </span>
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $patient->vital_status === 'Deceased' ? 'bg-red-900/40 text-red-300 border border-red-800' : 'bg-emerald-900/40 text-emerald-300 border border-emerald-800' }}">
                                    {{ $patient->vital_status ?? 'Unknown' }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <a href="{{ route('patients.show', $patient->patient_code) }}" 
                                   class="inline-block bg-slate-700 hover:bg-sky-600 text-slate-200 hover:text-white text-xs font-semibold px-3 py-1.5 rounded transition">
                                    View Profile &rarr;
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-500">
                                No patient records found matching your filter criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="pt-2">
            {{ $patients->links() }}
        </div>

    </div>

    <!-- Safe Chart Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('mutationChart');
            
            if (!canvas) {
                console.warn('Canvas element #mutationChart not found in DOM.');
                return;
            }

            const ctx = canvas.getContext('2d');
            const labels = {!! json_encode($chartLabels ?? []) !!};
            const data = {!! json_encode($chartData ?? []) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Sample Mutation Frequency',
                        data: data,
                        backgroundColor: 'rgba(56, 189, 248, 0.6)',
                        borderColor: 'rgba(56, 189, 248, 1)',
                        borderWidth: 1.5,
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            ticks: { color: '#94a3b8' },
                            grid: { color: 'rgba(51, 65, 85, 0.4)' }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: { color: '#94a3b8', precision: 0 },
                            grid: { color: 'rgba(51, 65, 85, 0.4)' },
                            title: {
                                display: true,
                                text: 'Number of Mutated Samples',
                                color: '#64748b'
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
