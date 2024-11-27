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
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Analysis for: </h2>
                    <p class="text-gray-700 mb-4 font-medium">{!! $sentiment->highlighted_text !!}</p>

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
@endsection
