<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-orange-500">{{ __('New personnel (last 30 days)') }}</h3>
                            <span class="text-sm text-gray-500">{{ $newPersonnelTotal30d }} {{ __('added') }}</span>
                        </div>

                        <div class="mt-4">
                            <canvas id="personnelNew30dChart" height="140"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-orange-500">{{ __('Gender distribution') }}</h3>
                            <span class="text-sm text-gray-500">{{ $personnelTotal }} {{ __('total') }}</span>
                        </div>

                        <div class="mt-4">
                            <canvas id="personnelGenderChart" height="140"></canvas>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h3 class="text-base font-semibold text-orange-500">{{ __('Upcoming birthdays') }}</h3>
                            <span class="text-sm text-gray-500">{{ __('Next :days days', ['days' => 60]) }}</span>
                        </div>

                        <div class="mt-4 w-full" id="birthday-calendar" data-url="{{ route('dashboard.birthdays', [], false) }}" data-days="60"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
        <script>
            (() => {
                const newLabels = @json($newPersonnelLabels);
                const newData = @json($newPersonnelData);

                const newEl = document.getElementById('personnelNew30dChart');
                if (newEl && window.Chart) {
                    // Tailwind orange-500
                    const orange500 = '#f97316';

                    new Chart(newEl, {
                        type: 'line',
                        data: {
                            labels: newLabels,
                            datasets: [{
                                label: 'New personnel',
                                data: newData,
                                borderColor: orange500,
                                backgroundColor: 'rgba(249, 115, 22, 0.20)',
                                fill: true,
                                tension: 0.35,
                                pointRadius: 2,
                                pointHoverRadius: 4,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: { intersect: false, mode: 'index' },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { precision: 0 },
                                    grid: { color: 'rgba(17, 24, 39, 0.08)' },
                                },
                                x: {
                                    grid: { display: false },
                                },
                            },
                        },
                    });
                }

                const genderLabels = @json($genderLabels);
                const genderData = @json($genderData);

                const genderEl = document.getElementById('personnelGenderChart');
                if (genderEl && window.Chart) {
                    new Chart(genderEl, {
                        type: 'doughnut',
                        data: {
                            labels: genderLabels,
                            datasets: [{
                                data: genderData,
                                backgroundColor: [
                                    '#f97316', // Male (orange-500)
                                    '#fb923c', // Female (orange-400)
                                    '#fdba74', // Other (orange-300)
                                    '#e5e7eb', // Unspecified (gray-200)
                                ],
                                borderColor: '#ffffff',
                                borderWidth: 2,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: { boxWidth: 12, boxHeight: 12 },
                                },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => {
                                            const label = context.label ?? '';
                                            const value = context.parsed ?? 0;
                                            const data = context.dataset?.data ?? [];
                                            const total = data.reduce((sum, current) => sum + (Number(current) || 0), 0);
                                            const pct = total ? (value / total) * 100 : 0;
                                            return `${label}: ${value} (${pct.toFixed(1)}%)`;
                                        },
                                    },
                                },
                            },
                            cutout: '62%',
                        },
                    });
                }
            })();
        </script>
    @endpush
</x-app-layout>


