<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LandingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ParticipantController;

// Route Initialization
Route::get('/', [LandingController::class, 'index'])->name('landing');


// Routes for authenticated users and administrators
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/export-participants', [ParticipantController::class, 'export'])->name('participants.export');
});


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
});

require __DIR__ . '/auth.php';


// Routes initials with creation project
// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });