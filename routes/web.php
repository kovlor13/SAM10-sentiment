<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\AnalysisController;


Route::post('/analyze', [SentimentController::class, 'analyze'])->name('analyze.sentiment');
Route::get('/history', [SentimentController::class, 'history'])->name('sentiments.history');
Route::get('/sentiments/{id}', function ($id) {
    $sentiment = App\Models\Sentiment::findOrFail($id);
    return response()->json(['text' => $sentiment->highlighted_text]);
})->name('sentiments.show');



Route::view('/', 'welcome');




Route::get('/analysis', function () {
    return view('analysis');
})->name('analysis');


Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

    // About Us Page Route
Route::get('/about', [AboutController::class, 'index'])->name('about');

// Contact Us Page Route
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

require __DIR__.'/auth.php';
