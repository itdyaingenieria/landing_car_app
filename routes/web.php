<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ParticipantController;

Route::get('/', function () {
    return Inertia::render('Landing');
})->name('landing');

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public API routes
Route::get('/departments', [LocationController::class, 'departments']);
Route::get('/cities', [LocationController::class, 'cities']);
Route::post('/participants', [ParticipantController::class, 'store']);
Route::get('/winner', [ParticipantController::class, 'getWinner']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/participants', [ParticipantController::class, 'index']);
    Route::post('/draw-winner', [ParticipantController::class, 'drawWinner']);
    Route::get('/export-participants', [ParticipantController::class, 'export']);
});

require __DIR__ . '/auth.php';
