@extends('layouts.app')
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/sentiment_analysis.css') }}" rel="stylesheet">

@section('content')
<div class="container">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Sentiment Analysis</h1>

    <div class="flex justify-center">
        <form id="sentiment-form" class="w-full max-w-lg">
            @csrf
            <div class="mb-6">
                <label for="text" class="block text-sm font-medium text-gray-700 mb-2">Enter text for analysis</label>
                <textarea id="text" name="text" rows="6" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Type your text here..." required></textarea>
                <span id="text-error" class="text-red-500 text-xs mt-2" style="display: none;"></span>
            </div>
            <div class="flex justify-center">
                <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 ease-in-out transform hover:scale-105 w-full sm:w-auto">Analyze Sentiment</button>
            </div>
        </form>
    </div>

    <div id="result" class="mt-8 bg-white p-6 rounded-xl shadow-lg border border-gray-200 hidden">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Analysis Result</h2>
        <p id="input-text" class="text-gray-700 mb-4 font-medium"></p>

        <div id="metrics" class="mt-6">
            <h3 class="text-lg font-semibold mb-4">Sentiment Metrics</h3>
            <div id="metrics" class="mt-6 space-y-4">
                    <div class="pill bg-blue-500 text-white">
                        <span>Positive words count</span>
                        <span id="positive-count-value" class="font-semibold"></span>
                    </div>
                    <div class="pill bg-red-500 text-white">
                        <span>Negative words count</span>
                        <span id="negative-count-value" class="font-semibold"></span>
                    </div>
                    <div class="pill bg-yellow-300 text-yellow-800">
                        <span>Neutral words count</span>
                        <span id="neutral-count-value" class="font-semibold"></span>
                    </div>
                    <div class="pill bg-gray-200 text-gray-800">
                        <span>Total word count</span>
                        <span id="total-word-count-value" class="font-semibold"></span>
                    </div>
                    <div class="pill bg-green-500 text-white">
                        <span>Sentiment Grade</span>
                        <span id="grade-value" class="font-semibold"></span>
                    </div>
            </div>

        </div>

        <div class="mt-6">
            <h3 class="text-lg font-semibold">Sentiment Score</h3>
            <div class="score-container">
                <div class="score-bar">
                    <div id="score-indicator" class="score-indicator"></div>
                </div>
                <div class="score-labels flex justify-between text-sm mt-2">
                    <span class="text-red-500">Negative</span>
                    <span class="text-gray-500">Neutral</span>
                    <span class="text-blue-500">Positive</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('#sentiment-form').on('submit', function (e) {
            e.preventDefault();
            var text = $('#text').val();

            // Clear previous error and result
            $('#text-error').hide();
            $('#result').hide();

            // AJAX request
            $.ajax({
                url: '{{ route("analyze.sentiment") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    text: text
                },
                success: function (response) {
                // Use highlighted text from the backend for display
                $('#input-text').html('Input Text: ' + response.highlighted_text);

                // Populate metrics
                $('#positive-count-value').text(response.positive_count);
                $('#negative-count-value').text(response.negative_count);
                $('#neutral-count-value').text(response.neutral_count);
                $('#total-word-count-value').text(response.total_word_count);
                $('#score-value').text(response.score);
                $('#magnitude-value').text(response.magnitude);
                $('#grade-value').text(response.grade);

                // Update the bar position
                var leftPercentage = ((response.score + 1) / 2) * 100; // Normalize to 0-100%
                $('#score-indicator').css('left', leftPercentage + '%');

                // Display the result
                $('#result').fadeIn();
            },

                error: function () {
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    });
</script>
@endsection
