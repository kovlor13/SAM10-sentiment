<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SentimentController;
use App\Http\Controllers\AnalysisController;


Route::post('/analyze-sentiment', [SentimentController::class, 'analyze'])->name('analyze.sentiment');



Route::get('/analyze', [SentimentController::class, 'analyze']);
Route::post('/analyze', [SentimentController::class, 'analyze']); // For POST requests


Route::view('/', 'welcome');




Route::get('/history', function () {
    return view('history');
})->name('history');

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
