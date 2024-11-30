
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/sentiment_analysis.css') }}" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
@livewireStyles
@livewireScripts
<script src="//unpkg.com/alpinejs" defer></script>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Analyze Text') }}
        </h2>
    </x-slot>
   
    <div class="container">
        <div class="flex justify-center">
            
            <form id="sentiment-form" class="w-full max-w-lg">
                @csrf
                <div class="mb-6">
                <div style="text-align: center; font-weight: bold;">
                    <h2><i class="fas fa-search"></i> Analyze Sentiment Text Here</h2>
                 </div>
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-2">Enter text for analysis</label>
                        <textarea 
                        id="text" 
                        name="text" 
                        rows="1" 
                        class="mt-1 block w-full px-4 py-4 border border-gray-300 rounded-3xl shadow-sm focus:ring-gray-500 focus:border-gray-500 sm:text-lg bg-gray-100 resize-none overflow-hidden text-gray-800 placeholder-gray-400"
                        placeholder="Type your text here..." 
                        required 
                        oninput="adjustTextareaHeight(this)">
                    </textarea>

                    <span id="text-error" class="text-red-500 text-xs mt-2" style="display: none;"></span>
                </div>
                <div class="flex justify-center">
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-6 py-3 bg-gray-600 text-white rounded-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 transition duration-300 ease-in-out transform hover:scale-105">
                        Analyze Sentiment
                    </button>
                </div>
            </form>
        </div>


        <div id="result" class="mt-8 bg-white p-8 rounded-3xl shadow-lg hidden">
            <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Analysis Result</h2>
            <p id="input-text" class="text-gray-700 mb-6 font-medium text-center"></p>

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

        <!-- Sentiment Metrics -->
        <div id="metrics" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Positive Words -->
                <div class="bg-blue-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                    <div class="text-blue-600 text-3xl">
                        <i class="fas fa-smile"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-600 mt-2">Positive Words</h3>
                    <p id="positive-count-value" class="text-4xl font-bold text-blue-600 mt-2"></p>
                </div>

                <!-- Negative Words -->
                <div class="bg-red-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                    <div class="text-red-600 text-3xl">
                        <i class="fas fa-frown"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-red-600 mt-2">Negative Words</h3>
                    <p id="negative-count-value" class="text-4xl font-bold text-red-600 mt-2"></p>
                </div>

                <!-- Neutral Words -->
                <div class="bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                    <div class="text-green-600 text-3xl">
                        <i class="fas fa-meh"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-green-600 mt-2">Neutral Words</h3>
                    <p id="neutral-count-value" class="text-4xl font-bold text-green-600 mt-2"></p>
                </div>

                <!-- Total Words -->
                <div class="bg-gray-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                    <div class="text-gray-600 text-3xl">
                        <i class="fas fa-align-left"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-600 mt-2">Total Words</h3>
                    <p id="total-word-count-value" class="text-4xl font-bold text-gray-600 mt-2"></p>
                </div>
            </div>

            <!-- Sentiment Grade -->
            <div class="mt-8 bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                <h3 class="text-lg font-semibold text-green-600">Sentiment Grade</h3>
                <p id="grade-value" class="text-4xl font-bold text-green-600 mt-2"></p>
            </div>

            <!-- Sentiment Percentages -->
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Positive Percentage -->
                <div class="bg-blue-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                    <div class="text-blue-600 text-3xl">
                        <i class="fas fa-smile"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-blue-600 mt-2">Positive Percentage</h3>
                    <p id="positive-percentage-value" class="text-4xl font-bold text-blue-600 mt-2"></p>
                </div>

                <!-- Negative Percentage -->
                <div class="bg-red-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                    <div class="text-red-600 text-3xl">
                        <i class="fas fa-frown"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-red-600 mt-2">Negative Percentage</h3>
                    <p id="negative-percentage-value" class="text-4xl font-bold text-red-600 mt-2"></p>
                </div>

                <!-- Neutral Percentage -->
                <div class="bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-6">
                    <div class="text-green-600 text-3xl">
                        <i class="fas fa-meh"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-green-600 mt-2">Neutral Percentage</h3>
                    <p id="neutral-percentage-value" class="text-4xl font-bold text-green-600 mt-2"></p>
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

    // Populate percentages
    $('#positive-percentage-value').text(response.positive_percentage + '%');
    $('#negative-percentage-value').text(response.negative_percentage + '%');
    $('#neutral-percentage-value').text(response.neutral_percentage + '%');

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

     // Auto-expand textarea height
     function adjustTextareaHeight(textarea) {
        textarea.style.height = 'auto'; // Reset height to auto
        textarea.style.height = textarea.scrollHeight + 'px'; // Set height to scroll height
    }
</script>
</x-app-layout>
