@extends('layouts.app')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@section('title', 'Statistics - Ticket Recorder')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Support Performance Analytics</h1>

        <div class="mb-6">
            <a href="{{ route('dashboard') }}"
               class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                Back to Dashboard
            </a>
        </div>

        <!-- Overview Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <h3 class="text-sm font-semibold text-gray-700">Total Answered</h3>
                <p class="text-lg font-bold text-gray-800">{{ $totalAnsweredByMe }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <h3 class="text-sm font-semibold text-gray-700">Avg Resolution Time</h3>
                <p class="text-lg font-bold text-gray-800">
                    @if($myAvgResponseTime !== null)
                        {{ number_format($myAvgResponseTime, 2) }} hours
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <h3 class="text-sm font-semibold text-gray-700">Resolution Rate</h3>
                <p class="text-lg font-bold text-gray-800">{{ number_format($resolutionRate, 1) }}%</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-md shadow">
                <h3 class="text-sm font-semibold text-gray-700">Current Backlog</h3>
                <p class="text-lg font-bold text-gray-800">{{ $backlog }}</p>
            </div>
        </div>

        <!-- Detailed Analytics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div>
                <!-- Resolution Time Distribution Chart -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Resolution Time Distribution</h3>
                    <canvas id="resolutionChart" class="w-full h-64"></canvas>
                </div>

                <!-- Tickets Answered per Period Chart -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Tickets Answered per Period</h3>
                    <canvas id="periodChart" class="w-full h-64"></canvas>
                </div>

                <!-- Efficiency Score -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Efficiency Score</h3>
                    <p class="text-gray-800">
                        @if($efficiencyScore !== null)
                            {{ number_format($efficiencyScore, 1) }}%
                            <span class="text-sm text-gray-600">(vs. overall average)</span>
                            <span @class([
                                'inline-block px-2 py-1 text-sm rounded-full ml-2',
                                'bg-green-100 text-green-800' => $efficiencyScore > 20,
                                'bg-yellow-100 text-yellow-800' => $efficiencyScore <= 20 && $efficiencyScore > -20,
                                'bg-red-100 text-red-800' => $efficiencyScore <= -20,
                            ])>
                                {{ $efficiencyScore > 20 ? 'High' : ($efficiencyScore > -20 ? 'Medium' : 'Low') }}
                            </span>
                        @else
                            N/A
                        @endif
                    </p>
                </div>
            </div>

            <!-- Right Column -->
            <div>
                <!-- Priority Handling Breakdown Chart -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Priority Handling</h3>
                    <canvas id="priorityChart" class="w-full h-64"></canvas>
                </div>

                <!-- Peak Performance Periods Chart -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Peak Performance Hours</h3>
                    <canvas id="hourlyChart" class="w-full h-64"></canvas>
                </div>

                <!-- Additional Metrics -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Additional Metrics</h3>
                    <ul class="space-y-2">
                        <li class="bg-gray-50 p-3 rounded-md shadow">
                            <strong class="text-gray-700">Escalated to L2:</strong> {{ $myEscalatedToL2 }}
                        </li>
                        <li class="bg-gray-50 p-3 rounded-md shadow">
                            <strong class="text-gray-700">First Response Time:</strong>
                            {{ $firstResponseTime ? number_format($firstResponseTime, 2) . ' hours' : 'N/A' }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script>
        // Debugging: Log data to console
        console.log('Resolution Distribution:', @json($resolutionDistribution));
        console.log('Priority Breakdown:', @json($priorityBreakdown));
        console.log('Hourly Breakdown:', @json($hourlyBreakdown));

        // 1. Resolution Time Distribution Chart
        const resolutionCtx = document.getElementById('resolutionChart').getContext('2d');
        new Chart(resolutionCtx, {
            type: 'bar',
            data: {
                labels: ['< 1 Hour', '1-24 Hours', '> 24 Hours'],
                datasets: [{
                    label: 'Tickets',
                    data: [
                        {{ $resolutionDistribution['less_than_1h'] ?? 0 }},
                        {{ $resolutionDistribution['1_to_24h'] ?? 0 }},
                        {{ $resolutionDistribution['more_than_24h'] ?? 0 }}
                    ],
                    backgroundColor: ['#34D399', '#FBBF24', '#F87171'],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Number of Tickets' } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 2. Tickets Answered per Period Chart
        const periodCtx = document.getElementById('periodChart').getContext('2d');
        new Chart(periodCtx, {
            type: 'bar',
            data: {
                labels: ['Today', 'This Week', 'This Month'],
                datasets: [{
                    label: 'Tickets Answered',
                    data: [
                        {{ $myAnsweredPerDay ?? 0 }},
                        {{ $myAnsweredPerWeek ?? 0 }},
                        {{ $myAnsweredPerMonth ?? 0 }}
                    ],
                    backgroundColor: '#3B82F6',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Number of Tickets' } }
                },
                plugins: { legend: { display: false } }
            }
        });

        // 3. Priority Handling Breakdown Chart
        const priorityCtx = document.getElementById('priorityChart').getContext('2d');
        new Chart(priorityCtx, {
            type: 'bar',
            data: {
                labels: [@foreach($priorityBreakdown as $p)'{{ $p->priority }}', @endforeach],
                datasets: [
                    {
                        label: 'Count',
                        data: [@foreach($priorityBreakdown as $p){{ $p->count }}, @endforeach],
                        backgroundColor: '#10B981',
                        yAxisID: 'y'
                    },
                    {
                        label: 'Avg Time (hours)',
                        type: 'line',
                        data: [@foreach($priorityBreakdown as $p){{ $p->avg_time ?? 0 }}, @endforeach],
                        borderColor: '#EF4444',
                        fill: false,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, position: 'left', title: { display: true, text: 'Count' } },
                    y1: { beginAtZero: true, position: 'right', title: { display: true, text: 'Avg Time (h)' } }
                }
            }
        });

        // 4. Peak Performance Periods Chart
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: [@foreach($hourlyBreakdown as $h)'{{ sprintf("%02d:00", $h->hour) }}', @endforeach],
                datasets: [{
                    label: 'Tickets Answered',
                    data: [@foreach($hourlyBreakdown as $h){{ $h->count }}, @endforeach],
                    backgroundColor: '#8B5CF6',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true, title: { display: true, text: 'Number of Tickets' } },
                    x: { title: { display: true, text: 'Hour of Day' } }
                },
                plugins: { legend: { display: false } }
            }
        });
    </script>
@endsection
