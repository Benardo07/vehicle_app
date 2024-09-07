<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Vehicle Usage Details - {{ $vehicle->model_name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <canvas id="usageChart"></canvas>
                    <table class="mt-4 min-w-full leading-normal">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Kilometers Driven</th>
                                <th>Fuel Used (liters)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usages as $usage)
                            <tr>
                                <td class="text-center">{{ $usage->start_time->format('Y-m-d') }}&nbsp;&nbsp; - &nbsp;&nbsp;{{ $usage->end_time->format('Y-m-d') }}</td>
                                <td  class="text-center">{{ $usage->kilometers_driven }}</td>
                                <td  class="text-center">{{ $usage->kilometers_driven * $usage->vehicle->fuel_consumption_per_km }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('usageChart').getContext('2d');
            const usageChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json(array_column($dailyData, 'date')),
                    datasets: [{
                        label: 'Kilometers Driven',
                        data: @json(array_column($dailyData, 'kilometers')),
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        fill: true,
                    }, {
                        label: 'Fuel Used',
                        data: @json(array_column($dailyData, 'fuel')),
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.5)',
                        fill: true,
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
