<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Sentiments -->
        <div class="bg-white shadow-lg rounded-3xl flex flex-col items-center p-6">
            <div class="text-gray-800 text-3xl mb-2">
                <i class="fas fa-chart-bar"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Total Sentiments</h3>
            <p class="text-4xl font-bold text-gray-900">{{ $totalSentiments }}</p>
        </div>

        <!-- Positive Sentiments -->
        <div class="bg-blue-200 shadow-lg rounded-3xl flex flex-col items-center p-6">
            <div class="text-blue-700 text-3xl mb-2">
                <i class="fas fa-smile-beam"></i>
            </div>
            <h3 class="text-lg font-semibold text-blue-700">Positive Sentiments</h3>
            <p class="text-4xl font-bold text-blue-700">{{ $positiveCount }}</p>
        </div>

        <!-- Neutral Sentiments -->
        <div class="bg-green-200 shadow-lg rounded-3xl flex flex-col items-center p-6">
            <div class="text-green-700 text-3xl mb-2">
                <i class="fas fa-meh"></i>
            </div>
            <h3 class="text-lg font-semibold text-green-700">Neutral Sentiments</h3>
            <p class="text-4xl font-bold text-green-700">{{ $neutralCount }}</p>
        </div>

        <!-- Negative Sentiments -->
        <div class="bg-red-200 shadow-lg rounded-3xl flex flex-col items-center p-6">
            <div class="text-red-700 text-3xl mb-2">
                <i class="fas fa-frown"></i>
            </div>
            <h3 class="text-lg font-semibold text-red-700">Negative Sentiments</h3>
            <p class="text-4xl font-bold text-red-700">{{ $negativeCount }}</p>
        </div>
    </div>

    <!-- Simple Horizontal Line Graph Section -->
    <div class="mt-12 bg-white shadow-lg rounded-3xl mx-6 lg:mx-12 p-8">
        <h3 class="text-lg font-semibold mb-6 text-gray-800 text-center">Sentiment Trends</h3>
        <div id="simple-line-graph" style="height: 250px;">
            <canvas id="sentimentSimpleLineGraph"></canvas>
        </div>
    </div>
</div>


    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('sentimentSimpleLineGraph').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['January', 'February', 'March', 'April', 'May'], // Example months
                    datasets: [
                        {
                            label: 'Positive',
                            data: [3, 5, 2, 6, 4], // Example data
                            borderColor: '#4299e1', // Blue
                            borderWidth: 2,
                            tension: 0.4, // Slight curve
                        },
                        {
                            label: 'Neutral',
                            data: [4, 3, 5, 3, 5], // Example data
                            borderColor: '#38a169', // Green
                            borderWidth: 2,
                            tension: 0.4, // Slight curve
                        },
                        {
                            label: 'Negative',
                            data: [2, 1, 3, 2, 1], // Example data
                            borderColor: '#e53e3e', // Red
                            borderWidth: 2,
                            tension: 0.4, // Slight curve
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top', // Show legend at the top
                        },
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false, // No gridlines for X-axis
                            },
                            ticks: {
                                color: '#4a5568',
                            }
                        },
                        y: {
                            grid: {
                                color: '#e2e8f0', // Light gray gridlines
                            },
                            ticks: {
                                color: '#4a5568',
                            },
                            beginAtZero: true,
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
