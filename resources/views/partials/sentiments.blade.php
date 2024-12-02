@foreach($sentiments as $sentiment)
    <div class="bg-white p-6 rounded-3xl shadow-lg border border-gray-200 sentiment-card" data-grade="{{ $sentiment->grade }}" data-date="{{ $sentiment->created_at->format('Y-m-d') }}">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Analysis for:</h2>
            <button
                class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 delete-sentiment"
                data-id="{{ $sentiment->id }}">
                &times;
            </button>
        </div>

        <p class="text-gray-700 mb-4 font-medium sentiment-text" data-full-text="{{ $sentiment->highlighted_text }}">
        {!! \Illuminate\Support\Str::limit(strip_tags($sentiment->highlighted_text), 150, '...') !!}
            </p>
            @if(strlen(strip_tags($sentiment->highlighted_text)) > 150)
                <p>
                    <a href="#" class="text-blue-500 font-bold text-sm flex items-center space-x-1 read-more" data-id="{{ $sentiment->id }}">
                        <i class="fas fa-eye"></i>
                        <span>Read More</span>
                    </a>
                </p>
            @endif


        <!-- Sentiment Score -->
        <div class="mt-4">
            <h3 class="text-md font-semibold text-gray-800">Sentiment Score</h3>
            <div class="score-container bg-gray-100 rounded-full h-6 relative mt-2">
                <div class="score-indicator rounded-full h-full"
                     style="width: {{ (($sentiment->score + 1) / 2) * 100 }}%; background-color: #3b82f6;">
                </div>
            </div>
            <div class="flex justify-between text-sm mt-2">
                <span class="text-red-500">Negative</span>
                <span class="text-gray-500">Neutral</span>
                <span class="text-blue-500">Positive</span>
            </div>
        </div>

        <!-- Metrics -->
        <div class="grid grid-cols-2 gap-4 mt-6">
            <div class="bg-blue-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                <i class="fas fa-smile text-blue-600 text-2xl"></i>
                <h4 class="text-blue-600 mt-2 text-sm font-semibold">Positive Count</h4>
                <p class="text-blue-600 font-bold text-xl">{{ $sentiment->positive_count }}</p>
            </div>
            <div class="bg-red-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                <i class="fas fa-frown text-red-600 text-2xl"></i>
                <h4 class="text-red-600 mt-2 text-sm font-semibold">Negative Count</h4>
                <p class="text-red-600 font-bold text-xl">{{ $sentiment->negative_count }}</p>
            </div>
            <div class="bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                <i class="fas fa-meh text-green-600 text-2xl"></i>
                <h4 class="text-green-600 mt-2 text-sm font-semibold">Neutral Count</h4>
                <p class="text-green-600 font-bold text-xl">{{ $sentiment->neutral_count }}</p>
            </div>
            <div class="bg-gray-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                <i class="fas fa-align-left text-gray-600 text-2xl"></i>
                <h4 class="text-gray-600 mt-2 text-sm font-semibold">Sentimented Words</h4>
                <p class="text-gray-600 font-bold text-xl">{{ $sentiment->total_word_count }}</p>
            </div>
        </div>

    <!-- Sentiment Grade -->
            @php
                $gradeColors = [
                    'Positive' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                    'Neutral' => ['bg' => 'bg-green-100', 'text' => 'text-green-600'],
                    'Negative' => ['bg' => 'bg-red-100', 'text' => 'text-red-600'],
                ];
                $gradeClass = $gradeColors[$sentiment->grade] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600'];
            @endphp

        <div class="{{ $gradeClass['bg'] }} shadow-lg rounded-3xl flex flex-col items-center p-4 mt-6">
            <h4 class="{{ $gradeClass['text'] }} text-sm font-semibold">Grade</h4>
            <p class="{{ $gradeClass['text'] }} font-bold text-xl">{{ $sentiment->grade }}</p>
        </div>


        <!-- Percentages -->
        <div class="grid grid-cols-3 gap-4 mt-6">
            <div class="bg-blue-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                <h4 class="text-blue-600 text-sm font-semibold">Positive</h4>
                <p class="text-blue-600 font-bold text-xl">{{ $sentiment->positive_percentage }}%</p>
            </div>
            <div class="bg-red-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                <h4 class="text-red-600 text-sm font-semibold">Negative</h4>
                <p class="text-red-600 font-bold text-xl">{{ $sentiment->negative_percentage }}%</p>
            </div>
            <div class="bg-green-100 shadow-lg rounded-3xl flex flex-col items-center p-4">
                <h4 class="text-green-600 text-sm font-semibold">Neutral</h4>
                <p class="text-green-600 font-bold text-xl">{{ $sentiment->neutral_percentage }}%</p>
            </div>
        </div>
    </div>


    <script>
document.addEventListener('DOMContentLoaded', () => {
    const sentimentTexts = document.querySelectorAll('.sentiment-text');

    sentimentTexts.forEach(sentimentText => {
        const fullText = sentimentText.getAttribute('data-full-text'); // Get the full highlighted text
        const maxLength = 150; // Maximum length of text to display

        if (!fullText) return;

        // Parse the full text as HTML
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = fullText;

        let truncatedHtml = '';
        let currentLength = 0;
        let isTruncated = false;

        Array.from(tempDiv.childNodes).forEach(node => {
            if (currentLength >= maxLength) return;

            if (node.nodeType === Node.TEXT_NODE) {
                // Handle plain text nodes
                const remainingLength = maxLength - currentLength;
                if (node.textContent.length > remainingLength) {
                    truncatedHtml += node.textContent.substring(0, remainingLength) + '...';
                    currentLength = maxLength;
                    isTruncated = true;
                } else {
                    truncatedHtml += node.textContent;
                    currentLength += node.textContent.length;
                }
            } else if (node.nodeType === Node.ELEMENT_NODE) {
                // Handle element nodes (e.g., <mark>)
                const remainingLength = maxLength - currentLength;
                if (node.textContent.length > remainingLength) {
                    const truncatedContent = node.textContent.substring(0, remainingLength);
                    truncatedHtml += `<${node.tagName.toLowerCase()} ${[...node.attributes].map(attr => `${attr.name}="${attr.value}"`).join(' ')}>${truncatedContent}</${node.tagName.toLowerCase()}>...`;
                    currentLength = maxLength;
                    isTruncated = true;
                } else {
                    truncatedHtml += node.outerHTML;
                    currentLength += node.textContent.length;
                }
            }
        });

        // Set the truncated HTML back into the element
        sentimentText.innerHTML = truncatedHtml;
    });
});

</script>
@endforeach
