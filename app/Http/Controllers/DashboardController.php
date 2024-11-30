<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Sentiment;

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
}
