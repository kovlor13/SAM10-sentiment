<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SentimentController extends Controller
{
    public function analyze(Request $request)
    {
        // Get the input text from the request
        $inputText = $request->input('text');
    
        // Load the JSON dataset
        $datasetPath = storage_path('app/data/data.json');
        if (!File::exists($datasetPath)) {
            return response()->json(['error' => 'Dataset file not found!'], 404);
        }
    
        $data = json_decode(File::get($datasetPath), true);
    
        // Extract words and phrases
        $positiveWords = $data['positive_words'] ?? [];
        $negativeWords = $data['negative_words'] ?? [];
        $neutralWords = $data['neutral_words'] ?? [];
        $positivePhrases = $data['positive_phrases'] ?? [];
        $negativePhrases = $data['negative_phrases'] ?? [];
        $neutralPhrases = $data['neutral_phrases'] ?? [];
    
        // Initialize counters
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;
    
        // First, handle phrases (ensure these are matched before splitting the text into words)
        foreach ($positivePhrases as $phrase) {
            if (stripos($inputText, $phrase) !== false) {
                $positiveCount++;
                $inputText = str_ireplace($phrase, '', $inputText);  // Remove the phrase from text to avoid double counting
            }
        }
    
        foreach ($negativePhrases as $phrase) {
            if (stripos($inputText, $phrase) !== false) {
                $negativeCount++;
                $inputText = str_ireplace($phrase, '', $inputText);  // Remove the phrase from text to avoid double counting
            }
        }
    
        foreach ($neutralPhrases as $phrase) {
            if (stripos($inputText, $phrase) !== false) {
                $neutralCount++;
                $inputText = str_ireplace($phrase, '', $inputText);  // Remove the phrase from text to avoid double counting
            }
        }
    
        // After checking for phrases, split the remaining text into words
        $inputWords = explode(' ', strtolower($inputText)); // Convert to lowercase and split into words
    
        // Initialize an array to track unique words for avoiding double counting
        $trackedWords = [];
    
        // Loop through the words in the input text
        foreach ($inputWords as $word) {
            // If the word is in the list of sentiment words and hasn't been counted yet
            if (in_array($word, $positiveWords) && !in_array($word, $trackedWords)) {
                $positiveCount++;
                $trackedWords[] = $word;
            } elseif (in_array($word, $negativeWords) && !in_array($word, $trackedWords)) {
                $negativeCount++;
                $trackedWords[] = $word;
            } elseif (in_array($word, $neutralWords) && !in_array($word, $trackedWords)) {
                $neutralCount++;
                $trackedWords[] = $word;
            }
        }
    
        // Highlight the words in the input text
        $highlightedText = $this->highlightWords($inputText, $positiveWords, 'blue', $negativeWords, 'red', $neutralWords, 'green');
        
        // Determine overall sentiment based on the highest count
        $overallSentiment = 'neutral';
        if ($positiveCount > $negativeCount && $positiveCount > $neutralCount) {
            $overallSentiment = 'positive';
        } elseif ($negativeCount > $positiveCount && $negativeCount > $neutralCount) {
            $overallSentiment = 'negative';
        }
    
        // Return the result as a JSON response
        return response()->json([
            'text' => $inputText,
            'highlighted_text' => $highlightedText, // Highlighted text to display
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount,
            'neutral_count' => $neutralCount, // Include neutral count
            'total_word_count' => count($inputWords), // Total word count
            'overall_sentiment' => $overallSentiment,
        ]);
    }
    
    // Function to highlight words in the text
    private function highlightWords($text, $positiveWords, $positiveColor, $negativeWords, $negativeColor, $neutralWords, $neutralColor)
    {
        // Highlight the positive words with bold and larger font size
        foreach ($positiveWords as $word) {
            $text = preg_replace_callback('/\b' . preg_quote($word, '/') . '\b/i', function($matches) use ($positiveColor) {
                return "<span class='highlight positive'>{$matches[0]}</span>";
            }, $text);
        }
    
        // Highlight the negative words with bold and larger font size
        foreach ($negativeWords as $word) {
            $text = preg_replace_callback('/\b' . preg_quote($word, '/') . '\b/i', function($matches) use ($negativeColor) {
                return "<span class='highlight negative'>{$matches[0]}</span>";
            }, $text);
        }
    
        // Highlight the neutral words with bold and larger font size
        foreach ($neutralWords as $word) {
            $text = preg_replace_callback('/\b' . preg_quote($word, '/') . '\b/i', function($matches) use ($neutralColor) {
                return "<span class='highlight neutral'>{$matches[0]}</span>";
            }, $text);
        }
    
        return $text;
    }
}    