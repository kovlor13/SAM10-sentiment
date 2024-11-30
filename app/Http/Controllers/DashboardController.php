<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Sentiment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class DashboardController extends Controller
{

    
    public function index()
    {
        $user = Auth::user();

        return view('dashboard', [
            'totalSentiments' => $user->sentiments()->count(),
            'positiveCount' => $user->sentiments()->where('grade', 'Positive')->count(),
            'neutralCount' => $user->sentiments()->where('grade', 'Neutral')->count(),
            'negativeCount' => $user->sentiments()->where('grade', 'Negative')->count(),
        ]);
        
    }
    public function dashboard()
    {
        $path = storage_path('app/data/data.json');

        if (!File::exists($path)) {
            return view('dashboard', ['data' => [], 'error' => 'Data file not found.']);
        }

        $data = json_decode(File::get($path), true);

        return view('dashboard', ['data' => $data]);
    }

    public function addPhrase(Request $request)
    {
        $path = storage_path('app/data/data.json');
    
        // Validate request data
        $request->validate([
            'phrase' => 'required|string|max:255',
            'category' => 'required|in:positive_phrases,negative_phrases,neutral_phrases',
        ]);
    
        // Check if the file exists
        if (!File::exists($path)) {
            return redirect()->back()->with('error', 'Data file not found.');
        }
    
        // Load the existing data
        $data = json_decode(File::get($path), true);
    
        // Get the new phrase and category
        $newPhrase = strtolower(trim($request->input('phrase'))); // Normalize the input
        $category = $request->input('category');
    
        // Normalize existing phrases for comparison
        $normalizedPhrases = array_map('strtolower', array_map('trim', $data[$category]));
    
        // Ensure the phrase is not already in the category
        if (!in_array($newPhrase, $normalizedPhrases)) {
            $data[$category][] = ucfirst($newPhrase); // Add the phrase in title case for better readability
    
            // Save the updated data back to the file
            File::put($path, json_encode($data, JSON_PRETTY_PRINT));
    
            return redirect()->back()->with('success', 'Phrase added successfully!');
        }
    
        return redirect()->back()->with('error', 'Phrase already exists in this category.');
    }
    
}
