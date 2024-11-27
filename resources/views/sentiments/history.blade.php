@extends('layouts.app')
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<link href="{{ asset('css/sentiment_analysis.css') }}" rel="stylesheet">

@section('content')
<div class="container">
    <h1 class="text-3xl font-semibold text-gray-800 mb-6 text-center">Sentiment History</h1>

    @if($sentiments->isEmpty())
        <div class="text-center text-gray-500">
            <p>No sentiment analyses have been performed yet.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($sentiments as $sentiment)
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-200">
           

                                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Analysis for:</h2>
                        <button 
                            class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 delete-sentiment"
                            data-id="{{ $sentiment->id }}"
                        >
                            &times;
                        </button>
                    </div>

                    <!-- Display a shortened version of the text -->
                    <p class="text-gray-700 mb-4 font-medium">
                        {!! \Illuminate\Support\Str::limit($sentiment->highlighted_text, 150, '...') !!}
                        @if(strlen(strip_tags($sentiment->highlighted_text)) > 150)
                            <a href="#" data-id="{{ $sentiment->id }}" class="text-blue-500 underline read-more">Read More</a>
                        @endif
                    </p>

                    <div class="mt-4">
                        <h3 class="text-lg font-semibold">Sentiment Score</h3>
                        <div class="score-container">
                            <div class="score-bar">
                                <div class="score-indicator" style="left: {{ (($sentiment->score + 1) / 2) * 100 }}%;"></div>
                            </div>
                            <div class="score-labels flex justify-between text-sm mt-2">
                                <span class="text-red-500">Negative</span>
                                <span class="text-gray-500">Neutral</span>
                                <span class="text-blue-500">Positive</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-semibold">Sentiment Metrics</h3>
                        <div class="mt-6 flex flex-col items-center space-y-4">
                            <div class="flex justify-center w-full space-x-4">
                                <div class="pill bg-blue-500 text-white w-1/3">
                                    <span>Positive Count</span>
                                    <span class="font-semibold">{{ $sentiment->positive_count }}</span>
                                </div>
                                <div class="pill bg-red-500 text-white w-1/3">
                                    <span>Negative Count</span>
                                    <span class="font-semibold">{{ $sentiment->negative_count }}</span>
                                </div>
                            </div>
                            <div class="flex justify-center w-full space-x-4">
                                <div class="pill bg-yellow-300 text-yellow-800 w-1/3">
                                    <span>Neutral Count</span>
                                    <span class="font-semibold">{{ $sentiment->neutral_count }}</span>
                                </div>
                                <div class="pill bg-gray-200 text-gray-800 w-1/3">
                                    <span>Total Words</span>
                                    <span class="font-semibold">{{ $sentiment->total_word_count }}</span>
                                </div>
                            </div>
                            <div class="pill bg-green-500 text-white text-center w-1/2 mt-4">
                                <span>Grade</span>
                                <span class="font-semibold block">{{ $sentiment->grade }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-4">
                        <h3 class="text-lg font-semibold">Percentages</h3>
                        <div class="pill bg-blue-500 text-white">
                            <span>Positive</span>
                            <span class="font-semibold">{{ $sentiment->positive_percentage }}%</span>
                        </div>
                        <div class="pill bg-red-500 text-white">
                            <span>Negative</span>
                            <span class="font-semibold">{{ $sentiment->negative_percentage }}%</span>
                        </div>
                        <div class="pill bg-yellow-300 text-yellow-800">
                            <span>Neutral</span>
                            <span class="font-semibold">{{ $sentiment->neutral_percentage }}%</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<!-- Modal for Full Text -->
<div id="full-text-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex justify-center items-center">
    <div class="modal-content">
        <h2 class="text-xl font-semibold mb-4">Full Analysis Text</h2>
        <p id="full-text-content" class="text-gray-700"></p>
        <div class="mt-4 text-right">
            <button id="close-modal" class="bg-red-500 text-white px-4 py-2 rounded">Close</button>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('full-text-modal');
    const modalContent = document.getElementById('full-text-content');
    const closeModal = document.getElementById('close-modal');

    document.querySelectorAll('.read-more').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const sentimentId = this.dataset.id;

            // Fetch the full text using AJAX
            fetch(`/sentiments/${sentimentId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to fetch sentiment text');
                    }
                    return response.json();
                })
                .then(data => {
                    modalContent.innerHTML = data.text;
                    modal.classList.remove('hidden');
                    modal.classList.add('show'); // Trigger animation
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to load full text.');
                });
        });
    });

    closeModal.addEventListener('click', () => {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.classList.add('hidden'); // Ensure it's completely hidden after animation
        }, 300); // Match the duration of the CSS animation
    });

    // Close the modal when clicking outside the modal content
    modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        e.stopPropagation(); // Stop the event from bubbling
        closeModal.click();
    }
});

});

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.delete-sentiment').forEach(button => {
        button.addEventListener('click', function () {
            const sentimentId = this.getAttribute('data-id');

            if (confirm('Are you sure you want to delete this sentiment?')) {
                fetch(`/sentiments/${sentimentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete sentiment');
                    }
                    return response.json();
                })
                .then(data => {
                    alert(data.message);
                    location.reload(); // Reload the page to reflect changes
                })
                .catch(error => {
                    console.error(error);
                    alert('An error occurred while deleting the sentiment.');
                });
            }
        });
    });
});

</script>
@endsection
