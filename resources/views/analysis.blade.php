@extends('layouts.app')
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/sentiment_analysis.css') }}" rel="stylesheet">

@section('content')
    <div class="container">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Sentiment Analysis</h1>

        <!-- Form for text input -->
        <div class="flex justify-center">
            <form id="sentiment-form" class="w-full max-w-lg">
                @csrf
                <div class="mb-6">
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-2">Enter text for analysis</label>
                    <textarea id="text" name="text" rows="6" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Type your text here..." required>{{ old('text') }}</textarea>
                    <span id="text-error" class="text-red-500 text-xs mt-2" style="display: none;"></span>
                </div>

                <div class="flex justify-center">
                    <button type="submit" class="px-6 py-3 bg-blue-500 text-white rounded-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-400 transition duration-300 ease-in-out transform hover:scale-105 w-full sm:w-auto">
                        Analyze Sentiment
                    </button>
                </div>
            </form>
        </div>

        <!-- Show the sentiment analysis result -->
        <!-- Show the sentiment analysis result -->
<div id="result" class="mt-8 bg-white p-6 rounded-xl shadow-lg border border-gray-200 hidden">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Analysis Result</h2>
    <p id="input-text" class="text-gray-700 mb-4 font-medium"></p>

    <div id="word-count" class="space-y-4">
    <div id="word-count" class="space-y-4">
    <div id="positive-count" class="bg-blue-100 text-blue-800 p-4 rounded-xl flex justify-between items-center">
        <span>Positive words count</span>
        <span id="positive-count-value" class="font-semibold"></span>
    </div>
            <div id="negative-count" class="bg-red-100 text-red-800 p-4 rounded-xl flex justify-between items-center">
                <span>Negative words count</span>
                <span id="negative-count-value" class="font-semibold"></span>
            </div>
            <!-- Add a container for neutral count -->
            <div id="neutral-count" class="bg-yellow-100 text-yellow-800 p-4 rounded-xl flex justify-between items-center">
                <span>Neutral words count</span>
                <span id="neutral-count-value" class="font-semibold"></span>
            </div>
            <div id="total-word-count" class="bg-gray-100 text-gray-800 p-4 rounded-xl flex justify-between items-center">
                <span>Total word count</span>
                <span id="total-word-count-value" class="font-semibold"></span>
            </div>
    </div>


    <div class="mt-4">
        <p id="sentiment-result" class="text-lg font-semibold"></p>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#sentiment-form').on('submit', function(e) {
                e.preventDefault();
                var text = $('#text').val();

                // Clear previous error and result
                $('#text-error').hide();
                $('#result').hide();

                // Simple validation for text input
                if (text.trim() === "") {
                    $('#text-error').text('Text is required').show();
                    return;
                }

                // AJAX request
     $.ajax({
                    url: '{{ route("analyze.sentiment") }}', // Adjust to your route name
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        text: text
            },
            success: function(response) {
                // Display the result with highlighted text
             $('#input-text').html('Input Text: "' + response.highlighted_text + '"'); // Use .html() to render highlighted text

            // Remove previous styles if any
            $('#input-text').find('.highlight').css({
                'font-weight': '', // Reset styles
                'font-size': '',    // Reset styles
        });

        $('#sentiment-result').text('Sentiment Result: ' + response.overall_sentiment);

        // Display the counts for positive, negative, and neutral words
        $('#positive-count-value').text(response.positive_count);
        $('#negative-count-value').text(response.negative_count);
        $('#neutral-count-value').text(response.neutral_count);


        // Display the total word count
        $('#total-word-count-value').text(response.total_word_count);

        // Change sentiment result color based on the overall sentiment
        if (response.overall_sentiment === 'positive') {
            $('#sentiment-result').removeClass('text-gray-500 text-red-500').addClass('text-green-500');
        } else if (response.overall_sentiment === 'negative') {
            $('#sentiment-result').removeClass('text-gray-500 text-green-500').addClass('text-red-500');
        } else {
            $('#sentiment-result').removeClass('text-green-500 text-red-500').addClass('text-gray-500');
        }

        // Show the result section
        $('#result').fadeIn();
    },



    error: function(xhr, status, error) {
        // Handle error
        alert('Something went wrong. Please try again.');
    }
});


            });
        });
    </script>
@endsection
