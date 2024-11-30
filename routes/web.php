<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\DashboardController;
use App\Models\Sentiment;

// Dashboard Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/add-phrase', [DashboardController::class, 'addPhrase'])->name('add.phrase');
    Route::post('/delete-phrase', [DashboardController::class, 'deletePhrase'])->name('delete.phrase');
});

// Sentiment Analysis Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/analyze', [SentimentController::class, 'analyze'])->name('analyze.sentiment');
    Route::get('/history', [SentimentController::class, 'history'])->name('sentiments.history');
    Route::get('/sentiments/{id}', function ($id) {
        $sentiment = Sentiment::findOrFail($id);
        return response()->json(['text' => $sentiment->highlighted_text]);
    })->name('sentiments.show');
    Route::delete('/sentiments/{id}', [SentimentController::class, 'destroy'])->name('sentiments.destroy');
});

// Static Pages
Route::view('/', 'welcome')->name('home');
Route::view('/profile', 'profile')->middleware(['auth'])->name('profile');
Route::view('/analysis', 'analysis')->name('analysis');

// About and Contact Pages
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Authentication Routes
require __DIR__.'/auth.php';
