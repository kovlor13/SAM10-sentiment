<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Total Sentiments -->
            <div class="bg-white shadow rounded-lg flex flex-col items-center p-4">
                <h3 class="text-lg font-semibold text-gray-800">Total Sentiments</h3>
                <p class="text-3xl font-bold text-gray-900">{{ $totalSentiments }}</p>
            </div>

            <!-- Positive Sentiments -->
            <div class="bg-blue-500 text-white shadow rounded-lg flex flex-col items-center p-4">
                <h3 class="text-lg font-semibold">Positive Sentiments</h3>
                <p class="text-3xl font-bold">{{ $positiveCount }}</p>
            </div>

            <!-- Neutral Sentiments -->
            <div class="bg-green-500 text-white shadow rounded-lg flex flex-col items-center p-4">
                <h3 class="text-lg font-semibold">Neutral Sentiments</h3>
                <p class="text-3xl font-bold">{{ $neutralCount }}</p>
            </div>

            <!-- Negative Sentiments -->
            <div class="bg-red-500 text-white shadow rounded-lg flex flex-col items-center p-4">
                <h3 class="text-lg font-semibold">Negative Sentiments</h3>
                <p class="text-3xl font-bold">{{ $negativeCount }}</p>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="mt-8 bg-white shadow rounded-lg mx-6 lg:mx-12 p-6">
            <h3 class="text-lg font-semibold mb-4">Sentiment Trends (Bar Chart)</h3>
            <div>
                <canvas id="sentimentBarChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('sentimentBarChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar', // Bar Chart for experimentation
                data: {
                    labels: ['Positive', 'Neutral', 'Negative'],
                    datasets: [{
                        label: 'Sentiment Trends',
                        data: [{{ $positiveCount }}, {{ $neutralCount }}, {{ $negativeCount }}],
                        backgroundColor: ['#4299e1', '#48bb78', '#f56565'], // Bar colors
                        borderWidth: 1,
                        borderColor: '#ffffff',
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
                            grid: { display: false },
                            title: { display: true, text: 'Sentiment Types', color: '#666' }
                        },
                        y: {
                            beginAtZero: true,
                            title: { display: true, text: 'Counts', color: '#666' },
                            ticks: { stepSize: 1 }
                        }
                    },
                    layout: {
                        padding: { left: 24, right: 24 } // Adds padding inside the chart for better spacing
                    }
                }
            });
        });
    </script>
</x-app-layout>
