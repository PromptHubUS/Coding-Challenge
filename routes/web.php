<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromptController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    Route::group(['prefix' => 'prompt'], function () {
        Route::get('/', [PromptController::class, 'index'])->name('prompt.index');
        Route::post('/step1', [PromptController::class, 'step1'])->name('prompt.step1');
        Route::post('/step2', [PromptController::class, 'step2'])->name('prompt.step2');
    });
});

require __DIR__ . '/auth.php';
