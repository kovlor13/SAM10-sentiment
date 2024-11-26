<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SentimentController extends Controller
{
    public function analyze(Request $request)
    {
        $inputText = $request->input('text');
    
        // Define phrases for sentiment analysis
        $positivePhrases = ['not great', 'good enough', 'excellent'];
        $negativePhrases = ['not good', 'not great', 'horrible'];
        $neutralPhrases = ['okayish', 'meh', 'so-so'];
    
        // Define words for sentiment analysis
        $positiveWords = ['love', 'happy', 'amazing', 'great', 'best'];
        $negativeWords = ['hate', 'terrible', 'worst', 'disaster'];
        $neutralWords = ['okay', 'fine', 'decent'];
    
        // Analyze phrases first
        $positiveCount = 0;
        $negativeCount = 0;
        $neutralCount = 0;
    
        // Check for phrases
        foreach ($positivePhrases as $phrase) {
            if (stripos($inputText, $phrase) !== false) {
                $positiveCount++;
                $inputText = str_ireplace($phrase, '', $inputText);  // Remove phrase to avoid double counting
            }
        }
    
        foreach ($negativePhrases as $phrase) {
            if (stripos($inputText, $phrase) !== false) {
                $negativeCount++;
                $inputText = str_ireplace($phrase, '', $inputText);  // Remove phrase to avoid double counting
            }
        }
    
        foreach ($neutralPhrases as $phrase) {
            if (stripos($inputText, $phrase) !== false) {
                $neutralCount++;
                $inputText = str_ireplace($phrase, '', $inputText);  // Remove phrase to avoid double counting
            }
        }
    
        // Process remaining words
        $inputWords = explode(' ', strtolower($inputText));
        foreach ($inputWords as $word) {
            if (in_array($word, $positiveWords)) {
                $positiveCount++;
            } elseif (in_array($word, $negativeWords)) {
                $negativeCount++;
            } elseif (in_array($word, $neutralWords)) {
                $neutralCount++;
            }
        }
    
        // Highlight words and phrases in the text
        $highlightedText = $this->highlightWords($inputText, $positiveWords, 'blue', $negativeWords, 'red', $neutralWords, 'green');
    
        // Determine overall sentiment
        $overallSentiment = 'neutral';
        if ($positiveCount > $negativeCount) {
            $overallSentiment = 'positive';
        } elseif ($negativeCount > $positiveCount) {
            $overallSentiment = 'negative';
        }
    
        return response()->json([
            'highlighted_text' => $highlightedText,
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount,
            'neutral_count' => $neutralCount,
            'overall_sentiment' => $overallSentiment,
        ]);
    }
    
    

    // Function to highlight words in the text
private function highlightWords($text, $positiveWords, $positiveColor, $negativeWords, $negativeColor, $neutralWords, $neutralColor)
{
    // Highlight positive phrases and words
    foreach ($positiveWords as $word) {
        $text = preg_replace_callback('/\b' . preg_quote($word, '/') . '\b/i', function($matches) use ($positiveColor) {
            return "<span class='highlight positive'>{$matches[0]}</span>";
        }, $text);
    }

    // Highlight negative phrases and words
    foreach ($negativeWords as $word) {
        $text = preg_replace_callback('/\b' . preg_quote($word, '/') . '\b/i', function($matches) use ($negativeColor) {
            return "<span class='highlight negative'>{$matches[0]}</span>";
        }, $text);
    }

    // Highlight neutral phrases and words
    foreach ($neutralWords as $word) {
        $text = preg_replace_callback('/\b' . preg_quote($word, '/') . '\b/i', function($matches) use ($neutralColor) {
            return "<span class='highlight neutral'>{$matches[0]}</span>";
        }, $text);
    }

    return $text;
}


}
