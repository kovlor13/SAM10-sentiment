<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\DashboardController;

Route::get('/dashboard', [DashboardController::class, 'dashboard'])->middleware(['auth'])->name('dashboard');
Route::post('/add-phrase', [DashboardController::class, 'addPhrase'])->name('add.phrase');
Route::post('/delete-phrase', [DashboardController::class, 'deletePhrase'])->name('delete.phrase');
    

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Sentiment-related routes
Route::post('/analyze', [SentimentController::class, 'analyze'])->name('analyze.sentiment');
Route::get('/history', [SentimentController::class, 'history'])->name('sentiments.history');
Route::get('/sentiments/{id}', function ($id) {
    $sentiment = App\Models\Sentiment::findOrFail($id);
    return response()->json(['text' => $sentiment->highlighted_text]);
})->name('sentiments.show');
Route::delete('/sentiments/{id}', [SentimentController::class, 'destroy'])->name('sentiments.destroy');

// Static pages
Route::view('/', 'welcome')->name('home');
Route::get('/analysis', fn () => view('analysis'))->name('analysis');
Route::view('/profile', 'profile')->middleware(['auth'])->name('profile');

// About and Contact
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Authentication routes
require __DIR__.'/auth.php';
