<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .section {
            margin-bottom: 20px;
        }
        .highlight {
            background-color: yellow;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sentiment Analysis Report</h1>
    </div>
    <div class="section">
        <h3>Analysis for:</h3>
        <p>{!! $sentiment->highlighted_text !!}</p>
    </div>
    <div class="section">
        <h3>Sentiment Score:</h3>
        <p>{{ $sentiment->score }}</p>
    </div>
    <div class="section">
        <h3>Metrics:</h3>
        <ul>
            <li>Positive Words: {{ $sentiment->positive_count }}</li>
            <li>Negative Words: {{ $sentiment->negative_count }}</li>
            <li>Neutral Words: {{ $sentiment->neutral_count }}</li>
            <li>Total Words: {{ $sentiment->total_word_count }}</li>
        </ul>
    </div>
    <div class="section">
        <h3>Sentiment Grade:</h3>
        <p>{{ $sentiment->grade }}</p>
    </div>
</body>
</html>
